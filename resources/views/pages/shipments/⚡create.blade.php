<?php

use Livewire\Component;
use App\Models\Shipment;
use App\Models\Order;
use App\Models\ShippingMethod;

new class extends Component {
    public $order_id = '';
    public $shipping_method_id = '';
    public $tracking_number = '';
    public $status = 'pending';
    public $notes = '';

    public $orders = [];
    public $shipping_methods = [];

    public function mount()
    {
        $this->orders = Order::with(['sale', 'shipment'])
            ->doesntHave('shipment')
            ->latest()
            ->get();

        $this->shipping_methods = ShippingMethod::latest()->get();

        $this->tracking_number = 'TRK-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function updatedOrderId($value)
    {
        $this->shipping_method_id = '';

        if (!$value) {
            return;
        }

        $order = Order::with('sale')->find($value);

        if (!$order || !$order->sale) {
            return;
        }

        $shippingCost = (float) $order->sale->shipping_cost;

        $shippingMethod = ShippingMethod::where('cost', $shippingCost)->first();

        if ($shippingMethod) {
            $this->shipping_method_id = $shippingMethod->id;
        }
    }

    protected function rules()
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'tracking_number' => 'required|unique:shipments,tracking_number',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ];
    }

    public function save()
    {
        $this->validate();

        Shipment::create([
            'order_id' => $this->order_id,
            'shipping_method_id' => $this->shipping_method_id,
            'tracking_number' => $this->tracking_number,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);

        session()->flash('success', 'Shipment added successfully.');

        return $this->redirectRoute('shipments.index', navigate: true);
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Add Shipment</h4>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="save">

            <div class="mb-3">
                <label class="form-label">Order</label>
                <select wire:model.live="order_id" class="form-select @error('order_id') is-invalid @enderror">
                    <option value="">Select Order</option>

                    @foreach ($orders as $order)
                        <option value="{{ $order->id }}">
                            Order #{{ $order->id }}
                            @if ($order->sale)
                                - {{ $order->sale->invoice_no }}
                                - Shipping Rs {{ number_format($order->sale->shipping_cost, 2) }}
                            @endif
                        </option>
                    @endforeach
                </select>

                @error('order_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Shipping Method</label>
                <select wire:model.live="shipping_method_id"
                    class="form-select @error('shipping_method_id') is-invalid @enderror">
                    <option value="">Select Shipping Method</option>

                    @foreach ($shipping_methods as $shipping_method)
                        <option value="{{ $shipping_method->id }}">
                            {{ $shipping_method->name }}
                            @if (isset($shipping_method->cost))
                                - Rs {{ number_format($shipping_method->cost, 2) }}
                            @endif
                        </option>
                    @endforeach
                </select>

                @error('shipping_method_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <small class="text-muted">
                    Shipping method will auto select according to order shipping cost.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Tracking Number</label>
                <input type="text" wire:model.live="tracking_number"
                    class="form-control @error('tracking_number') is-invalid @enderror">

                @error('tracking_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select wire:model.live="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="pending">Pending</option>
                </select>

                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Notes</label>
                <textarea wire:model.live="notes" rows="4" class="form-control @error('notes') is-invalid @enderror"></textarea>

                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary rounded-pill">
                Save Shipment
            </button>

        </form>
    </div>
</div>
