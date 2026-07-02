<?php

use Livewire\Component;
use App\Models\DeliveryBoy;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

new class extends Component {
    public $deliveryboyId;

    public $name = '';
    public $phone = '';
    public $email = '';
    public $address = '';
    public $vehicle_type = 'bike';
    public $vehicle_number = '';
    public $status = 1;

    public function mount($id)
    {
        $deliveryboy = DeliveryBoy::findOrFail($id);

        $this->deliveryboyId = $deliveryboy->id;
        $this->name = $deliveryboy->user->name;
        $this->phone = $deliveryboy->phone;
        $this->email = $deliveryboy->user->email;

        $this->vehicle_type = $deliveryboy->vehicle_type;
        $this->vehicle_number = $deliveryboy->vehicle_number;
        $this->status = $deliveryboy->status;
    }

    protected function rules()
    {
        return [
            'name' => 'required|min:2|max:255',
            'phone' => ['required', 'max:30', Rule::unique('delivery_boys', 'phone')->ignore($this->deliveryboyId)],
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($this->deliveryboyId)],

            'vehicle_type' => 'required|string|max:50',
            'vehicle_number' => 'nullable|max:100',
            'status' => 'required|boolean',
        ];
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function update()
    {
        $this->validate();
        $delivery = DeliveryBoy::findOrFail($this->deliveryboyId);
        $user_id = $delivery->user_id;
        $user = User::where('id', $user_id)->update([
            'name' => $this->name,

            'email' => $this->email ?: null,
        ]);

        $delivery->update([
            'phone' => $this->phone,
            'vehicle_type' => $this->vehicle_type,
            'vehicle_number' => $this->vehicle_number,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Delivery boy updated successfully.');
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Edit Delivery Boy</h4>
    </div>

    <div class="card-body">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form wire:submit="update">

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" wire:model.live="name" class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" wire:model.live="phone" class="form-control @error('phone') is-invalid @enderror">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" wire:model.live="email" class="form-control @error('email') is-invalid @enderror">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Vehicle Type</label>
                <select wire:model.live="vehicle_type" class="form-select">
                    <option value="bike">Bike</option>
                    <option value="car">Car</option>
                    <option value="van">Van</option>
                    <option value="rickshaw">Rickshaw</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Vehicle Number</label>
                <input type="text" wire:model.live="vehicle_number" class="form-control">
            </div>


            <div class="mb-4">
                <label class="form-label">Status</label>
                <select wire:model.live="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('deliveryboys.index') }}" class="btn btn-secondary rounded-pill">
                    Back
                </a>

                <button class="btn btn-warning rounded-pill">
                    Update Delivery Boy
                </button>
            </div>

        </form>
    </div>
</div>
