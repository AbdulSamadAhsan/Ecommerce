<?php

use Livewire\Component;
use App\Models\DeliveryBoy;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
new class extends Component {
    public string $search = '';
    public $deliveryboys;

    public function mount(): void
    {
        $this->loadDeliveryboys();
    }

    public function updatedSearch(): void
    {
        $this->loadDeliveryboys();
    }

    public function loadDeliveryboys(): void
    {
        $this->deliveryboys = DeliveryBoy::query()
            ->when($this->search, function ($query) {
                $query
                    ->where('user.name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('user.email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();
    }

    public function delete($id): void
    {
        Deliveryboy::findOrFail($id)->delete();

        $this->loadDeliveryboys();

        session()->flash('success', 'Delivery boy deleted successfully.');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Delivery Boys</h3>
            <p class="text-muted mb-0">Manage delivery staff</p>
        </div>

        <a href="{{ route('deliveryboys.create') }}" class="btn btn-primary rounded-pill">
            Add Delivery Boy
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search delivery boy...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($deliveryboys as $boy)
                        <tr>
                            <td>#{{ $boy['id'] }}</td>
                            <td>{{ $boy->user->name }}</td>
                            <td>{{ $boy->phone }}</td>
                            <td>{{ $boy->user->email ?? '-' }}</td>
                            <td>{{ $boy['vehicle_number'] ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $boy['status'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $boy['status'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('deliveryboys.show', $boy['id']) }}"
                                    class="btn btn-sm btn-info text-white rounded-pill">View</a>
                                <a href="{{ route('deliveryboys.edit', $boy['id']) }}"
                                    class="btn btn-sm btn-warning rounded-pill">Edit</a>
                                <button wire:click="delete({{ $boy['id'] }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No delivery boys found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
