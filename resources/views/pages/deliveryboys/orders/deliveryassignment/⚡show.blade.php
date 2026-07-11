<?php

use Livewire\Component;
use App\Models\DeliveryAssignment as DeliveryBoyAssignment;

new class extends Component {
    public DeliveryBoyAssignment $assignment;

    public function mount($id)
    {
        $this->assignment = DeliveryBoyAssignment::with(['order', 'shipment', 'deliveryboy'])->findOrFail($id);
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Assignment Details</h3>

        <a href="{{ route('delivery-boy-assignments.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                Assignment #{{ $assignment->id }}
            </h5>
        </div>

        <div class="card-body">
            <p><strong>Order:</strong> #{{ $assignment->order_id }}</p>
            <p><strong>Shipment:</strong> {{ $assignment->shipment->tracking_number ?? 'N/A' }}</p>
            <p><strong>Delivery Boy:</strong> {{ $assignment->deliveryboy->name ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $assignment->deliveryboy->phone ?? 'N/A' }}</p>
            <p><strong>Assigned Date:</strong> {{ $assignment->assigned_date }}</p>

            <p>
                <strong>Status:</strong>
                <span class="badge bg-info">
                    {{ ucfirst($assignment->status) }}
                </span>
            </p>

            <p><strong>Notes:</strong></p>
            <div class="border rounded p-3 bg-light">
                {{ $assignment->notes ?: 'No notes available.' }}
            </div>
        </div>
    </div>
</div>
