<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Inventory Management System</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>

        body{
            background: #f4f7fb;
            font-family: 'Nunito', sans-serif;
        }

        /* Navbar */

        .navbar{
            background: rgba(255,255,255,0.9) !important;
            backdrop-filter: blur(10px);
            padding: 15px 0;
        }

        .navbar-brand{
            font-size: 24px;
            font-weight: 700;
            color: #2563eb !important;
        }

        .nav-link{
            font-weight: 600;
            color: #374151 !important;
            margin-left: 10px;
            transition: 0.3s;
        }

        .nav-link:hover{
            color: #2563eb !important;
        }

        /* Buttons */

        .btn-login{
            border-radius: 50px;
            padding: 10px 22px;
            font-weight: 600;
            border: 1px solid #2563eb;
            color: #2563eb;
            transition: 0.3s;
        }

        .btn-login:hover{
            background: #2563eb;
            color: white;
        }

        .btn-register{
            border-radius: 50px;
            padding: 10px 22px;
            font-weight: 600;
            background: #2563eb;
            color: white;
            transition: 0.3s;
        }

        .btn-register:hover{
            background: #1d4ed8;
            color: white;
        }

        /* Main Content */

        .main-content{
            padding: 40px 0;
            min-height: 100vh;
        }

        /* Cards */

        .dashboard-card{
            border: none;
            border-radius: 20px;
            background: white;
            padding: 25px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.05);
            transition: 0.3s;
        }

        .dashboard-card:hover{
            transform: translateY(-5px);
        }

        /* Dropdown */

        .dropdown-menu{
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .dropdown-item{
            padding: 10px 18px;
            font-weight: 600;
        }

        .dropdown-item:hover{
            background: #f3f4f6;
        }

        /* Footer */

        .footer{
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 14px;
        }

    </style>
</head>

<body>

<div id="app">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg shadow-sm sticky-top">

        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-box-seam-fill"></i>
                Inventory System
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">

                <span class="navbar-toggler-icon"></span>

            </button>

         

              

            </div> 

        </div>

    </nav>

    <!-- Main Content -->
    <main class="main-content">

        <div class="container">

            @yield('content')

        </div>

    </main>

    <!-- Footer -->
    <div class="footer">

        © {{ date('Y') }} Inventory Management System.
        All Rights Reserved.

    </div>

</div>

</body>
</html>