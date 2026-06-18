<?php

use Livewire\Component;

new class extends Component {
    public int $id;

    public array $supplier = [];
    public array $purchases = [];
    public array $payments = [];
    public array $products = [];

    public function mount($id): void
    {
        $this->id = (int) $id;

        $this->supplier = [
            'id' => $this->id,
            'name' => 'Apple Store',
            'email' => 'supplier@example.com',
            'phone' => '03001234567',
            'address' => 'Karachi, Pakistan',
            'status' => 1,
        ];

        $this->purchases = [['purchase_no' => 'PUR-1001', 'amount' => 11000, 'status' => 'received', 'date' => '2026-06-10'], ['purchase_no' => 'PUR-1002', 'amount' => 16200, 'status' => 'pending', 'date' => '2026-06-12']];

        $this->payments = [['amount' => 10000, 'method' => 'bank_transfer', 'date' => '2026-06-11'], ['amount' => 5000, 'method' => 'cash', 'date' => '2026-06-13']];

        $this->products = [['name' => 'MacBook Pro M3', 'sku' => 'MBP-M3', 'price' => 1100], ['name' => 'iPhone 15', 'sku' => 'IPH-15', 'price' => 999]];
    }

    public function getTotalPurchasesProperty(): float
    {
        return collect($this->purchases)->sum('amount');
    }

    public function getTotalPaidProperty(): float
    {
        return collect($this->payments)->sum('amount');
    }

    public function getBalanceProperty(): float
    {
        return $this->totalPurchases - $this->totalPaid;
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Supplier Details</h3>
            <p class="text-muted mb-0">Purchases, payments and supplied products</p>
        </div>

        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Sales</h6>
                    <h3 class="fw-bold text-primary">${{ number_format($this->totalPurchases, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Paid</h6>
                    <h3 class="fw-bold text-success">${{ number_format($this->totalPaid, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Balance</h6>
                    <h3 class="fw-bold text-danger">${{ number_format($this->balance, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <h4 class="fw-bold">{{ $supplier['name'] }}</h4>

            <hr>

            <p><strong>Email:</strong> {{ $supplier['email'] }}</p>
            <p><strong>Phone:</strong> {{ $supplier['phone'] }}</p>
            <p><strong>Address:</strong> {{ $supplier['address'] }}</p>

            <p>
                <strong>Status:</strong>
                @if ($supplier['status'])
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </p>
        </div>
    </div>



    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Payment History</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($payments as $payment)
                        <tr>
                            <td>${{ number_format($payment['amount'], 2) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment['method'])) }}</td>
                            <td>{{ $payment['date'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Products Supplied</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Purchase Price</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['sku'] }}</td>
                            <td>${{ number_format($product['price'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
