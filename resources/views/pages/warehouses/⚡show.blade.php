<?php

use Livewire\Component;
use App\Models\Warehouse;
new class extends Component {
    public int $id;

    public $warehouse;
    public $inventory;
    public $movements;

    public function mount($id): void
    {
        $this->id = (int) $id;
        $this->warehouse = Warehouse::find($this->id);

        $this->inventory = $this->warehouse->products;

        $this->movements = $this->warehouse->stockmovements;
    }

    public function getTotalStockProperty(): int
    {
        return collect($this->inventory)->sum('stock');
    }

    public function getTotalValueProperty(): float
    {
        return $this->inventory->sum(function ($stock) {
            return (float) $stock->quantity * (float) $stock->price_after_discount;
        });
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Warehouse Details</h3>
            <p class="text-muted mb-0">Inventory and stock movements</p>
        </div>

        <a href="{{ route('warehouses.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h6>Total Products</h6>
                    <h3 class="fw-bold text-primary">{{ count($inventory) }}</h3>
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
                    <h3 class="fw-bold text-info">{{ number_format($this->totalValue, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <h4 class="fw-bold">{{ $warehouse['name'] }}</h4>

            <hr>


            <p><strong>Manager:</strong> {{ $warehouse->manager?->user?->name ?? 'No Manager' }}</p>
            <p><strong>Phone:</strong> {{ $warehouse['phone'] }}</p>

            <p>
                <strong>Status:</strong>
                @if ($warehouse['status'])
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </p>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Current Inventory</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Supplier</th>
                        <th>SKU</th>
                        <th>Stock</th>
                        <th>Value</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($inventory as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item->supplier->user->name }}</td>
                            <td>{{ $item['sku'] }}</td>
                            <td>{{ $item['stock'] }}</td>
                            <td>{{ number_format($item['price_after_discount'], 2) }}</td>
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
                        <th>Supplier</th>
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
                            <td>{{ $movement->product->supplier->user->name }}</td>
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
</div>
