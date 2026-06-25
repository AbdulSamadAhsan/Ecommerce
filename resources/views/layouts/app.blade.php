<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    @livewireStyles
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

        /* ===================================
   Sidebar Links
=================================== */

        .sidebar .nav-link {
            color: #d1d5db;
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 8px;
            transition: all .3s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #2563eb;
            color: #fff;
        }

        /* ===================================
   Dropdown Parent
=================================== */

        .sidebar-dropdown {
            margin-bottom: 6px;
        }

        .dropdown-toggle-btn {
            display: flex !important;
            justify-content: space-between;
            align-items: center;
        }

        .dropdown-toggle-btn span {
            display: flex;
            align-items: center;
        }

        .dropdown-toggle-btn span i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        /* ===================================
   Arrow Animation
=================================== */

        .dropdown-arrow {
            margin-left: auto;
            font-size: 12px;
            transition: all .3s ease;
            opacity: .7;
        }

        .dropdown-arrow.rotate {
            transform: rotate(180deg);
        }

        .sidebar-dropdown.open>.dropdown-toggle-btn {
            background: #2563eb;
            color: white !important;
        }

        /* ===================================
   Dropdown Menu
=================================== */

        .sidebar-dropdown-menu {
            display: none;
            margin-top: 6px;
            margin-left: 18px;
            padding-left: 14px;
            border-left: 2px solid rgba(255, 255, 255, .08);
        }

        .sidebar-dropdown-menu.show {
            display: block;
            animation: slideDown .25s ease;
        }

        /* ===================================
   Dropdown Items
=================================== */

        .sidebar-dropdown-menu .nav-link {
            padding: 10px 14px;
            margin-bottom: 4px;
            border-radius: 12px;
            color: #9ca3af;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all .25s ease;
        }

        /* Reduced icon gap */

        .sidebar-dropdown-menu .nav-link i {
            width: 16px;
            margin-right: 6px;
            font-size: 14px;
            text-align: center;
        }

        .sidebar-dropdown-menu .nav-link:hover {
            background: rgba(37, 99, 235, .15);
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar-dropdown-menu .nav-link.active {
            background: #2563eb;
            color: #fff;
        }

        /* ===================================
   Animation
=================================== */

        @keyframes slideDown {

            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }

        /* ===================================
   Mobile Responsive
=================================== */

        @media (max-width: 768px) {

            .sidebar-dropdown-menu {
                margin-left: 10px;
                padding-left: 10px;
            }

            .sidebar-dropdown-menu .nav-link {
                padding: 10px 12px;
            }

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

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
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

</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->

    <aside class="sidebar" id="sidebar">

        <div class="brand">
            <i class="bi bi-box-seam"></i>
            Inventory
        </div>

        @php
            use Illuminate\Support\Facades\Route;

            function adminRoute($name)
            {
                return Route::has($name) ? route($name) : '#';
            }

            function activeRoute($pattern)
            {
                return request()->routeIs($pattern) ? 'active' : '';
            }

            function openRoute($pattern)
            {
                return request()->routeIs($pattern) ? 'open' : '';
            }

            function showRoute($pattern)
            {
                return request()->routeIs($pattern) ? 'show' : '';
            }

            function rotateRoute($pattern)
            {
                return request()->routeIs($pattern) ? 'rotate' : '';
            }
        @endphp
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ adminRoute('dashboard') }}" class="nav-link {{ activeRoute('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('products.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-box-seam-fill"></i> Products</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('products.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('products.*') }}">
                    <a href="{{ adminRoute('products.index') }}" class="nav-link {{ activeRoute('products.index') }}">
                        <i class="bi bi-grid"></i> All Products
                    </a>

                    <a href="{{ adminRoute('products.create') }}"
                        class="nav-link {{ activeRoute('products.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Product
                    </a>

                    <a href="{{ adminRoute('reviews.index') }}" class="nav-link {{ activeRoute('reviews.*') }}">
                        <i class="bi bi-star-fill"></i> Reviews
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('categories.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-tags-fill"></i> Categories</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('categories.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('categories.*') }}">
                    <a href="{{ adminRoute('categories.index') }}"
                        class="nav-link {{ activeRoute('categories.index') }}">
                        <i class="bi bi-list-ul"></i> All Categories
                    </a>

                    <a href="{{ adminRoute('categories.create') }}"
                        class="nav-link {{ activeRoute('categories.create') }}">
                        <i class="bi bi-folder-plus"></i> Add Category
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('brands.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-award-fill"></i> Brands</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('brands.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('brands.*') }}">
                    <a href="{{ adminRoute('brands.index') }}" class="nav-link {{ activeRoute('brands.index') }}">
                        <i class="bi bi-list-ul"></i> All Brands
                    </a>

                    <a href="{{ adminRoute('brands.create') }}" class="nav-link {{ activeRoute('brands.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Brand
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('orders.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-cart-check-fill"></i> Orders</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('orders.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('orders.*') }}">
                    <a href="{{ adminRoute('orders.index') }}" class="nav-link {{ activeRoute('orders.index') }}">
                        <i class="bi bi-list-check"></i> All Orders
                    </a>

                    <a href="{{ adminRoute('orders.pending') }}" class="nav-link {{ activeRoute('orders.pending') }}">
                        <i class="bi bi-clock-history"></i> Pending Orders
                    </a>

                    <a href="{{ adminRoute('orders.completed') }}"
                        class="nav-link {{ activeRoute('orders.completed') }}">
                        <i class="bi bi-check-circle-fill"></i> Completed Orders
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('warehouses.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-building-fill"></i> Warehouses</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('warehouses.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('warehouses.*') }}">
                    <a href="{{ adminRoute('warehouses.index') }}"
                        class="nav-link {{ activeRoute('warehouses.index') }}">
                        <i class="bi bi-list-ul"></i> All Warehouses
                    </a>

                    <a href="{{ adminRoute('warehouses.create') }}"
                        class="nav-link {{ activeRoute('warehouses.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Warehouse
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('suppliers.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-truck"></i> Suppliers</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('suppliers.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('suppliers.*') }}">
                    <a href="{{ adminRoute('suppliers.index') }}"
                        class="nav-link {{ activeRoute('suppliers.index') }}">
                        <i class="bi bi-list-ul"></i> All Suppliers
                    </a>

                    <a href="{{ adminRoute('suppliers.create') }}"
                        class="nav-link {{ activeRoute('suppliers.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Supplier
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('departments.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-diagram-3"></i> Departments</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('departments.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('departments.*') }}">
                    <a href="{{ adminRoute('departments.index') }}"
                        class="nav-link {{ activeRoute('departments.index') }}">
                        <i class="bi bi-list-ul"></i> All Departments
                    </a>

                    <a href="{{ adminRoute('departments.create') }}"
                        class="nav-link {{ activeRoute('departments.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Department
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('employees.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-people-fill"></i> Employees</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('employees.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('employees.*') }}">
                    <a href="{{ adminRoute('employees.index') }}"
                        class="nav-link {{ activeRoute('employees.index') }}">
                        <i class="bi bi-list-ul"></i> All Employees
                    </a>

                    <a href="{{ adminRoute('employees.create') }}"
                        class="nav-link {{ activeRoute('employees.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Employee
                    </a>
                </div>
            </li>

            <li
                class="nav-item sidebar-dropdown {{ request()->routeIs('educations.*') || request()->routeIs('institutions.*') ? 'open' : '' }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-mortarboard-fill"></i> Education</span>
                    <i
                        class="bi bi-chevron-down dropdown-arrow {{ request()->routeIs('educations.*') || request()->routeIs('institutions.*') ? 'rotate' : '' }}"></i>
                </a>

                <div
                    class="sidebar-dropdown-menu {{ request()->routeIs('educations.*') || request()->routeIs('institutions.*') ? 'show' : '' }}">
                    <a href="{{ adminRoute('institutions.index') }}"
                        class="nav-link {{ activeRoute('institutions.index') }}">
                        <i class="bi bi-building-fill"></i> All Institutions
                    </a>

                    <a href="{{ adminRoute('institutions.create') }}"
                        class="nav-link {{ activeRoute('institutions.create') }}">
                        <i class="bi bi-building-add"></i> Add Institution
                    </a>

                    <a href="{{ adminRoute('educations.index') }}"
                        class="nav-link {{ activeRoute('educations.index') }}">
                        <i class="bi bi-list-ul"></i> All Educations
                    </a>

                    <a href="{{ adminRoute('educations.create') }}"
                        class="nav-link {{ activeRoute('educations.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Education
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('shipments.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-truck"></i> Shipments</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('shipments.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('shipments.*') }}">
                    <a href="{{ adminRoute('shipments.index') }}"
                        class="nav-link {{ activeRoute('shipments.index') }}">
                        <i class="bi bi-list-ul"></i> All Shipments
                    </a>

                    <a href="{{ adminRoute('shipments.create') }}"
                        class="nav-link {{ activeRoute('shipments.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Shipment
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('purchases.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-bag-plus-fill"></i> Purchases</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('purchases.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('purchases.*') }}">
                    <a href="{{ adminRoute('purchases.history') }}"
                        class="nav-link {{ activeRoute('purchases.history') }}">
                        <i class="bi bi-list-ul"></i> All Purchases
                    </a>

                    <a href="{{ adminRoute('purchases.create') }}"
                        class="nav-link {{ activeRoute('purchases.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Purchase
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('wallet-topups.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-wallet2"></i> Wallet Topups</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('wallet-topups.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('wallet-topups.*') }}">
                    <a href="{{ adminRoute('wallet-topups.index') }}"
                        class="nav-link {{ activeRoute('wallet-topups.index') }}">
                        <i class="bi bi-list-ul"></i> All Topup Requests
                    </a>

                    <a href="{{ adminRoute('wallet-topups.create') }}"
                        class="nav-link {{ activeRoute('wallet-topups.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Topup Request
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('tickets.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-headset"></i> Tickets</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('tickets.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('tickets.*') }}">
                    <a href="{{ adminRoute('tickets.index') }}"
                        class="nav-link {{ activeRoute('tickets.index') }}">
                        <i class="bi bi-list-ul"></i> All Tickets
                    </a>

                    <a href="{{ adminRoute('tickets.create') }}"
                        class="nav-link {{ activeRoute('tickets.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Ticket
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('delivery-boys.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-person-badge-fill"></i> Delivery Boys</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('delivery-boys.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('delivery-boys.*') }}">
                    <a href="{{ adminRoute('delivery-boys.index') }}"
                        class="nav-link {{ activeRoute('delivery-boys.index') }}">
                        <i class="bi bi-list-ul"></i> All Delivery Boys
                    </a>

                    <a href="{{ adminRoute('delivery-boys.create') }}"
                        class="nav-link {{ activeRoute('delivery-boys.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Delivery Boy
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('coupons.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-ticket-perforated-fill"></i> Coupons</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('coupons.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('coupons.*') }}">
                    <a href="{{ adminRoute('coupons.index') }}"
                        class="nav-link {{ activeRoute('coupons.index') }}">
                        <i class="bi bi-list-ul"></i> All Coupons
                    </a>

                    <a href="{{ adminRoute('coupons.create') }}"
                        class="nav-link {{ activeRoute('coupons.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Coupon
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('expenses.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-cash-stack"></i> Expenses</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('expenses.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('expenses.*') }}">
                    <a href="{{ adminRoute('expenses.index') }}"
                        class="nav-link {{ activeRoute('expenses.index') }}">
                        <i class="bi bi-list-ul"></i> All Expenses
                    </a>

                    <a href="{{ adminRoute('expenses.create') }}"
                        class="nav-link {{ activeRoute('expenses.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Expense
                    </a>

                    <a href="{{ adminRoute('expense-categories.index') }}"
                        class="nav-link {{ activeRoute('expense-categories.index') }}">
                        <i class="bi bi-tags-fill"></i> Expense Categories
                    </a>

                    <a href="{{ adminRoute('expense-categories.create') }}"
                        class="nav-link {{ activeRoute('expense-categories.create') }}">
                        <i class="bi bi-folder-plus"></i> Add Category
                    </a>
                </div>
            </li>

            <li class="nav-item">
                <a href="{{ adminRoute('salaries.all') }}" class="nav-link {{ activeRoute('salaries.*') }}">
                    <i class="bi bi-cash-stack"></i> Salaries
                </a>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('transactions.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-arrow-left-right"></i> Transactions</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('transactions.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('transactions.*') }}">
                    <a href="{{ adminRoute('transactions.index') }}"
                        class="nav-link {{ activeRoute('transactions.index') }}">
                        <i class="bi bi-list-ul"></i> All Transactions
                    </a>

                    <a href="{{ adminRoute('transactions.create') }}"
                        class="nav-link {{ activeRoute('transactions.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Transaction
                    </a>
                </div>
            </li>

            <li class="nav-item sidebar-dropdown {{ openRoute('taxes.*') }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span><i class="bi bi-percent"></i> Taxes</span>
                    <i class="bi bi-chevron-down dropdown-arrow {{ rotateRoute('taxes.*') }}"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ showRoute('taxes.*') }}">
                    <a href="{{ adminRoute('taxes.index') }}" class="nav-link {{ activeRoute('taxes.index') }}">
                        <i class="bi bi-list-ul"></i> All Taxes
                    </a>

                    <a href="{{ adminRoute('taxes.create') }}" class="nav-link {{ activeRoute('taxes.create') }}">
                        <i class="bi bi-plus-circle-fill"></i> Add Tax
                    </a>
                </div>
            </li>

            <li class="nav-item">
                <a href="{{ adminRoute('attendances.index') }}"
                    class="nav-link {{ activeRoute('attendances.*') }}">
                    <i class="bi bi-calendar-check-fill"></i> Attendance
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ adminRoute('leaves.index') }}" class="nav-link {{ activeRoute('leaves.*') }}">
                    <i class="bi bi-calendar-x-fill"></i> Leaves
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ adminRoute('payrolls.index') }}" class="nav-link {{ activeRoute('payrolls.*') }}">
                    <i class="bi bi-wallet2"></i> Payroll
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ adminRoute('customers.index') }}" class="nav-link {{ activeRoute('customers.*') }}">
                    <i class="bi bi-people-fill"></i> Customers
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ adminRoute('stocks.index') }}" class="nav-link {{ activeRoute('stocks.*') }}">
                    <i class="bi bi-arrow-left-right"></i> Stock
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ adminRoute('sales.history') }}" class="nav-link {{ activeRoute('sales.*') }}">
                    <i class="bi bi-cash-stack"></i> Sales
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ adminRoute('reports.index') }}" class="nav-link {{ activeRoute('reports.*') }}">
                    <i class="bi bi-bar-chart-line-fill"></i> Reports
                </a>
            </li>


        </ul>

    </aside>

    <!-- Main Content -->

    <main class="main-content">

        <nav class="top-navbar d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-3">

                <button class="menu-toggle" id="menuToggle">
                    <i class="bi bi-list"></i>
                </button>


            </div>

            <div class="dropdown">

                <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">

                    <i class="bi bi-person-circle"></i>
                    {{ auth()->user()->name ?? 'Admin' }}

                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item" href="#">
                            Profile
                        </a>
                    </li>

                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button class="dropdown-item text-danger">
                                Logout
                            </button>
                        </form>
                    </li>

                </ul>

            </div>

        </nav>

        <div class="content-wrapper">
            {{ $slot }}
        </div>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('menuToggle');

        if (toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
        }

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    </script>
    <script>
        document.querySelectorAll('.dropdown-toggle-btn')
            .forEach(button => {

                button.addEventListener('click', function() {

                    const menu = this.nextElementSibling;
                    const arrow = this.querySelector('.dropdown-arrow');

                    menu.classList.toggle('show');
                    arrow.classList.toggle('rotate');

                });

            });
    </script>
    @livewireScripts




</body>

</html>
