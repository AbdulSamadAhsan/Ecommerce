<?php

use Livewire\Component;
use App\Models\Supplier;
new class extends Component {
    public int $id;

    public array $supplier = [];
    public $purchases;
    public $payments;
    public $products;
    public $movements;
    public function mount($id): void
    {
        $this->id = (int) $id;
        $supplier_data = Supplier::find($this->id);
        $this->supplier = [
            'id' => $this->id,
            'name' => $supplier_data->user->name,
            'email' => $supplier_data->user->email,
            'phone' => $supplier_data->phone,
            'address' => $supplier_data->address,
            'status' => 1,
        ];

        $this->movements = $supplier_data->stockmovements;
        $this->purchases = $supplier_data->purchases;
        $this->payments = $supplier_data->payments;

        $this->products = $supplier_data->products;
    }
    public function getTotalPurchasesProperty(): float
    {
        return collect($this->purchases)->sum('total_amount');
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
                    <h3 class="fw-bold text-primary">{{ number_format($this->totalPurchases, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Paid</h6>
                    <h3 class="fw-bold text-success">{{ number_format($this->totalPaid, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Balance</h6>
                    <h3 class="fw-bold text-danger">{{ number_format($this->balance, 2) }}</h3>
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
                            <td>{{ number_format($payment['amount'], 2) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment['payment_method'])) }}</td>
                            <td>{{ date('d-F-Y', strtotime($payment['created_at'])) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>




    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Stock Movements</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Qty</th>
                        <th>Before</th>
                        <th>After</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($movements as $movement)
                        @php
                            $shipmentStatus = data_get($movement, 'type', 'pending');

                            $statusBadge = match ($shipmentStatus) {
                                'purchase' => 'bg-success',
                                'sale' => 'bg-danger',
                                'purchase_return' => 'bg-warning',
                                'shipped' => 'bg-warning',
                                default => 'bg-primary',
                            };
                        @endphp
                        <tr>
                            <td>{{ date('d-F-Y', strtotime($movement['created_at'])) }}</td>
                            <td>{{ $movement->product->name }}</td>
                            <td>
                                <span class="badge {{ $statusBadge }}">
                                    {{ ucwords(str_replace('_', ' ', $movement['type'])) }}
                                </span>
                            </td>
                            <td>{{ $movement['quantity'] }}</td>
                            <td>{{ $movement['stock_before'] }}</td>
                            <td>{{ $movement['stock_after'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Purchase History</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Purchase No</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th> No Of Item </th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->purchase_no }}</td>
                            <td>{{ number_format($purchase['total_amount'], 2) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $purchase['payment_status'])) }}</td>
                            <td>
                                {{ count($purchase->items) }}
                            </td>
                            <td>{{ date('d-F-Y', strtotime($purchase['purchase_date'])) }}</td>

                            <td>
                                <a href="{{ route('purchases.show', $purchase['id']) }}"
                                    class="btn btn-sm btn-info rounded-pill text-white">
                                    View
                                </a>
                            </td>




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
                            <td>Rs {{ number_format($product['purchase_price'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
