<?php

use Livewire\Component;
use App\Models\JobPosting;

new class extends Component {
    public int $id;

    public array $jobPosting = [];
    public array $jobApplications = [];
    public function mount($id): void
    {
        $this->id = (int) $id;

        $job = JobPosting::with(['department:name,id', 'creator', 'applications:id,job_posting_id,father_name,full_name,cnic,created_at,status'])->findOrFail($this->id);
        $this->jobApplications = $job->applications->toArray();

        $this->jobPosting = [
            'id' => $job->id,

            'department' => $job->department?->name ?? 'No Department',

            'created_by' => $job->creator?->name ?? 'Unknown',

            'job_title' => $job->job_title,

            'description' => $job->description,

            'responsibilities' => $job->responsibilities,

            'requirements' => $job->requirements,

            'benefits' => $job->benefits,

            'vacancies' => $job->vacancies,

            'minimum_salary' => $job->minimum_salary,

            'maximum_salary' => $job->maximum_salary,

            'employment_type' => $job->employment_type,

            'work_mode' => $job->work_mode,

            'closing_date' => $job->closing_date ? \Carbon\Carbon::parse($job->closing_date)->format('d M Y') : 'No Closing Date',

            'is_active' => (bool) $job->is_active,

            'created_at' => $job->created_at?->format('d M Y'),
        ];
    }
};
?>

<div>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Job Posting Details
            </h3>

            <p class="text-muted mb-0">
                View complete information about this job posting
            </p>
        </div>

        <a href="{{ route('job_postings.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>

    </div>


    {{-- Statistics --}}
    <div class="row mb-4">

        {{-- Vacancies --}}
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Vacancies
                    </h6>

                    <h3 class="fw-bold text-primary">
                        {{ $jobPosting['vacancies'] }}
                    </h3>

                </div>

            </div>
        </div>


        {{-- Employment Type --}}
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Employment Type
                    </h6>

                    <h5 class="fw-bold text-success text-capitalize">
                        {{ str_replace('_', ' ', $jobPosting['employment_type']) }}
                    </h5>

                </div>

            </div>
        </div>


        {{-- Work Mode --}}
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Work Mode
                    </h6>

                    <h5 class="fw-bold text-info text-capitalize">
                        {{ $jobPosting['work_mode'] }}
                    </h5>

                </div>

            </div>
        </div>


        {{-- Status --}}
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Status
                    </h6>

                    @if ($jobPosting['is_active'])
                        <span class="badge bg-success fs-6 px-3 py-2">
                            Active
                        </span>
                    @else
                        <span class="badge bg-danger fs-6 px-3 py-2">
                            Inactive
                        </span>
                    @endif

                </div>

            </div>
        </div>

    </div>


    {{-- Basic Information --}}
    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                Job Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-8">

                    <h3 class="fw-bold mb-3">
                        {{ $jobPosting['job_title'] }}
                    </h3>

                </div>

                <div class="col-md-4 text-md-end">

                    @if ($jobPosting['is_active'])
                        <span class="badge bg-success px-3 py-2">
                            Active
                        </span>
                    @else
                        <span class="badge bg-danger px-3 py-2">
                            Inactive
                        </span>
                    @endif

                </div>

            </div>

            <hr>


            <div class="row">

                {{-- Department --}}
                <div class="col-md-4 mb-3">

                    <strong>
                        Department
                    </strong>

                    <p class="text-muted mb-0">
                        {{ $jobPosting['department'] }}
                    </p>

                </div>


                {{-- Created By --}}
                <div class="col-md-4 mb-3">

                    <strong>
                        Created By
                    </strong>

                    <p class="text-muted mb-0">
                        {{ $jobPosting['created_by'] }}
                    </p>

                </div>


                {{-- Created At --}}
                <div class="col-md-4 mb-3">

                    <strong>
                        Created At
                    </strong>

                    <p class="text-muted mb-0">
                        {{ $jobPosting['created_at'] ?? 'N/A' }}
                    </p>

                </div>


                {{-- Employment Type --}}
                <div class="col-md-4 mb-3">

                    <strong>
                        Employment Type
                    </strong>

                    <p class="text-muted mb-0 text-capitalize">
                        {{ str_replace('_', ' ', $jobPosting['employment_type']) }}
                    </p>

                </div>


                {{-- Work Mode --}}
                <div class="col-md-4 mb-3">

                    <strong>
                        Work Mode
                    </strong>

                    <p class="text-muted mb-0 text-capitalize">
                        {{ $jobPosting['work_mode'] }}
                    </p>

                </div>


                {{-- Vacancies --}}
                <div class="col-md-4 mb-3">

                    <strong>
                        Vacancies
                    </strong>

                    <p class="text-muted mb-0">
                        {{ $jobPosting['vacancies'] }}
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- Salary --}}
    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                Salary Information
            </h5>
        </div>

        <div class="card-body">

            @if ($jobPosting['minimum_salary'] !== null || $jobPosting['maximum_salary'] !== null)

                <h4 class="fw-bold text-success">

                    @if ($jobPosting['minimum_salary'] !== null)
                        {{ number_format($jobPosting['minimum_salary'], 2) }}
                    @endif

                    @if ($jobPosting['minimum_salary'] !== null && $jobPosting['maximum_salary'] !== null)
                        -
                    @endif

                    @if ($jobPosting['maximum_salary'] !== null)
                        {{ number_format($jobPosting['maximum_salary'], 2) }}
                    @endif

                </h4>

                <p class="text-muted mb-0">
                    Salary range
                </p>
            @else
                <p class="text-muted mb-0">
                    Salary information not provided.
                </p>

            @endif

        </div>

    </div>


    {{-- Description --}}
    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                Job Description
            </h5>
        </div>

        <div class="card-body">

            <p class="mb-0">
                {!! nl2br(e($jobPosting['description'])) !!}
            </p>

        </div>

    </div>


    {{-- Responsibilities --}}
    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                Responsibilities
            </h5>
        </div>

        <div class="card-body">

            @if ($jobPosting['responsibilities'])
                <div>
                    {!! nl2br(e($jobPosting['responsibilities'])) !!}
                </div>
            @else
                <p class="text-muted mb-0">
                    No responsibilities provided.
                </p>
            @endif

        </div>

    </div>


    {{-- Requirements --}}
    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                Requirements
            </h5>
        </div>

        <div class="card-body">

            @if ($jobPosting['requirements'])
                <div>
                    {!! nl2br(e($jobPosting['requirements'])) !!}
                </div>
            @else
                <p class="text-muted mb-0">
                    No requirements provided.
                </p>
            @endif

        </div>

    </div>


    {{-- Benefits --}}
    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                Benefits
            </h5>
        </div>

        <div class="card-body">

            @if ($jobPosting['benefits'])
                <div>
                    {!! nl2br(e($jobPosting['benefits'])) !!}
                </div>
            @else
                <p class="text-muted mb-0">
                    No benefits provided.
                </p>
            @endif

        </div>

    </div>


    {{-- Closing Information --}}
    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
                Posting Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <strong>
                        Closing Date
                    </strong>

                    <p class="text-muted mb-0">
                        {{ $jobPosting['closing_date'] }}
                    </p>

                </div>


                <div class="col-md-6">

                    <strong>
                        Status
                    </strong>

                    <p class="mb-0">

                        @if ($jobPosting['is_active'])
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

        </div>

    </div>

    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0">
                Applicants
            </h5>
        </div>

        <div class="card-body table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Father Name</th>
                        <th>Cnic</th>
                        <th>Applied Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                @foreach ($this->jobApplications as $key => $application)
                    <tr>
                        <td>
                            {{ $key + 1 }}
                        </td>
                        <td>
                            {{ $application['full_name'] }}
                        </td>
                        <td>
                            {{ $application['father_name'] }}
                        </td>
                        <td>
                            {{ $application['cnic'] }}
                        </td>
                        <td>
                            {{ date('d-M-Y', strtotime($application['created_at'])) }}
                        </td>
                        <td>

                            <a href="{{ route('job_applications.show', $application['id']) }}"
                                class="btn btn-sm btn-primary rounded-pill">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach

            </table>

        </div>

    </div>




</div>
