<?php

use Livewire\Component;
use App\Models\DeliveryAssignment as DeliveryBoyAssignment;

new class extends Component {
    public string $search = '';

    public $assignments;

    public function mount(): void
    {
        $this->loadAssignments();
    }

    public function updatedSearch(): void
    {
        $this->loadAssignments();
    }

    public function loadAssignments(): void
    {
        $this->assignments = DeliveryBoyAssignment::with(['order', 'shipment', 'deliveryboy'])
            ->when($this->search, function ($query) {
                $query->where('status', 'like', '%' . $this->search . '%')->orWhereHas('deliveryboy', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();
    }

    public function delete($id): void
    {
        DeliveryBoyAssignment::findOrFail($id)->delete();

        $this->loadAssignments();

        session()->flash('success', 'Assignment deleted successfully.');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Delivery Boy Assignments</h3>
            <p class="text-muted mb-0">Manage delivery assignments</p>
        </div>

        <a href="{{ route('delivery-boy-assignments.create') }}" class="btn btn-primary rounded-pill">
            Assign Delivery Boy
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search assignment...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Order</th>
                        <th>Shipment</th>
                        <th>Delivery Boy</th>
                        <th>Assigned Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($assignments as $assignment)
                        <tr>
                            <td>#{{ $assignment['id'] }}</td>

                            <td>#{{ $assignment['order']['id'] ?? 'N/A' }}</td>

                            <td>
                                {{ $assignment['shipment']['tracking_number'] ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $assignment->deliveryboy->user->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $assignment['assigned_at'] }}
                            </td>

                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst($assignment['status']) }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('delivery-boy-assignments.show', $assignment['id']) }}"
                                    class="btn btn-sm btn-info text-white rounded-pill">
                                    View
                                </a>



                                <button wire:click="delete({{ $assignment['id'] }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No assignments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
