<?php

use Livewire\Component;
use App\Models\ShippingMethod;

new class extends Component {
    public string $search = '';
    public array $shippingMethods = [];

    public function mount(): void
    {
        $this->loadShippingMethods();
    }

    public function updatedSearch(): void
    {
        $this->loadShippingMethods();
    }

    public function loadShippingMethods(): void
    {
        $this->shippingMethods = ShippingMethod::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get()
            ->toArray();
    }

    public function delete($id): void
    {
        ShippingMethod::findOrFail($id)->delete();

        $this->loadShippingMethods();

        session()->flash('success', 'Shipping method deleted successfully.');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Shipping Methods</h3>
            <p class="text-muted mb-0">Manage shipping methods</p>
        </div>

        <a href="{{ route('shipping-methods.create') }}" class="btn btn-primary rounded-pill">
            Add Shipping Method
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search shipping method...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Cost</th>
                        <th>Estimated Days</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($shippingMethods as $method)
                        <tr>
                            <td>#{{ $method['id'] }}</td>
                            <td>{{ $method['name'] }}</td>
                            <td>Rs. {{ number_format($method['cost'], 2) }}</td>
                            <td>{{ $method['estimated_days'] ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $method['is_active'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $method['is_active'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('shipping-methods.show', $method['id']) }}"
                                    class="btn btn-sm btn-info text-white rounded-pill">
                                    View
                                </a>

                                <a href="{{ route('shipping-methods.edit', $method['id']) }}"
                                    class="btn btn-sm btn-warning rounded-pill">
                                    Edit
                                </a>

                                <button wire:click="delete({{ $method['id'] }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No shipping methods found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
