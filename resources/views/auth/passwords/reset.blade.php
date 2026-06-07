@section('content')
    <div class="container ">
        <div class="row justify-content-center align-items-center ">
            <div class="col-md-6">

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                    <!-- Header -->
                    <div class="bg-primary text-white text-center py-4">
                        <h2 class="fw-bold mb-1">Welcome Back</h2>
                        <p class="mb-0 opacity-75">Login to continue</p>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-5">

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">
                                    Email Address
                                </label>

                                <input id="email" type="email"
                                    class="form-control form-control-lg rounded-3 @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" placeholder="Enter your email" required
                                    autocomplete="email" autofocus>

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

                                <input id="password" type="password"
                                    class="form-control form-control-lg rounded-3 @error('password') is-invalid @enderror"
                                    name="password" placeholder="Enter your password" required
                                    autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Remember -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        Remember Me
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small" href="{{ route('password.request') }}">
                                        Forgot Password?
                                    </a>
                                @endif
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-bold">
                                    Login
                                </button>
                            </div>

                            <div class="text-center">
                                <small class="text-muted">
                                    if you don't have an account?
                                </small>

                                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                                    Register
                                </a>
                            </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
