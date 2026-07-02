<?php

use Livewire\Component;
use App\Models\Deliveryboy;

new class extends Component {
    public Deliveryboy $deliveryboy;

    public function mount($id)
    {
        $this->deliveryboy = Deliveryboy::with(['user', 'shipments.order.sale.customer.user'])
            ->withCount('shipments')
            ->findOrFail($id);
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Delivery Boy Details</h3>

        <a href="{{ route('deliveryboys.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $deliveryboy->user->name ?? 'Delivery Boy' }}</h5>
        </div>

        <div class="card-body">
            <p><strong>ID:</strong> #{{ $deliveryboy->id }}</p>
            <p><strong>Email:</strong> {{ $deliveryboy->user->email ?? '-' }}</p>
            <p><strong>Vehicle Type:</strong> {{ ucfirst($deliveryboy->vehicle_type) }}</p>
            <p><strong>Vehicle Number:</strong> {{ $deliveryboy->vehicle_number ?? '-' }}</p>
            <p><strong>Total Shipments:</strong> {{ $deliveryboy->shipments_count }}</p>

            <p>
                <strong>Status:</strong>
                <span class="badge {{ $deliveryboy->is_available ? 'bg-success' : 'bg-danger' }}">
                    {{ $deliveryboy->is_available ? 'Available' : 'Unavailable' }}
                </span>
            </p>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Assigned Orders</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order</th>
                            <th>Tracking No</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Shipment Status</th>
                            <th>Assignment Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($deliveryboy->shipments as $shipment)
                            <tr>
                                <td>#{{ $shipment->order->id ?? 'N/A' }}</td>
                                <td>{{ $shipment->tracking_number ?? '-' }}</td>
                                <td>
                                    {{ $shipment->order->sale->customer->user->name ?? ($shipment->order->sale->customer->name ?? 'N/A') }}
                                </td>
                                <td>
                                    Rs {{ number_format($shipment->order->sale->total_amount ?? 0, 2) }}
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst($shipment->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ ucfirst($shipment->pivot->status ?? 'assigned') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No assigned orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
