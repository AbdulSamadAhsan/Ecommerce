<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public $customer_id = '';
    public $invoice_no = '';
    public $address = '';
    public $order_status = 'pending';
    public $order_date = '';
    public $discount = 0;
    public $tax = 0;

    public $items = [
        [
            'product_id' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'total_price' => 0,
        ],
    ];

    public function mount()
    {
        $this->order_date = now()->format('Y-m-d');
        $this->invoice_no = $this->generateInvoiceNo();
    }

    public function generateInvoiceNo()
    {
        $lastSale = Sale::latest('id')->first();
        $nextId = $lastSale ? $lastSale->id + 1 : 1;

        return 'SALE-' . now()->format('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public function addItem()
    {
        $this->items[] = [
            'product_id' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'total_price' => 0,
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

            $this->items[$index]['unit_price'] = $product ? $product->selling_price ?? 0 : 0;
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
            $price = (float) ($item['unit_price'] ?? 0);

            $this->items[$index]['total_price'] = $qty * $price;
        }
    }

    public function getSubtotalProperty()
    {
        return collect($this->items)->sum('total_price');
    }

    public function getTotalAmountProperty()
    {
        return max($this->subtotal - (float) $this->discount + (float) $this->tax, 0);
    }

    public function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'invoice_no' => 'required|string|max:255',
            'address' => 'required|string',
            'order_status' => 'required|string',
            'order_date' => 'required|date',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id|distinct',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    public function save()
    {
        $this->removeDuplicateRows();
        $this->calculateTotals();
        $this->validate();

        DB::transaction(function () {
            foreach ($this->items as $item) {
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->firstOrFail();

                if ($product->quantity < $item['quantity']) {
                    throw new Exception($product->name . ' stock is not enough.');
                }
            }

            $sale = Sale::create([
                'customer_id' => $this->customer_id,
                'invoice_no' => $this->invoice_no,
                'subtotal' => $this->subtotal,
                'discount' => $this->discount ?? 0,
                'tax' => $this->tax ?? 0,
                'total_amount' => $this->total_amount,
            ]);

            foreach ($this->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);

                $product = Product::where('id', $item['product_id'])->lockForUpdate()->firstOrFail();

                $product->quantity = $product->quantity - $item['quantity'];
                $product->save();
            }

            Order::create([
                'sale_id' => $sale->id,
                'address' => $this->address,
                'order_status' => $this->order_status,
                'order_date' => $this->order_date,
            ]);
        });

        session()->flash('success', 'Sale and order created successfully.');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->customer_id = '';
        $this->invoice_no = $this->generateInvoiceNo();
        $this->address = '';
        $this->order_status = 'pending';
        $this->order_date = now()->format('Y-m-d');
        $this->discount = 0;
        $this->tax = 0;

        $this->items = [
            [
                'product_id' => '',
                'quantity' => 1,
                'unit_price' => 0,
                'total_price' => 0,
            ],
        ];

        $this->resetValidation();
    }

    public function with()
    {
        return [
            'customers' => Customer::with('user')->get(),
            'products' => Product::orderBy('name')->get(),
            'sales' => Sale::with('customer.user')->latest()->get(),
        ];
    }
};
?>

<div class="container-fluid">
    <h4 class="mb-3">Create Sale & Order </h4>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form wire:submit.prevent="save">
        <div class="card mb-4">
            <div class="card-header">Sale Information</div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Customer</label>
                        <select wire:model="customer_id" class="form-control">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->user->name ?? ($customer->name ?? 'Customer') }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
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

                    <div class="col-md-4 mb-3">
                        <label>Order Date</label>
                        <input type="date" wire:model="order_date" class="form-control">
                        @error('order_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label>Address</label>
                    <textarea wire:model="address" class="form-control"></textarea>
                    @error('address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Order Status</label>
                    <select wire:model="order_status" class="form-control">
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    @error('order_status')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <h5 class="mt-3">Sale Items</h5>

                @foreach ($items as $index => $item)
                    @php
                        $selectedProductIds = collect($items)->pluck('product_id')->filter()->values()->toArray();
                    @endphp

                    <div class="row mb-3 align-items-end"
                        wire:key="sale-item-{{ $index }}-{{ $item['product_id'] ?? 'empty' }}">
                        <div class="col-md-4">
                            <label>Product</label>
                            <select wire:model.live="items.{{ $index }}.product_id" class="form-control">
                                <option value="">Select Product</option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" @disabled(in_array($product->id, $selectedProductIds) && $product->id != ($items[$index]['product_id'] ?? null))>
                                        {{ $product->name }} | Stock: {{ $product->quantity }}
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
                            <label>Unit Price</label>
                            <input type="number" wire:model.live="items.{{ $index }}.unit_price"
                                class="form-control" min="0" step="0.01">
                            @error('items.' . $index . '.unit_price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label>Total Price</label>
                            <input type="number" value="{{ $items[$index]['total_price'] ?? 0 }}" class="form-control"
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
                        <label>Subtotal</label>
                        <input type="number" value="{{ $this->subtotal }}" class="form-control" readonly>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Discount</label>
                        <input type="number" wire:model.live="discount" class="form-control" min="0">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Tax</label>
                        <input type="number" wire:model.live="tax" class="form-control" min="0">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Total Amount</label>
                        <input type="number" value="{{ $this->total_amount }}" class="form-control" readonly>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Create Sale & Order
                </button>

                <button type="button" wire:click="resetForm" class="btn btn-warning">
                    Reset
                </button>
            </div>
        </div>
    </form>
</div>
