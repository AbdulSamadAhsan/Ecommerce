<?php

use Livewire\Component;

new class extends Component {
    public int $id;

    public array $category = [];
    public array $products = [];

    public function mount($id): void
    {
        $this->id = (int) $id;

        $this->category = [
            'id' => $this->id,
            'name' => 'Laptops',
            'parent' => 'Electronics',
            'description' => 'Laptop products and accessories.',
            'status' => 1,
            'created_at' => '2026-06-18',
        ];

        $this->products = [['id' => 1, 'name' => 'MacBook Pro M3', 'sku' => 'MBP-M3', 'price' => 1299, 'stock' => 25], ['id' => 2, 'name' => 'Dell XPS 15', 'sku' => 'DXPS-15', 'price' => 1199, 'stock' => 15]];
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Category Details</h3>
            <p class="text-muted mb-0">Category information and products</p>
        </div>

        <a href="{{ route('categories') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Products</h6>
                    <h3 class="fw-bold text-primary">{{ count($products) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <h4 class="fw-bold">{{ $category['name'] }}</h4>

            <hr>


            <p><strong>Description:</strong> {{ $category['description'] }}</p>
            <p><strong>Created:</strong> {{ $category['created_at'] }}</p>

            <p>
                <strong>Status:</strong>
                @if ($category['status'])
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </p>
        </div>
    </div>

    <div class="card border-0 shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Products in Category</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>#{{ $product['id'] }}</td>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['sku'] }}</td>
                            <td>${{ number_format($product['price'], 2) }}</td>
                            <td>{{ $product['stock'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
