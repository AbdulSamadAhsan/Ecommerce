<?php

use Livewire\Component;

new class extends Component {
    public int $id;

    public array $customer = [];
    public array $orders = [];
    public array $walletTransactions = [];
    public array $supportTickets = [];
    public array $addresses = [];

    public function mount($id): void
    {
        $this->id = (int) $id;

        $this->customer = [
            'id' => $this->id,
            'name' => 'Ali Khan',
            'email' => 'ali@example.com',
            'phone' => '03001234567',
            'status' => 1,
            'joined_at' => '2026-06-01',
            'wallet_balance' => 8500,
            'reward_points' => 1200,
        ];

        $this->orders = [['order_no' => 'ORD-1001', 'total' => 185000, 'status' => 'delivered', 'payment' => 'paid', 'date' => '2026-06-18'], ['order_no' => 'ORD-1002', 'total' => 45000, 'status' => 'processing', 'payment' => 'pending', 'date' => '2026-06-17']];

        $this->walletTransactions = [['type' => 'deposit', 'amount' => 5000, 'date' => '2026-06-18'], ['type' => 'refund', 'amount' => 3500, 'date' => '2026-06-17']];

        $this->supportTickets = [['ticket_no' => 'TK-1001', 'subject' => 'Product damaged', 'status' => 'open', 'date' => '2026-06-18'], ['ticket_no' => 'TK-1002', 'subject' => 'Late delivery', 'status' => 'resolved', 'date' => '2026-06-15']];

        $this->addresses = [['city' => 'Karachi', 'address' => 'Gulshan-e-Iqbal, Karachi', 'default' => true], ['city' => 'Lahore', 'address' => 'Model Town, Lahore', 'default' => false]];
    }

    public function getTotalSpentProperty(): float
    {
        return collect($this->orders)->sum('total');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Customer Details</h3>
            <p class="text-muted mb-0">Customer profile, orders, wallet and support history</p>
        </div>

        <a href="{{ route('customers.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Orders</h6>
                    <h3 class="fw-bold text-primary">{{ count($orders) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Spent</h6>
                    <h3 class="fw-bold text-success">Rs {{ number_format($this->totalSpent) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Wallet</h6>
                    <h3 class="fw-bold text-info">Rs {{ number_format($customer['wallet_balance']) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Reward Points</h6>
                    <h3 class="fw-bold text-warning">{{ number_format($customer['reward_points']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <h4 class="fw-bold">{{ $customer['name'] }}</h4>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3"><strong>Email:</strong><br>{{ $customer['email'] }}</div>
                <div class="col-md-6 mb-3"><strong>Phone:</strong><br>{{ $customer['phone'] }}</div>
                <div class="col-md-6 mb-3"><strong>Joined:</strong><br>{{ $customer['joined_at'] }}</div>
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
            <h5 class="mb-0">Addresses</h5>
        </div>

        <div class="card-body">
            @foreach ($addresses as $address)
                <div class="border-bottom pb-3 mb-3">
                    <strong>{{ $address['city'] }}</strong>

                    @if ($address['default'])
                        <span class="badge bg-primary ms-2">Default</span>
                    @endif

                    <p class="text-muted mb-0">{{ $address['address'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Order History</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order['order_no'] }}</td>
                            <td>Rs {{ number_format($order['total']) }}</td>
                            <td><span class="badge bg-primary">{{ ucfirst($order['status']) }}</span></td>
                            <td>{{ ucfirst($order['payment']) }}</td>
                            <td>{{ $order['date'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Wallet Transactions</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($walletTransactions as $transaction)
                        <tr>
                            <td>{{ ucfirst($transaction['type']) }}</td>
                            <td>Rs {{ number_format($transaction['amount']) }}</td>
                            <td>{{ $transaction['date'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Support Tickets</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Ticket No</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($supportTickets as $ticket)
                        <tr>
                            <td>{{ $ticket['ticket_no'] }}</td>
                            <td>{{ $ticket['subject'] }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($ticket['status']) }}</span></td>
                            <td>{{ $ticket['date'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
