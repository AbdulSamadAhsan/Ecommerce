<?php

use Livewire\Component;
use App\Models\Designation;

new class extends Component {
    public int $id;

    public array $designation = [];

    public array $jobs = [];

    public function mount($id): void
    {
        $this->id = (int) $id;

        $designationData = Designation::withCount('jobPostings')
            ->with(['department', 'jobPostings.department', 'jobApplications'])
            ->findOrFail($this->id);
        dd($designationData);
        $this->designation = [
            'id' => $designationData->id,
            'name' => $designationData->name,
            'description' => $designationData->description ?? 'No description available.',
            'department' => $designationData->department?->name ?? 'No Department',
            'status' => (bool) $designationData->status,
            'jobs_count' => $designationData->job_postings_count,
            'created_at' => $designationData->created_at?->format('d M Y'),
        ];

        $this->jobs = $designationData->jobPostings
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'job_title' => $job->job_title,
                    'work_mode' => $job->work_mode ?? 'N/A',
                    'department' => $job->department?->name ?? 'No Department',
                    'status' => (bool) $job->status,
                ];
            })
            ->values()
            ->toArray();
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Designation Details</h3>

            <p class="text-muted mb-0">
                Designation information and related job postings
            </p>
        </div>

        <a href="{{ route('departments.designations.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    {{-- Statistics --}}
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">

                <div class="card-body text-center">

                    <h6>Total Jobs</h6>

                    <h3 class="fw-bold text-primary">
                        {{ $designation['jobs_count'] }}
                    </h3>

                </div>

            </div>
        </div>

    </div>

    {{-- Designation Details --}}
    <div class="card border-0 shadow mb-4">

        <div class="card-body">

            <h4 class="fw-bold">
                {{ $designation['name'] }}
            </h4>

            <hr>

            <p>
                <strong>Department:</strong>

                {{ $designation['department'] }}
            </p>

            <p>
                <strong>Description:</strong>

                {{ $designation['description'] }}
            </p>

            <p>
                <strong>Created:</strong>

                {{ $designation['created_at'] }}
            </p>

            <p>
                <strong>Status:</strong>

                @if ($designation['status'])
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

    {{-- Jobs --}}
    <div class="card border-0 shadow">

        <div class="card-header bg-light">

            <h5 class="mb-0">
                Jobs for this Designation
            </h5>

        </div>

        <div class="card-body table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Work Mode</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse ($jobs as $job)
                        <tr>

                            <td>
                                #{{ $job['id'] }}
                            </td>

                            <td>
                                {{ $job['job_title'] }}
                            </td>

                            <td>
                                {{ $job['department'] }}
                            </td>

                            <td>
                                {{ ucfirst($job['work_mode']) }}
                            </td>

                            <td>

                                @if ($job['status'])
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

                                <a href="{{ route('jobs.show', $job['id']) }}"
                                    class="btn btn-sm btn-primary rounded-pill">

                                    View

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center text-danger fw-bold py-4">

                                No Job Posting For
                                {{ $designation['name'] }}

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
