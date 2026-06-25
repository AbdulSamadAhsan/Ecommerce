<?php

use Livewire\Component;
use App\Models\Tax;

new class extends Component {
    public string $search = '';
    public array $taxes = [];

    public function mount(): void
    {
        $this->loadTaxes();
    }

    public function updatedSearch(): void
    {
        $this->loadTaxes();
    }

    public function loadTaxes(): void
    {
        $this->taxes = Tax::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')->orWhere('type', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get()
            ->toArray();
    }

    public function delete($id): void
    {
        Tax::findOrFail($id)->delete();

        $this->loadTaxes();

        session()->flash('success', 'Tax deleted successfully.');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Taxes</h3>
            <p class="text-muted mb-0">Manage taxes</p>
        </div>

        <a href="{{ route('taxes.create') }}" class="btn btn-primary rounded-pill">
            Add Tax
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4" placeholder="Search tax...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Rate</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($taxes as $tax)
                        <tr>
                            <td>#{{ $tax['id'] }}</td>
                            <td>{{ $tax['name'] }}</td>
                            <td>{{ $tax['rate'] }}%</td>
                            <td>{{ ucfirst($tax['type']) }}</td>
                            <td>
                                <span class="badge {{ $tax['is_active'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $tax['is_active'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('taxes.show', $tax['id']) }}"
                                    class="btn btn-sm btn-info text-white rounded-pill">View</a>
                                <a href="{{ route('taxes.edit', $tax['id']) }}"
                                    class="btn btn-sm btn-warning rounded-pill">Edit</a>
                                <button wire:click="delete({{ $tax['id'] }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No taxes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
