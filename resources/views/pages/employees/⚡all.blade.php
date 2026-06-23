<?php

use Livewire\Component;
use App\Models\Employee;
new class extends Component {
    public string $search = '';

    public $employees;
    public function mount()
    {
        $this->employees = Employee::with('institute')->get();
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Employees</h3>
            <p class="text-muted mb-0">Manage company employees</p>
        </div>

        <a href="{{ route('employees.create') }}" class="btn btn-primary rounded-pill">
            Add Employee
        </a>
    </div>

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4" placeholder="Search employee...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Phone</th>
                        <th>Designation</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($employees as $employee)
                        <tr>
                            <td>#{{ $employee->id }}</td>
                            <td>{{ $employee->user->name }}</td>
                            <td>{{ $employee->department->name }}</td>
                            <td>{{ $employee->phone }}</td>
                            <td>{{ $employee->designation }}</td>
                            <td>
                                <span class="badge {{ $employee['status'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $employee['status'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('employees.show', $employee['id']) }}"
                                    class="btn btn-sm btn-info rounded-pill text-white">View</a>

                                <button class="btn btn-sm btn-danger rounded-pill">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
