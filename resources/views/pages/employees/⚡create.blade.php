<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\Salary;
use App\Models\Employee;
use App\Models\Education;
use App\Models\Department;
use App\Models\Institution;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

new class extends Component {
    use WithFileUploads;

    public $institution_id = '';
    public $education_id = '';
    public $department_id = '';

    public $name = '';
    public $email = '';
    public $password = '';

    public $phone = '';
    public $designation = '';
    public $joining_date = '';
    public $address = '';
    public $cnic = '';
    public $photo;
    public $status = 'active';
    public $salary = 0;
    public $father_name = '';
    public $date_of_birth = '';

    public $bank_name = '';
    public $account_title = '';
    public $account_number = '';
    public $iban = '';
    public $branch_name = '';
    public $branch_code = '';
    public $swift_code = '';
    public $is_primary = 1;
    public $bank_notes = '';

    public $allowance = 0;
    public $tax_deduction = 0;
    public $net_salary = 0;
    public $emergency_contact_name;
    public $emergency_contact_number;
    public $emergency_contact_relationship;
    public $probation_period;
    public $employment_type;
    public $reporting_time;
    public $shift;
    public $experience_duration;
    public $educations = [];
    public $departments = [];
    public $institutions = [];
    public $gender = 'male';
    public array $genders = [
        'male' => 'Male',
        'female' => 'Female',
    ];
    public array $shifts = [
        'morning' => 'Morning',
        'evening' => 'Evening',
        'night' => 'Night',
    ];
    public $marital_status = 'single';
    public array $maritalStatuses = [
        'single' => 'Single',
        'married' => 'Married',
        'divorced' => 'Divorced',
    ];

    public function mount()
    {
        $this->departments = Department::where('status', 1)->get();
        $this->institutions = Institution::get();
        $this->educations = Education::get();
    }

    protected function rules()
    {
        $employeeRoleId = Role::where('name', 'Employee')->value('id');

        return [
            'institution_id' => 'required|exists:institutions,id',
            'education_id' => 'required|exists:educations,id',
            'department_id' => 'required|exists:departments,id',

            'name' => ['required', 'string', 'max:255', Rule::unique('users', 'name')->where('role_id', $employeeRoleId)],

            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',

            'phone' => 'required|min:11|max:20',
            'designation' => 'required|min:2|max:255',
            'joining_date' => 'required|date',
            'address' => 'required|min:3|max:1000',
            'cnic' => 'required|min:13|max:20|unique:employees,cnic',
            'photo' => 'nullable|image|max:2048',
            'status' => 'required|in:active,retired',
            'father_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',

            'salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',

            'bank_name' => 'nullable|string|max:255',
            'account_title' => 'nullable|string|max:255',
            'account_number' => 'required|string|max:255',
            'iban' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'branch_code' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
            'bank_notes' => 'nullable|string',
        ];
    }

    public function updated($property)
    {
        if (in_array($property, ['salary', 'allowance'])) {
            $this->calculateSalary();
        }
    }

    private function calculateSalary()
    {
        $salary = (float) $this->salary;
        $allowance = (float) $this->allowance;
        $taxPer = 10;

        $this->tax_deduction = ($taxPer / 100) * $salary;
        $this->net_salary = $salary + $allowance - $this->tax_deduction;
    }

    public function save()
    {
        try {
            $this->validate();

            DB::transaction(function () {
                $photoPath = null;

                if ($this->photo) {
                    $fileName = 'employee-' . time() . '-' . Str::random(8) . '.' . $this->photo->getClientOriginalExtension();
                    $photoPath = $this->photo->storeAs('employees', $fileName, 'public');
                }

                $role = Role::where('name', 'Employee')->firstOrFail();

                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'role_id' => $role->id,
                ]);

                $employee = Employee::create([
                    'user_id' => $user->id,
                    'institution_id' => $this->institution_id,
                    'education_id' => $this->education_id,
                    'department_id' => $this->department_id,
                    'phone' => $this->phone,
                    'designation' => $this->designation,
                    'joining_date' => $this->joining_date,
                    'address' => $this->address,
                    'cnic' => $this->cnic,
                    'photo' => $photoPath,
                    'status' => $this->status,
                    'gender' => $this->gender,
                    'father_name' => $this->father_name,
                    'date_of_birth' => $this->date_of_birth,
                    'emergency_contact_name' => $this->emergency_contact_name,
                    'emergency_contact_number' => $this->emergency_contact_number,
                    'emergency_contact_relationship' => $this->emergency_contact_relationship,
                    'employment_type' => $this->employment_type,
                    'probation_period' => $this->probation_period,
                    'reporting_time' => $this->reporting_time,
                    'shift' => $this->shift,

                    'bank_name' => $this->bank_name ?: null,
                    'account_title' => $this->account_title ?: null,
                    'account_number' => $this->account_number,
                    'iban' => $this->iban ?: null,
                    'branch_name' => $this->branch_name ?: null,
                    'branch_code' => $this->branch_code ?: null,
                    'swift_code' => $this->swift_code ?: null,
                    'is_primary' => $this->is_primary,

                    'notes' => $this->bank_notes ?: null,
                ]);

                Salary::create([
                    'employee_id' => $employee->id,
                    'basic_salary' => $this->salary,
                    'effective_from' => $this->joining_date,
                    'allowance' => $this->allowance,
                    'tax_deduction' => $this->tax_deduction,
                    'net_salary' => $this->net_salary,
                ]);
            });

            session()->flash('success', 'Employee added successfully.');

            return $this->redirectRoute('employees.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
};
?>

<div class="row">
    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    Add Employee
                </h4>
            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="save">

                    <div class="row">


                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Name
                            </label>

                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter Name" wire:model.live="name">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Education
                            </label>

                            <select class="form-select @error('education') is-invalid @enderror"
                                wire:model.live="education_id">
                                <option value="">Select Education</option>
                                @foreach ($educations as $education)
                                    <option value="{{ $education->id }}">{{ ucfirst($education->name) }}</option>
                                @endforeach
                            </select>

                            @error('education_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Email
                            </label>

                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                placeholder="Enter Email" wire:model.live="email">

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>




                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Password
                            </label>

                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter Password" wire:model.live="password">

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Eductional Institute
                            </label>

                            <select class="form-select @error('institution_id') is-invalid @enderror"
                                wire:model.live="institution_id">
                                <option value="">Select Institute</option>

                                @foreach ($institutions as $institution)
                                    <option value="{{ $institution->id }}">
                                        {{ $institution->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('institution_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Department
                            </label>

                            <select class="form-select @error('department_id') is-invalid @enderror"
                                wire:model.live="department_id">
                                <option value="">Select Department</option>

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
                                Phone
                            </label>

                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Enter phone number" wire:model.live="phone">

                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Designation
                            </label>

                            <input type="text" class="form-control @error('designation') is-invalid @enderror"
                                placeholder="Enter designation" wire:model.live="designation">

                            @error('designation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Father Name
                            </label>

                            <input type="text" class="form-control @error('father_name') is-invalid @enderror"
                                placeholder="Enter Father Name" wire:model.live="father_name">

                            @error('father_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth</label>

                            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                wire:model.live="date_of_birth">

                            @error('date_of_birth')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>




                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Joining Date
                            </label>

                            <input type="date" class="form-control @error('joining_date') is-invalid @enderror"
                                wire:model.live="joining_date">

                            @error('joining_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                CNIC
                            </label>

                            <input type="text" class="form-control @error('cnic') is-invalid @enderror"
                                placeholder="42201-1234567-8" wire:model.live="cnic">

                            @error('cnic')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label d-block">Gender <span class="text-danger">*</span></label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('gender') is-invalid @enderror" type="radio"
                                    id="male" value="male" wire:model.live="gender">

                                <label class="form-check-label" for="male">
                                    Male
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('gender') is-invalid @enderror" type="radio"
                                    id="female" value="female" wire:model.live="gender">

                                <label class="form-check-label" for="female">
                                    Female
                                </label>
                            </div>

                            @error('gender')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>




                        <div class="col-md-6 mb-3">
                            <label class="form-label d-block">Marital Status <span
                                    class="text-danger">*</span></label>
                            @foreach ($maritalStatuses as $key => $value)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('marital_status') is-invalid @enderror"
                                        type="radio" id="{{ $key }}" value="{{ $key }}"
                                        wire:model.live="marital_status">

                                    <label class="form-check-label" for="male">
                                        {{ $value }}
                                    </label>
                                </div>
                            @endforeach
                            @error('marital_status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="col-md-6 mb-3">
                            <label class="form-label">Emergency Contact Name</label>
                            <input type="text" class="form-control" wire:model="emergency_contact_name"
                                placeholder="Enter emergency contact name">
                            @error('emergency_contact_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Emergency Contact Number</label>
                            <input type="text" class="form-control" wire:model="emergency_contact_number"
                                placeholder="Enter emergency contact number">
                            @error('emergency_contact_number')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Emergency Contact Relationship</label>
                            <input type="text" class="form-control" wire:model="emergency_contact_relationship"
                                placeholder="Father, Mother, Brother, Spouse">
                            @error('emergency_contact_relationship')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employment Type</label>
                            <select class="form-select" wire:model="employment_type">
                                <option value="">Select Employment Type</option>
                                <option value="internship">Internship</option>
                                <option value="contract">Contract</option>
                                <option value="part-time">Part Time</option>
                                <option value="permanent">Permanent</option>
                            </select>
                            @error('employment_type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Probation Period</label>
                            <input type="text" class="form-control" wire:model="probation_period"
                                placeholder="e.g. 3 Months">
                            @error('probation_period')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Shift</label>
                            <select class="form-select" wire:model="shift">
                                <option value="">Select Shift</option>
                                <option value="morning">Morning</option>
                                <option value="evening">Evening</option>
                                <option value="night">Night</option>
                            </select>
                            @error('shift')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Reporting Time -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Reporting Time</label>
                            <input type="time" class="form-control" wire:model="reporting_time">
                            @error('reporting_time')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Experience Duration -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Experience Duration (Years)</label>
                            <input type="number" step="0.1" min="0" class="form-control"
                                wire:model="experience_duration" placeholder="e.g. 2.5">
                            @error('experience_duration')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Address
                            </label>

                            <textarea class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Enter address"
                                wire:model.live="address"></textarea>

                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Photo
                            </label>

                            <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                wire:model.live="photo">

                            @error('photo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div wire:loading wire:target="photo" class="mt-2 text-primary">
                                Uploading photo...
                            </div>

                            @if ($photo)
                                <div class="mt-3">
                                    <img src="{{ $photo->temporaryUrl() }}" width="150" class="img-thumbnail">
                                </div>
                            @endif
                        </div>

                        <div class="col-md-12 mb-4">
                            <label class="form-label">
                                Status
                            </label>

                            <select class="form-select" wire:model="status">
                                <option value="active">
                                    Active
                                </option>

                                <option value="retired">
                                    Inactive
                                </option>
                            </select>
                        </div>



                        <div class="col-md-12 mt-4 mb-3">
                            <h5 class="fw-bold">Salary Details</h5>
                        </div>

                        <div class="col-md-6 mb-6">
                            <label class="form-label">
                                Salary
                            </label>

                            <input type="text" class="form-control @error('salary') is-invalid @enderror"
                                placeholder="Enter Salary" wire:model.live="salary">

                            @error('salary')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-6">
                            <label class="form-label">
                                Allowance
                            </label>

                            <input type="text" class="form-control @error('allowance') is-invalid @enderror"
                                placeholder="Enter Allowance" wire:model.live="allowance">

                            @error('allowance')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        <div class="col-md-3 mb-6">
                            <label class="form-label">
                                Tax Deduction
                            </label>

                            <input type="text" class="form-control @error('tax_deduction') is-invalid @enderror"
                                placeholder="Tax Deduction" wire:model.live="tax_deduction" readonly>

                            @error('allowance')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        <div class="col-md-3 mb-6">
                            <label class="form-label">
                                Net Salary
                            </label>

                            <input type="text" class="form-control @error('net_salary') is-invalid @enderror"
                                placeholder="Net Salary" wire:model.live="net_salary" readonly>

                            @error('net_salary')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                        <div class="col-md-12 mt-4 mb-3">
                            <h5 class="fw-bold">Bank Account Details</h5>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Bank Name</label>
                            <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                wire:model.live="bank_name" placeholder="Enter bank name">
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Account Title</label>
                            <input type="text" class="form-control @error('account_title') is-invalid @enderror"
                                wire:model.live="account_title" placeholder="Enter account title">
                            @error('account_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Account Number</label>
                            <input type="text" class="form-control @error('account_number') is-invalid @enderror"
                                wire:model.live="account_number" placeholder="Enter account number">
                            @error('account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">IBAN</label>
                            <input type="text" class="form-control @error('iban') is-invalid @enderror"
                                wire:model.live="iban" placeholder="Enter IBAN">
                            @error('iban')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Branch Name</label>
                            <input type="text" class="form-control @error('branch_name') is-invalid @enderror"
                                wire:model.live="branch_name" placeholder="Enter branch name">
                            @error('branch_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Branch Code</label>
                            <input type="text" class="form-control @error('branch_code') is-invalid @enderror"
                                wire:model.live="branch_code" placeholder="Enter branch code">
                            @error('branch_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Swift Code</label>
                            <input type="text" class="form-control @error('swift_code') is-invalid @enderror"
                                wire:model.live="swift_code" placeholder="Enter swift code">
                            @error('swift_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Primary Account</label>
                            <select class="form-select" wire:model="is_primary">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Bank Notes</label>
                            <textarea class="form-control @error('bank_notes') is-invalid @enderror" wire:model.live="bank_notes" rows="2"
                                placeholder="Enter bank notes"></textarea>
                            @error('bank_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>





                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                            wire:target="save,photo">

                            <span wire:loading.remove wire:target="save">
                                Save Employee
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
