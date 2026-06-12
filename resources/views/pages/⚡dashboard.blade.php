<?php

use Livewire\Component;

new class extends Component {};
?>

<div class="content-wrapper">

    <!-- Cards -->
    <div class="row g-4 mb-4">

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Total Products</h6>
                        <h2 class="fw-bold">1,250</h2>
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
                        <h6 class="text-muted">Orders</h6>
                        <h2 class="fw-bold">530</h2>
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
                        <h6 class="text-muted">Revenue</h6>
                        <h2 class="fw-bold">$8,500</h2>
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
                        <h6 class="text-muted">Customers</h6>
                        <h2 class="fw-bold">1,820</h2>
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

        <div class="col-lg-8">
            <div class="dashboard-card">

                <div class="mb-4">
                    <h5 class="fw-bold">Sales Overview</h5>
                    <small class="text-muted">
                        Monthly Sales Report
                    </small>
                </div>

                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>

            </div>
        </div>

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

<style>

</style>

@script
    <script>
        function initializeChart() {

            const canvas = document.getElementById('salesChart');

            if (!canvas) {
                return;
            }

            if (window.salesChartInstance) {
                window.salesChartInstance.destroy();
            }

            window.salesChartInstance = new Chart(canvas, {
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
                            420,
                            540,
                            480,
                            610,
                            720,
                            680,
                            760
                        ],

                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.15)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#2563eb',
                        borderWidth: 3
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: false
                        }
                    },

                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },

                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        }

        document.addEventListener(
            'livewire:navigated',
            initializeChart
        );

        document.addEventListener(
            'DOMContentLoaded',
            initializeChart
        );

        initializeChart();
    </script>
@endscript
