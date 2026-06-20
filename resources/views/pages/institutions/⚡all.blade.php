<?php

use Livewire\Component;
use App\Models\Institution;

new class extends Component {
    public string $search = '';

    public function getInstitutionsProperty()
    {
        return Institution::when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))->latest()->get();
    }

    public function delete($id): void
    {
        Institution::findOrFail($id)->delete();

        session()->flash('success', 'Institution deleted successfully.');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Institutions</h3>
            <p class="text-muted mb-0">Manage institutions</p>
        </div>

        <a href="{{ route('institutions.create') }}" class="btn btn-primary rounded-pill">
            Add Institution
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control rounded-4 mb-4"
            placeholder="Search institution...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>City</th>

                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($this->institutions as $institution)
                        <tr>
                            <td>#{{ $institution->id }}</td>
                            <td>{{ $institution->name }}</td>
                            <td>{{ ucfirst($institution->type ?? 'N/A') }}</td>
                            <td>{{ $institution->city ?? 'N/A' }}</td>

                            <td>
                                <span class="badge {{ $institution->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $institution->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('institutions.edit', $institution->id) }}"
                                    class="btn btn-sm btn-primary rounded-pill">
                                    Edit
                                </a>
                                <a href="{{ route('institutions.show', $institution->id) }}"
                                    class="btn btn-sm btn-secondary rounded-pill">
                                    Show
                                </a>


                                <button wire:click="delete({{ $institution->id }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No institutions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
