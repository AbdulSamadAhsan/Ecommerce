<?php

use Livewire\Component;
use App\Models\Shipment;
use App\Models\Order;
use App\Models\Deliveryboy;
use Illuminate\Validation\Rule;

new class extends Component {
    public $shipmentId;

    public $order_id = '';
    public $deliveryboy_id = '';
    public $tracking_number = '';
    public $shipping_company = '';
    public $shipping_cost = 0;
    public $dispatch_date = '';
    public $delivery_date = '';
    public $status = 'pending';
    public $notes = '';

    public $orders = [];
    public $deliveryboys = [];

    public function mount($id)
    {
        $shipment = Shipment::findOrFail($id);

        $this->shipmentId = $shipment->id;
        $this->order_id = $shipment->order_id;
        $this->deliveryboy_id = $shipment->deliveryboy_id;
        $this->tracking_number = $shipment->tracking_number;
        $this->shipping_company = $shipment->shipping_company;
        $this->shipping_cost = $shipment->shipping_cost;
        $this->dispatch_date = $shipment->dispatch_date;
        $this->delivery_date = $shipment->delivery_date;
        $this->status = $shipment->status;
        $this->notes = $shipment->notes;

        $this->orders = Order::latest()->get();
        $this->deliveryboys = Deliveryboy::where('status', 1)->latest()->get();
    }

    protected function rules()
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'deliveryboy_id' => 'nullable|exists:deliveryboys,id',
            'tracking_number' => ['required', Rule::unique('shipments', 'tracking_number')->ignore($this->shipmentId)],
            'shipping_company' => 'required|max:255',
            'shipping_cost' => 'required|numeric|min:0',
            'dispatch_date' => 'nullable|date',
            'delivery_date' => 'nullable|date|after_or_equal:dispatch_date',
            'status' => 'required|string',
            'notes' => 'nullable|max:1000',
        ];
    }

    public function update()
    {
        $this->validate();

        Shipment::findOrFail($this->shipmentId)->update([
            'order_id' => $this->order_id,
            'deliveryboy_id' => $this->deliveryboy_id ?: null,
            'tracking_number' => $this->tracking_number,
            'shipping_company' => $this->shipping_company,
            'shipping_cost' => $this->shipping_cost,
            'dispatch_date' => $this->dispatch_date ?: null,
            'delivery_date' => $this->delivery_date ?: null,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);

        session()->flash('success', 'Shipment updated successfully.');
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Edit Shipment</h4>
    </div>

    <div class="card-body">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form wire:submit="update">

            <div class="mb-3">
                <label class="form-label">Order</label>
                <select wire:model.live="order_id" class="form-select">
                    @foreach ($orders as $order)
                        <option value="{{ $order->id }}">Order #{{ $order->id }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Delivery Boy</label>
                <select wire:model.live="deliveryboy_id" class="form-select">
                    <option value="">Select Delivery Boy</option>
                    @foreach ($deliveryboys as $boy)
                        <option value="{{ $boy->id }}">{{ $boy->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tracking Number</label>
                <input type="text" wire:model.live="tracking_number" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Shipping Company</label>
                <input type="text" wire:model.live="shipping_company" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Shipping Cost</label>
                <input type="number" step="0.01" wire:model.live="shipping_cost" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Dispatch Date</label>
                <input type="date" wire:model.live="dispatch_date" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Delivery Date</label>
                <input type="date" wire:model.live="delivery_date" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select wire:model.live="status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="packed">Packed</option>
                    <option value="dispatched">Dispatched</option>
                    <option value="in_transit">In Transit</option>
                    <option value="out_for_delivery">Out For Delivery</option>
                    <option value="delivered">Delivered</option>
                    <option value="returned">Returned</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Notes</label>
                <textarea wire:model.live="notes" rows="4" class="form-control"></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('shipments.index') }}" class="btn btn-secondary rounded-pill">
                    Back
                </a>

                <button class="btn btn-warning rounded-pill">
                    Update Shipment
                </button>
            </div>

        </form>
    </div>
</div>
