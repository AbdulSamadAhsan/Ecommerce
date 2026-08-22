<?php

use Livewire\Component;
use App\Models\Designation;

new class extends Component {
    public string $search = '';

    public array $designations = [];

    public function mount(): void
    {
        $this->loadDesignations();
    }

    public function updatedSearch(): void
    {
        $this->loadDesignations();
    }

    public function loadDesignations(): void
    {
        $this->designations = Designation::with(['department',"designations"])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')->orWhereHas('department', function ($department) {
                        $department->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->orderBy('department_id')
            ->latest()
            ->get()
            ->toArray();
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Designations</h3>
            <p class="text-muted mb-0">
                Manage department designations
            </p>
        </div>

        <a href="{{ route('departments.designations.create') }}" class="btn btn-primary rounded-pill">
            Add Designation
        </a>
    </div>

    <div class="dashboard-card">

        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search designation or department...">

        <div class="table-responsive">
            <table class="table align-middle">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($designations as $key => $designation)
                        <tr>
                            <td>
                                #{{ $key + 1 }}
                            </td>

                            <td>
                                {{ $designation['name'] }}
                            </td>

                            <td>
                                {{ $designation['department']['name'] ?? 'N/A' }}
                            </td>

                            <td>
                                <a href="{{ route('departments.designations.edit', $designation['id']) }}"
                                    class="btn btn-sm btn-info rounded-pill text-white">
                                    Edit
                                </a>

                                <a href="{{ route('departments.designations.show', $designation['id']) }}"
                                    class="btn btn-sm btn-success rounded-pill">
                                    View
                                </a>

                                <button class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No designations found.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>
    </div>
</div>
