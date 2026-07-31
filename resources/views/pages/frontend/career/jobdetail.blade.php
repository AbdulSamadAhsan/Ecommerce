<?php

use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    /*
    |--------------------------------------------------------------------------
    | Job Information
    |--------------------------------------------------------------------------
    */

    public array $job = [
        'title' => 'Senior Laravel Developer',

        'department' => 'Engineering',

        'location' => 'Karachi',

        'employment_type' => 'Full Time',

        'experience' => '3+ Years',
    ];

    /*
    |--------------------------------------------------------------------------
    | Personal Information
    |--------------------------------------------------------------------------
    */

    public array $form = [
        'full_name' => '',

        'email' => '',

        'phone' => '',

        'cnic' => '',

        'dob' => '',

        'gender' => 'Male',

        /*
        |--------------------------------------------------------------------------
        | Address
        |--------------------------------------------------------------------------
        */

        'country' => '',

        'province' => '',

        'city' => '',

        'postal_code' => '',

        'address' => '',

        /*
        |--------------------------------------------------------------------------
        | Professional
        |--------------------------------------------------------------------------
        */

        'current_company' => '',

        'current_designation' => '',

        'total_experience' => '',

        'relevant_experience' => '',

        'current_salary' => '',

        'expected_salary' => '',

        'notice_period' => '',

        'available_from' => '',

        /*
        |--------------------------------------------------------------------------
        | Portfolio
        |--------------------------------------------------------------------------
        */

        'portfolio' => '',

        'github' => '',

        'linkedin' => '',

        /*
        |--------------------------------------------------------------------------
        | Questions
        |--------------------------------------------------------------------------
        */

        'why_join_us' => '',

        'about_yourself' => '',

        /*
        |--------------------------------------------------------------------------
        | Declaration
        |--------------------------------------------------------------------------
        */

        'declaration' => false,
    ];

    /*
    |--------------------------------------------------------------------------
    | Education
    |--------------------------------------------------------------------------
    */

    public array $educations = [];

    /*
    |--------------------------------------------------------------------------
    | Experience
    |--------------------------------------------------------------------------
    */

    public array $experiences = [];

    /*
    |--------------------------------------------------------------------------
    | Uploads
    |--------------------------------------------------------------------------
    */

    public $resume;

    public $coverLetter;

    public $certificates = [];

    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $this->educations[] = [
            'degree' => '',

            'institute' => '',

            'year' => '',

            'grade' => '',
        ];

        $this->experiences[] = [
            'company' => '',

            'designation' => '',

            'from' => '',

            'to' => '',

            'responsibilities' => '',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Education
    |--------------------------------------------------------------------------
    */

    public function addEducation(): void
    {
        $this->educations[] = [
            'degree' => '',

            'institute' => '',

            'year' => '',

            'grade' => '',
        ];
    }

    public function removeEducation($index): void
    {
        unset($this->educations[$index]);

        $this->educations = array_values($this->educations);
    }

    /*
    |--------------------------------------------------------------------------
    | Experience
    |--------------------------------------------------------------------------
    */

    public function addExperience(): void
    {
        $this->experiences[] = [
            'company' => '',

            'designation' => '',

            'from' => '',

            'to' => '',

            'responsibilities' => '',
        ];
    }

    public function removeExperience($index): void
    {
        unset($this->experiences[$index]);

        $this->experiences = array_values($this->experiences);
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    protected function rules(): array
    {
        return [
            'form.full_name' => ['required', 'string', 'max:255'],

            'form.email' => ['required', 'email'],

            'form.phone' => ['required'],

            'form.country' => ['required'],

            'form.city' => ['required'],

            'form.address' => ['required'],

            'resume' => ['required'],

            'form.declaration' => ['accepted'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */
    public function goBack()
    {
        return redirect()->back();
    }
    public function rendering($view): void
    {
        $view->layout('components.layouts.ecommerce', [
            'cartCount' => 0,
        ]);
    }
    public function save(): void
    {
        $this->validate();

        session()->flash(
            'success',

            'Your application has been submitted successfully.',
        );
    }
};

?>
<div class="container py-5">
    {{-- ===========================
         SUCCESS MESSAGE
    ============================ --}}
    <div class="mb-4">
        <button type="button" wire:click="goBack"
            class="btn btn-outline-secondary rounded-pill px-4 py-2 shadow-sm hover-shadow">
            <i class="bi bi-arrow-left me-2"></i>
            Back to Job Listings
        </button>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success rounded-4 shadow-sm">
            <div class="d-flex">
                <div class="me-3">
                    <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                </div>
                <div>
                    <h5 class="mb-1">Application Submitted Successfully</h5>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="save">
        {{-- ===========================
             JOB SUMMARY
        ============================ --}}
        <section class="mb-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-body p-5">
                    <span class="badge bg-primary rounded-pill mb-3">APPLY FOR POSITION</span>
                    <h2 class="fw-bold">{{ $job['title'] }}</h2>
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <small class="text-muted">Department</small>
                            <h6 class="fw-semibold">{{ $job['department'] }}</h6>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Location</small>
                            <h6 class="fw-semibold">{{ $job['location'] }}</h6>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Employment Type</small>
                            <h6 class="fw-semibold">{{ $job['employment_type'] }}</h6>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Experience</small>
                            <h6 class="fw-semibold">{{ $job['experience'] }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===========================
             PERSONAL INFORMATION
        ============================ --}}
        <section class="mb-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h4 class="fw-bold mb-0">Personal Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input wire:model.live="form.full_name"
                                class="form-control @error('form.full_name') is-invalid @enderror"
                                placeholder="Enter your full name">
                            @error('form.full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Email Address <span
                                    class="text-danger">*</span></label>
                            <input type="email" wire:model.live="form.email"
                                class="form-control @error('form.email') is-invalid @enderror"
                                placeholder="Enter your email address">
                            @error('form.email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Phone Number <span
                                    class="text-danger">*</span></label>
                            <input wire:model.live="form.phone"
                                class="form-control @error('form.phone') is-invalid @enderror"
                                placeholder="Enter your phone number">
                            @error('form.phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">CNIC</label>
                            <input wire:model.live="form.cnic" class="form-control"
                                placeholder="Enter your CNIC number">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Date of Birth</label>
                            <input type="date" wire:model.live="form.dob" class="form-control">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Gender</label>
                            <select wire:model.live="form.gender" class="form-select">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===========================
             ADDRESS
        ============================ --}}
        <section class="mb-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h4 class="fw-bold mb-0">Address Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                            <input wire:model.live="form.country"
                                class="form-control @error('form.country') is-invalid @enderror"
                                placeholder="Enter your country">
                            @error('form.country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Province / State</label>
                            <input wire:model.live="form.province" class="form-control"
                                placeholder="Enter your province/state">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                            <input wire:model.live="form.city"
                                class="form-control @error('form.city') is-invalid @enderror"
                                placeholder="Enter your city">
                            @error('form.city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Postal Code</label>
                            <input wire:model.live="form.postal_code" class="form-control"
                                placeholder="Enter postal code">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Current Address <span
                                    class="text-danger">*</span></label>
                            <textarea rows="4" wire:model.live="form.address"
                                class="form-control @error('form.address') is-invalid @enderror" placeholder="Enter your current address"></textarea>
                            @error('form.address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===========================
             PROFESSIONAL DETAILS
        ============================ --}}
        <section class="mb-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h4 class="fw-bold mb-0">Professional Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Current Company</label>
                            <input wire:model.live="form.current_company" class="form-control"
                                placeholder="Enter your current company">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Current Designation</label>
                            <input wire:model.live="form.current_designation" class="form-control"
                                placeholder="Enter your current designation">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Total Experience (Years)</label>
                            <input wire:model.live="form.total_experience" class="form-control"
                                placeholder="e.g., 5">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Relevant Experience (Years)</label>
                            <input wire:model.live="form.relevant_experience" class="form-control"
                                placeholder="e.g., 3">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Current Salary (PKR)</label>
                            <input wire:model.live="form.current_salary" class="form-control"
                                placeholder="Enter current salary">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Expected Salary (PKR)</label>
                            <input wire:model.live="form.expected_salary" class="form-control"
                                placeholder="Enter expected salary">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Notice Period</label>
                            <input wire:model.live="form.notice_period" class="form-control"
                                placeholder="e.g., 15 days, 1 month">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Available From</label>
                            <input type="date" wire:model.live="form.available_from" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===========================
             EDUCATION
        ============================ --}}
        <section class="mb-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-0">Education</h4>
                        <small class="text-muted">Add your educational background</small>
                    </div>
                    <button type="button" wire:click="addEducation" class="btn btn-primary rounded-pill">
                        <i class="bi bi-plus-circle me-2"></i>Add Education
                    </button>
                </div>
                <div class="card-body">
                    @foreach ($educations as $index => $education)
                        <div wire:key="education-{{ $index }}" class="border rounded-4 p-4 bg-light mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-semibold mb-0">Education #{{ $loop->iteration }}</h5>
                                @if (count($educations) > 1)
                                    <button type="button" wire:click="removeEducation({{ $index }})"
                                        class="btn btn-outline-danger btn-sm rounded-pill">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Degree</label>
                                    <input type="text" wire:model.live="educations.{{ $index }}.degree"
                                        class="form-control" placeholder="e.g., BSCS">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Institute</label>
                                    <input type="text" wire:model.live="educations.{{ $index }}.institute"
                                        class="form-control" placeholder="e.g., University Name">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Year</label>
                                    <input type="text" wire:model.live="educations.{{ $index }}.year"
                                        class="form-control" placeholder="e.g., 2020-2024">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Grade</label>
                                    <input type="text" wire:model.live="educations.{{ $index }}.grade"
                                        class="form-control" placeholder="e.g., A+, 3.8 GPA">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===========================
             EXPERIENCE
        ============================ --}}
        <section class="mb-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-0">Work Experience</h4>
                        <small class="text-muted">Add your previous employment history</small>
                    </div>
                    <button type="button" wire:click="addExperience" class="btn btn-primary rounded-pill">
                        <i class="bi bi-plus-circle me-2"></i>Add Experience
                    </button>
                </div>
                <div class="card-body">
                    @foreach ($experiences as $index => $experience)
                        <div wire:key="experience-{{ $index }}" class="border rounded-4 p-4 bg-light mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-semibold mb-0">Experience #{{ $loop->iteration }}</h5>
                                @if (count($experiences) > 1)
                                    <button type="button" wire:click="removeExperience({{ $index }})"
                                        class="btn btn-outline-danger btn-sm rounded-pill">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Company</label>
                                    <input type="text" wire:model.live="experiences.{{ $index }}.company"
                                        class="form-control" placeholder="Enter company name">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Designation</label>
                                    <input type="text"
                                        wire:model.live="experiences.{{ $index }}.designation"
                                        class="form-control" placeholder="Enter your designation">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">From</label>
                                    <input type="date" wire:model.live="experiences.{{ $index }}.from"
                                        class="form-control">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">To</label>
                                    <input type="date" wire:model.live="experiences.{{ $index }}.to"
                                        class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Responsibilities</label>
                                    <textarea rows="4" wire:model.live="experiences.{{ $index }}.responsibilities" class="form-control"
                                        placeholder="Describe your key responsibilities"></textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ===========================
             PORTFOLIO
        ============================ --}}
        <section class="mb-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h4 class="fw-bold mb-0">Portfolio</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold">Portfolio Website</label>
                            <input type="url" wire:model.live="form.portfolio" class="form-control"
                                placeholder="https://example.com">
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold">GitHub</label>
                            <input type="url" wire:model.live="form.github" class="form-control"
                                placeholder="https://github.com/username">
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold">LinkedIn</label>
                            <input type="url" wire:model.live="form.linkedin" class="form-control"
                                placeholder="https://linkedin.com/in/username">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===========================
             DOCUMENTS
        ============================ --}}
        <section class="mb-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h4 class="fw-bold mb-0">Documents</h4>
                    <small class="text-muted">Upload your supporting documents</small>
                </div>
                <div class="card-body">
                    {{-- Resume --}}
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Resume <span class="text-danger">*</span></label>
                        <input type="file" wire:model="resume"
                            class="form-control @error('resume') is-invalid @enderror" accept=".pdf,.doc,.docx">
                        @error('resume')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div wire:loading wire:target="resume" class="small text-primary mt-2">
                            <i class="bi bi-spinner bi-spin me-1"></i> Uploading resume...
                        </div>
                        @if ($resume)
                            <div class="alert alert-success mt-3 mb-0">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ $resume->getClientOriginalName() }}
                                ({{ number_format($resume->getSize() / 1024, 2) }} KB)
                            </div>
                        @endif
                    </div>

                    {{-- Cover Letter --}}
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Cover Letter</label>
                        <input type="file" wire:model="coverLetter" class="form-control"
                            accept=".pdf,.doc,.docx">
                        <div wire:loading wire:target="coverLetter" class="small text-primary mt-2">
                            <i class="bi bi-spinner bi-spin me-1"></i> Uploading cover letter...
                        </div>
                        @if ($coverLetter)
                            <div class="alert alert-success mt-3 mb-0">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ $coverLetter->getClientOriginalName() }}
                                ({{ number_format($coverLetter->getSize() / 1024, 2) }} KB)
                            </div>
                        @endif
                    </div>

                    {{-- Certificates --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Certificates</label>
                        <input type="file" wire:model="certificates" multiple class="form-control"
                            accept=".pdf,.jpg,.jpeg,.png">
                        <div wire:loading wire:target="certificates" class="small text-primary mt-2">
                            <i class="bi bi-spinner bi-spin me-1"></i> Uploading certificates...
                        </div>
                    </div>

                    @if (count($certificates))
                        <div class="border rounded-4 p-3 bg-light">
                            <h6 class="fw-bold mb-3">Selected Certificates</h6>
                            @foreach ($certificates as $certificate)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-file-earmark-check text-success me-2"></i>
                                    {{ $certificate->getClientOriginalName() }}
                                    ({{ number_format($certificate->getSize() / 1024, 2) }} KB)
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Upload Guidelines --}}
                    <div class="alert alert-info rounded-4 mt-4">
                        <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Upload Guidelines</h6>
                        <ul class="mb-0">
                            <li>Resume must be PDF, DOC or DOCX format</li>
                            <li>Maximum file size: 5 MB per file</li>
                            <li>Certificates can be PDF or image files (JPG, JPEG, PNG)</li>
                            <li>Upload clear and readable documents</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===========================
             ADDITIONAL QUESTIONS
        ============================ --}}
        <section class="mb-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-white border-0 p-4">
                    <h4 class="fw-bold mb-0">Additional Questions</h4>
                    <small class="text-muted">Help us know more about you</small>
                </div>
                <div class="card-body">
                    {{-- Why Join Us --}}
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Why do you want to join our company?</label>
                        <textarea rows="5" wire:model.live="form.why_join_us" maxlength="1000"
                            class="form-control @error('form.why_join_us') is-invalid @enderror"
                            placeholder="Tell us why you would like to work with us..."></textarea>
                        @error('form.why_join_us')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="text-end mt-2">
                            <small class="text-muted">{{ strlen($form['why_join_us']) }}/1000</small>
                        </div>
                    </div>

                    {{-- About Yourself --}}
                    <div class="mb-5">
                        <label class="form-label fw-semibold">Tell us about yourself</label>
                        <textarea rows="6" wire:model.live="form.about_yourself" maxlength="2000"
                            class="form-control @error('form.about_yourself') is-invalid @enderror"
                            placeholder="Briefly introduce yourself, your strengths, achievements and career goals..."></textarea>
                        @error('form.about_yourself')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="text-end mt-2">
                            <small class="text-muted">{{ strlen($form['about_yourself']) }}/2000</small>
                        </div>
                    </div>

                    {{-- Availability --}}
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Are you willing to relocate?</label>
                            <select wire:model.live="form.relocate" class="form-select">
                                <option value="">Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Are you legally authorized to work?</label>
                            <select wire:model.live="form.authorized" class="form-select">
                                <option value="">Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    {{-- Optional Comments --}}
                    <div>
                        <label class="form-label fw-semibold">Additional Comments (Optional)</label>
                        <textarea rows="4" wire:model.live="form.comments" class="form-control"
                            placeholder="Anything else you'd like to share?"></textarea>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===========================
             DECLARATION
        ============================ --}}
        <section class="mb-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-body p-4">
                    <div class="form-check">
                        <input type="checkbox" id="declaration" wire:model.live="form.declaration"
                            class="form-check-input @error('form.declaration') is-invalid @enderror">
                        <label for="declaration" class="form-check-label">
                            I certify that all information provided in this application
                            is true, complete and accurate. I understand that providing
                            false information may result in rejection of my application
                            or termination of employment if hired.
                        </label>
                        @error('form.declaration')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- ===========================
             ACTION BUTTONS
        ============================ --}}
        <div class="text-center mb-5">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">
                    <i class="bi bi-send me-2"></i>Apply Now
                </span>
                <span wire:loading wire:target="save">
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Submitting Application...
                </span>
            </button>
        </div>
    </form>
</div>
