<?php

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $user_id = '';
    public $department_id = '';

    public $phone = '';
    public $designation = '';
    public $joining_date = '';
    public $address = '';
    public $cnic = '';
    public $photo;
    public $status = 1;

    public $users = [];
    public $departments = [];

    public function mount()
    {
        $this->users = User::select('id', 'name', 'email')->get();
        $this->departments = Department::where('status', 1)->get();
    }

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'department_id' => 'required|exists:departments,id',
        'phone' => 'required|min:11|max:20',
        'designation' => 'required|min:2|max:255',
        'joining_date' => 'required|date',
        'address' => 'required|min:3|max:1000',
        'cnic' => 'required|min:13|max:20|unique:employees,cnic',
        'photo' => 'nullable|image|max:2048',
        'status' => 'required|boolean',
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
        'photo.image' => 'Please upload a valid image.',
        'photo.max' => 'Photo size must not exceed 2MB.',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $this->validate();

        $photoPath = null;

        if ($this->photo) {
            $extension = $this->photo->getClientOriginalExtension();

            $fileName = 'employee-' . time() . '-' . Str::random(8) . '.' . $extension;

            $photoPath = $this->photo->storeAs('employees', $fileName, 'public');
        }

        Employee::create([
            'user_id' => $this->user_id,
            'department_id' => $this->department_id,
            'phone' => $this->phone,
            'designation' => $this->designation,
            'joining_date' => $this->joining_date,
            'address' => $this->address,
            'cnic' => $this->cnic,
            'photo' => $photoPath,
            'status' => $this->status,
        ]);

        $this->reset(['user_id', 'department_id', 'phone', 'designation', 'joining_date', 'address', 'cnic', 'photo']);

        $this->status = 1;

        session()->flash('success', 'Employee added successfully.');
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

                            <select class="form-select @error('department_id') is-invalid @enderror"
                                wire:model.live="department_id">
                                <option value="">Select Education</option>
                                <option value="M.B.A">M.B.A</option>
                            </select>

                            @error('department_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
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
                                Organization
                            </label>

                            <select class="form-select @error('user_id') is-invalid @enderror"
                                wire:model.live="user_id">
                                <option value="">Select Organization</option>


                                <option value="M.B.A">
                                    M.B.A
                                </option>

                            </select>

                            @error('user_id')
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
                            wire:target="save,photo" @disabled(
                                $errors->any() ||
                                    empty($user_id) ||
                                    empty($department_id) ||
                                    empty($phone) ||
                                    empty($designation) ||
                                    empty($joining_date) ||
                                    empty($address) ||
                                    empty($cnic))>

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
