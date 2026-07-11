<?php

use Livewire\Component;
use App\Models\Shipment;

new class extends Component {
    public Shipment $shipment;

    public function mount($id)
    {
        $this->shipment = Shipment::with(['order', 'deliveryboy'])->findOrFail($id);
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Shipment Details</h3>

        <a href="{{ route('shipments.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Shipment #{{ $shipment->id }}</h5>
        </div>

        <div class="card-body">
            <p><strong>Order:</strong> #{{ $shipment->order_id }}</p>
            <p><strong>Delivery Boy Name:</strong> {{ $shipment->deliveryboy->user->name ?? 'N/A' }}</p>

            <p><strong>Delivery Boy Number:</strong> {{ $shipment->deliveryboy->phone ?? 'N/A' }}</p>
            <p><strong>Tracking Number:</strong> {{ $shipment->tracking_number }}</p>

            <p><strong>Shipping Cost:</strong> Rs. {{ number_format($shipment->order->sale->shipping_cost, 2) }}</p>
            <p><strong>Dispatch Date:</strong>
                {{ $shipment->shipped_at ? date('d-F-Y', strtotime($shipment->shipped_at)) : '-' }}</p>
            <p><strong>Delivery Date:</strong>
                {{ $shipment->delivered_at ? date('d-F-Y', strtotime($shipment->delivered_at)) : '-' }}</p>

            <p>
            <p><strong>Delivery Time:</strong>
                {{ $shipment->delivered_at ? date('h:i A', strtotime($shipment->delivered_at)) : '-' }}</p>

            <p>
                <strong>Status:</strong>
                <span class="badge bg-info">
                    {{ ucwords(str_replace('_', ' ', $shipment->status)) }}
                </span>
            </p>

            <p><strong>Notes:</strong></p>
            <div class="border rounded p-3 bg-light">
                {{ $shipment->notes ?: 'No notes available.' }}
            </div>
        </div>
    </div>
</div>
