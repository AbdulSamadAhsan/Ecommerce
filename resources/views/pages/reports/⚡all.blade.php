<?php

use Livewire\Component;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\SalesReturn;
use App\Models\PurchaseReturn;
use App\Models\WalletTopupRequest;

new class extends Component {
    public string $from_date = '';
    public string $to_date = '';

    public function mount(): void
    {
        $this->from_date = now()->startOfMonth()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
    }

    public function filterQuery($query)
    {
        return $query
            ->when($this->from_date, function ($query) {
                $query->whereDate('created_at', '>=', $this->from_date);
            })
            ->when($this->to_date, function ($query) {
                $query->whereDate('created_at', '<=', $this->to_date);
            });
    }

    public function with(): array
    {
        return [
            'totalSales' => $this->filterQuery(Sale::query())->sum('subtotal'),

            'totalOrders' => $this->filterQuery(Order::query())->count(),

            'deliveredOrders' => $this->filterQuery(
                Order::query()->whereHas('shipment', function ($query) {
                    $query->where('status', 'delivered');
                }),
            )->count(),

            'pendingOrders' => $this->filterQuery(
                Order::query()->whereHas('shipment', function ($query) {
                    $query->where('status', 'pending');
                }),
            )->count(),

            'cancelledOrders' => $this->filterQuery(
                Order::query()->whereHas('shipment', function ($query) {
                    $query->where('status', 'cancelled');
                }),
            )->count(),

            'totalProducts' => Product::count(),

            'lowStockProducts' => Product::query()->whereColumn('quantity', '<=', 'minimum_stock')->count(),

            'outOfStockProducts' => Product::query()->where('quantity', '<=', 0)->count(),

            'customers' => Customer::count(),

            'suppliers' => Supplier::count(),

            'totalPurchases' => class_exists(Purchase::class) ? $this->filterQuery(Purchase::query())->sum('total_amount') : 0,

            'salesReturns' => $this->filterQuery(SalesReturn::query())->count(),

            'purchaseReturns' => $this->filterQuery(PurchaseReturn::query())->count(),

            'walletTopups' => $this->filterQuery(WalletTopupRequest::query()->where('status', 'approved'))->sum('amount'),

            'recentOrders' => $this->filterQuery(Order::query()->with(['sale.customer.user', 'shipment']))
                ->latest()
                ->limit(10)
                ->get(),
        ];
    }
};

?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                Reports
            </h3>

            <p class="text-muted mb-0">
                View sales, orders, stock, returns and financial reports
            </p>
        </div>
    </div>

    <div class="dashboard-card mb-4">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="from_date" class="form-label">
                    From Date
                </label>

                <input type="date" id="from_date" wire:model.live="from_date" class="form-control rounded-4">
            </div>

            <div class="col-md-4 mb-3">
                <label for="to_date" class="form-label">
                    To Date
                </label>

                <input type="date" id="to_date" wire:model.live="to_date" class="form-control rounded-4">
            </div>

            <div class="col-md-4 mb-3 d-flex align-items-end">
                <button type="button" class="btn btn-primary rounded-pill px-4 w-100">
                    Filter Reports
                </button>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Total Sales
                </h6>

                <h4 class="fw-bold">
                    Rs {{ number_format($totalSales, 2) }}
                </h4>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Total Orders
                </h6>

                <h4 class="fw-bold">
                    {{ $totalOrders }}
                </h4>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Delivered Orders
                </h6>

                <h4 class="fw-bold text-success">
                    {{ $deliveredOrders }}
                </h4>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Pending Orders
                </h6>

                <h4 class="fw-bold text-warning">
                    {{ $pendingOrders }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Cancelled Orders
                </h6>

                <h4 class="fw-bold text-danger">
                    {{ $cancelledOrders }}
                </h4>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Products
                </h6>

                <h4 class="fw-bold">
                    {{ $totalProducts }}
                </h4>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Low Stock
                </h6>

                <h4 class="fw-bold text-warning">
                    {{ $lowStockProducts }}
                </h4>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Out of Stock
                </h6>

                <h4 class="fw-bold text-danger">
                    {{ $outOfStockProducts }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Customers
                </h6>

                <h4 class="fw-bold">
                    {{ $customers }}
                </h4>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Suppliers
                </h6>

                <h4 class="fw-bold">
                    {{ $suppliers }}
                </h4>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Sales Returns
                </h6>

                <h4 class="fw-bold">
                    {{ $salesReturns }}
                </h4>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Purchase Returns
                </h6>

                <h4 class="fw-bold">
                    {{ $purchaseReturns }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Total Purchases
                </h6>

                <h4 class="fw-bold">
                    Rs {{ number_format($totalPurchases, 2) }}
                </h4>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="dashboard-card h-100">
                <h6 class="text-muted">
                    Approved Wallet Topups
                </h6>

                <h4 class="fw-bold">
                    Rs {{ number_format($walletTopups, 2) }}
                </h4>
            </div>
        </div>
    </div>

    <div class="dashboard-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">
                Recent Orders
            </h5>

            <span class="text-muted small">
                {{ $from_date ? \Carbon\Carbon::parse($from_date)->format('d M Y') : 'Beginning' }}
                -
                {{ $to_date ? \Carbon\Carbon::parse($to_date)->format('d M Y') : 'Today' }}
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Shipment</th>
                        <th>Payment</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentOrders as $order)
                        <tr wire:key="recent-order-{{ $order->id }}">
                            <td>
                                #{{ $order->id }}
                            </td>

                            <td>
                                {{ data_get($order, 'sale.customer.user.name', 'Walk-in Customer') }}
                            </td>

                            <td>
                                Rs {{ number_format(data_get($order, 'sale.total_amount', 0), 2) }}
                            </td>

                            <td>
                                @php
                                    $shipmentStatus = data_get($order, 'shipment.status', 'pending');

                                    $shipmentBadge = match ($shipmentStatus) {
                                        'delivered' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        'pending' => 'bg-warning text-dark',
                                        'in_transit' => 'bg-primary',
                                        default => 'bg-info',
                                    };
                                @endphp

                                <span class="badge {{ $shipmentBadge }} rounded-pill">
                                    {{ ucwords(str_replace('_', ' ', $shipmentStatus)) }}
                                </span>
                            </td>

                            <td>
                                @php
                                    $paymentStatus = data_get($order, 'sale.payment_status', 'pending');

                                    $paymentBadge = match ($paymentStatus) {
                                        'paid' => 'bg-success',
                                        'failed' => 'bg-danger',
                                        'refunded' => 'bg-dark',
                                        'partial' => 'bg-primary',
                                        default => 'bg-secondary',
                                    };
                                @endphp

                                <span class="badge {{ $paymentBadge }} rounded-pill">
                                    {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                                </span>
                            </td>

                            <td>
                                {{ $order->created_at?->format('d M Y') ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    No orders found between the selected dates.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
