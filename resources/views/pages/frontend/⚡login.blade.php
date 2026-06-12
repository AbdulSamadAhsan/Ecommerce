<?php

use Livewire\Component;

new class extends Component {
    public string $email = '';
    public string $password = '';

    public function login(): void
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        session()->flash('success', 'Login successful.');
    }

    public function rendering($view): void
    {
        $view->layout('components.layouts.auth');
    }
};
?>

<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-100">

        <div class="col-lg-5 col-md-7">

            <div class="card border-0 shadow-lg rounded-5">
                <div class="card-body p-4 p-lg-5">

                    <div class="text-center mb-4">
                        <i class="bi bi-bag-heart-fill text-primary display-4"></i>

                        <h2 class="fw-bold mt-3">
                            Welcome Back
                        </h2>

                        <p class="text-muted">
                            Login to continue shopping
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success rounded-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit="login">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Email Address
                            </label>

                            <input type="email" wire:model="email"
                                class="form-control rounded-pill @error('email') is-invalid @enderror"
                                placeholder="Enter email">

                            @error('email')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Password
                            </label>

                            <input type="password" wire:model="password"
                                class="form-control rounded-pill @error('password') is-invalid @enderror"
                                placeholder="Enter password">

                            @error('password')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                        <div class="text-end mb-3">

                            <a wire:navigate href="{{ route('customer.forget_password') }}"
                                class="text-decoration-none">

                                Forgot Password?

                            </a>

                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 mt-3">
                            Login
                        </button>

                        <p class="text-center text-muted mt-4 mb-0">
                            Don't have an account?
                            <a wire:navigate href="{{ route('customer.register') }}"
                                class="text-primary fw-bold text-decoration-none">
                                Register
                            </a>
                        </p>


                    </form>

                </div>
            </div>

        </div>

    </div>
</div>
