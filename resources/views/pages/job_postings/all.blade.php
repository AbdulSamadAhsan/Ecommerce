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

        <a href="{{ route('jobs.create') }}" class="btn btn-primary rounded-pill">
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

                        <th>Work Mode</th>


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
                                <span class="badge bg-secondary">
                                    {{ ucfirst($job['work_mode']) }}
                                </span>
                            </td>





                            <td>
                                <span class="badge {{ $job['is_active'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $job['is_active'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>

                                <a href="{{ route('jobs.edit', $job['id']) }}"
                                    class="btn btn-sm btn-info text-white rounded-pill">
                                    Edit
                                </a>

                                <a href="{{ route('jobs.show', $job['id']) }}"
                                    class="btn btn-sm btn-success rounded-pill">
                                    View
                                </a>



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
