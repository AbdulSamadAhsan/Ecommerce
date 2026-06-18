<?php

use Livewire\Component;

new class extends Component {
    public int $id;

    public array $customer = [];
    public array $orders = [];
    public array $reviews = [];
    public array $walletTransactions = [];

    public function mount($id): void
    {
        $this->id = (int) $id;

        $this->customer = [
            'id' => $this->id,
            'name' => 'Ali Khan',
            'email' => 'ali@example.com',
            'phone' => '03001234567',
            'city' => 'Karachi',
            'address' => 'Gulshan-e-Iqbal, Karachi',
            'status' => 1,
            'joined_at' => '2026-06-01',
            'wallet_balance' => 8500,
        ];

        $this->orders = [['order_no' => 'ORD-1001', 'total' => 185000, 'status' => 'Delivered', 'date' => '2026-06-18'], ['order_no' => 'ORD-1002', 'total' => 45000, 'status' => 'Processing', 'date' => '2026-06-17']];

        $this->reviews = [
            [
                'product' => 'MacBook Pro M3',
                'rating' => 5,
                'review' => 'Excellent product. Fast performance and premium quality.',
                'date' => '2026-06-18',
            ],
            [
                'product' => 'Wireless Mouse',
                'rating' => 4,
                'review' => 'Good mouse and comfortable grip.',
                'date' => '2026-06-17',
            ],
        ];

        $this->walletTransactions = [['type' => 'credit', 'amount' => 5000, 'description' => 'Wallet top-up', 'date' => '2026-06-18'], ['type' => 'refund', 'amount' => 3500, 'description' => 'Order refund', 'date' => '2026-06-16']];
    }

    public function getTotalSpentProperty(): float
    {
        return collect($this->orders)->sum('total');
    }

    public function getTotalOrdersProperty(): int
    {
        return count($this->orders);
    }

    public function getAverageRatingProperty(): float
    {
        if (count($this->reviews) === 0) {
            return 0;
        }

        return round(collect($this->reviews)->avg('rating'), 1);
    }
};
?>

<div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Customer Details</h3>
            <p class="text-muted mb-0">Customer profile, orders, wallet and product reviews</p>
        </div>

        <a href="{{ route('customers.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Orders</h6>
                    <h3 class="fw-bold text-primary">{{ $this->totalOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Spent</h6>
                    <h3 class="fw-bold text-success">Rs {{ number_format($this->totalSpent) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Wallet</h6>
                    <h3 class="fw-bold text-info">Rs {{ number_format($customer['wallet_balance']) }}</h3>
                </div>
            </div>
        </div>


    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <h4 class="fw-bold">{{ $customer['name'] }}</h4>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Email:</strong><br>
                    {{ $customer['email'] }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Phone:</strong><br>
                    {{ $customer['phone'] }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>City:</strong><br>
                    {{ $customer['city'] }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Joined:</strong><br>
                    {{ $customer['joined_at'] }}
                </div>

                <div class="col-md-12 mb-3">
                    <strong>Address:</strong><br>
                    {{ $customer['address'] }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Status:</strong><br>
                    @if ($customer['status'])
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Customer Orders</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order['order_no'] }}</td>
                            <td>Rs {{ number_format($order['total']) }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $order['status'] }}
                                </span>
                            </td>
                            <td>{{ $order['date'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Product Reviews</h5>
        </div>

        <div class="card-body">
            @forelse ($reviews as $review)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <div>
                            <h6 class="fw-bold mb-1">
                                {{ $review['product'] }}
                            </h6>

                            <small class="text-muted">
                                {{ $review['date'] }}
                            </small>
                        </div>

                        <div class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review['rating'])
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                    </div>

                    <p class="text-muted mt-2 mb-0">
                        {{ $review['review'] }}
                    </p>
                </div>
            @empty
                <p class="text-muted mb-0">No reviews found.</p>
            @endforelse
        </div>
    </div>

    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Wallet Transactions</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($walletTransactions as $transaction)
                        <tr>
                            <td>
                                <span class="badge bg-success">
                                    {{ ucfirst($transaction['type']) }}
                                </span>
                            </td>
                            <td>Rs {{ number_format($transaction['amount']) }}</td>
                            <td>{{ $transaction['description'] }}</td>
                            <td>{{ $transaction['date'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                No wallet transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
