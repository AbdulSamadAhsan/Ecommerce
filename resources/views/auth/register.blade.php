@extends('layouts.header')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-6">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- Header -->
                <div class="bg-primary text-white text-center py-4">
                    <h2 class="fw-bold mb-1">Create Account</h2>
                    <p class="mb-0 opacity-75">
                        Register to get started
                    </p>
                </div>

                <!-- Body -->
                <div class="card-body p-5">

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                Full Name
                            </label>

                            <input id="name"
                                   type="text"
                                   class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Enter your full name"
                                   required
                                   autocomplete="name"
                                   autofocus>

                            @error('name')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                Email Address
                            </label>

                            <input id="email"
                                   type="email"
                                   class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="Enter your email"
                                   required
                                   autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">
                                Password
                            </label>

                            <input id="password"
                                   type="password"
                                   class="form-control form-control-lg rounded-3 @error('password') is-invalid @enderror"
                                   name="password"
                                   placeholder="Create password"
                                   required
                                   autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password-confirm"
                                   class="form-label fw-semibold">
                                Confirm Password
                            </label>

                            <input id="password-confirm"
                                   type="password"
                                   class="form-control form-control-lg rounded-3"
                                   name="password_confirmation"
                                   placeholder="Confirm password"
                                   required
                                   autocomplete="new-password">
                        </div>

                        <!-- Register Button -->
                        <div class="d-grid mb-3">
                            <button type="submit"
                                    class="btn btn-primary btn-lg rounded-3 fw-bold">
                                Register
                            </button>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center">
                            <small class="text-muted">
                                Already have an account?
                            </small>

                            <a href="{{ route('login') }}"
                               class="text-decoration-none fw-semibold">
                                Login
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection