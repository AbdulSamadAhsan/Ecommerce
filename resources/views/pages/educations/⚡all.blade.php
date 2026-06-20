<?php

use Livewire\Component;
use App\Models\Education;

new class extends Component {
    public string $search = '';

    public function getEducationsProperty()
    {
        return Education::with('institution')->when($this->search, fn($q) => $q->where('degree', 'like', '%' . $this->search . '%')->orWhere('field_of_study', 'like', '%' . $this->search . '%'))->latest()->get();
    }

    public function delete($id): void
    {
        Education::findOrFail($id)->delete();

        session()->flash('success', 'Education deleted successfully.');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Educations</h3>
            <p class="text-muted mb-0">Manage education records</p>
        </div>

        <a href="{{ route('educations.create') }}" class="btn btn-primary rounded-pill">
            Add Education
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live.debounce.300ms="search" class="form-control rounded-4 mb-4"
            placeholder="Search education...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Degree</th>
                        <th>Short Code</th>
                        <th>Institution</th>

                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($this->educations as $education)
                        <tr>
                            <td>#{{ $education->id }}</td>
                            <td>{{ $education->name }}</td>
                            <td>{{ $education->short_code }}</td>
                            <td>{{ $education->institution?->name ?? 'N/A' }}</td>

                            <td>
                                <span class="badge {{ $education->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $education->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('educations.edit', $education->id) }}"
                                    class="btn btn-sm btn-primary rounded-pill">
                                    Edit
                                </a>

                                <button wire:click="delete({{ $education->id }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No education records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
