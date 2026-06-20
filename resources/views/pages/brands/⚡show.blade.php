<?php

use Livewire\Component;
use App\Models\Brand;

new class extends Component {
    public int $id;

    public array $brand = [];

    public array $products = [];

    public array $brandCategories = [];

    public $branddata;

    public function mount($id): void
    {
        $this->id = (int) $id;

        $this->branddata = Brand::with(['products.category'])->findOrFail($this->id);

        $this->brand = [
            'id' => $this->branddata->id,
            'title' => $this->branddata->title,
            'logo' => $this->branddata->logo ? asset('storage/' . $this->branddata->logo) : asset('images/no-image.png'),
            'description' => 'Premium electronics brand.',
            'status' => (bool) $this->branddata->status,
        ];

        $this->products = $this->branddata->products
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'selling_price' => $product->selling_price,
                    'quantity' => (int) $product->quantity,
                    'status' => (bool) $product->status,
                ];
            })
            ->values()
            ->toArray();

        $this->brandCategories = $this->branddata->products
            ->filter(fn($product) => $product->category)
            ->groupBy('category_id')
            ->map(function ($items) {
                $category = $items->first()->category;

                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'products_count' => $items->count(),
                    'total_stock' => $items->sum('quantity'),
                    'status' => (bool) $category->status,
                ];
            })
            ->values()
            ->toArray();
    }

    public function getTotalStockProperty(): int
    {
        return collect($this->products)->sum('quantity');
    }

    public function getTotalStockValueProperty(): float
    {
        return collect($this->products)->sum('selling_price');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Brand Details</h3>
            <p class="text-muted mb-0">Brand products and categories</p>
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
                    <h6>Total Stock</h6>
                    <h3 class="fw-bold text-success">{{ $this->totalStock }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Stock Value</h6>
                    <h3 class="fw-bold text-info">
                        Rs {{ number_format($this->totalStockValue, 2) }}
                    </h3>
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
                        <th>SKU</th>

                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['sku'] ?? '-' }}</td>
                            <td><a class="btn btn-primary" href ="{{ route('products.show', $product['id']) }}">View
                                    Details</a>



                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No products found for this brand.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Brand Categories</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>No Of Products</th>

                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($brandCategories as $category)
                        <tr>
                            <td>{{ $category['name'] }}</td>
                            <td>{{ $category['products_count'] }}</td>

                            <td>
                                <span class="badge {{ $category['status'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $category['status'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No categories found for this brand.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
