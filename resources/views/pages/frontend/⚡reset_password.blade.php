<?php

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

new class extends Component {
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->email ?? '';
    }

    public function resetPassword(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'token' => ['required'],
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {
                $user
                    ->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])
                    ->save();
            },
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('success', 'Password has been reset successfully.');

            $this->redirect(route('customer.login'), navigate: true);

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

                        <i class="bi bi-shield-lock-fill text-primary display-4"></i>

                        <h2 class="fw-bold mt-3">
                            Reset Password
                        </h2>

                        <p class="text-muted">
                            Enter your new password
                        </p>

                    </div>

                    @if (session('success'))
                        <div class="alert alert-success rounded-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit="resetPassword">

                        <input type="hidden" wire:model="token">

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Email Address
                            </label>

                            <input type="email" wire:model="email"
                                class="form-control rounded-pill @error('email') is-invalid @enderror" readonly>

                            @error('email')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                New Password
                            </label>

                            <input type="password" wire:model="password"
                                class="form-control rounded-pill @error('password') is-invalid @enderror"
                                placeholder="Enter new password">

                            @error('password')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Confirm Password
                            </label>

                            <input type="password" wire:model="password_confirmation" class="form-control rounded-pill"
                                placeholder="Confirm password">

                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">
                            Reset Password
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
