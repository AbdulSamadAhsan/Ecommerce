<?php

use App\Models\Employee;
use App\Models\Warehouse;
use Livewire\Component;
use Illuminate\Validation\Rule;

new class extends Component {
    public $warehouse_id;

    public $name = '';
    public $code = '';
    public $manager_id = '';
    public $address = '';
    public $phone = '';
    public $status = 1;

    public function mount($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $this->warehouse_id = $warehouse->id;
        $this->name = $warehouse->name;
        $this->code = $warehouse->code;
        $this->manager_id = $warehouse->manager_id;
        $this->address = $warehouse->address;
        $this->phone = $warehouse->phone;
        $this->status = (int) $warehouse->status;
    }

    public function rules()
    {
        return [
            'name' => 'required|min:2|max:255',

            'code' => ['required', 'min:2', 'max:100', Rule::unique('warehouses', 'code')->ignore($this->warehouse_id)],

            'manager_id' => 'nullable|exists:employees,id',
            'address' => 'nullable|max:1000',
            'phone' => 'nullable|max:50',
            'status' => 'required|boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'Warehouse name is required.',
        'name.min' => 'Warehouse name must contain at least 2 characters.',
        'code.required' => 'Warehouse code is required.',
        'code.unique' => 'Warehouse code already exists.',
        'manager_id.exists' => 'Selected manager is invalid.',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function update()
    {
        $this->validate();

        $warehouse = Warehouse::findOrFail($this->warehouse_id);

        $warehouse->update([
            'name' => $this->name,
            'code' => $this->code,
            'manager_id' => $this->manager_id ?: null,
            'address' => $this->address,
            'phone' => $this->phone,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Warehouse updated successfully.');
    }

    public function managers()
    {
        return Employee::with('user')->where('status', true)->get();
    }
};
?>

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    Edit Warehouse
                </h4>
            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="update">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Warehouse Name
                            </label>

                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter warehouse name" wire:model.live="name">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Warehouse Code
                            </label>

                            <input type="text" class="form-control @error('code') is-invalid @enderror"
                                placeholder="Example: WH-001" wire:model.live="code">

                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                Manager
                            </label>

                            <select class="form-select @error('manager_id') is-invalid @enderror"
                                wire:model.live="manager_id">

                                <option value="">
                                    Select manager
                                </option>

                                @foreach ($this->managers() as $manager)
                                    <option value="{{ $manager->id }}">
                                        {{ $manager->user?->name ?? $manager->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('manager_id')
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

                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                Address
                            </label>

                            <textarea class="form-control @error('address') is-invalid @enderror" rows="3"
                                placeholder="Enter warehouse address" wire:model.live="address"></textarea>

                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
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

                    </div>

                    <div class="d-flex justify-content-end">

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="update"
                            @disabled($errors->has('name') || $errors->has('code') || empty($name) || empty($code))>

                            <span wire:loading.remove wire:target="update">
                                Update Warehouse
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
