<?php

use App\Models\Department;
use App\Models\Employee;
use App\Models\Institution;
use App\Models\Education;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;

new class extends Component {
    use WithFileUploads;

    public $institution_id = '';
    public $department_id = '';
    public $education_id = '';
    public $phone = '';
    public $designation = '';
    public $joining_date = '';
    public $address = '';
    public $cnic = '';
    public $photo;
    public $status = 1;
    public $salary;
    public $educations;
    public $departments;
    public $institutions;
    public $father_name;
    public $date_of_birth;
    public $name;
    public $email;
    public $user_id;
    public $password;
    public function mount()
    {
        $this->educations = Education::get();
        $this->departments = Department::where('status', 1)->get();
        $this->institutions = Institution::get();
    }

    protected $rules = [
        'institution_id' => 'required',
        'education_id' => 'required|exists:educations,id',
        'department_id' => 'required|exists:departments,id',
        'phone' => 'required|min:11|max:20',
        'designation' => 'required|min:2|max:255',
        'joining_date' => 'required|date',
        'address' => 'required|min:3|max:1000',
        'cnic' => 'required|min:13|max:20|unique:employees,cnic',
        'photo' => 'nullable|image|max:2048',
        'status' => 'required|boolean',
        'father_name' => 'required',

        'name' => 'required|string|max:255|unique:users,name',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'salary' => 'required|numeric',
        'date_of_birth' => 'required|date',
        'institution_id' => 'required|exists:institutions,id',
    ];

    protected $messages = [
        'user_id.required' => 'Please select a user.',
        'department_id.required' => 'Please select a department.',
        'phone.required' => 'Phone number is required.',
        'designation.required' => 'Designation is required.',
        'joining_date.required' => 'Joining date is required.',
        'address.required' => 'Address is required.',
        'cnic.required' => 'CNIC is required.',
        'cnic.unique' => 'CNIC already exists.',
        'father_name.required' => 'Father Name is Required',
        'photo.image' => 'Please upload a valid image.',
        'photo.max' => 'Photo size must not exceed 2MB.',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $validated = $this->validate();

        $photoPath = null;

        if ($this->photo) {
            $extension = $this->photo->getClientOriginalExtension();

            $fileName = 'employee-' . time() . '-' . Str::random(8) . '.' . $extension;

            $photoPath = $this->photo->storeAs('employees', $fileName, 'public');
        }
        $user = User::create([
            'name' => $this->name,
            'password' => Hash::make($this->password),
            'email' => $this->email,
            'role_id' => 5,
        ]);
        Employee::create([
            'user_id' => $user->id,
            'department_id' => $this->department_id,
            'phone' => $this->phone,
            'institution_id' => $this->institution_id,
            'education_id' => $this->education_id,
            'father_name' => $this->father_name,
            'salary' => $this->salary,
            'date_of_birth' => $this->date_of_birth,
            'designation' => $this->designation,
            'joining_date' => $this->joining_date,
            'address' => $this->address,
            'cnic' => $this->cnic,
            'photo' => $photoPath,
            'status' => $this->status,
        ]);

        $this->status = 1;
        $this->reset();

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
                        <div class="col-md-4 mb-3">
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


                        <div class="col-md-4 mb-3">
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

                        <div class="col-md-4 mb-3">
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

                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                            wire:target="save,photo" @disabled($errors->any())>

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
