<?php

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

new class extends Component {
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $this->validate([
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:10|max:20',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
        ]);

        session()->flash('success', 'Account created successfully.');

        $this->redirectRoute('login', navigate: true);
    }

    public function rendering($view): void
    {
        $view->layout('components.layouts.auth');
    }
};
?>

<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-lg-6 col-md-8">

            <div class="card border-0 shadow-lg rounded-5">
                <div class="card-body p-4 p-lg-5">

                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill text-primary display-4"></i>

                        <h2 class="fw-bold mt-3">
                            Create Account
                        </h2>

                        <p class="text-muted">
                            Register to start shopping
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success rounded-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit="register">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">First Name</label>

                                <input type="text" wire:model="first_name"
                                    class="form-control rounded-pill @error('first_name') is-invalid @enderror"
                                    placeholder="First name">

                                @error('first_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Last Name</label>

                                <input type="text" wire:model="last_name"
                                    class="form-control rounded-pill @error('last_name') is-invalid @enderror"
                                    placeholder="Last name">

                                @error('last_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Email Address</label>

                                <input type="email" wire:model="email"
                                    class="form-control rounded-pill @error('email') is-invalid @enderror"
                                    placeholder="Enter email">

                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Phone Number</label>

                                <input type="text" wire:model="phone"
                                    class="form-control rounded-pill @error('phone') is-invalid @enderror"
                                    placeholder="03XXXXXXXXX">

                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Password</label>

                                <input type="password" wire:model="password"
                                    class="form-control rounded-pill @error('password') is-invalid @enderror"
                                    placeholder="Password">

                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Confirm Password</label>

                                <input type="password" wire:model="password_confirmation"
                                    class="form-control rounded-pill" placeholder="Confirm password">
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 mt-4">
                            Register
                        </button>

                        <p class="text-center text-muted mt-4 mb-0">
                            Already have an account?
                            <a wire:navigate href="{{ route('customer.login') }}"
                                class="text-primary fw-bold text-decoration-none">
                                Login
                            </a>
                        </p>

                    </form>

                </div>
            </div>

        </div>

    </div>
</div>
