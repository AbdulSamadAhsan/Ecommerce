<?php

use App\Models\Department;
use App\Models\Designation;
use Livewire\Component;

new class extends Component {
    public $department_id = '';
    public $name = '';

    public $departments = [];

    protected $rules = [
        'department_id' => 'required|exists:departments,id',
        'name' => 'required|min:2|max:255|unique:designations,name',
    ];

    protected $messages = [
        'department_id.required' => 'Please select a department.',
        'department_id.exists' => 'Selected department is invalid.',

        'name.required' => 'Designation name is required.',
        'name.min' => 'Designation name must contain at least 2 characters.',
        'name.unique' => 'Designation already exists.',
    ];

    public function mount(): void
    {
        $this->departments = Department::orderBy('name')->get()->toArray();
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $this->validate();

        Designation::create([
            'department_id' => $this->department_id,
            'name' => $this->name,
        ]);

        $this->reset(['department_id', 'name']);

        session()->flash('success', 'Designation added successfully.');
    }
};

?>

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">

                <h4 class="mb-0">
                    Add Designation
                </h4>

            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="save">

                    {{-- Department --}}

                    <div class="mb-3">

                        <label class="form-label">
                            Department
                        </label>

                        <select class="form-select @error('department_id') is-invalid @enderror"
                            wire:model.live="department_id">

                            <option value="">
                                Select Department
                            </option>

                            @foreach ($departments as $department)
                                <option value="{{ $department['id'] }}">
                                    {{ $department['name'] }}
                                </option>
                            @endforeach

                        </select>

                        @error('department_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Designation Name --}}

                    <div class="mb-4">

                        <label class="form-label">
                            Designation Name
                        </label>

                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter designation name" wire:model.live="name">

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Submit Button --}}

                    <div class="d-flex justify-content-end">

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save"
                            @disabled($errors->has('name') || $errors->has('department_id') || empty($name) || empty($department_id))>

                            <span wire:loading.remove wire:target="save">
                                Save Designation
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
