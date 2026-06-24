<?php

use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Stock;
use App\Models\SupplierPayment;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $purchase_id = null;
    public $supplier_id = '';
    public $purchase_date = '';
    public $purchase_no = '';
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
        $this->purchase_no = $this->generateInvoiceNo();
    }

    public function generateInvoiceNo()
    {
        $lastPurchase = Purchase::latest('id')->first();
        $nextId = $lastPurchase ? $lastPurchase->id + 1 : 1;

        return 'PUR-' . now()->format('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public function updatedSupplierId()
    {
        $this->items = [
            [
                'product_id' => '',
                'quantity' => 1,
                'purchase_price' => 0,
                'subtotal' => 0,
            ],
        ];
    }

    public function addItem()
    {
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
            $index = explode('.', $property)[1];

            $product = Product::where('supplier_id', $this->supplier_id)->find($this->items[$index]['product_id'] ?? null);

            $this->items[$index]['purchase_price'] = $product ? $product->purchase_price ?? ($product->selling_price ?? 0) : 0;
        }

        $this->calculateTotals();
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
            'purchase_no' => 'required|string|max:255',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:unpaid,partial,paid',
            'status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
        ];
    }

    public function save()
    {
        $this->calculateTotals();
        $this->validate();

        DB::transaction(function () {
            $purchase = Purchase::create([
                'supplier_id' => $this->supplier_id,
                'purchase_date' => $this->purchase_date,
                'purchase_no' => $this->purchase_no,
                'total_amount' => $this->total_amount,
                'paid_amount' => $this->paid_amount ?? 0,
                'due_amount' => $this->due_amount,
                'payment_status' => $this->payment_status,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);
            if (in_array($this->payment_status, ['partial', 'paid'])) {
                SupplierPayment::create([
                    'amount' => $this->paid_amount,
                    'payment_method' => 'card',
                    'payment_date' => $this->purchase_date,
                    'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                    'purchase_id' => $purchase->id,
                    'supplier_id' => $this->supplier_id,
                    'notes' => 'Item Purchased : ' . collect($this->items)->sum('quantity'),
                ]);
            }
            foreach ($this->items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'subtotal' => $item['subtotal'],
                ]);
                $productData = Product::find($item['product_id']);
                $productquantity = $productData->quantity;
                $last = StockMovement::latest('id')->first();

                $nextId = $last ? $last->id + 1 : 1;
                Product::where('id', $item['product_id'])->increment('quantity', $item['quantity']);
                $ref = 'SM-' . now()->format('Ymd') . '-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
                $productData->refresh();

                $afterStock = $productData->quantity;
                $warehouseId = $productData->warehouse_id;
                StockMovement::create([
                    'warehouse_id' => $productData->warehouse_id,
                    'supplier_id' => $this->supplier_id,
                    'product_id' => $item['product_id'],
                    'type' => 'purchase',
                    'quantity' => $item['quantity'],
                    'stock_before' => $productquantity,
                    'stock_after' => $afterStock,
                    'reference_no' => $ref,
                    'remarks' => 'Item Purchased : ' . collect($this->items)->sum('quantity'),
                ]);
                Stock::updateOrCreate(
                    [
                        'product_id' => $item['product_id'],
                    ],

                    [
                        'quantity' => $afterStock,
                        'warehouse_id' => $warehouseId,
                        'minimum_stock' => $productData->minimum_stock,
                    ],
                );
            }
        });

        session()->flash('success', 'Purchase created successfully.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $purchase = Purchase::with('items')->findOrFail($id);

        $this->purchase_id = $purchase->id;
        $this->supplier_id = $purchase->supplier_id;
        $this->purchase_date = $purchase->purchase_date;
        $this->purchase_no = $purchase->purchase_no;
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

        $this->calculateTotals();
    }

    public function update()
    {
        $this->calculateTotals();
        $this->validate();

        DB::transaction(function () {
            $purchase = Purchase::with('items')->findOrFail($this->purchase_id);

            foreach ($purchase->items as $oldItem) {
                Product::where('id', $oldItem->product_id)->decrement('quantity', $oldItem->quantity);
            }

            $purchase->items()->delete();

            $purchase->update([
                'supplier_id' => $this->supplier_id,
                'purchase_date' => $this->purchase_date,
                'purchase_no' => $this->purchase_no,
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

                Product::where('id', $item['product_id'])->increment('quantity', $item['quantity']);
            }
        });

        session()->flash('success', 'Purchase updated successfully.');
        $this->resetForm();
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $purchase = Purchase::with('items')->findOrFail($id);

            foreach ($purchase->items as $item) {
                Product::where('id', $item->product_id)->decrement('quantity', $item->quantity);
            }

            $purchase->items()->delete();
            $purchase->delete();
        });

        session()->flash('success', 'Purchase deleted successfully.');
    }

    public function resetForm()
    {
        $this->purchase_id = null;
        $this->supplier_id = '';
        $this->purchase_date = now()->format('Y-m-d');
        $this->purchase_no = $this->generateInvoiceNo();
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
            'suppliers' => Supplier::with('user')->get(),

            'products' => $this->supplier_id ? Product::where('supplier_id', $this->supplier_id)->orderBy('name')->get() : collect(),

            'purchases' => Purchase::with('supplier.user')
                ->where('purchase_no', 'like', '%' . $this->search . '%')
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

    <form wire:submit.prevent="{{ $purchase_id ? 'update' : 'save' }}">
        <div class="card mb-4">
            <div class="card-header">
                {{ $purchase_id ? 'Update Purchase' : 'Create Purchase' }}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Supplier</label>
                        <select wire:model.live="supplier_id" class="form-control">
                            <option value="">Select Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{ $supplier->user->name ?? ($supplier->name ?? 'N/A') }}
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
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Invoice No</label>
                        <input type="text" wire:model="purchase_no" class="form-control">
                    </div>
                </div>

                <h5 class="mt-3">Purchase Items</h5>

                @foreach ($items as $index => $item)
                    <div class="row mb-3 align-items-end">
                        <div class="col-md-4">
                            <label>Product</label>
                            <select wire:model.live="items.{{ $index }}.product_id" class="form-control">
                                <option value="">
                                    {{ $supplier_id ? 'Select Product' : 'Select Supplier First' }}
                                </option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
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
                        </div>

                        <div class="col-md-2">
                            <label>Price</label>
                            <input type="number" wire:model.live="items.{{ $index }}.purchase_price"
                                class="form-control" min="0" step="0.01">
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
                    </div>
                </div>

                <div class="mb-3">
                    <label>Status</label>
                    <select wire:model="status" class="form-control">
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
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
                            <td>{{ $purchase->purchase_no }}</td>
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
                            <td colspan="9" class="text-center">
                                No purchases found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
