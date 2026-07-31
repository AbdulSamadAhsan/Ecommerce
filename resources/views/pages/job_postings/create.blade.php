<?php

use Livewire\Component;
use App\Models\Department;
use App\Models\JobPosting;

new class extends Component {
    public $department_id = '';

    public $job_title = '';

    public $description = '';

    public $responsibilities = '';

    public $requirements = '';

    public $benefits = '';

    public $vacancies = 1;

    public $minimum_salary = '';

    public $maximum_salary = '';

    public $employment_type = 'permanent';

    public $work_mode = 'onsite';

    public $closing_date = '';

    public $year_experience = '';

    public $is_active = 1;

    public $departments = [];

    public function mount()
    {
        $this->departments = Department::where('status', 1)->orderBy('name')->get();

        $this->closing_date = now()->addDays(30)->toDateString();
    }

    protected function rules()
    {
        return [
            'department_id' => ['required', 'exists:departments,id'],

            'job_title' => ['required', 'string', 'max:255'],

            'description' => ['required', 'string'],

            'responsibilities' => ['nullable', 'string'],

            'requirements' => ['nullable', 'string'],

            'benefits' => ['nullable', 'string'],

            'vacancies' => ['required', 'integer', 'min:1'],

            'minimum_salary' => ['nullable', 'numeric', 'min:0'],

            'maximum_salary' => ['nullable', 'numeric', 'gte:minimum_salary'],

            'employment_type' => ['required', 'in:permanent,part-time,contract,intern'],

            'work_mode' => ['required', 'in:onsite,remote,hybrid'],

            'closing_date' => ['required', 'date', 'after_or_equal:today'],

            'year_experience' => ['required', 'numeric', 'min:0'],

            'is_active' => ['required', 'boolean'],
        ];
    }

    protected $messages = [
        'department_id.required' => 'Please select a department.',

        'job_title.required' => 'Job title is required.',

        'description.required' => 'Job description is required.',

        'vacancies.min' => 'Vacancies must be at least 1.',

        'maximum_salary.gte' => 'Maximum salary must be greater than or equal to minimum salary.',

        'closing_date.after_or_equal' => 'Closing date cannot be in the past.',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $this->validate();
        dd('');
        JobPosting::create([
            'department_id' => $this->department_id,

            'created_by' => auth()->id(),

            'job_title' => $this->job_title,

            'description' => $this->description,

            'responsibilities' => $this->responsibilities,

            'requirements' => $this->requirements,

            'benefits' => $this->benefits,

            'vacancies' => $this->vacancies,

            'minimum_salary' => $this->minimum_salary,

            'maximum_salary' => $this->maximum_salary,

            'employment_type' => $this->employment_type,

            'work_mode' => $this->work_mode,

            'closing_date' => $this->closing_date,

            'year_experience' => $this->year_experience,

            'is_active' => $this->is_active,
        ]);

        $this->reset(['department_id', 'job_title', 'description', 'responsibilities', 'requirements', 'benefits', 'minimum_salary', 'maximum_salary', 'year_experience']);

        $this->vacancies = 1;

        $this->employment_type = 'permanent';

        $this->work_mode = 'onsite';

        $this->is_active = 1;

        $this->closing_date = now()->addDays(30)->toDateString();

        session()->flash('success', 'Job posting created successfully.');
    }
};

?>
<div class="row">
    <div class="col-lg-12">

        <div class="card shadow border-0">


            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    Create Job Posting
                </h4>
            </div>
            <div class="card-body">
                <form wire:submit="save">
                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Department
                            </label>

                            <select wire:model.live="department_id"
                                class="form-select @error('department_id') is-invalid @enderror">

                                <option value="">
                                    Select Department
                                </option>

                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">

                                        {{ $department->name }}

                                    </option>
                                @endforeach

                            </select>

                            @error('department_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Job Title
                            </label>

                            <input type="text" class="form-control @error('job_title') is-invalid @enderror"
                                placeholder="Senior Laravel Developer" wire:model.live="job_title">

                            @error('job_title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Vacancies
                            </label>

                            <input type="number" min="1"
                                class="form-control @error('vacancies') is-invalid @enderror"
                                wire:model.live="vacancies">

                            @error('vacancies')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Minimum Experience (Years)
                            </label>

                            <input type="number" step="0.5" min="0"
                                class="form-control @error('year_experience') is-invalid @enderror"
                                wire:model.live="year_experience">

                            @error('year_experience')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Closing Date
                            </label>

                            <input type="date" class="form-control @error('closing_date') is-invalid @enderror"
                                wire:model.live="closing_date">

                            @error('closing_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3">

                        Salary Information

                    </h5>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Minimum Salary
                            </label>

                            <input type="number" min="0" step="0.01"
                                class="form-control @error('minimum_salary') is-invalid @enderror" placeholder="50000"
                                wire:model.live="minimum_salary">

                            @error('minimum_salary')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Maximum Salary
                            </label>

                            <input type="number" min="0" step="0.01"
                                class="form-control @error('maximum_salary') is-invalid @enderror" placeholder="100000"
                                wire:model.live="maximum_salary">

                            @error('maximum_salary')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3">

                        Employment Details

                    </h5>

                    <div class="row">

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Employment Type
                            </label>

                            <select wire:model="employment_type" class="form-select">

                                <option value="permanent">
                                    Permanent
                                </option>

                                <option value="part-time">
                                    Part Time
                                </option>

                                <option value="contract">
                                    Contract
                                </option>

                                <option value="intern">
                                    Intern
                                </option>

                            </select>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Work Mode
                            </label>

                            <select wire:model="work_mode" class="form-select">

                                <option value="onsite">
                                    Onsite
                                </option>

                                <option value="remote">
                                    Remote
                                </option>

                                <option value="hybrid">
                                    Hybrid
                                </option>

                            </select>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select wire:model="is_active" class="form-select">

                                <option value="1">
                                    Active
                                </option>

                                <option value="0">
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>
                    <hr class="my-4">

                    <h5 class="fw-bold mb-3">

                        Job Details

                    </h5>

                    <div class="mb-3">

                        <label class="form-label">

                            Job Description

                        </label>

                        <textarea rows="5" wire:model.live="description" class="form-control @error('description') is-invalid @enderror"
                            placeholder="Enter complete job description..."></textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Responsibilities

                        </label>

                        <textarea rows="5" wire:model.live="responsibilities"
                            class="form-control @error('responsibilities') is-invalid @enderror" placeholder="List job responsibilities..."></textarea>

                        @error('responsibilities')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Requirements

                        </label>

                        <textarea rows="5" wire:model.live="requirements"
                            class="form-control @error('requirements') is-invalid @enderror"
                            placeholder="Education, experience and skills..."></textarea>

                        @error('requirements')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="mb-4">

                        <label class="form-label">

                            Benefits

                        </label>

                        <textarea rows="5" wire:model.live="benefits" class="form-control @error('benefits') is-invalid @enderror"
                            placeholder="Salary, bonuses, medical, insurance, leave etc..."></textarea>

                        @error('benefits')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="d-flex justify-content-end">

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                            wire:target="save">

                            <span wire:loading.remove wire:target="save">

                                Save Job Posting

                            </span>

                            <span wire:loading wire:target="save">

                                Saving...

                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
