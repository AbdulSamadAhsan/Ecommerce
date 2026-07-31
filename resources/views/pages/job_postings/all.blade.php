<?php

use Livewire\Component;
use App\Models\JobPosting;

new class extends Component {
    public string $search = '';

    public array $jobPostings = [];

    public function mount(): void
    {
        $this->loadJobPostings();
    }

    public function updatedSearch(): void
    {
        $this->loadJobPostings();
    }

    public function loadJobPostings(): void
    {
        $this->jobPostings = JobPosting::with(['department', 'creator'])
            ->when($this->search, function ($query) {
                $query->where('job_title', 'like', '%' . $this->search . '%')->orWhereHas('department', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get()
            ->toArray();
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Job Postings</h3>
            <p class="text-muted mb-0">Manage company job postings</p>
        </div>

        <a href="{{ route('job_postings.create') }}" class="btn btn-primary rounded-pill">
            Add Job Posting
        </a>
    </div>

    <div class="dashboard-card">

        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search job title or department...">

        <div class="table-responsive">
            <table class="table align-middle">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Vacancies</th>
                        <th>Employment</th>
                        <th>Work Mode</th>
                        <th>Salary</th>
                        <th>Closing Date</th>
                        <th>Status</th>
                        <th width="170">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($jobPostings as $job)
                        <tr>

                            <td>#{{ $job['id'] }}</td>

                            <td>
                                <strong>{{ $job['job_title'] }}</strong>
                            </td>

                            <td>
                                {{ $job['department']['name'] ?? '-' }}
                            </td>

                            <td>
                                {{ $job['vacancies'] }}
                            </td>

                            <td>
                                <span class="badge bg-info">
                                    {{ ucwords(str_replace('_', ' ', $job['employment_type'])) }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst($job['work_mode']) }}
                                </span>
                            </td>

                            <td>

                                @if ($job['minimum_salary'] || $job['maximum_salary'])
                                    {{ number_format($job['minimum_salary']) }}
                                    -

                                    {{ number_format($job['maximum_salary']) }}
                                @else
                                    -
                                @endif

                            </td>

                            <td>
                                {{ $job['closing_date'] }}
                            </td>

                            <td>
                                <span class="badge {{ $job['is_active'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $job['is_active'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>

                                <a href="{{ route('job_postings.edit', $job['id']) }}"
                                    class="btn btn-sm btn-info text-white rounded-pill">
                                    Edit
                                </a>

                                <a href="{{ route('job_postings.show', $job['id']) }}"
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

                            <td colspan="10" class="text-center py-4 text-muted">
                                No job postings found.
                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>
</div>
