<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>TechStore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @livewireStyles

    <style>
        body {
            background: #f8fafc;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar-custom {
            border-radius: 22px;
            background: rgba(255, 255, 255, .95);
            backdrop-filter: blur(12px);
        }

        .footer {
            background: #0f172a;
            color: #cbd5e1;
            border-radius: 35px 35px 0 0;
        }

        .footer a {
            color: #cbd5e1;
            text-decoration: none;
        }

        .footer a:hover {
            color: white;
        }
    </style>
</head>

<body>

    <div class="container py-3">

        <nav class="navbar navbar-expand-lg navbar-light navbar-custom shadow-sm px-3 px-lg-4 mb-4 sticky-top">

            <a wire:navigate href="{{ route('home') }}" class="navbar-brand fw-bold fs-3 text-primary">
                <i class="bi bi-bag-heart-fill me-1"></i>
                TechStore
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="mainNavbar">

                <ul class="navbar-nav mx-auto gap-lg-3">

                    <li class="nav-item">
                        <a wire:navigate href="{{ route('front') }}" @class([
                            'nav-link fw-semibold',
                            'active' => request()->routeIs('home'),
                        ])>
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link fw-semibold">
                            Shop
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link fw-semibold">
                            Contact
                        </a>
                    </li>

                </ul>

                <div class="d-flex gap-2 mt-3 mt-lg-0">

                    <a wire:navigate href="{{ route('cart') }}" class="btn btn-light rounded-pill position-relative">

                        <i class="bi bi-cart3"></i>


                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <livewire:cart-counter />
                        </span>


                    </a>

                    <button class="btn btn-primary rounded-pill px-4">
                        Login
                    </button>

                </div>

            </div>

        </nav>

    </div>

    {{ $slot }}

    <footer class="footer pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row g-4">

                <div class="col-md-4">
                    <h3 class="text-white fw-bold">
                        TechStore
                    </h3>

                    <p>
                        Modern ecommerce store for electronics, gadgets and accessories.
                    </p>
                </div>

                <div class="col-md-2">
                    <h6 class="text-white fw-bold">Links</h6>
                    <a wire:navigate href="{{ route('home') }}" class="d-block mb-2">Home</a>
                    <a href="#" class="d-block mb-2">Shop</a>
                    <a wire:navigate href="{{ route('cart') }}" class="d-block mb-2">Cart</a>
                </div>

                <div class="col-md-3">
                    <h6 class="text-white fw-bold">Support</h6>
                    <a href="#" class="d-block mb-2">Contact</a>
                    <a href="#" class="d-block mb-2">Returns</a>
                    <a href="#" class="d-block mb-2">Shipping</a>
                </div>

                <div class="col-md-3">
                    <h6 class="text-white fw-bold">Newsletter</h6>

                    <div class="input-group">
                        <input type="email" class="form-control rounded-start-pill" placeholder="Email address">

                        <button class="btn btn-primary rounded-end-pill">
                            Send
                        </button>
                    </div>
                </div>

            </div>

            <hr class="border-secondary my-4">

            <div class="text-center">
                © {{ date('Y') }} TechStore. All rights reserved.
            </div>
        </div>
    </footer>

    @livewireScripts

</body>

</html>
