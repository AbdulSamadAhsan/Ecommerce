<?php

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component {
    public $suppliers = [], $products = [], $purchases = [];
    public $supplier_id = '', $purchase_date = '', $reference_no = '', $status = 'pending', $notes = '';
    public $items = [];
    public $discount = 0, $tax = 0, $search = '';

    public function mount(): void
    {
        $this->purchase_date = now()->toDateString();
        $this->resetItems();
        $this->loadData();
    }

    public function loadData(): void
    {
        $this->suppliers = Supplier::orderBy('company_name')->get();
        $this->products = Product::orderBy('name')->get();
        $query = Purchase::with(['supplier', 'items.product'])->latest();
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('reference_no', 'like', '%' . $this->search . '%')
                  ->orWhere('id', $this->search);
            });
        }
        $this->purchases = $query->take(50)->get();
    }

    public function updatedSearch(): void
    {
        $this->loadData();
    }

    public function resetItems(): void
    {
        $this->items = [['product_id' => '', 'quantity' => 1, 'price' => 0]];
    }

    public function addItem(): void
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1, 'price' => 0];
    }

    public function removeItem($index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updatedItems($value, $key): void
    {
        if (str_ends_with($key, '.product_id')) {
            $index = explode('.', $key)[0];
            $product = Product::find($value);
            if ($product) {
                $this->items[$index]['price'] = $product->purchase_price ?? 0;
            }
        }
    }

    public function getSubTotalProperty(): float
    {
        return collect($this->items)->sum(fn ($item) => (float) ($item['quantity'] ?? 0) * (float) ($item['price'] ?? 0));
    }

    public function getGrandTotalProperty(): float
    {
        return max(0, $this->subTotal + (float) $this->tax - (float) $this->discount);
    }

    public function save(): void
    {
        $this->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            $purchaseData = $this->onlyExisting('purchases', [
                'supplier_id' => $this->supplier_id,
                'purchase_date' => $this->purchase_date,
                'date' => $this->purchase_date,
                'reference_no' => $this->reference_no ?: 'PUR-' . now()->format('YmdHis'),
                'subtotal' => $this->subTotal,
                'sub_total' => $this->subTotal,
                'discount' => $this->discount ?: 0,
                'tax' => $this->tax ?: 0,
                'total' => $this->grandTotal,
                'grand_total' => $this->grandTotal,
                'total_amount' => $this->grandTotal,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);

            $purchase = Purchase::create($purchaseData ?: ['supplier_id' => $this->supplier_id]);

            foreach ($this->items as $row) {
                $quantity = (float) $row['quantity'];
                $price = (float) $row['price'];
                $itemData = $this->onlyExisting('purchase_items', [
                    'purchase_id' => $purchase->id,
                    'product_id' => $row['product_id'],
                    'quantity' => $quantity,
                    'qty' => $quantity,
                    'price' => $price,
                    'unit_price' => $price,
                    'purchase_price' => $price,
                    'total' => $quantity * $price,
                    'total_price' => $quantity * $price,
                ]);
                PurchaseItem::create($itemData);
                Product::whereKey($row['product_id'])->increment('quantity', $quantity);
            }
        });

        session()->flash('success', 'Purchase created successfully.');
        $this->resetForm();
        $this->loadData();
    }

    public function delete($id): void
    {
        DB::transaction(function () use ($id) {
            $purchase = Purchase::with('items')->findOrFail($id);
            foreach ($purchase->items as $item) {
                $qty = $item->quantity ?? $item->qty ?? 0;
                if ($item->product_id && $qty > 0) Product::whereKey($item->product_id)->decrement('quantity', $qty);
            }
            $purchase->items()->delete();
            $purchase->delete();
        });
        session()->flash('success', 'Purchase deleted successfully.');
        $this->loadData();
    }

    public function resetForm(): void
    {
        $this->supplier_id = '';
        $this->purchase_date = now()->toDateString();
        $this->reference_no = '';
        $this->status = 'pending';
        $this->notes = '';
        $this->discount = 0;
        $this->tax = 0;
        $this->resetItems();
        $this->resetValidation();
    }

    private function onlyExisting(string $table, array $data): array
    {
        return collect($data)->filter(fn ($v, $k) => Schema::hasColumn($table, $k))->all();
    }
};
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h3 class="mb-0">Purchases</h3><small class="text-muted">Create purchase and add stock</small></div>
        <button wire:click="resetForm" class="btn btn-secondary">Reset</button>
    </div>

    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Add Purchase</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><label class="form-label">Supplier</label><select wire:model="supplier_id" class="form-select"><option value="">Select Supplier</option>@foreach($suppliers as $supplier)<option value="{{ $supplier->id }}">{{ $supplier->company_name ?? $supplier->name }}</option>@endforeach</select>@error('supplier_id')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="col-md-3"><label class="form-label">Date</label><input type="date" wire:model="purchase_date" class="form-control">@error('purchase_date')<small class="text-danger">{{ $message }}</small>@enderror</div>
                <div class="col-md-3"><label class="form-label">Reference No</label><input type="text" wire:model="reference_no" class="form-control" placeholder="Auto if empty"></div>
                <div class="col-md-3"><label class="form-label">Status</label><select wire:model="status" class="form-select"><option value="pending">Pending</option><option value="received">Received</option><option value="cancelled">Cancelled</option></select></div>
            </div>

            <hr>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead><tr><th>Product</th><th width="140">Qty</th><th width="160">Price</th><th width="160">Total</th><th width="80">Action</th></tr></thead>
                    <tbody>
                    @foreach($items as $index => $item)
                        <tr>
                            <td><select wire:model.live="items.{{ $index }}.product_id" class="form-select"><option value="">Select Product</option>@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->quantity }})</option>@endforeach</select>@error('items.'.$index.'.product_id')<small class="text-danger">{{ $message }}</small>@enderror</td>
                            <td><input type="number" min="1" wire:model.live="items.{{ $index }}.quantity" class="form-control">@error('items.'.$index.'.quantity')<small class="text-danger">{{ $message }}</small>@enderror</td>
                            <td><input type="number" step="0.01" min="0" wire:model.live="items.{{ $index }}.price" class="form-control">@error('items.'.$index.'.price')<small class="text-danger">{{ $message }}</small>@enderror</td>
                            <td>{{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 2) }}</td>
                            <td><button type="button" wire:click="removeItem({{ $index }})" class="btn btn-sm btn-danger" @disabled(count($items) === 1)>X</button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <button type="button" wire:click="addItem" class="btn btn-outline-primary btn-sm">+ Add Item</button>

            <div class="row g-3 mt-2">
                <div class="col-md-4"><label class="form-label">Discount</label><input type="number" step="0.01" wire:model.live="discount" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Tax</label><input type="number" step="0.01" wire:model.live="tax" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Notes</label><input type="text" wire:model="notes" class="form-control"></div>
            </div>
            <div class="text-end mt-3"><h5>Subtotal: {{ number_format($this->subTotal, 2) }}</h5><h4>Grand Total: {{ number_format($this->grandTotal, 2) }}</h4><button wire:click="save" class="btn btn-primary">Save Purchase</button></div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between"><span class="fw-bold">Purchase List</span><input type="text" wire:model.live.debounce.500ms="search" class="form-control form-control-sm w-auto" placeholder="Search"></div>
        <div class="table-responsive"><table class="table table-striped mb-0"><thead><tr><th>#</th><th>Supplier</th><th>Date</th><th>Total</th><th>Status</th><th>Action</th></tr></thead><tbody>@forelse($purchases as $purchase)<tr><td>{{ $purchase->id }}</td><td>{{ $purchase->supplier->company_name ?? $purchase->supplier->name ?? '-' }}</td><td>{{ $purchase->purchase_date ?? $purchase->date ?? $purchase->created_at?->format('Y-m-d') }}</td><td>{{ number_format($purchase->grand_total ?? $purchase->total_amount ?? $purchase->total ?? 0, 2) }}</td><td><span class="badge bg-info">{{ $purchase->status ?? '-' }}</span></td><td><a href="{{ route('purchases.items', $purchase->id) }}" class="btn btn-sm btn-info">Items</a> <button wire:click="delete({{ $purchase->id }})" wire:confirm="Delete this purchase?" class="btn btn-sm btn-danger">Delete</button></td></tr>@empty<tr><td colspan="6" class="text-center text-muted">No purchases found</td></tr>@endforelse</tbody></table></div>
    </div>
</div>
