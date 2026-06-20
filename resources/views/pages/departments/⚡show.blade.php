<?php

use Livewire\Component;
use App\Models\Department;
new class extends Component {
    public int $id;

    public $department;

    public $employees = [];

    public function mount($id): void
    {
        $this->id = (int) $id;

        $this->department = Department::withCount(['employees'])
            ->with(['employees'])
            ->find($this->id);

        $this->employees = $this->department->employees;
    }

    public function getEmployeeCountProperty(): int
    {
        return count($this->employees);
    }
};

?>

<div>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold">
                Department Details
            </h3>

            <p class="text-muted">
                Department information and employees
            </p>
        </div>

        <a href="{{ route('departments.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>

    </div>

    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <h6>Total Employees</h6>

                    <h2 class="fw-bold text-primary">
                        {{ $this->employeeCount }}
                    </h2>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow mb-4">

        <div class="card-body">

            <h4 class="fw-bold">
                {{ $department['name'] }}
            </h4>

            <hr>

            <p>
                <strong>Description:</strong>
                {{ $department['description'] }}
            </p>


            <p>
                <strong>Created:</strong>
                {{ $department['created_at'] }}
            </p>

            <p>
                <strong>Status:</strong>

                @if ($department['status'])
                    <span class="badge bg-success">
                        Active
                    </span>
                @else
                    <span class="badge bg-danger">
                        Inactive
                    </span>
                @endif

            </p>

        </div>

    </div>

    <div class="card border-0 shadow">

        <div class="card-header bg-light">

            <h5 class="mb-0">
                Department Employees
            </h5>

        </div>

        <div class="card-body table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Phone</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach ($employees as $employee)
                        <tr>

                            <td>#{{ $employee->id }}</td>

                            <td>{{ $employee->user->name }}</td>

                            <td>{{ $employee->designation }}</td>

                            <td>{{ $employee->phone }}</td>

                            <td>

                                @if ($employee->status)
                                    <span class="badge bg-success">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>
                                @endif

                            </td>

                        </tr>
                    @endforeach
                    @if (count($employees) == 0)
                        <tr>
                            <td>
                                No Employee in {{ $department['name'] }} Department
                            </td>
                        </tr>
                    @endif
                </tbody>

            </table>

        </div>

    </div>

</div>
