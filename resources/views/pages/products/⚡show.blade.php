<?php

use Livewire\Component;
use App\Models\Product;
new class extends Component {
    public int $id;

    public array $product = [];

    public $reviews;

    public $salesHistory;

    public $purchaseHistory;

    public $stockMovements;

    public function mount($id): void
    {
        $this->id = (int) $id;
        $productData = Product::with(['reviews'])->find($this->id);

        $this->product = [
            'id' => $this->id,
            'name' => $productData->name,
            'sku' => $productData->sku,
            'category' => $productData->category->name,
            'brand' => $productData->brand->title,
            'supplier' => $productData->supplier->user->name,
            'warehouse' => $productData->warehouse->name,
            'purchase_price' => $productData->purchase_price,
            'selling_price' => $productData->selling_price,
            'stock' => $productData->quantity,
            'minimum_stock' => $productData->minimum_stock,
            'status' => $productData->status,
            'description' => $productData->description,
            'profitperunit' => $productData->selling_price - $productData->purchase_price,
            'image' => asset('storage/' . $productData->image),
        ];

        $this->reviews = [
            [
                'customer' => 'Ali Khan',
                'rating' => 5,
                'review' => 'Excellent product. Fast performance and premium quality.',
                'date' => '2026-06-18',
            ],
            [
                'customer' => 'Sara Ahmed',
                'rating' => 4,
                'review' => 'Good laptop, battery timing is impressive.',
                'date' => '2026-06-17',
            ],
        ];
        $this->reviews = $productData->reviews;

        $this->salesHistory = [
            [
                'order_no' => 'ORD-1001',
                'customer' => 'Ali Khan',
                'quantity' => 1,
                'price' => 1299,
                'total' => 1299,
                'date' => '2026-06-18',
            ],
            [
                'order_no' => 'ORD-1002',
                'customer' => 'Sara Ahmed',
                'quantity' => 2,
                'price' => 1299,
                'total' => 2598,
                'date' => '2026-06-17',
            ],
        ];

        $this->purchaseHistory = [
            [
                'purchase_no' => 'PUR-1001',
                'supplier' => 'Apple Store',
                'quantity' => 10,
                'price' => 1100,
                'total' => 11000,
                'date' => '2026-06-10',
            ],
            [
                'purchase_no' => 'PUR-1002',
                'supplier' => 'Tech Supplier',
                'quantity' => 15,
                'price' => 1080,
                'total' => 16200,
                'date' => '2026-06-05',
            ],
        ];

        $this->stockMovements = [
            [
                'date' => '2026-06-10',
                'type' => 'purchase',
                'quantity' => 10,
                'stock_before' => 0,
                'stock_after' => 10,
                'remarks' => 'Purchase received',
            ],
            [
                'date' => '2026-06-12',
                'type' => 'purchase',
                'quantity' => 15,
                'stock_before' => 10,
                'stock_after' => 25,
                'remarks' => 'Purchase received',
            ],
            [
                'date' => '2026-06-18',
                'type' => 'sale',
                'quantity' => 1,
                'stock_before' => 25,
                'stock_after' => 24,
                'remarks' => 'Order ORD-1001',
            ],
        ];
    }

    public function getAverageRatingProperty(): float
    {
        if (count($this->reviews) === 0) {
            return 0;
        }

        return round(collect($this->reviews)->avg('rating'), 1);
    }

    public function getTotalSoldProperty(): int
    {
        return collect($this->salesHistory)->sum('quantity');
    }

    public function getTotalPurchasedProperty(): int
    {
        return collect($this->purchaseHistory)->sum('quantity');
    }

    public function getTotalRevenueProperty(): float
    {
        return collect($this->salesHistory)->sum('total');
    }

    public function getTotalPurchaseCostProperty(): float
    {
        return collect($this->purchaseHistory)->sum('total');
    }
};

?>

<div>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Product Details
            </h3>

            <p class="text-muted mb-0">
                Product information, inventory, reviews, sales and purchase history
            </p>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>

    </div>

    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Sold</h6>
                    <h3 class="text-primary fw-bold">
                        {{ $this->totalSold }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Purchased</h6>
                    <h3 class="text-success fw-bold">
                        {{ $this->totalPurchased }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Revenue</h6>
                    <h3 class="text-info fw-bold">
                        ${{ number_format($this->totalRevenue, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Average Rating</h6>
                    <h3 class="text-warning fw-bold">
                        {{ $this->averageRating }} ★
                    </h3>
                </div>
            </div>
        </div>

    </div>

    @if ($product['stock'] <= $product['minimum_stock'])
        <div class="alert alert-warning rounded-4">
            <strong>Low Stock Alert:</strong>
            This product stock is low. Current stock is {{ $product['stock'] }} and minimum stock is
            {{ $product['minimum_stock'] }}.
        </div>
    @endif

    <div class="card border-0 shadow mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-4 text-center mb-3">

                    <img src="{{ $product['image'] }}" class="img-fluid rounded shadow-sm" alt="{{ $product['name'] }}">

                </div>

                <div class="col-md-8">

                    <h2 class="fw-bold">
                        {{ $product['name'] }}
                    </h2>

                    <hr>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <strong>Product ID:</strong><br>
                            #{{ $product['id'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>SKU:</strong><br>
                            {{ $product['sku'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Category:</strong><br>
                            {{ $product['category'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Brand:</strong><br>
                            {{ $product['brand'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Supplier:</strong><br>
                            {{ $product['supplier'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Warehouse:</strong><br>
                            {{ $product['warehouse'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Purchase Price:</strong><br>
                            ${{ number_format($product['purchase_price'], 2) }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Selling Price:</strong><br>
                            ${{ number_format($product['selling_price'], 2) }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Stock:</strong><br>
                            {{ $product['stock'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Minimum Stock:</strong><br>
                            {{ $product['minimum_stock'] }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Status:</strong><br>

                            @if ($product['status'])
                                <span class="badge bg-success">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Ratings:</strong><br>
                            {{ $this->averageRating }}/5
                            <span class="text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= round($this->averageRating))
                                        <i class="bi bi-star-fill"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </span>
                        </div>

                    </div>

                    <hr>

                    <h5>Description</h5>

                    <p class="text-muted">
                        {{ $product['description'] }}
                    </p>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0">
                Inventory Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">
                    <strong>Current Stock</strong>
                    <p>{{ $product['stock'] }}</p>
                </div>

                <div class="col-md-4">
                    <strong>Minimum Stock</strong>
                    <p>{{ $product['minimum_stock'] }}</p>
                </div>

                <div class="col-md-4">
                    <strong>Warehouse</strong>
                    <p>{{ $product['warehouse'] }}</p>
                </div>
                <div class="col-md-4">
                    <strong>Profit Per Unit</strong>
                    <p>{{ $product['profitperunit'] }}</p>
                </div>
            </div>

        </div>

    </div>

    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0">
                Customer Reviews
            </h5>
        </div>

        <div class="card-body">

            @forelse ($reviews as $review)

                <div class="border-bottom pb-3 mb-3">

                    <div class="d-flex justify-content-between flex-wrap gap-2">

                        <div>
                            <h6 class="fw-bold mb-1">
                                {{ $review->customer->user->name }}
                            </h6>

                            <small class="text-muted">
                                {{ $review->created_at?->format('d M Y') }}
                            </small>
                        </div>

                        <div class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>

                    </div>

                    <p class="text-muted mt-2 mb-0">
                        {{ $review->review }}
                    </p>

                </div>

            @empty

                <p class="text-muted mb-0">
                    No reviews found.
                </p>

            @endforelse

        </div>

    </div>

    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0">
                Sales History
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Order No</th>
                            <th>Customer</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($salesHistory as $sale)
                            <tr>
                                <td>{{ $sale['order_no'] }}</td>
                                <td>{{ $sale['customer'] }}</td>
                                <td>{{ $sale['quantity'] }}</td>
                                <td>${{ number_format($sale['price'], 2) }}</td>
                                <td>${{ number_format($sale['total'], 2) }}</td>
                                <td>{{ $sale['date'] }}</td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No sales history found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0">
                Purchase History
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Purchase No</th>
                            <th>Supplier</th>
                            <th>Qty</th>
                            <th>Purchase Price</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($purchaseHistory as $purchase)
                            <tr>
                                <td>{{ $purchase['purchase_no'] }}</td>
                                <td>{{ $purchase['supplier'] }}</td>
                                <td>{{ $purchase['quantity'] }}</td>
                                <td>${{ number_format($purchase['price'], 2) }}</td>
                                <td>${{ number_format($purchase['total'], 2) }}</td>
                                <td>{{ $purchase['date'] }}</td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No purchase history found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow mb-4">

        <div class="card-header bg-light">
            <h5 class="mb-0">
                Stock Movements
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Before</th>
                            <th>After</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($stockMovements as $movement)
                            <tr>
                                <td>{{ $movement['date'] }}</td>

                                <td>
                                    @if ($movement['type'] === 'purchase')
                                        <span class="badge bg-success">
                                            Purchase / IN
                                        </span>
                                    @elseif ($movement['type'] === 'sale')
                                        <span class="badge bg-danger">
                                            Sale / OUT
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($movement['type']) }}
                                        </span>
                                    @endif
                                </td>

                                <td>{{ $movement['quantity'] }}</td>
                                <td>{{ $movement['stock_before'] }}</td>
                                <td>{{ $movement['stock_after'] }}</td>
                                <td>{{ $movement['remarks'] }}</td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No stock movements found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow">

        <div class="card-body">

            <div class="d-flex gap-2 flex-wrap">

                <a href="{{ route('products.edit', $product['id']) }}" class="btn btn-primary rounded-pill">
                    Edit Product
                </a>

                <a href="#" class="btn btn-success rounded-pill">
                    Add Stock
                </a>

                <a href="{{ route('purchases.create') }}" class="btn btn-info rounded-pill text-white">
                    Create Purchase
                </a>

                <a href="#" class="btn btn-warning rounded-pill">
                    View Sales
                </a>

            </div>

        </div>

    </div>

</div>
