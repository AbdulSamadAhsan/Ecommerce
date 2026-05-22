<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Inventory Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700"
          rel="stylesheet">

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            background: #f4f7fc;
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar{
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

        .sidebar.active{
            left: 0;
        }

        .brand{
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 35px;
        }

        .sidebar .nav-link{
            color: #d1d5db;
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 10px;
            transition: 0.3s;
            font-weight: 600;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active{
            background: #2563eb;
            color: white;
        }

        .sidebar .nav-link i{
            margin-right: 10px;
        }

        /* =========================
           MAIN CONTENT
        ========================= */

        .main-content{
            margin-left: 260px;
            transition: 0.3s;
            min-height: 100vh;
        }

        /* =========================
           NAVBAR
        ========================= */

        .top-navbar{
            background: white;
            padding: 16px 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .menu-toggle{
            border: none;
            background: transparent;
            font-size: 24px;
            display: none;
        }

        .profile-btn{
            border: none;
            background: transparent;
            font-weight: 600;
        }

        /* =========================
           CONTENT
        ========================= */

        .content-wrapper{
            padding: 25px;
        }

        /* =========================
           DASHBOARD CARDS
        ========================= */

        .dashboard-card{
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            height: 100%;
            transition: 0.3s;
        }

        .dashboard-card:hover{
            transform: translateY(-5px);
        }

        .dashboard-icon{
            width: 55px;
            height: 55px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
        }

        .bg-blue{
            background: #2563eb;
        }

        .bg-green{
            background: #10b981;
        }

        .bg-orange{
            background: #f59e0b;
        }

        .bg-red{
            background: #ef4444;
        }

        /* =========================
           TABLE
        ========================= */

        .table th{
            border: none;
            color: #6b7280;
            font-size: 14px;
        }

        .table td{
            border-color: #f1f5f9;
            padding: 14px 8px;
        }

        /* =========================
           CHART
        ========================= */

        .chart-container{
            position: relative;
            height: 350px;
        }

        /* =========================
           OVERLAY
        ========================= */

        .sidebar-overlay{
            position: fixed;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            top: 0;
            left: 0;
            z-index: 1040;
            display: none;
        }

        .sidebar-overlay.active{
            display: block;
        }

        /* =========================
           TABLET
        ========================= */

        @media(max-width: 992px){

            .sidebar{
                width: 220px;
            }

            .main-content{
                margin-left: 220px;
            }

        }

        /* =========================
           MOBILE
        ========================= */

        @media(max-width: 768px){

            .menu-toggle{
                display: block;
            }

            .sidebar{
                left: -260px;
                width: 260px;
            }

            .sidebar.active{
                left: 0;
            }

            .main-content{
                margin-left: 0;
            }

            .top-navbar{
                padding: 15px;
            }

            .content-wrapper{
                padding: 15px;
            }

            .dashboard-card{
                padding: 18px;
            }

            .chart-container{
                height: 260px;
            }

            .table-responsive{
                overflow-x: auto;
            }

        }

        /* =========================
           SMALL MOBILE
        ========================= */

        @media(max-width: 576px){

            .dashboard-card h2{
                font-size: 24px;
            }

            .dashboard-icon{
                width: 45px;
                height: 45px;
                font-size: 18px;
            }

            .chart-container{
                height: 220px;
            }

        }

    </style>

</head>

<body>

<div id="app">

    <!-- Sidebar Overlay -->

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->

    <div class="sidebar" id="sidebar">

        <div class="brand">
            <i class="bi bi-box-seam"></i>
            Inventory
        </div>

        <ul class="nav flex-column">

            <li class="nav-item">
                <a href="#" class="nav-link active">
                    <i class="bi bi-grid-fill"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('products.index') }}" class="nav-link">
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

        </ul>

    </div>

    <!-- Main Content -->

    <div class="main-content">

        <!-- Navbar -->

        <nav class="top-navbar d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-3">

                <button class="menu-toggle"
                        id="menuToggle">

                    <i class="bi bi-list"></i>

                </button>

                <h4 class="fw-bold mb-0">
                    Dashboard
                </h4>

            </div>

            <div class="dropdown">

                <button class="profile-btn dropdown-toggle"
                        data-bs-toggle="dropdown">

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
                  <a href="{{ route('logout') }}"
   class="dropdown-item text-danger"
   onclick="event.preventDefault();
   document.getElementById('logout-form').submit();">

    <i class="bi bi-box-arrow-right me-2"></i>
    Logout

</a>

<form id="logout-form"
      action="{{ route('logout') }}"
      method="POST"
      class="d-none">

    @csrf

</form>
                    </li>

                </ul>

            </div>

        </nav>

        <!-- Content -->

        <div class="content-wrapper">

            <!-- Cards -->

            <div class="row g-4 mb-4">

                <div class="col-lg-3 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h6 class="text-muted">
                                    Total Products
                                </h6>

                                <h2 class="fw-bold">
                                    1,250
                                </h2>

                            </div>

                            <div class="dashboard-icon bg-blue">
                                <i class="bi bi-box"></i>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h6 class="text-muted">
                                    Orders
                                </h6>

                                <h2 class="fw-bold">
                                    530
                                </h2>

                            </div>

                            <div class="dashboard-icon bg-green">
                                <i class="bi bi-cart-check"></i>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h6 class="text-muted">
                                    Revenue
                                </h6>

                                <h2 class="fw-bold">
                                    $8,500
                                </h2>

                            </div>

                            <div class="dashboard-icon bg-orange">
                                <i class="bi bi-currency-dollar"></i>
                            </div>

                        </div>

                    </div>

                </div>
<div class="col-lg-3 col-md-6">

    <div class="dashboard-card">

        <div class="d-flex justify-content-between">

            <div>

                <h6 class="text-muted">
                    Customers
                </h6>

                <h2 class="fw-bold">
                    1,820
                </h2>

            </div>

            <div class="dashboard-icon bg-red">
                <i class="bi bi-people-fill"></i>
            </div>

        </div>

    </div>

</div>

            </div>

            <!-- Graph + Table -->

            <div class="row g-4">

                <!-- Sales Graph -->

                <div class="col-lg-8">

                    <div class="dashboard-card">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>

                                <h5 class="fw-bold">
                                    Sales Overview
                                </h5>

                                <small class="text-muted">
                                    Monthly Sales Report
                                </small>

                            </div>

                        </div>

                        <div class="chart-container">

                            <canvas id="salesChart"></canvas>

                        </div>

                    </div>

                </div>

                <!-- Product Table -->

                <div class="col-lg-4">

                    <div class="dashboard-card">

                        <h5 class="fw-bold mb-4">
                            Popular Products
                        </h5>

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead>

                                    <tr>
                                        <th>Product</th>
                                        <th>Sales</th>
                                        <th>Status</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <tr>
                                        <td>Laptop</td>
                                        <td>320</td>
                                        <td>
                                            <span class="badge bg-success">
                                                High
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Keyboard</td>
                                        <td>210</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                Good
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Mouse</td>
                                        <td>180</td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                Medium
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Monitor</td>
                                        <td>150</td>
                                        <td>
                                            <span class="badge bg-danger">
                                                Low
                                            </span>
                                        </td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Bootstrap JS -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sidebar Toggle -->

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

<!-- Chart -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('salesChart');

    if(canvas){

        const ctx = canvas.getContext('2d');

        new Chart(ctx, {

            type: 'line',

            data: {

                labels: [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May',
                    'Jun',
                    'Jul'
                ],

                datasets: [{

                    label: 'Sales',

                    data: [
                        1200,
                        1900,
                        3000,
                        2500,
                        4200,
                        3800,
                        5000
                    ],

                    borderColor: '#2563eb',

                    backgroundColor: 'rgba(37,99,235,0.15)',

                    fill: true,

                    tension: 0.4,

                    borderWidth: 3,

                    pointRadius: 5,

                    pointBackgroundColor: '#2563eb'

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    }

                }

            }

        });

    }

});

</script>

</body>
</html>