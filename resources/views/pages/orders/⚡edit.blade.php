<?php

use Livewire\Component;
use App\Models\Order;
use App\Models\DeliveryBoy;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $orderId;

    public string $status = 'pending';
    public string $payment_status = 'unpaid';
    public $delivery_boy_id = '';
    public string $notes = '';

    public $deliveryBoys = [];

    public function mount($id): void
    {
        $order = Order::with(['shipment.deliveryassign', 'sale'])->findOrFail($id);

        if ($order->shipment?->status === 'delivered') {
            redirect()->route('orders.index');
            return;
        }

        $this->orderId = $order->id;
        $this->status = $order->shipment?->status ?? 'pending';
        $this->payment_status = $order->sale?->payment_status ?? 'unpaid';
        $this->delivery_boy_id = $order->shipment?->deliveryassign?->delivery_boy_id ?? '';
        $this->notes = $order->cancellation_reason ?? '';

        $this->deliveryBoys = DeliveryBoy::with('user')->where('status', 1)->latest()->get();
    }

    public function rules(): array
    {
        $rules = [
            'status' => 'required|in:pending,packed,shipped,in_transit,out_for_delivery,delivered,cancelled',
            'payment_status' => 'required|in:unpaid,paid,partial,refunded,pending',
            'notes' => 'nullable|string|max:1000',
        ];

        if (in_array($this->status, ['shipped', 'in_transit', 'out_for_delivery', 'delivered'], true)) {
            $rules['delivery_boy_id'] = 'required|exists:delivery_boys,id';
        }

        if ($this->status === 'cancelled') {
            $rules['notes'] = 'required|string|max:1000';
        }

        return $rules;
    }

    public function updatedStatus($value): void
    {
        if ($value === 'delivered') {
            $this->payment_status = 'paid';
        }

        if ($value === 'cancelled') {
            $this->payment_status = 'refunded';
        }

        if (!in_array($value, ['shipped', 'in_transit', 'out_for_delivery', 'delivered'], true)) {
            $this->delivery_boy_id = '';
        }
    }

    public function update(): void
    {
        $this->validate();

        DB::transaction(function () {
            $order = Order::with(['shipment.deliveryassign', 'sale.items', 'sale.customer.wallet.transactions'])
                ->lockForUpdate()
                ->findOrFail($this->orderId);

            $oldStatus = $order->shipment?->status;

            if ($oldStatus === 'delivered') {
                throw new \Exception('Delivered order cannot be updated.');
            }

            /*
            |--------------------------------------------------------------------------
            | Cancel Order: Return Stock + Refund Once
            |--------------------------------------------------------------------------
            */
            if ($this->status === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->sale->items as $item) {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->firstOrFail();

                    $stockBefore = $product->quantity;
                    $stockAfter = $stockBefore + $item->quantity;

                    $product->update([
                        'quantity' => $stockAfter,
                    ]);

                    Stock::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'warehouse_id' => $product->warehouse_id,
                        ],
                        [
                            'quantity' => $stockAfter,
                            'minimum_stock' => $product->minimum_stock,
                        ],
                    );

                    StockMovement::create([
                        'warehouse_id' => $product->warehouse_id,
                        'supplier_id' => $product->supplier_id,
                        'product_id' => $product->id,
                        'type' => 'order_cancelled',
                        'quantity' => $item->quantity,
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockAfter,
                        'reference_no' => 'ORD-CANCEL-' . $order->id,
                        'remarks' => 'Stock returned because order #' . $order->id . ' was cancelled',
                    ]);
                }

                if ($order->sale && $order->sale->payment_method !== 'cash') {
                    $amountPaid = (float) $order->sale->paid_amount;

                    if ($amountPaid > 0) {
                        $wallet = $order->sale->customer->wallet;

                        $transaction = $wallet->transactions()->updateOrCreate(
                            [
                                'reference_id' => 'ORDER-REFUND-' . $order->id,
                            ],
                            [
                                'amount' => $amountPaid,
                                'type' => 'credit',
                                'description' => 'Order #' . $order->id . ' refunded',
                                'status' => 'success',
                            ],
                        );

                        if ($transaction->wasRecentlyCreated) {
                            $wallet->increment('balance', $amountPaid);
                        }
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Shipment Update
            |--------------------------------------------------------------------------
            */
            if ($order->shipment) {
                $shipmentData = [
                    'status' => $this->status,
                ];

                if ($this->status === 'packed' && !$order->shipment->packed_at) {
                    $shipmentData['packed_at'] = now();
                }

                if ($this->status === 'shipped' && !$order->shipment->shipped_at) {
                    $shipmentData['shipped_at'] = now();
                    $shipmentData['dispatch_by'] = auth()->id();
                    $shipmentData['expected_delivery'] = now()->addDays(3);
                }

                if ($this->status === 'delivered' && !$order->shipment->delivered_at) {
                    $shipmentData['delivered_at'] = now();
                }

                if ($this->status === 'cancelled' && !$order->shipment->cancelled_at) {
                    $shipmentData['cancelled_at'] = now();
                }

                $order->shipment()->update($shipmentData);

                if (in_array($this->status, ['shipped', 'in_transit', 'out_for_delivery', 'delivered'], true)) {
                    $order->shipment->deliveryassign()->updateOrCreate(
                        [
                            'shipment_id' => $order->shipment->id,
                        ],
                        [
                            'delivery_boy_id' => $this->delivery_boy_id,
                            'status' => 'assigned',
                            'assigned_at' => $order->shipment->deliveryassign?->assigned_at ?? now(),
                        ],
                    );
                } else {
                    $order->shipment->deliveryassign()?->delete();
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Sale Payment Status
            |--------------------------------------------------------------------------
            */
            if ($order->sale) {
                $order->sale()->update([
                    'payment_status' => $this->payment_status,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Order Cancellation Reason
            |--------------------------------------------------------------------------
            */
            $order->update([
                'cancellation_reason' => $this->status === 'cancelled' ? $this->notes : null,
            ]);
        });

        session()->flash('success', 'Order updated successfully.');
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Edit Order</h4>
    </div>

    <div class="card-body">

        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <h6 class="mb-3">Validation Errors</h6>

                <ul class="list-group">
                    @foreach ($errors->all() as $error)
                        <li class="list-group-item list-group-item-danger">
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form wire:submit.prevent="update">

            <div class="mb-3">
                <label class="form-label">Order Status</label>
                <select wire:model.live="status" class="form-select">
                    <option value="">Select Order Status</option>
                    <option value="pending">Pending</option>
                    <option value="packed">Packed</option>
                    <option value="shipped">Shipped</option>
                    <option value="in_transit">On Way</option>
                    <option value="out_for_delivery">Out for Delivery</option>
                    <option value="delivered">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            @if (in_array($status, ['shipped', 'in_transit', 'out_for_delivery', 'delivered'], true))
                <div class="mb-3">
                    <label class="form-label">Delivery Boy</label>
                    <select wire:model.live="delivery_boy_id" class="form-select">
                        <option value="">Select Delivery Boy</option>

                        @foreach ($deliveryBoys as $boy)
                            <option value="{{ $boy->id }}">
                                {{ $boy->user->name ?? 'Delivery Boy' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Payment Status</label>
                <select wire:model.live="payment_status" class="form-select">
                    <option value="">Select Payment Status</option>
                    <option value="pending">Pending</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="paid">Paid</option>
                    <option value="partial">Partial</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>

            @if ($status === 'cancelled')
                <div class="mb-4">
                    <label class="form-label">Cancellation Reason</label>
                    <textarea wire:model.live="notes" rows="4" class="form-control"></textarea>
                </div>
            @endif

            <div class="d-flex justify-content-between">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary rounded-pill">
                    Back
                </a>

                <button type="submit" class="btn btn-warning rounded-pill">
                    Update Order
                </button>
            </div>

        </form>
    </div>
</div>
