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
    public $status = 1;
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

    public $educations = [];
    public $departments = [];
    public $institutions = [];

    public function mount()
    {
        $this->departments = Department::where('status', 1)->get();
        $this->institutions = Institution::get();
        $this->educations = [];
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
            'status' => 'required|boolean',
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

    public function updatedInstitutionId($value)
    {
        $this->education_id = '';

        $this->educations = Education::where('institution_id', $value)->get();
    }

    public function updated($property)
    {
        if (in_array($property, ['salary', 'allowance'])) {
            $this->calculateSalary();
        }

        $this->validateOnly($property);
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
        $validated = $this->validate();

        $ageAtJoining = Carbon::parse($this->date_of_birth)->diffInYears(Carbon::parse($this->joining_date));

        if ($ageAtJoining < 23) {
            $this->addError('joining_date', 'Employee must be at least 23 years old on the joining date.');

            return;
        }

        $this->calculateSalary();

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
                'salary' => $this->salary,
                'father_name' => $this->father_name,
                'date_of_birth' => $this->date_of_birth,

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
                                <option value="1">
                                    Active
                                </option>

                                <option value="0">
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
