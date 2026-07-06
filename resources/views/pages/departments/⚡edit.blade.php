<?php

use App\Models\Department;
use Livewire\Component;
use Illuminate\Validation\Rule;

new class extends Component {
    public $department_id;

    public $name = '';
    public $description = '';
    public $status = 1;

    public function mount($id)
    {
        $department = Department::findOrFail($id);

        $this->department_id = $department->id;
        $this->name = $department->name;
        $this->description = $department->description;
        $this->status = (int) $department->status;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'min:2', 'max:255', Rule::unique('departments', 'name')->ignore($this->department_id)],

            'description' => 'nullable|max:1000',
            'status' => 'required|boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'Department name is required.',
        'name.min' => 'Department name must contain at least 2 characters.',
        'name.unique' => 'Department already exists.',
        'description.max' => 'Description must not exceed 1000 characters.',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function update()
    {
        $this->validate();

        Department::findOrFail($this->department_id)->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Department updated successfully.');
    }
};

?>

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0">


            <div class="card-header bg-primary text-white">

                <h4 class="mb-0">
                    Edit Department
                </h4>

            </div>


            <div class="card-body">


                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif



                <form wire:submit="update">


                    <div class="mb-3">


                        <label class="form-label">
                            Department Name
                        </label>


                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter department name" wire:model.live="name">


                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror


                    </div>



                    <div class="mb-3">


                        <label class="form-label">
                            Description
                        </label>


                        <textarea class="form-control @error('description') is-invalid @enderror" rows="4"
                            placeholder="Enter department description" wire:model.live="description"></textarea>


                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror


                    </div>



                    <div class="mb-4">


                        <label class="form-label">
                            Status
                        </label>


                        <select class="form-select @error('status') is-invalid @enderror" wire:model.live="status">


                            <option value="1">
                                Active
                            </option>


                            <option value="0">
                                Inactive
                            </option>


                        </select>


                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror


                    </div>




                    <div class="d-flex justify-content-end">


                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="update"
                            @disabled($errors->has('name') || empty($name))>


                            <span wire:loading.remove wire:target="update">

                                Update Department

                            </span>


                            <span wire:loading wire:target="update">

                                Updating...

                            </span>


                        </button>


                    </div>


                </form>


            </div>


        </div>


    </div>

</div>
