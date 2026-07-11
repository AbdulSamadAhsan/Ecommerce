<?php

use Livewire\Component;
use App\Models\Shipment;

new class extends Component {
    public string $search = '';
    public $shipments;

    public function mount(): void
    {
        $this->loadShipments();
    }

    public function updatedSearch(): void
    {
        $this->loadShipments();
    }

    public function loadShipments(): void
    {
        $this->shipments = Shipment::with(['order', 'deliveryboy'])
            ->when($this->search, function ($query) {
                $query->where('tracking_number', 'like', '%' . $this->search . '%')->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();
    }

    public function delete($id): void
    {
        Shipment::findOrFail($id)->delete();

        $this->loadShipments();

        session()->flash('success', 'Shipment deleted successfully.');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Shipments</h3>
            <p class="text-muted mb-0">Manage order shipments</p>
        </div>

        <a href="{{ route('shipments.create') }}" class="btn btn-primary rounded-pill">
            Add Shipment
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search shipment...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Order</th>
                        <th>Delivery Boy</th>
                        <th>Tracking No</th>
                        <th>Shipping Cost</th>
                        <th>Status</th>
                        <th>Delivery Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($shipments as $shipment)
                        <tr>
                            <td>#{{ $shipment['id'] }}</td>
                            <td>#{{ $shipment['order']['id'] ?? 'N/A' }}</td>
                            <td>{{ $shipment->deliveryboy->user->name ?? 'N/A' }}</td>
                            <td>{{ $shipment['tracking_number'] ?? '-' }}</td>
                            <td>Rs. {{ number_format($shipment->order->sale->shipping_cost ?? 0, 2) }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($shipment['status']) }}</span></td>
                            <td>
                                {{ $shipment->delivered_at ? date('d-F-Y', strtotime($shipment->delivered_at)) : '-' }}
                            </td>
                            <td>
                                <a href="{{ route('shipments.show', $shipment['id']) }}"
                                    class="btn btn-sm btn-info text-white rounded-pill">View</a>
                                <a href="{{ route('shipments.edit', $shipment['id']) }}"
                                    class="btn btn-sm btn-warning rounded-pill">Edit</a>
                                <button wire:click="delete({{ $shipment['id'] }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No shipments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
