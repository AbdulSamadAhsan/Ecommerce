<?php

use Livewire\Component;

new class extends Component {
    public int $id;

    public array $brand = [];
    public array $products = [];
    public array $reviews = [];

    public function mount($id): void
    {
        $this->id = (int) $id;

        $this->brand = [
            'id' => $this->id,
            'title' => 'HP',
            'logo' => asset('asset/HP.png'),
            'description' => 'Premium electronics brand.',
            'status' => 1,
        ];

        $this->products = [['name' => 'MacBook Pro M3', 'sold' => 40, 'revenue' => 51960], ['name' => 'iPhone 15', 'sold' => 70, 'revenue' => 69930]];

        $this->reviews = [['customer' => 'Ali Khan', 'rating' => 5, 'review' => 'Excellent brand quality.'], ['customer' => 'Sara Ahmed', 'rating' => 4, 'review' => 'Premium but expensive.']];
    }

    public function getTotalSoldProperty(): int
    {
        return collect($this->products)->sum('sold');
    }

    public function getTotalRevenueProperty(): float
    {
        return collect($this->products)->sum('revenue');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Brand Details</h3>
            <p class="text-muted mb-0">Brand products and reviews</p>
        </div>

        <a href="{{ route('brands.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Products</h6>
                    <h3 class="fw-bold text-primary">{{ count($products) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Sold</h6>
                    <h3 class="fw-bold text-success">{{ $this->totalSold }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Revenue</h6>
                    <h3 class="fw-bold text-info">${{ number_format($this->totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $brand['logo'] }}" width="90" class="rounded shadow-sm">

                <div>
                    <h3 class="fw-bold">{{ $brand['title'] }}</h3>
                    <p class="text-muted mb-1">{{ $brand['description'] }}</p>

                    @if ($brand['status'])
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
            <h5 class="mb-0">Brand Products</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Sold</th>
                        <th>Revenue</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['sold'] }}</td>
                            <td>${{ number_format($product['revenue'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
