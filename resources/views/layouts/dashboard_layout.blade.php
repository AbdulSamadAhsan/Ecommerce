<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Inventory Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f7fc;
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: #111827;
            color: white;
            padding: 25px 15px;
            overflow-y: auto;
            transition: 0.3s;
            z-index: 1050;
        }

        .sidebar.active {
            left: 0;
        }

        .brand {
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 35px;
        }

        .sidebar .nav-link {
            color: #d1d5db;
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 10px;
            transition: 0.3s;
            font-weight: 600;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #2563eb;
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 260px;
            transition: 0.3s;
            min-height: 100vh;
        }

        .top-navbar {
            background: white;
            padding: 16px 25px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .menu-toggle {
            border: none;
            background: transparent;
            font-size: 24px;
            display: none;
        }

        .profile-btn {
            border: none;
            background: transparent;
            font-weight: 600;
        }

        .content-wrapper {
            padding: 25px;
        }

        .dashboard-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: 0.3s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .dashboard-icon {
            width: 55px;
            height: 55px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
        }

        .bg-blue {
            background: #2563eb;
        }

        .bg-green {
            background: #10b981;
        }

        .bg-orange {
            background: #f59e0b;
        }

        .bg-red {
            background: #ef4444;
        }

        .table th {
            border: none;
            color: #6b7280;
            font-size: 14px;
        }

        .table td {
            border-color: #f1f5f9;
            padding: 14px 8px;
        }

        .chart-container {
            position: relative;
            height: 350px;
        }

        .sidebar-overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            top: 0;
            left: 0;
            z-index: 1040;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        @media(max-width:768px) {

            .menu-toggle {
                display: block;
            }

            .sidebar {
                left: -260px;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')

</head>

<body>

    <div id="app">

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->

        <div class="sidebar" id="sidebar">

            <div class="brand">
                <i class="bi bi-box-seam"></i>
                Inventory
            </div>

            <ul class="nav flex-column">

                <li class="nav-item">

                    <a href="{{ route('home') }}" @class(['nav-link', 'active' => request()->routeIs('home')])>

                        <i class="bi bi-grid-fill"></i>
                        Dashboard

                    </a>

                </li>

                <li class="nav-item">
                    <a href="{{ route('products.index') }}" @class(['nav-link', 'active' => request()->routeIs('products.*')])>
                        <i class="bi bi-box"></i>
                        Products
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-tags"></i>
                        Categories
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-cart-check"></i>
                        Orders
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-people"></i>
                        Customers
                    </a>
                </li> 



                <li class="nav-item">
    <a href="#" class="nav-link">
        <i class="bi bi-truck"></i>
        Suppliers
    </a>
</li>

<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="bi bi-arrow-left-right"></i>
        Stock
    </a>
</li>

<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="bi bi-cart-check"></i>
        Sales
    </a>
</li>

<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="bi bi-bar-chart"></i>
        Reports
    </a>
</li>


            </ul>

        </div>

        <!-- Main Content -->

        <div class="main-content">

            <!-- Navbar -->

            <nav class="top-navbar d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center gap-3">

                    <button class="menu-toggle" id="menuToggle">
                        <i class="bi bi-list"></i>
                    </button>

                    <h4 class="fw-bold mb-0">
                        @yield('page-title')
                    </h4>

                </div>

                <div class="dropdown">

                    <button class="profile-btn dropdown-toggle" data-bs-toggle="dropdown">

                        <i class="bi bi-person-circle"></i>
                        Admin

                    </button>

                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4">

                        <li>
                            <a class="dropdown-item" href="#">
                                Profile
                            </a>
                        </li>

                        <li>

                            <a href="{{ route('logout') }}" class="dropdown-item text-danger"
                                onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">

                                <i class="bi bi-box-arrow-right me-2"></i>
                                Logout

                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">

                                @csrf

                            </form>

                        </li>

                    </ul>

                </div>

            </nav>

            <!-- Dynamic Content -->

            <div class="content-wrapper">

                @yield('content')

            </div>

        </div>

    </div>

    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('menuToggle');

        toggleBtn.addEventListener('click', () => {

            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');

        });

        overlay.addEventListener('click', () => {

            sidebar.classList.remove('active');
            overlay.classList.remove('active');

        });
    </script>

    @stack('scripts')

</body>

</html>
