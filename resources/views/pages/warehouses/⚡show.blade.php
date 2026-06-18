<?php

use Livewire\Component;

new class extends Component {
    public int $id;

    public array $warehouse = [];
    public array $inventory = [];
    public array $movements = [];

    public function mount($id): void
    {
        $this->id = (int) $id;

        $this->warehouse = [
            'id' => $this->id,
            'name' => 'Main Warehouse',
            'location' => 'Karachi',
            'manager' => 'Ahmed Raza',
            'phone' => '03001234567',
            'status' => 1,
        ];

        $this->inventory = [['product' => 'MacBook Pro M3', 'sku' => 'MBP-M3', 'stock' => 25, 'value' => 27500], ['product' => 'Wireless Mouse', 'sku' => 'WM-001', 'stock' => 90, 'value' => 4500]];

        $this->movements = [['date' => '2026-06-18', 'product' => 'MacBook Pro M3', 'type' => 'IN', 'qty' => 10], ['date' => '2026-06-19', 'product' => 'Wireless Mouse', 'type' => 'OUT', 'qty' => 5]];
    }

    public function getTotalStockProperty(): int
    {
        return collect($this->inventory)->sum('stock');
    }

    public function getTotalValueProperty(): float
    {
        return collect($this->inventory)->sum('value');
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
                    <h3 class="fw-bold text-info">${{ number_format($this->totalValue, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <h4 class="fw-bold">{{ $warehouse['name'] }}</h4>

            <hr>

            <p><strong>Location:</strong> {{ $warehouse['location'] }}</p>
            <p><strong>Manager:</strong> {{ $warehouse['manager'] }}</p>
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
                        <th>SKU</th>
                        <th>Stock</th>
                        <th>Value</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($inventory as $item)
                        <tr>
                            <td>{{ $item['product'] }}</td>
                            <td>{{ $item['sku'] }}</td>
                            <td>{{ $item['stock'] }}</td>
                            <td>${{ number_format($item['value'], 2) }}</td>
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
                    </tr>
                </thead>

                <tbody>
                    @foreach ($movements as $movement)
                        <tr>
                            <td>{{ $movement['date'] }}</td>
                            <td>{{ $movement['product'] }}</td>
                            <td>
                                <span class="badge {{ $movement['type'] === 'IN' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $movement['type'] }}
                                </span>
                            </td>
                            <td>{{ $movement['qty'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
