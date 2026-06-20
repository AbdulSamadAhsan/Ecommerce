<?php

use App\Models\Tax;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function toggleStatus(int $id): void
    {
        $tax = Tax::findOrFail($id);
        $tax->update(['status' => ! $tax->status]);
        session()->flash('success', 'Tax status updated successfully.');
    }

    public function delete(int $id): void
    {
        Tax::findOrFail($id)->delete();
        session()->flash('success', 'Tax deleted successfully.');
    }

    public function taxes()
    {
        return Tax::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('type', 'like', '%' . $this->search . '%')
                    ->orWhere('rate', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);
    }
};

?>

<div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Taxes</h3>
            <p class="text-muted mb-0">Manage percentage and fixed tax rules.</p>
        </div>
        <a href="{{ route('taxes.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-circle me-1"></i> Add Tax
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control rounded-pill" placeholder="Search tax name, type or rate...">
                </div>
                <div class="col-md-4">
                    <select wire:model.live="perPage" class="form-select rounded-pill">
                        <option value="10">10 Records</option>
                        <option value="25">25 Records</option>
                        <option value="50">50 Records</option>
                    </select>
                </div>
            </div>

            @php($taxes = $this->taxes())

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Rate</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($taxes as $tax)
                            <tr wire:key="tax-{{ $tax->id }}">
                                <td>{{ $tax->id }}</td>
                                <td class="fw-semibold">{{ $tax->name }}</td>
                                <td><span class="badge bg-info text-dark text-capitalize">{{ $tax->type }}</span></td>
                                <td>{{ $tax->type === 'percentage' ? $tax->rate . '%' : number_format($tax->rate, 2) }}</td>
                                <td>
                                    <button type="button" wire:click="toggleStatus({{ $tax->id }})" class="btn btn-sm {{ $tax->status ? 'btn-success' : 'btn-secondary' }} rounded-pill">
                                        {{ $tax->status ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('taxes.show', $tax->id) }}" class="btn btn-sm btn-light rounded-pill"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('taxes.edit', $tax->id) }}" class="btn btn-sm btn-warning rounded-pill"><i class="bi bi-pencil-square"></i></a>
                                    <button type="button" wire:click="delete({{ $tax->id }})" wire:confirm="Delete this tax?" class="btn btn-sm btn-danger rounded-pill"><i class="bi bi-trash"></i></button>
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

            {{ $taxes->links() }}
        </div>
    </div>
</div>
