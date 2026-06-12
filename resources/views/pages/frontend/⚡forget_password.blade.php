<?php

use Livewire\Component;
use Illuminate\Support\Facades\Password;

new class extends Component {
    public string $email = '';

    public function sendResetLink(): void
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink([
            'email' => $this->email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('success', __($status));
            return;
        }

        $this->addError('email', __($status));
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
                        <i class="bi bi-envelope-lock-fill text-primary display-4"></i>
                        <h2 class="fw-bold mt-3">Forgot Password</h2>
                        <p class="text-muted">Enter your email to receive reset link</p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success rounded-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit="sendResetLink">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address</label>

                            <input type="email" wire:model="email"
                                class="form-control rounded-pill @error('email') is-invalid @enderror"
                                placeholder="Enter your email">

                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 mt-3">
                            Send Reset Link
                        </button>

                        <p class="text-center mt-4 mb-0">
                            <a wire:navigate href="{{ route('customer.login') }}"
                                class="text-primary fw-bold text-decoration-none">
                                Back to Login
                            </a>
                        </p>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
