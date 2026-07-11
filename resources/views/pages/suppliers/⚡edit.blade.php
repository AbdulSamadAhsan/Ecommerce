<?php

use App\Models\Supplier;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

new class extends Component {
    public $supplier_id;
    public $user_id;

    public $company_name = '';
    public $email = '';
    public $phone = '';
    public $mobile = '';
    public $address = '';
    public $opening_balance = 0;
    public $status = 1;
    public $name = '';
    public $password = '';

    public function mount($id)
    {
        $supplier = Supplier::with('user')->findOrFail($id);

        $this->supplier_id = $supplier->id;
        $this->user_id = $supplier->user_id;

        $this->company_name = $supplier->company_name;
        $this->email = $supplier->email;
        $this->phone = $supplier->phone;
        $this->mobile = $supplier->mobile;
        $this->address = $supplier->address;
        $this->opening_balance = $supplier->opening_balance;
        $this->status = (int) $supplier->status;

        $this->name = $supplier->user?->name ?? '';
    }

    public function rules()
    {
        return [
            'company_name' => ['required', 'min:2', 'max:255', Rule::unique('suppliers', 'company_name')->ignore($this->supplier_id)],

            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user_id)],

            'phone' => 'nullable|max:50',
            'mobile' => 'nullable|max:50',
            'name' => 'nullable|max:50',
            'address' => 'nullable|max:1000',
            'opening_balance' => 'required|numeric|min:0',
            'status' => 'required|boolean',
            'password' => 'nullable|min:6',
        ];
    }

    protected $messages = [
        'company_name.required' => 'Company name is required.',
        'company_name.min' => 'Company name must contain at least 2 characters.',
        'company_name.unique' => 'Supplier already exists.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email already exists.',
        'opening_balance.required' => 'Opening balance is required.',
        'opening_balance.numeric' => 'Opening balance must be a number.',
        'password.min' => 'Password must be at least 6 characters.',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function update()
    {
        $this->validate();

        DB::transaction(function () {
            $supplier = Supplier::findOrFail($this->supplier_id);

            if ($supplier->user_id) {
                $user = User::find($supplier->user_id);

                if ($user) {
                    $userData = [
                        'name' => $this->name,
                        'email' => $this->email,
                    ];

                    if (!empty($this->password)) {
                        $userData['password'] = Hash::make($this->password);
                    }

                    $user->update($userData);
                }
            }

            $supplier->update([
                'company_name' => $this->company_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'mobile' => $this->mobile,
                'address' => $this->address,
                'opening_balance' => $this->opening_balance,
                'status' => $this->status,
            ]);
        });

        $this->password = '';

        session()->flash('success', 'Supplier updated successfully.');
    }
};
?>

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    Edit Supplier
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
                                Name
                            </label>

                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter name" wire:model.live="name">

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
                                New Password
                            </label>

                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="Leave empty to keep old password" wire:model.live="password">

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
                            @disabled($errors->has('company_name') || empty($company_name))>

                            <span wire:loading.remove wire:target="update">
                                Update Supplier
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
