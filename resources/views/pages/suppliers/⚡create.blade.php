<?php

use App\Models\Supplier;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
new class extends Component {
    public $company_name = '';
    public $email = '';
    public $phone = '';
    public $mobile = '';
    public $address = '';
    public $opening_balance = 0;
    public $status = 1;
    public $name = '';
    public $password = '';

    protected $rules = [
        'company_name' => 'required|min:2|max:255|unique:suppliers,company_name',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|max:50',
        'name' => 'nullable|max:50',
        'address' => 'nullable|max:1000',
        'opening_balance' => 'required|numeric|min:0',
        'status' => 'required|boolean',
        'password' => 'required',
    ];

    protected $messages = [
        'company_name.required' => 'Company name is required.',
        'company_name.min' => 'Company name must contain at least 2 characters.',
        'company_name.unique' => 'Supplier already exists.',
        'email.email' => 'Please enter a valid email address.',
        'opening_balance.required' => 'Opening balance is required.',
        'opening_balance.numeric' => 'Opening balance must be a number.',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $this->validate();
        $role = Role::where('name', 'Supplier')->first();
        $user = User::create([
            'name' => $this->name,
            'password' => Hash::make($this->password),
            'email' => $this->email,
            'role_id' => $role->id,
        ]);
        Supplier::create([
            'user_id' => $user->id,
            'company_name' => $this->company_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'address' => $this->address,
            'opening_balance' => $this->opening_balance,
            'status' => $this->status,
        ]);

        $this->reset(['company_name', 'email', 'phone', 'mobile', 'address', 'opening_balance']);

        $this->status = 1;
        $this->opening_balance = 0;

        session()->flash('success', 'Supplier added successfully.');
    }
};
?>

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">

                <h4 class="mb-0">
                    Add Supplier
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
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Company Name
                            </label>

                            <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                placeholder="Enter company name" wire:model.live="company_name">

                            @error('company_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="Enter email" wire:model.live="email">

                            @error('email')
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
                                placeholder="Enter phone" wire:model.live="phone">

                            @error('phone')
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
                                placeholder="Enter phone" wire:model.live="password">

                            @error('password')
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

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Opening Balance
                            </label>

                            <input type="number" step="0.01"
                                class="form-control @error('opening_balance') is-invalid @enderror"
                                placeholder="Enter opening balance" wire:model.live="opening_balance">

                            @error('opening_balance')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-4">

                            <label class="form-label">
                                Status
                            </label>

                            <select class="form-select @error('status') is-invalid @enderror" wire:model="status">

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

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save"
                            @disabled($errors->has('company_name') || empty($company_name))>

                            <span wire:loading.remove wire:target="save">
                                Save Supplier
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
