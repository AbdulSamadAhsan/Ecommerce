<?php

use Livewire\Component;
use App\Models\Product;
use App\Models\StockMovement;

new class extends Component {
    public string $search = '';
    public $products;
    public $stockMovements;

    public function mount(): void
    {
        $this->loadStocks();
    }

    public function updatedSearch(): void
    {
        $this->loadStocks();
    }

    public function loadStocks(): void
    {
        $this->products = Product::with(['category', 'supplier', 'warehouse'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();

        $this->stockMovements = StockMovement::with(['product', 'warehouse', 'supplier'])
            ->latest()
            ->limit(20)
            ->get();
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Stocks</h3>
            <p class="text-muted mb-0">Manage product stock and movements</p>
        </div>
    </div>

    <div class="dashboard-card mb-4">
        <h5 class="fw-bold mb-3">Current Stock</h5>

        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search product or SKU...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Supplier</th>
                        <th>Warehouse</th>
                        <th>Quantity</th>
                        <th>Minimum Stock</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>#{{ $product['id'] }}</td>
                            <td>{{ $product['name'] }}</td>
                            <td>{{ $product['sku'] }}</td>
                            <td>{{ $product['category']['name'] ?? 'N/A' }}</td>
                            <td>{{ $product->supplier->user->name ?? 'N/A' }}</td>
                            <td>{{ $product['warehouse']['name'] ?? 'N/A' }}</td>
                            <td>{{ $product['quantity'] }}</td>
                            <td>{{ $product['minimum_stock'] }}</td>
                            <td>
                                @if ($product['quantity'] <= $product['minimum_stock'])
                                    <span class="badge bg-danger">Low Stock</span>
                                @else
                                    <span class="badge bg-success">Available</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No stock found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-card">
        <h5 class="fw-bold mb-3">Recent Stock Movements</h5>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Warehouse</th>
                        <th>Supplier</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Before</th>
                        <th>After</th>
                        <th>Reference</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($stockMovements as $movement)
                        @php
                            $typeColor = match ($movement['type']) {
                                'purchase' => 'bg-success',
                                'sale' => 'bg-primary',
                                'purchase_return' => 'bg-warning text-dark',
                                'return' => 'bg-info',
                                'adjustment' => 'bg-secondary',
                                'damage' => 'bg-danger',
                                default => 'bg-dark',
                            };
                        @endphp


                        <tr>
                            <td>#{{ $movement['id'] }}</td>
                            <td>{{ $movement['product']['name'] ?? 'N/A' }}</td>
                            <td>{{ $movement['warehouse']['name'] ?? 'N/A' }}</td>
                            <td>{{ $movement->supplier->user->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $typeColor }}">
                                    {{ ucwords(str_replace('_', ' ', $movement['type'])) }}
                                </span>
                            </td>
                            <td>{{ $movement['quantity'] }}</td>
                            <td>{{ $movement['stock_before'] }}</td>
                            <td>{{ $movement['stock_after'] }}</td>
                            <td>{{ $movement['reference_no'] ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No stock movements found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
