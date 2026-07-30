<?php

use Livewire\Component;
use App\Models\Customer;
use App\Models\Sale;
new class extends Component {
    public int $id;

    public $customer;
    public $orders;
    public $reviews;
    public $walletTransactions;
    public $lastOrder;
    public function mount($id): void
    {
        $this->id = (int) $id;

        $this->customer = Customer::findOrFail($id);
        $this->orders = $this->customer->sales;

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

        $this->lastOrder = $this->customer->sales()->with('orderNumber.shipment')->latest()->first();
        //  dd($this->lastOrder->orderNumber->created_at->format('d M Y'), $this->lastOrder->orderNumber->id, $this->lastOrder->orderNumber->created_at->diffForHumans());
        $this->walletTransactions = $this->customer->wallet->transactions;
    }
    public function getTotalSpentProperty(): float
    {
        return Sale::where('customer_id', $this->customer->id)
            ->whereHas('orderNumber.shipment', function ($query) {
                $query->where('status', 'delivered');
            })
            ->sum('total_amount');
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
                    <h3 class="fw-bold text-info">Rs {{ number_format($customer->wallet->balance) }}</h3>
                </div>
            </div>
        </div>


    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <h4 class="fw-bold">{{ $customer->user->name }}</h4>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Email:</strong><br>
                    {{ $customer->user->email }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Phone:</strong><br>
                    {{ $customer['phone'] }}
                </div>

                <div class="col-md-6 mb-3">
                    <strong>City:</strong><br>
                    @if (!empty($customer->addresses->toArray()) && count(array_filter($customer->addresses->toArray())))
                        @foreach ($customer->addresses as $address)
                            @if (!empty($address))
                                {{ $address->city }}<br>
                            @endif
                        @endforeach
                    @else
                        No address provided
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Joined:</strong><br>
                    {{ date('d-F-Y', strtotime($customer['created_at'])) }}
                    {{ $this->customer->created_at->diffForHumans() }}

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
                        @php
                            $shipmentStatus = data_get($order, 'orderNumber.shipment.status', 'pending');

                            $statusBadge = match ($shipmentStatus) {
                                'delivered' => 'bg-success',
                                'cancelled' => 'bg-danger',
                                'out_for_delivery' => 'bg-secondary',
                                'shipped' => 'bg-warning',
                                default => 'bg-primary',
                            };
                        @endphp


                        <tr>
                            <td>{{ $order->orderNumber->id }}</td>
                            <td>Rs {{ number_format($order->total_amount) }}</td>
                            <td>
                                <span class="badge {{ $statusBadge }}">
                                    {{ ucwords(str_replace('_', ' ', $order->orderNumber->shipment->status)) }}
                                </span>
                            </td>
                            <td>{{ $order->orderNumber->order_date }}</td>
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
                    @forelse ($walletTransactions  as $transaction)
                        <tr>
                            <td>
                                <span
                                    class="badge {{ $transaction['type'] === 'debit' ? 'bg-danger' : 'bg-success' }}">
                                    {{ ucfirst($transaction['type']) }}
                                </span>
                            </td>
                            <td>Rs {{ number_format($transaction['amount']) }}</td>
                            <td>{{ $transaction['description'] }}</td>
                            <td>{{ date('d-F-Y', strtotime($transaction['created_at'])) }}</td>
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
