<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

new #[Layout('components.layouts.app')] class extends Component {
    public $purchase_id = null;
    public $supplier_id = '';
    public $purchase_date = '';
    public $invoice_no = '';
    public $paid_amount = 0;
    public $payment_status = 'unpaid';
    public $status = 'pending';
    public $notes = '';
    public $search = '';

    public $items = [
        [
            'product_id' => '',
            'quantity' => 1,
            'purchase_price' => 0,
            'subtotal' => 0,
        ],
    ];

    public function mount()
    {
        $this->purchase_date = now()->format('Y-m-d');
        $this->invoice_no = $this->generateInvoiceNo();
    }

    public function generateInvoiceNo()
    {
        $lastPurchase = Purchase::latest('id')->first();
        $nextId = $lastPurchase ? $lastPurchase->id + 1 : 1;

        return 'PUR-' . now()->format('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public function addItem()
    {
        $this->removeDuplicateRows();

        $this->items[] = [
            'product_id' => '',
            'quantity' => 1,
            'purchase_price' => 0,
            'subtotal' => 0,
        ];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }

        $this->calculateTotals();
    }

    public function updated($property)
    {
        if (str_contains($property, 'items.') && str_contains($property, '.product_id')) {
            $index = (int) explode('.', $property)[1];
            $productId = $this->items[$index]['product_id'] ?? null;

            if ($productId && $this->isDuplicateProduct($productId, $index)) {
                unset($this->items[$index]);
                $this->items = array_values($this->items);

                session()->flash('error', 'Duplicate product row removed.');
                $this->calculateTotals();
                return;
            }

            $product = Product::find($productId);

            $this->items[$index]['purchase_price'] = $product ? $product->purchase_price ?? ($product->selling_price ?? 0) : 0;
        }

        $this->removeDuplicateRows();
        $this->calculateTotals();
    }

    public function isDuplicateProduct($productId, $currentIndex)
    {
        foreach ($this->items as $index => $item) {
            if ($index !== $currentIndex && !empty($item['product_id']) && (int) $item['product_id'] === (int) $productId) {
                return true;
            }
        }

        return false;
    }

    public function removeDuplicateRows()
    {
        $seen = [];

        foreach ($this->items as $index => $item) {
            $productId = $item['product_id'] ?? null;

            if (!$productId) {
                continue;
            }

            if (in_array($productId, $seen)) {
                unset($this->items[$index]);
            } else {
                $seen[] = $productId;
            }
        }

        $this->items = array_values($this->items);
    }

    public function calculateTotals()
    {
        foreach ($this->items as $index => $item) {
            $qty = (float) ($item['quantity'] ?? 0);
            $price = (float) ($item['purchase_price'] ?? 0);

            $this->items[$index]['subtotal'] = $qty * $price;
        }
    }

    public function getTotalAmountProperty()
    {
        return collect($this->items)->sum('subtotal');
    }

    public function getDueAmountProperty()
    {
        return max($this->total_amount - (float) $this->paid_amount, 0);
    }

    public function rules()
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'invoice_no' => 'required|string|max:255',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:unpaid,partial,paid',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id|distinct',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
        ];
    }

    public function save()
    {
        $this->removeDuplicateRows();
        $this->calculateTotals();
        $this->validate();

        DB::transaction(function () {
            $purchase = Purchase::create([
                'supplier_id' => $this->supplier_id,
                'purchase_date' => $this->purchase_date,
                'invoice_no' => $this->invoice_no,
                'total_amount' => $this->total_amount,
                'paid_amount' => $this->paid_amount ?? 0,
                'due_amount' => $this->due_amount,
                'payment_status' => $this->payment_status,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);

            foreach ($this->items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $product = Product::where('id', $item['product_id'])->lockForUpdate()->firstOrFail();

                $product->quantity = $product->quantity + $item['quantity'];
                $product->save();
            }
        });

        session()->flash('success', 'Purchase created and product quantity increased successfully.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $purchase = Purchase::with('items')->findOrFail($id);

        $this->purchase_id = $purchase->id;
        $this->supplier_id = $purchase->supplier_id;
        $this->purchase_date = $purchase->purchase_date;
        $this->invoice_no = $purchase->invoice_no;
        $this->paid_amount = $purchase->paid_amount;
        $this->payment_status = $purchase->payment_status;
        $this->status = $purchase->status;
        $this->notes = $purchase->notes;

        $this->items = $purchase->items
            ->map(
                fn($item) => [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'purchase_price' => $item->purchase_price,
                    'subtotal' => $item->subtotal,
                ],
            )
            ->toArray();

        $this->removeDuplicateRows();
        $this->calculateTotals();
    }

    public function update()
    {
        $this->removeDuplicateRows();
        $this->calculateTotals();
        $this->validate();

        DB::transaction(function () {
            $purchase = Purchase::with('items')->findOrFail($this->purchase_id);

            foreach ($purchase->items as $oldItem) {
                $product = Product::where('id', $oldItem->product_id)->lockForUpdate()->firstOrFail();

                $product->quantity = max($product->quantity - $oldItem->quantity, 0);
                $product->save();
            }

            $purchase->items()->delete();

            $purchase->update([
                'supplier_id' => $this->supplier_id,
                'purchase_date' => $this->purchase_date,
                'invoice_no' => $this->invoice_no,
                'total_amount' => $this->total_amount,
                'paid_amount' => $this->paid_amount ?? 0,
                'due_amount' => $this->due_amount,
                'payment_status' => $this->payment_status,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);

            foreach ($this->items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $product = Product::where('id', $item['product_id'])->lockForUpdate()->firstOrFail();

                $product->quantity = $product->quantity + $item['quantity'];
                $product->save();
            }
        });

        session()->flash('success', 'Purchase updated and product quantity recalculated successfully.');
        $this->resetForm();
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $purchase = Purchase::with('items')->findOrFail($id);

            foreach ($purchase->items as $item) {
                $product = Product::where('id', $item->product_id)->lockForUpdate()->firstOrFail();

                $product->quantity = max($product->quantity - $item->quantity, 0);
                $product->save();
            }

            $purchase->items()->delete();
            $purchase->delete();
        });

        session()->flash('success', 'Purchase deleted and product quantity reduced successfully.');
    }

    public function resetForm()
    {
        $this->purchase_id = null;
        $this->supplier_id = '';
        $this->purchase_date = now()->format('Y-m-d');
        $this->invoice_no = $this->generateInvoiceNo();
        $this->paid_amount = 0;
        $this->payment_status = 'unpaid';
        $this->status = 'pending';
        $this->notes = '';

        $this->items = [
            [
                'product_id' => '',
                'quantity' => 1,
                'purchase_price' => 0,
                'subtotal' => 0,
            ],
        ];

        $this->resetValidation();
    }

    public function with()
    {
        return [
            'suppliers' => Supplier::with('user')->get()->sortBy('user.name'),
            'products' => Product::orderBy('name')->get(),
            'purchases' => Purchase::with(['supplier.user'])
                ->where('invoice_no', 'like', '%' . $this->search . '%')
                ->latest()
                ->get(),
        ];
    }
};
?>

<div class="container-fluid">
    <h4 class="mb-3">Purchase Management</h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form wire:submit.prevent="{{ $purchase_id ? 'update' : 'save' }}">
        <div class="card mb-4">
            <div class="card-header">
                {{ $purchase_id ? 'Update Purchase' : 'Create Purchase' }}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Supplier</label>
                        <select wire:model="supplier_id" class="form-control">
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->user->name ?? ($supplier->name ?? 'Supplier') }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Purchase Date</label>
                        <input type="date" wire:model="purchase_date" class="form-control">
                        @error('purchase_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Invoice No</label>
                        <input type="text" wire:model="invoice_no" class="form-control">
                        @error('invoice_no')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <h5 class="mt-3">Purchase Items</h5>

                @foreach ($items as $index => $item)
                    @php
                        $selectedProductIds = collect($items)->pluck('product_id')->filter()->values()->toArray();
                    @endphp

                    <div class="row mb-3 align-items-end"
                        wire:key="purchase-item-{{ $index }}-{{ $item['product_id'] ?? 'empty' }}">
                        <div class="col-md-4">
                            <label>Product</label>
                            <select wire:model.live="items.{{ $index }}.product_id" class="form-control">
                                <option value="">Select Product</option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @disabled(in_array($product->id, $selectedProductIds) && $product->id != ($items[$index]['product_id'] ?? null))>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('items.' . $index . '.product_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label>Quantity</label>
                            <input type="number" wire:model.live="items.{{ $index }}.quantity"
                                class="form-control" min="1">
                            @error('items.' . $index . '.quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label>Price</label>
                            <input type="number" wire:model.live="items.{{ $index }}.purchase_price"
                                class="form-control" min="0" step="0.01">
                            @error('items.' . $index . '.purchase_price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label>Subtotal</label>
                            <input type="number" value="{{ $items[$index]['subtotal'] ?? 0 }}" class="form-control"
                                readonly>
                        </div>

                        <div class="col-md-2">
                            <button type="button" wire:click="removeItem({{ $index }})" class="btn btn-danger">
                                Remove
                            </button>
                        </div>
                    </div>
                @endforeach

                <button type="button" wire:click="addItem" class="btn btn-secondary mb-3">
                    Add Item
                </button>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Total Amount</label>
                        <input type="number" value="{{ $this->total_amount }}" class="form-control" readonly>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Paid Amount</label>
                        <input type="number" wire:model.live="paid_amount" class="form-control">
                        @error('paid_amount')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Due Amount</label>
                        <input type="number" value="{{ $this->due_amount }}" class="form-control" readonly>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Payment Status</label>
                        <select wire:model="payment_status" class="form-control">
                            <option value="unpaid">Unpaid</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                        </select>
                        @error('payment_status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select wire:model="status" class="form-control">
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    @error('status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Notes</label>
                    <textarea wire:model="notes" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ $purchase_id ? 'Update' : 'Save' }}
                </button>

                <button type="button" wire:click="resetForm" class="btn btn-warning">
                    Reset
                </button>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-header">Purchase List</div>

        <div class="card-body">
            <input type="text" wire:model.live="search" class="form-control mb-3" placeholder="Search invoice no">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Supplier</th>
                        <th>Invoice</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->id }}</td>
                            <td>{{ $purchase->supplier->user->name ?? ($purchase->supplier->name ?? 'N/A') }}</td>
                            <td>{{ $purchase->invoice_no }}</td>
                            <td>{{ $purchase->total_amount }}</td>
                            <td>{{ $purchase->paid_amount }}</td>
                            <td>{{ $purchase->due_amount }}</td>
                            <td>{{ ucfirst($purchase->payment_status) }}</td>
                            <td>{{ ucfirst($purchase->status) }}</td>
                            <td>
                                <button wire:click="edit({{ $purchase->id }})" class="btn btn-sm btn-info">
                                    Edit
                                </button>

                                <button wire:click="delete({{ $purchase->id }})"
                                    onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No purchases found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
