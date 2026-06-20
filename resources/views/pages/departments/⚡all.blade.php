<?php

use Livewire\Component;
use App\Models\Department;
new class extends Component {
    public string $search = '';

    public $departments;
    public function mount()
    {
        $this->departments = Department::withCount(['employees'])->get();
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Departments</h3>
            <p class="text-muted mb-0">Manage departments</p>
        </div>

        <a href="{{ route('departments.create') }}" class="btn btn-primary rounded-pill">
            Add Department
        </a>
    </div>

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search department...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Department</th>
                        <th>Employees</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($departments as $department)
                        <tr>
                            <td>#{{ $department['id'] }}</td>
                            <td>{{ $department['name'] }}</td>
                            <td>{{ $department['employees_count'] }}</td>
                            <td>
                                <span class="badge {{ $department['status'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $department['status'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('departments.show', $department['id']) }}"
                                    class="btn btn-sm btn-info rounded-pill text-white">

                                    <i class="bi bi-eye-fill"></i>
                                    View

                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
