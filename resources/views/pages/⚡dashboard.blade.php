<?php

use Livewire\Component;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\DeliveryBoy;
use App\Models\Warehouse;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use App\Models\Expense;
use App\Models\Employee;
new class extends Component {
    public $number_of_customer = 0;
    public $products_count = 0;
    public $orders_count = 0;
    public $revenue = 0;
    public $low_stock_products = [];
    public $brandsCount = 0;
    public $categoriesCount = 0;
    public $warehousesCount = 0;
    public $suppliersCount = 0;
    public $number_of_employee = 0;
    public $number_of_delivery_boy = 0;
    public $expenses = 0;
    public $earning = 0;
    public $salesLabels;
    public $salesData;
    public $sales;
    public function mount()
    {
        $this->number_of_customer = Customer::count();

        $this->products_count = Product::count();
        $this->categoriesCount = Category::count();
        $this->warehousesCount = Warehouse::count();
        $this->brandsCount = Brand::count();
        $this->orders_count = Order::count();

        $this->revenue = Sale::sum('subtotal');
        $this->suppliersCount = Supplier::count();
        $this->number_of_employee = Employee::count();
        $this->number_of_delivery_boy = DeliveryBoy::count();
        $this->expenses = Expense::sum('amount');
        $this->earning = $this->revenue - $this->expenses;
        $this->low_stock_products = Product::whereColumn('quantity', '<=', 'minimum_stock')->latest()->take(10)->get();
        $sales = Sale::selectRaw(
            "
        DATE_FORMAT(MIN(created_at), '%b %Y') as month,
        YEAR(created_at) as year,
        MONTH(created_at) as month_number,
        SUM(subtotal) as total
    ",
        )
            ->groupBy('year', 'month_number')
            ->orderBy('year')
            ->orderBy('month_number')
            ->get();
        $this->salesLabels = $sales->pluck('month')->toArray();
        $this->salesData = $sales->pluck('total')->toArray();
    }
};
?>

<div class="content-wrapper">

    <div class="row g-4 mb-4">

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Total Products</h6>
                        <h2 class="fw-bold">{{ $products_count }}</h2>
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
                        <h2 class="fw-bold">{{ $orders_count }}</h2>
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
                        <h2 class="fw-bold">Rs {{ number_format($revenue) }}</h2>
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
                        <h6 class="text-muted">Expenses</h6>
                        <h2 class="fw-bold">Rs {{ number_format($expenses) }}</h2>
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
                        <h6 class="text-muted">Earnings</h6>
                        <h2 class="fw-bold">Rs {{ number_format($earning) }}</h2>
                    </div>
                    <div class="dashboard-icon bg-orange">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Brands</h6>
                        <h2 class="fw-bold">{{ number_format($brandsCount) }}</h2>
                    </div>
                    <div class="dashboard-icon bg-primary">
                        <i class="bi bi-award"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Categories</h6>
                        <h2 class="fw-bold">{{ number_format($categoriesCount) }}</h2>
                    </div>
                    <div class="dashboard-icon bg-success">
                        <i class="bi bi-grid"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warehouses -->
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Warehouses</h6>
                        <h2 class="fw-bold">{{ number_format($warehousesCount) }}</h2>
                    </div>
                    <div class="dashboard-icon bg-warning">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Customers</h6>
                        <h2 class="fw-bold">{{ $number_of_customer }}</h2>
                    </div>
                    <div class="dashboard-icon bg-red">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Suppliers</h6>
                        <h2 class="fw-bold">{{ $suppliersCount }}</h2>
                    </div>
                    <div class="dashboard-icon bg-red">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Employees</h6>
                        <h2 class="fw-bold">{{ $number_of_employee }}</h2>
                    </div>
                    <div class="dashboard-icon bg-red">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Delivery Boys</h6>
                        <h2 class="fw-bold">{{ $number_of_delivery_boy }}</h2>
                    </div>
                    <div class="dashboard-icon bg-red">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        <div class="col-lg-8">
            <div class="dashboard-card">

                <div class="mb-4">
                    <h5 class="fw-bold">Sales Overview</h5>
                    <small class="text-muted">Monthly Sales Report</small>
                </div>

                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>

            </div>
        </div>

        <div class="col-lg-4">
            <div class="dashboard-card">

                <h5 class="fw-bold mb-4">Popular Products</h5>

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
                                <td><span class="badge bg-success">High</span></td>
                            </tr>

                            <tr>
                                <td>Keyboard</td>
                                <td>210</td>
                                <td><span class="badge bg-primary">Good</span></td>
                            </tr>

                            <tr>
                                <td>Mouse</td>
                                <td>180</td>
                                <td><span class="badge bg-warning text-dark">Medium</span></td>
                            </tr>

                            <tr>
                                <td>Monitor</td>
                                <td>150</td>
                                <td><span class="badge bg-danger">Low</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

    <div class="row g-4 mt-2">

        <div class="col-lg-12">
            <div class="dashboard-card">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-0">Low Stock Products</h5>
                        <small class="text-muted">Products that need restocking</small>
                    </div>

                    <span class="badge bg-danger">
                        {{ count($low_stock_products) }} Items
                    </span>
                </div>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Current Stock</th>
                                <th>Minimum Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($low_stock_products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>{{ $product->minimum_stock }}</td>
                                    <td>
                                        @if ($product->quantity == 0)
                                            <span class="badge bg-danger">Out Of Stock</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Low Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-success">
                                        No low stock products found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

            </div>
        </div>

    </div>

</div>

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
                    labels: @json($salesLabels),

                    datasets: [{
                        label: 'Sales',
                        data: [420, 540, 480, 610, 720, 680, 760],
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

        document.addEventListener('livewire:navigated', initializeChart);
        document.addEventListener('DOMContentLoaded', initializeChart);

        initializeChart();
    </script>
@endscript
