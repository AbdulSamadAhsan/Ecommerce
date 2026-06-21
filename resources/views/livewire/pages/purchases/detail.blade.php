<?php

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component {
    public $purchase, $products = [], $items = [];
    public $product_id = '', $quantity = 1, $price = 0;

    public function mount($id): void
    {
        $this->purchase = Purchase::with(['supplier', 'items.product'])->findOrFail($id);
        $this->products = Product::orderBy('name')->get();
        $this->loadItems();
    }

    public function loadItems(): void
    {
        $this->purchase->refresh();
        $this->purchase->load(['supplier', 'items.product']);
        $this->items = $this->purchase->items;
    }

    public function updatedProductId($value): void
    {
        $product = Product::find($value);
        $this->price = $product->purchase_price ?? 0;
    }

    public function addItem(): void
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            $qty = (float) $this->quantity;
            $rate = (float) $this->price;
            PurchaseItem::create($this->onlyExisting('purchase_items', [
                'purchase_id' => $this->purchase->id,
                'product_id' => $this->product_id,
                'quantity' => $qty,
                'qty' => $qty,
                'price' => $rate,
                'unit_price' => $rate,
                'purchase_price' => $rate,
                'total' => $qty * $rate,
                'total_price' => $qty * $rate,
            ]));
            Product::whereKey($this->product_id)->increment('quantity', $qty);
            $this->syncPurchaseTotal();
        });

        $this->reset(['product_id', 'price']);
        $this->quantity = 1;
        session()->flash('success', 'Purchase item added successfully.');
        $this->loadItems();
    }

    public function deleteItem($id): void
    {
        DB::transaction(function () use ($id) {
            $item = PurchaseItem::where('purchase_id', $this->purchase->id)->findOrFail($id);
            $qty = $item->quantity ?? $item->qty ?? 0;
            if ($item->product_id && $qty > 0) Product::whereKey($item->product_id)->decrement('quantity', $qty);
            $item->delete();
            $this->syncPurchaseTotal();
        });
        session()->flash('success', 'Purchase item deleted successfully.');
        $this->loadItems();
    }

    private function syncPurchaseTotal(): void
    {
        $this->purchase->load('items');
        $subtotal = $this->purchase->items->sum(fn ($item) => (float) ($item->total ?? $item->total_price ?? (($item->quantity ?? $item->qty ?? 0) * ($item->price ?? $item->unit_price ?? $item->purchase_price ?? 0))));
        $discount = $this->purchase->discount ?? 0;
        $tax = $this->purchase->tax ?? 0;
        $total = max(0, $subtotal + $tax - $discount);
        $data = $this->onlyExisting('purchases', [
            'subtotal' => $subtotal,
            'sub_total' => $subtotal,
            'total' => $total,
            'grand_total' => $total,
            'total_amount' => $total,
        ]);
        if ($data) $this->purchase->update($data);
    }

    private function onlyExisting(string $table, array $data): array
    {
        return collect($data)->filter(fn ($v, $k) => Schema::hasColumn($table, $k))->all();
    }
};
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h3 class="mb-0">Purchase Items</h3><small class="text-muted">Purchase #{{ $purchase->id }} - {{ $purchase->supplier->company_name ?? $purchase->supplier->name ?? '-' }}</small></div>
        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Add Purchase Item</div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-5"><label class="form-label">Product</label><select wire:model.live="product_id" class="form-select"><option value="">Select Product</option>@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->quantity }})</option>@endforeach</select>@error('product_id')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="col-md-2"><label class="form-label">Quantity</label><input type="number" min="1" wire:model="quantity" class="form-control">@error('quantity')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="col-md-2"><label class="form-label">Price</label><input type="number" step="0.01" min="0" wire:model="price" class="form-control">@error('price')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="col-md-2"><label class="form-label">Total</label><input type="text" class="form-control" value="{{ number_format((float)$quantity * (float)$price, 2) }}" readonly></div>
                <div class="col-md-1"><button wire:click="addItem" class="btn btn-primary w-100">Add</button></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-bold">Item List</div>
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead><tr><th>#</th><th>Product</th><th>Qty</th><th>Price</th><th>Total</th><th>Action</th></tr></thead>
                <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->product->name ?? '-' }}</td>
                        <td>{{ $item->quantity ?? $item->qty ?? 0 }}</td>
                        <td>{{ number_format($item->price ?? $item->unit_price ?? $item->purchase_price ?? 0, 2) }}</td>
                        <td>{{ number_format($item->total ?? $item->total_price ?? (($item->quantity ?? $item->qty ?? 0) * ($item->price ?? $item->unit_price ?? $item->purchase_price ?? 0)), 2) }}</td>
                        <td><button wire:click="deleteItem({{ $item->id }})" wire:confirm="Delete this item?" class="btn btn-sm btn-danger">Delete</button></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">No purchase items found</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
