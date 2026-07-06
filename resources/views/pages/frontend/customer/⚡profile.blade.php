<?php

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Customer;
new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public string $name = 'Customer Name';
    public string $email = 'customer@example.com';
    public string $phone = '03000000000';
    public string $address = 'Karachi, Pakistan';
    public string $city = 'Karachi';
    public string $password = '';
    public function mount()
    {
        $this->name = auth('customer')->user()->name;
        $this->phone = auth('customer')->user()->customer->phone;
        $this->email = auth('customer')->user()->email;
        $user = auth('customer')->user();
        $customer = $user->customer;
        $address = $customer->addresses()->where('is_default', true)->first();
        if ($address) {
            $this->city = $address->city ?? '';

            $this->address = trim(($address->address_line_1 ?? '') . ' ' . ($address->address_line_2 ?? '') . ' ' . ($address->province ?? '') . ' ' . ($address->city ?? ''));
        }
    }

    public function updateProfile(): void
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'nullable|min:10',
            'address' => 'nullable|min:3',
            'city' => 'required',
            'password' => [
                'required',
                'min:3',

                function ($attribute, $value, $fail) {
                    if (Hash::check($value, auth('customer')->user()->password)) {
                        $fail('New password cannot be the same as old password.');
                    }
                },
            ],
        ]);

        $newPassword = Hash::make($this->password);
        $customerName = $this->name;
        $customerPhone = $this->phone;
        $customerEmail = $this->email;
        $customerAddress = $this->address;
        $customerCity = $this->city;
        $customer_id = auth('customer')->user()->customer->id;
        $user_id = auth('customer')->user()->id;

        if (!empty($this->password)) {
            User::where('id', $user_id)->update([
                'password' => $newPassword,
                'name' => $customerName,
                'email' => $customerEmail,
            ]);
        }
        $user = User::where('id', $user_id)->update([
            'name' => $customerName,
            'email' => $customerEmail,
        ]);
        Customer::where('user_id', $user_id)->update([
            'phone' => $customerPhone,
        ]);
        $address = auth()->user()->customer->address()->first();

        if ($address) {
            $address->update([
                'address' => $this->address,
                'city' => $this->city,
            ]);
        }
        session()->flash('success', 'Profile updated successfully.');
    }
};
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">My Profile</h2>

    <div class="row g-4">
        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Profile Information</h4>

                    @if (session('success'))
                        <div class="alert alert-success rounded-4">{{ session('success') }}</div>
                    @endif

                    <form wire:submit.prevent="updateProfile">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" wire:model="name" class="form-control rounded-pill">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" wire:model="email" class="form-control rounded-pill">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" wire:model="phone" class="form-control rounded-pill">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input type="text" wire:model="city" class="form-control rounded-pill">
                                @error('city')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="text" wire:model="password" class="form-control rounded-pill">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" wire:model="address" class="form-control rounded-pill">
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary rounded-pill px-4">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
