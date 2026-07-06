<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\Salary;
use App\Models\Employee;
use App\Models\Education;
use App\Models\Department;
use App\Models\Institution;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

new class extends Component {
    use WithFileUploads;

    public $employeeId;
    public $userId;
    public $salaryId;

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
    public $oldPhoto;
    public $status = 1;

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

    public $salary = 0;
    public $allowance = 0;
    public $tax_deduction = 0;
    public $net_salary = 0;

    public $educations = [];
    public $departments = [];
    public $institutions = [];

    public function mount($id)
    {
        $employee = Employee::with(['user', 'salary'])->findOrFail($id);

        $this->employeeId = $employee->id;
        $this->userId = $employee->user_id;

        $this->name = $employee->user->name ?? '';
        $this->email = $employee->user->email ?? '';

        $this->institution_id = (string) $employee->institution_id;
        $this->education_id = (string) $employee->education_id;
        $this->department_id = (string) $employee->department_id;

        $this->phone = $employee->phone;
        $this->designation = $employee->designation;
        $this->joining_date = $employee->joining_date;
        $this->address = $employee->address;
        $this->cnic = $employee->cnic;
        $this->oldPhoto = $employee->photo;
        $this->status = (string) $employee->status;

        $this->father_name = $employee->father_name;
        $this->date_of_birth = $employee->date_of_birth;

        $this->bank_name = $employee->bank_name;
        $this->account_title = $employee->account_title;
        $this->account_number = $employee->account_number;
        $this->iban = $employee->iban;
        $this->branch_name = $employee->branch_name;
        $this->branch_code = $employee->branch_code;
        $this->swift_code = $employee->swift_code;
        $this->is_primary = (string) $employee->is_primary;
        $this->bank_notes = $employee->notes;

        $salary = Salary::where('employee_id', $employee->id)->latest()->first();

        if ($salary) {
            $this->salaryId = $salary->id;
            $this->salary = $salary->basic_salary;
            $this->allowance = $salary->allowance;
            $this->tax_deduction = $salary->tax_deduction;
            $this->net_salary = $salary->net_salary;
        }

        $this->departments = Department::where('status', 1)->get();
        $this->institutions = Institution::get();
        $this->educations = Education::where('institution_id', $this->institution_id)->get();

        $this->calculateSalary();
    }

    protected function rules()
    {
        $employeeRoleId = Role::where('name', 'Employee')->value('id');

        return [
            'institution_id' => 'required|exists:institutions,id',
            'education_id' => 'required|exists:educations,id',
            'department_id' => 'required|exists:departments,id',

            'name' => ['required', 'string', 'max:255', Rule::unique('users', 'name')->where('role_id', $employeeRoleId)->ignore($this->userId)],

            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->userId)],

            'password' => 'nullable|min:8',

            'phone' => 'required|min:11|max:20',
            'designation' => 'required|min:2|max:255',
            'joining_date' => 'required|date',
            'address' => 'required|min:3|max:1000',

            'cnic' => ['required', 'min:13', 'max:20', Rule::unique('employees', 'cnic')->ignore($this->employeeId)],

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

    public function update()
    {
        $this->validate();

        $ageAtJoining = Carbon::parse($this->date_of_birth)->diffInYears(Carbon::parse($this->joining_date));

        if ($ageAtJoining < 23) {
            $this->addError('joining_date', 'Employee must be at least 23 years old on the joining date.');
            return;
        }

        $this->calculateSalary();

        DB::transaction(function () {
            $employee = Employee::findOrFail($this->employeeId);
            $user = User::findOrFail($this->userId);

            $photoPath = $this->oldPhoto;

            if ($this->photo) {
                if ($this->oldPhoto && Storage::disk('public')->exists($this->oldPhoto)) {
                    Storage::disk('public')->delete($this->oldPhoto);
                }

                $fileName = 'employee-' . time() . '-' . Str::random(8) . '.' . $this->photo->getClientOriginalExtension();
                $photoPath = $this->photo->storeAs('employees', $fileName, 'public');
            }

            $userData = [
                'name' => $this->name,
                'email' => $this->email,
            ];

            if (!empty($this->password)) {
                $userData['password'] = Hash::make($this->password);
            }

            $user->update($userData);

            $employee->update([
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

            Salary::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                ],
                [
                    'basic_salary' => $this->salary,
                    'effective_from' => $this->joining_date,
                    'allowance' => $this->allowance,
                    'tax_deduction' => $this->tax_deduction,
                    'net_salary' => $this->net_salary,
                    'is_active' => 1,
                ],
            );
        });

        session()->flash('success', 'Employee updated successfully.');

        return $this->redirectRoute('employees.index', navigate: true);
    }
};
?>
