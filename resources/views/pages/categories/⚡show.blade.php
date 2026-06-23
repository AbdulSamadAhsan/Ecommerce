<?php

use Livewire\Component;
use App\Models\Category;

new class extends Component {
    public int $id;

    public array $category = [];

    public array $products = [];

    public array $brands = [];

    public function mount($id): void
    {
        $this->id = (int) $id;

        $categoryData = Category::withCount('products')
            ->with(['products.brand', 'products.supplier'])
            ->findOrFail($this->id);

        $this->category = [
            'id' => $categoryData->id,
            'name' => $categoryData->name,
            'description' => $categoryData->description ?? 'No description available.',
            'status' => (bool) $categoryData->status,
            'products_count' => $categoryData->products_count,
            'created_at' => $categoryData->created_at?->format('d M Y'),
        ];

        $this->products = $categoryData->products
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'brand' => $product->brand?->title ?? 'No Brand',
                    'supplier' => $product->supplier?->name ?? 'No Supplier',
                    'price' => $product->selling_price ?? ($product->price ?? 0),
                    'quantity' => $product->quantity ?? 0,
                    'status' => (bool) $product->status,
                ];
            })
            ->values()
            ->toArray();

        $this->brands = $categoryData->products
            ->filter(fn($product) => $product->brand)
            ->groupBy('brand_id')
            ->map(function ($items) {
                $brand = $items->first()->brand;

                return [
                    'id' => $brand->id,
                    'title' => $brand->title,
                    'products_count' => $items->count(),
                    'status' => (bool) $brand->status,
                ];
            })
            ->values()
            ->toArray();
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Category Details</h3>
            <p class="text-muted mb-0">Category information, brands and products</p>
        </div>

        <a href="{{ route('categories.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Products</h6>
                    <h3 class="fw-bold text-primary">
                        {{ $category['products_count'] }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Brands</h6>
                    <h3 class="fw-bold text-success">
                        {{ count($brands) }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <h4 class="fw-bold">{{ $category['name'] }}</h4>

            <hr>

            <p>
                <strong>Description:</strong>
                {{ $category['description'] }}
            </p>

            <p>
                <strong>Created:</strong>
                {{ $category['created_at'] }}
            </p>

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

    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Brands in this Category</h5>
        </div>

        <div class="card-body">
            @forelse ($brands as $brand)
                <span class="badge bg-primary rounded-pill me-2 mb-2 p-2">
                    {{ $brand['title'] }} - {{ $brand['products_count'] }} Products
                </span>
            @empty
                <p class="text-muted mb-0">No brands found in this category.</p>
            @endforelse
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

                        <th>Brand</th>



                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>#{{ $product['id'] }}</td>

                            <td>{{ $product['name'] }}</td>



                            <td>{{ $product['brand'] }}</td>







                            <td>
                                <a href="{{ route('products.show', $product['id']) }}"
                                    class="btn btn-small btn-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-danger fw-bold py-4">
                                No Product In {{ $category['name'] }} Category
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
