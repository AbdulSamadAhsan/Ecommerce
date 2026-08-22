<?php

use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public $orders;

    public $cancelOrderId = null;
    public $cancellation_reason = '';
    public $cancellation_window = 0;
    public $cancellation_penalty = 0;
    public function mount()
    {
        $this->loadOrders();
        $setting = Setting::first();
        $this->cancellation_window = (int) $setting?->cancellation_window ?? 35;
        $this->cancellation_penalty = (int) $setting?->cancellation_penalty ?? 0;
    }

    public function loadOrders()
    {
        $this->orders = auth('customer')->user()->customer->sales()->with('orderNumber.shipment')->latest()->get();
    }

    public function canCancel($sale): bool
    {
        $order = $sale->orderNumber;

        if (!$order) {
            return false;
        }

        if ($order->order_status === 'cancelled') {
            return false;
        }

        if (in_array($order->shipment?->status, ['shipped', 'out_for_delivery', 'delivered', 'cancelled'])) {
            return false;
        }

        return now()->lte($order->created_at->copy()->addMinutes($this->cancellation_window));
    }

    public function openCancelModal($orderId): void
    {
        $this->cancelOrderId = $orderId;
        $this->cancellation_reason = '';

        $this->dispatch('show-cancel-modal');
    }

    public function cancelOrder(): void
    {
        $this->validate([
            'cancellation_reason' => 'required|min:5|max:500',
        ]);

        $customer = auth('customer')->user()->customer;

        $order = Order::with(['sale.customer.wallet.transactions', 'sale.items.product', 'shipment'])
            ->where('id', $this->cancelOrderId)
            ->firstOrFail();

        if ($order->sale->customer_id !== $customer->id) {
            abort(403);
        }

        if (now()->gt($order->created_at->copy()->addMinutes($this->cancellation_window))) {
            session()->flash('error', "You can cancel the order only within {$this->cancellation_window} minutes of order placement.");
            return;
        }

        if (in_array($order->shipment?->status, ['shipped', 'out_for_delivery', 'delivered', 'cancelled'])) {
            session()->flash('error', 'Order cannot be cancelled now.');
            return;
        }

        DB::transaction(function () use ($order) {
            if ((float) $order->sale->paid_amount > 0 && $order->sale->payment_method !== 'cash') {
                $amountPaid = (float) $order->sale->paid_amount;

                $wallet = $order->sale->customer->wallet;

                if ($wallet) {
                    $transactions = $wallet->transactions()->create([
                        'reference_id' => $order->id,
                        'amount' => $this->cancellation_penalty,
                        'type' => 'debit',
                        'description' => 'Order #' . $order->id . ' cancellation charge',
                    ]);

                    $transaction = $wallet->transactions()->create([
                        'reference_id' => $order->id,
                        'amount' => $amountPaid,
                        'type' => 'credit',
                        'description' => 'Order #' . $order->id . ' refunded',
                    ]);

                    $wallet->decrement('balance', $this->cancellation_penalty);
                    $wallet->increment('balance', $amountPaid);
                }
            }

            foreach ($order->sale->items as $item) {
                $product = $item->product;

                if (!$product) {
                    continue;
                }

                $stockBefore = (int) $product->quantity;
                $stockAfter = $stockBefore + (int) $item->quantity;

                $product->update([
                    'quantity' => $stockAfter,
                ]);

                $product->stocks()->updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'warehouse_id' => $product->warehouse_id,
                    ],
                    [
                        'quantity' => $stockAfter,
                        'minimum_stock' => $product->minimum_stock,
                    ],
                );

                $product->stockmovement()->create([
                    'warehouse_id' => $product->warehouse_id,
                    'supplier_id' => $product->supplier_id,
                    'product_id' => $product->id,
                    'type' => 'return',
                    'quantity' => $item->quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reference_no' => 'ORD-CANCEL-' . $order->id,
                    'remarks' => 'Stock returned because order #' . $order->id . ' was cancelled',
                ]);
            }
            $order->sale()->update([
                'payment_status' => 'refunded',
            ]);
            $order->update([
                'cancellation_reason' => $this->cancellation_reason,
            ]);

            $order->shipment()->update([
                'status' => 'cancelled',
            ]);
        });

        $this->cancelOrderId = null;
        $this->cancellation_reason = '';

        $this->dispatch('hide-cancel-modal');

        session()->flash('success', 'Order cancelled successfully.');

        $this->loadOrders();
    }
};
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">Order History</h2>

    <div class="row g-4">
        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">

                    @if (session()->has('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0">My Orders</h4>
                        <span class="badge bg-primary rounded-pill">{{ count($orders) }} Orders</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($orders as $order)
                                    @php
                                        $mainOrder = $order->orderNumber;
                                        $status =
                                            $mainOrder?->shipment?->status ?? ($mainOrder?->order_status ?? 'pending');
                                    @endphp

                                    <tr>
                                        <td class="fw-semibold">#{{ $mainOrder?->id }}</td>

                                        <td>
                                            <p> {{ $mainOrder?->order_date ? date('d-F-Y', strtotime($mainOrder->created_at)) : '-' }}
                                            </p>
                                            {{ $mainOrder?->order_date ? date('h:i A', strtotime($mainOrder->created_at)) : '-' }}
                                        </td>

                                        <td>Rs {{ number_format($order->total_amount) }}</td>

                                        <td>
                                            <span
                                                class="badge {{ $status === 'cancelled' ? 'bg-danger' : 'bg-success' }}">
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </span>
                                        </td>

                                        <td>{{ ucfirst($order->payment_status) }}</td>

                                        <td>
                                            <a wire:navigate
                                                href="{{ route('customer.order.detail', $mainOrder->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill">
                                                View Detail
                                            </a>

                                            @if ($this->canCancel($order))
                                                <button type="button"
                                                    wire:click="openCancelModal({{ $mainOrder->id }})"
                                                    class="btn btn-sm btn-outline-danger rounded-pill">
                                                    Cancel
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="cancelOrderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Cancel Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label fw-semibold">Cancellation Reason</label>

                    <textarea wire:model="cancellation_reason" class="form-control" rows="4"
                        placeholder="Please enter reason for cancellation"></textarea>

                    @error('cancellation_reason')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="button" wire:click="cancelOrder" class="btn btn-danger rounded-pill">
                        Confirm Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('show-cancel-modal', () => {
        new bootstrap.Modal(document.getElementById('cancelOrderModal')).show();
    });

    window.addEventListener('hide-cancel-modal', () => {
        bootstrap.Modal.getInstance(document.getElementById('cancelOrderModal'))?.hide();
    });
</script>
