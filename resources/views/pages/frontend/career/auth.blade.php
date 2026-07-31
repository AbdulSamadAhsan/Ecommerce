<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

new class extends Component {
    // Toggle state
    public $isLogin = true;

    // Login fields
    public $login_email = '';
    public $login_password = '';
    public $remember = false;

    // Register fields
    public $reg_name = '';
    public $reg_email = '';
    public $reg_password = '';
    public $reg_password_confirmation = '';

    // Switch between forms
    public function switchMode($mode)
    {
        $this->isLogin = $mode === 'login';
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // Login Logic
    public function login()
    {
        $this->validate([
            'login_email' => 'required|email',
            'login_password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $this->login_email, 'password' => $this->login_password], $this->remember)) {
            session()->flash('success', 'Welcome back!');
            return redirect()->route('applicant.portal'); // Change to your portal route name
        }

        session()->flash('error', 'Invalid email or password.');
    }

    // Register Logic
    public function register()
    {
        $this->validate([
            'reg_name' => 'required|min:3',
            'reg_email' => 'required|email|unique:users,email',
            'reg_password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $this->reg_name,
            'email' => $this->reg_email,
            'password' => Hash::make($this->reg_password),
        ]);

        Auth::login($user);
        session()->flash('success', 'Registration successful! Welcome to the Applicant Portal.');

        return redirect()->route('applicant.portal'); // Change to your portal route name
    }

    // Layout Wrapper
    public function rendering($view): void
    {
        $view->layout('components.layouts.ecommerce', [
            'cartCount' => 0,
        ]);
    }
};

?>

<div>
    {{-- ===========================
         HEADER - MODERN RESPONSIVE
    ============================ --}}
    <div class=" py-4 mb-4 shadow-sm">
        <div class="container">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center gap-3 gap-lg-2">
                <div class="text-center text-lg-start d-flex flex-column align-items-center align-items-lg-start">
                    <h4 class=" mb-0 fw-bold fs-4 fs-md-3 fs-lg-2">
                        {{ $isLogin ? 'Applicant Login' : 'Create Account' }}
                    </h4>
                    <small class="text-white-50 fw-light mt-1 d-none d-sm-block">
                        {{ $isLogin ? 'Access your job applications & interviews' : 'Start your journey to your dream job' }}
                    </small>
                </div>
                <div>
                    <button type="button" onclick="window.history.back()"
                        class="btn btn-light btn-sm rounded-pill px-3 px-md-4 fw-medium shadow-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow rounded-4">
                    <div class="card-body p-4 p-md-5">

                        {{-- ===========================
                             SUCCESS/ERROR MESSAGES
                        ============================ --}}
                        @if (session()->has('success'))
                            <div class="alert alert-success rounded-4 mb-4">
                                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger rounded-4 mb-4">
                                <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                            </div>
                        @endif

                        {{-- ===========================
                             LOGIN FORM
                        ============================ --}}
                        @if ($isLogin)
                            <form wire:submit.prevent="login">
                                <div class="mb-3">
                                    <label for="login_email" class="form-label fw-semibold small">Email Address</label>
                                    <input wire:model="login_email" type="email"
                                        class="form-control form-control-lg rounded-3 @error('login_email') is-invalid @enderror"
                                        id="login_email" placeholder="john@example.com" required>
                                    @error('login_email')
                                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="login_password" class="form-label fw-semibold small">Password</label>
                                    <input wire:model="login_password" type="password"
                                        class="form-control form-control-lg rounded-3 @error('login_password') is-invalid @enderror"
                                        id="login_password" placeholder="••••••••" required>
                                    @error('login_password')
                                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4 form-check">
                                    <input wire:model="remember" type="checkbox" class="form-check-input"
                                        id="rememberMe">
                                    <label class="form-check-label small" for="rememberMe">Remember me</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold mb-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                                </button>

                                <div class="text-center small">
                                    Don't have an account?
                                    <a href="#" wire:click.prevent="switchMode('register')"
                                        class="text-decoration-none fw-bold">Register here</a>
                                </div>
                            </form>
                        @endif

                        {{-- ===========================
                             REGISTER FORM
                        ============================ --}}
                        @if (!$isLogin)
                            <form wire:submit.prevent="register">
                                <div class="mb-3">
                                    <label for="reg_name" class="form-label fw-semibold small">Full Name</label>
                                    <input wire:model="reg_name" type="text"
                                        class="form-control form-control-lg rounded-3 @error('reg_name') is-invalid @enderror"
                                        id="reg_name" placeholder="John Doe" required>
                                    @error('reg_name')
                                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="reg_email" class="form-label fw-semibold small">Email Address</label>
                                    <input wire:model="reg_email" type="email"
                                        class="form-control form-control-lg rounded-3 @error('reg_email') is-invalid @enderror"
                                        id="reg_email" placeholder="john@example.com" required>
                                    @error('reg_email')
                                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="reg_password" class="form-label fw-semibold small">Password</label>
                                    <input wire:model="reg_password" type="password"
                                        class="form-control form-control-lg rounded-3 @error('reg_password') is-invalid @enderror"
                                        id="reg_password" placeholder="••••••••" required>
                                    @error('reg_password')
                                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="reg_password_confirmation" class="form-label fw-semibold small">Confirm
                                        Password</label>
                                    <input wire:model="reg_password_confirmation" type="password"
                                        class="form-control form-control-lg rounded-3" id="reg_password_confirmation"
                                        placeholder="••••••••" required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold mb-3">
                                    <i class="bi bi-person-plus me-2"></i> Register
                                </button>

                                <div class="text-center small">
                                    Already have an account?
                                    <a href="#" wire:click.prevent="switchMode('login')"
                                        class="text-decoration-none fw-bold">Login here</a>
                                </div>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
