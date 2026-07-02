<?php

use Livewire\Component;
use App\Models\DeliveryBoy;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
new class extends Component {
    public $name = '';
    public $phone = '';
    public $email = '';
    public $password = '';
    public $vehicle_type = 'bike';
    public $vehicle_number = '';
    public $status = 1;
    public $cnic;

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'phone' => 'required|max:30|unique:delivery_boys,phone',
        'email' => 'nullable|email|unique:users,email',

        'vehicle_type' => 'required|string|max:50',
        'vehicle_number' => 'nullable|max:100',
        'status' => 'required|boolean',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $this->validate();
        $role = Role::where('name', 'DeliveryBoy')->first();
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $role->id,
        ]);
        DeliveryBoy::create([
            'phone' => $this->phone,
            'user_id' => $user->id,
            'cnic' => $this->cnic,
            'vehicle_type' => $this->vehicle_type,
            'vehicle_number' => $this->vehicle_number,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Delivery boy added successfully.');

        return $this->redirectRoute('deliveryboys.index', navigate: true);
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Add Delivery Boy</h4>
    </div>

    <div class="card-body">
        <form wire:submit="save">

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
                <label class="form-label">Password</label>
                <input type="password" wire:model.live="password"
                    class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">CNIC</label>
                <input type="text" wire:model.live="cnic" class="form-control @error('cnic') is-invalid @enderror">
                @error('cnic')
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

            <button class="btn btn-primary rounded-pill">
                Save Delivery Boy
            </button>

        </form>
    </div>
</div>
