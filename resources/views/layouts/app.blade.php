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

        <ul class="nav flex-column">

            {{-- Dashboard --}}
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" @class(['nav-link', 'active' => request()->routeIs('dashboard')])>

                    <i class="bi bi-grid-1x2-fill"></i>
                    Dashboard

                </a>
            </li>

            {{-- Products --}}
            <li class="nav-item sidebar-dropdown  {{ request()->routeIs('products.*') ? 'open' : '' }}">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">

                    <span>
                        <i class="bi bi-box-seam-fill"></i>
                        Products
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>

                </a>

                <div class="sidebar-dropdown-menu ">

                    <a href="{{ route('products.index') }}" @class(['nav-link', 'active' => request()->routeIs('products.index')])>

                        <i class="bi bi-grid"></i>
                        All Products

                    </a>

                    <a href="{{ route('products.create') }}" @class([
                        'nav-link',
                        'active' => request()->routeIs('products.create'),
                    ])>

                        <i class="bi bi-plus-circle-fill"></i>
                        Add Product

                    </a>

                    <a href="" @class(['nav-link'])>
                        <i class="bi bi-star-fill"></i>

                        Reviews

                    </a>
                </div>

            </li>
            {{-- Categories --}}
            <li class="nav-item sidebar-dropdown {{ request()->routeIs('categories.*') ? 'open' : '' }}">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn ">

                    <span>
                        <i class="bi bi-tags-fill"></i>
                        Categories
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>

                </a>

                <div class="sidebar-dropdown-menu ">

                    <a href="{{ route('categories.index') }}" class="nav-link">
                        <i class="bi bi-list-ul"></i>
                        All Categories
                    </a>

                    <a href="{{ route('categories.create') }}" @class([
                        'nav-link',
                        'active' => request()->routeIs('categories.create'),
                    ])>
                        <i class="bi bi-folder-plus"></i>
                        Add Category
                    </a>

                </div>

            </li>



            <li class="nav-item sidebar-dropdown {{ request()->routeIs('brands.*') ? 'open' : '' }}">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">

                    <span>
                        <i class="bi bi-award-fill"></i>
                        Brands
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>

                </a>

                <div class="sidebar-dropdown-menu {{ request()->routeIs('brands.*') ? 'show' : '' }}">

                    <a href="{{ route('brands.index') }}" @class(['nav-link', 'active' => request()->routeIs('brands.index')])>

                        <i class="bi bi-list-ul"></i>
                        All Brands

                    </a>

                    <a href="{{ route('brands.create') }}" @class(['nav-link', 'active' => request()->routeIs('brands.create')])>

                        <i class="bi bi-plus-circle-fill"></i>
                        Add Brand

                    </a>

                </div>

            </li>



            {{-- Orders --}}
            <li class="nav-item sidebar-dropdown">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">

                    <span>
                        <i class="bi bi-cart-check-fill"></i>
                        Orders
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>

                </a>

                <div class="sidebar-dropdown-menu">

                    <a href="#" class="nav-link">
                        <i class="bi bi-list-check"></i>
                        All Orders
                    </a>

                    <a href="#" class="nav-link">
                        <i class="bi bi-clock-history"></i>
                        Pending Orders
                    </a>

                    <a href="#" class="nav-link">
                        <i class="bi bi-check-circle-fill"></i>
                        Completed Orders
                    </a>

                </div>

            </li>
            <li class="nav-item sidebar-dropdown">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">

                    <span>
                        <i class="bi bi-building-fill"></i>
                        Warehouses
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>

                </a>

                <div class="sidebar-dropdown-menu">

                    <a href="{{ route('warehouses.index') }}" class="nav-link">

                        <i class="bi bi-list-ul"></i>
                        All Warehouses

                    </a>

                    <a href="{{ route('warehouses.create') }}" class="nav-link">

                        <i class="bi bi-plus-circle-fill"></i>
                        Add Warehouse

                    </a>

                </div>

            </li>
            <li class="nav-item sidebar-dropdown">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">

                    <span>
                        <i class="bi bi-truck"></i>
                        Suppliers
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>

                </a>

                <div class="sidebar-dropdown-menu">

                    <a href="{{ route('suppliers.index') }}" class="nav-link">

                        <i class="bi bi-list-ul"></i>
                        All Suppliers

                    </a>

                    <a href="{{ route('suppliers.create') }}" class="nav-link">

                        <i class="bi bi-plus-circle-fill"></i>
                        Add Supplier

                    </a>

                </div>

            </li>





            {{-- Departments --}}
            <li class="nav-item sidebar-dropdown {{ request()->routeIs('departments.*') ? 'open' : '' }}">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-diagram-3"></i>
                        Departments
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ request()->routeIs('departments.*') ? 'show' : '' }}">

                    <a href="{{ route('departments.index') }}"
                        class="nav-link {{ request()->routeIs('departments.index') ? 'active' : '' }}">
                        <i class="bi bi-list-ul"></i>
                        All Departments
                    </a>

                    <a href="{{ route('departments.create') }}"
                        class="nav-link {{ request()->routeIs('departments.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Department
                    </a>


                </div>
            </li>


            {{-- Employees --}}
            <li class="nav-item sidebar-dropdown {{ request()->routeIs('employees.*') ? 'open' : '' }}">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-people-fill"></i>
                        Employees
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu {{ request()->routeIs('employees.*') ? 'show' : '' }}">

                    <a href="{{ route('employees.index') }}"
                        class="nav-link {{ request()->routeIs('employees.index') ? 'active' : '' }}">
                        <i class="bi bi-list-ul"></i>
                        All Employees
                    </a>

                    <a href="{{ route('employees.create') }}"
                        class="nav-link {{ request()->routeIs('employees.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Employee
                    </a>

                </div>
            </li>

            <li
                class="nav-item sidebar-dropdown {{ request()->routeIs('educations.*') || request()->routeIs('institutions.*') ? 'open' : '' }}">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-mortarboard-fill"></i>
                        Education
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div
                    class="sidebar-dropdown-menu {{ request()->routeIs('educations.*') || request()->routeIs('institutions.*') ? 'show' : '' }}">

                    <a href="{{ route('institutions.index') }}"
                        class="nav-link {{ request()->routeIs('institutions.index') ? 'active' : '' }}">
                        <i class="bi bi-building-fill"></i>
                        All Institutions
                    </a>

                    <a href="{{ route('institutions.create') }}"
                        class="nav-link {{ request()->routeIs('institutions.create') ? 'active' : '' }}">
                        <i class="bi bi-building-add"></i>
                        Add Institution
                    </a>

                    <a href="{{ route('educations.index') }}"
                        class="nav-link {{ request()->routeIs('educations.index') ? 'active' : '' }}">
                        <i class="bi bi-list-ul"></i>
                        All Educations
                    </a>

                    <a href="{{ route('educations.create') }}"
                        class="nav-link {{ request()->routeIs('educations.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Education
                    </a>

                </div>
            </li>
            {{-- Shipments --}}
            <li class="nav-item sidebar-dropdown ">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-truck"></i>
                        Shipments
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu">

                    <a href="#" class="nav-link ">
                        <i class="bi bi-list-ul"></i>
                        All Shipments
                    </a>

                    <a href="" class="nav-link ">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Shipment
                    </a>

                </div>
            </li>


            {{-- Purchases --}}
            <li class="nav-item sidebar-dropdown ">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-bag-plus-fill"></i>
                        Purchases
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu ">

                    <a href="" class="nav-link ">
                        <i class="bi bi-list-ul"></i>
                        All Purchases
                    </a>

                    <a href="" class="nav-link ">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Purchase
                    </a>

                </div>
            </li>


            {{-- Wallet Topup Requests --}}
            <li class="nav-item sidebar-dropdown ">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-wallet2"></i>
                        Wallet Topups
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu ">

                    <a href="" class="nav-link ">
                        <i class="bi bi-list-ul"></i>
                        All Topup Requests
                    </a>

                    <a href="" class="nav-link ">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Topup Request
                    </a>

                </div>
            </li>


            {{-- Customer Support Tickets --}}
            <li class="nav-item sidebar-dropdown ">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-headset"></i>
                        Tickets
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu ">

                    <a href="" class="nav-link ">
                        <i class="bi bi-list-ul"></i>
                        All Tickets
                    </a>

                    <a href="" class="nav-link ">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Ticket
                    </a>

                </div>
            </li>


            {{-- Delivery Boys --}}
            <li class="nav-item sidebar-dropdown">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-person-badge-fill"></i>
                        Delivery Boys
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu ">

                    <a href="" class="nav-link ">
                        <i class="bi bi-list-ul"></i>
                        All Delivery Boys
                    </a>

                    <a href="" class="nav-link">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Delivery Boy
                    </a>

                </div>
            </li>


            {{-- Product Reviews --}}


            <li class="nav-item sidebar-dropdown">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-ticket-perforated-fill"></i>
                        Coupons
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu">

                    <a href="" class="nav-link">
                        <i class="bi bi-list-ul"></i>
                        All Coupons
                    </a>

                    <a href="" class="nav-link">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Coupon
                    </a>

                </div>

            </li>
            <li class="nav-item sidebar-dropdown">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-cash-stack"></i>
                        Expenses
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu">

                    <a href="" class="nav-link">
                        <i class="bi bi-list-ul"></i>
                        All Expenses
                    </a>

                    <a href="" class="nav-link">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Expense
                    </a>

                    <a href="" class="nav-link">
                        <i class="bi bi-tags-fill"></i>
                        Expense Categories
                    </a>

                    <a href="" class="nav-link">
                        <i class="bi bi-folder-plus"></i>
                        Add Category
                    </a>

                </div>

            </li>


            <li class="nav-item sidebar-dropdown">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-cash-stack"></i>
                        Salaries
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu">

                    <a href="" class="nav-link">
                        <i class="bi bi-list-ul"></i>
                        All Salaries
                    </a>

                    <a href="" class="nav-link">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Salary
                    </a>

                </div>

            </li>

            <li class="nav-item sidebar-dropdown">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-arrow-left-right"></i>
                        Transactions
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu">

                    <a href="" class="nav-link">
                        <i class="bi bi-list-ul"></i>
                        All Transactions
                    </a>

                    <a href="" class="nav-link">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Transaction
                    </a>

                </div>

            </li>
            <li class="nav-item sidebar-dropdown">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-percent"></i>
                        Taxes
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu">

                    <a href="" class="nav-link">
                        <i class="bi bi-list-ul"></i>
                        All Taxes
                    </a>

                    <a href="" class="nav-link">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Tax
                    </a>

                </div>

            </li>
            <li class="nav-item sidebar-dropdown">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-calendar-check-fill"></i>
                        Attendance
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu">

                    <a href="" class="nav-link">
                        <i class="bi bi-list-ul"></i>
                        All Attendance
                    </a>

                    <a href="" class="nav-link">
                        <i class="bi bi-plus-circle-fill"></i>
                        Mark Attendance
                    </a>

                </div>

            </li>
            <li class="nav-item sidebar-dropdown">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-calendar-x-fill"></i>
                        Leaves
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu">

                    <a href="" class="nav-link">
                        <i class="bi bi-list-ul"></i>
                        All Leaves
                    </a>

                    <a href="" class="nav-link">
                        <i class="bi bi-plus-circle-fill"></i>
                        Add Leave
                    </a>

                </div>

            </li>
            <li class="nav-item sidebar-dropdown">

                <a href="javascript:void(0)" class="nav-link dropdown-toggle-btn">
                    <span>
                        <i class="bi bi-wallet2"></i>
                        Payroll
                    </span>

                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </a>

                <div class="sidebar-dropdown-menu">

                    <a href="" class="nav-link">
                        <i class="bi bi-list-ul"></i>
                        All Payrolls
                    </a>

                    <a href="" class="nav-link">
                        <i class="bi bi-plus-circle-fill"></i>
                        Generate Payroll
                    </a>

                </div>

            </li>
            {{-- Customers --}}
            <li class="nav-item">
                <a href="{{ route('customers.index') }}" class="nav-link">
                    <i class="bi bi-people-fill"></i>
                    Customers
                </a>
            </li>




            {{-- Stock --}}
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-arrow-left-right"></i>
                    Stock
                </a>
            </li>

            {{-- Sales -
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-cash-stack"></i>
                    Sales
                </a>
            </li> --}}

            {{-- Reports --}}
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    Reports
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
