<?php

use Livewire\Component;
use App\Models\Department;
new class extends Component {
    public int $id;
    public $department;
    public $employees = [];
    public $department_jobs = [];
    public $designations = [];
    public int $department_application_count;
    public $applications;
    public function mount($id): void
    {
        $this->id = (int) $id;
        $this->department = Department::withCount(['employees', 'job', 'applications', 'designations'])
            ->with(['employees', 'job', 'designations', 'applications'])
            ->find($this->id);

        $this->department_application_count = (int) $this->department->applications_count;
        $this->employees = $this->department->employees;
        $this->department_jobs = $this->department->job;
        $this->applications = $this->department->applications;
        $this->designations = $this->department->designations;
    }

    public function getEmployeeCountProperty(): int
    {
        return count($this->employees);
    }
    public function getJobCountProperty(): int
    {
        return count($this->department_jobs);
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
        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <h6>Total Jobs</h6>

                    <h2 class="fw-bold text-primary">
                        {{ $this->jobCount }}
                    </h2>

                </div>


            </div>

        </div>
        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <h6>Total Applications</h6>

                    <h2 class="fw-bold text-primary">
                        {{ count($this->applications) }}
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
                            <td>
                                <a href="{{ route('employees.show', $employee->id) }}"
                                    class="btn btn-sm btn-info rounded-pill text-white">View</a>
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

    <div class="card border-0 shadow">

        <div class="card-header bg-light">

            <h5 class="mb-0">
                Department Jobs
            </h5>

        </div>

        <div class="card-body table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Job Title</th>
                        <th>Work Mode</th>
                        <th>Salary Range</th>
                        <th>Total Application</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach ($department->job as $department_job)
                        <tr>
                            <td>{{ $department_job->id }}</td>
                            <td>{{ $department_job->job_title }}</td>
                            <td>{{ $department_job->work_mode }}</td>
                            <td>
                                {{ $department_job->minimum_salary }}-
                                {{ $department_job->maximum_salary }}
                            </td>
                            <td>{{ $department_job->applications->count() }}</td>
                            <td>
                                <a href="{{ route('jobs.applications.show', $department_job['id']) }}"
                                    class="btn btn-sm btn-primary rounded-pill">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @if (count($department->job) == 0)
                        <tr>
                            <td>
                                No Job in {{ $department['name'] }} Department
                            </td>
                        </tr>
                    @endif
                </tbody>

            </table>

        </div>

    </div>

    <div class="card border-0 shadow">

        <div class="card-header bg-light">

            <h5 class="mb-0">
                Department Job Applicants
            </h5>

        </div>

        <div class="card-body table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Applicant Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Job Title</th>
                        <th>Action</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($applications as $application)
                        <tr>
                            <td>{{ $application->id }}</td>
                            <td>{{ $application->applicant->full_name }}</td>
                            <td>{{ $application->applicant->phone }}</td>
                            <td>{{ $application->applicant->email }}</td>
                            <td>
                                {{ $application->jobPosting->job_title }}
                            </td>
                            <td>
                                <a href="{{ route('jobs.applications.show', $application['id']) }}"
                                    class="btn btn-sm btn-primary
                                            rounded-pill">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>


            </table>

        </div>

    </div>
    <div class="card border-0 shadow">

        <div class="card-header bg-light">

            <h5 class="mb-0">
                Department Designation
            </h5>

        </div>

        <div class="card-body table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>#</th>
                        <th> Name</th>

                    </tr>

                </thead>
                <tbody>
                    @foreach ($designations as $key => $designation)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $designation['name'] }}</td>
                        </tr>
                    @endforeach
                </tbody>


            </table>

        </div>

    </div>


</div>
