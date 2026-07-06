<?php

use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public string $return_no = '';
    public string $purchase_id = '';
    public string $supplier_name = '';
    public string $warehouse_name = '';
    public string $reason = '';
    public string $notes = '';
    public float $total_amount = 0;

    public array $items = [];

    public function mount(): void
    {
        $this->return_no = $this->generateReturnNo();
    }

    public function generateReturnNo(): string
    {
        $nextId = (PurchaseReturn::max('id') ?? 0) + 1;

        return 'PRET-' . now()->format('Ymd') . '-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }

    public function updatedPurchaseId(): void
    {
        $this->items = [];
        $this->supplier_name = '';
        $this->warehouse_name = '';
        $this->total_amount = 0;

        if (!$this->purchase_id) {
            return;
        }

        $purchase = Purchase::with(['supplier', 'items.product.warehouse'])->findOrFail($this->purchase_id);

        $this->supplier_name = $purchase->supplier->user->name ?? 'N/A';

        $warehouse = $purchase->items->pluck('product.warehouse')->filter()->first();

        $this->warehouse_name = $warehouse->name ?? 'N/A';

        foreach ($purchase->items as $item) {
            $alreadyReturned = PurchaseReturnItem::where('product_id', $item->product_id)
                ->whereHas('purchaseReturn', function ($query) use ($item) {
                    $query->where('purchase_id', $item->purchase_id)->whereIn('status', ['pending', 'approved']);
                })
                ->sum('quantity');

            $availableQty = max(0, (int) $item->quantity - (int) $alreadyReturned);

            $unitPrice = (float) ($item->purchase_price ?? 0);

            $this->items[$item->id] = [
                'selected' => false,
                'purchase_item_id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? 'Deleted Product',
                'purchased_quantity' => (int) $item->quantity,
                'already_returned' => (int) $alreadyReturned,
                'available_quantity' => $availableQty,
                'return_quantity' => $availableQty > 0 ? 1 : 0,
                'unit_price' => $unitPrice,
                'total_price' => 0,
            ];
        }

        $this->calculateTotal();
    }

    public function updatedItems(): void
    {
        $this->calculateTotal();
    }

    public function calculateTotal(): void
    {
        $total = 0;

        foreach ($this->items as $key => $item) {
            $qty = (int) ($item['return_quantity'] ?? 0);
            $price = (float) ($item['unit_price'] ?? 0);

            $this->items[$key]['total_price'] = $qty * $price;

            if (!empty($item['selected'])) {
                $total += $qty * $price;
            }
        }

        $this->total_amount = $total;
    }

    public function save()
    {
        $this->calculateTotal();

        $this->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'return_no' => 'required|string|unique:purchase_returns,return_no',
            'reason' => 'required|min:5|max:1000',
            'notes' => 'nullable|max:1000',
            'items' => 'required|array',
        ]);

        $selectedItems = collect($this->items)->where('selected', true);

        if ($selectedItems->isEmpty()) {
            $this->addError('items', 'Please select at least one item.');
            return;
        }

        foreach ($selectedItems as $item) {
            if ((int) $item['available_quantity'] <= 0) {
                $this->addError('items', $item['product_name'] . ' is already fully returned.');
                return;
            }

            if ((int) $item['return_quantity'] < 1) {
                $this->addError('items', 'Return quantity must be at least 1 for ' . $item['product_name']);
                return;
            }

            if ((int) $item['return_quantity'] > (int) $item['available_quantity']) {
                $this->addError('items', 'Return quantity cannot exceed available quantity for ' . $item['product_name']);
                return;
            }
        }

        DB::transaction(function () use ($selectedItems) {
            $purchase = Purchase::with(['supplier', 'items.product.warehouse'])->findOrFail($this->purchase_id);

            $warehouse = $purchase->items->pluck('product.warehouse')->filter()->first();

            $purchaseReturn = PurchaseReturn::create([
                'return_no' => $this->return_no,
                'purchase_id' => $purchase->id,
                'supplier_id' => $purchase->supplier_id ?? null,
                'warehouse_id' => $warehouse?->id,
                'total_amount' => $this->total_amount,
                'reason' => $this->reason,

                'status' => 'pending',
            ]);

            foreach ($selectedItems as $item) {
                PurchaseReturnItem::create([
                    'purchase_return_id' => $purchaseReturn->id,
                    'purchase_item_id' => $item['purchase_item_id'],
                    'product_id' => $item['product_id'],
                    'quantity' => $item['return_quantity'],
                    'unit_price' => $item['unit_price'],
                    'amount' => $item['return_quantity'] * $item['unit_price'],
                ]);
            }
        });

        session()->flash('success', 'Purchase return created successfully.');

        return redirect()->route('purchases.returns.index');
    }

    public function with(): array
    {
        return [
            'purchases' => Purchase::with('supplier')->latest()->get(),
        ];
    }
};
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add Purchase Return</h4>
            </div>

            <div class="card-body">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @error('items')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror

                <form wire:submit.prevent="save">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Return No</label>
                            <input type="text" wire:model="return_no"
                                class="form-control @error('return_no') is-invalid @enderror" readonly>

                            @error('return_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Purchase</label>

                            <select wire:model.live="purchase_id"
                                class="form-select @error('purchase_id') is-invalid @enderror">

                                <option value="">Select Purchase</option>

                                @foreach ($purchases as $purchase)
                                    <option value="{{ $purchase->id }}">
                                        #{{ $purchase->id }}
                                        -
                                        {{ $purchase->purchase_no ?? 'Purchase' }}
                                        -
                                        {{ $purchase->supplier->name ?? 'Supplier' }}
                                    </option>
                                @endforeach
                            </select>

                            @error('purchase_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <input type="text" class="form-control" value="Pending" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier</label>
                            <input type="text" class="form-control" value="{{ $supplier_name }}" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Warehouse</label>
                            <input type="text" class="form-control" value="{{ $warehouse_name }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Return Reason</label>

                        <textarea wire:model.live="reason" rows="4" class="form-control @error('reason') is-invalid @enderror"
                            placeholder="Enter return reason"></textarea>

                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Notes</label>

                        <textarea wire:model.live="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"
                            placeholder="Optional notes"></textarea>

                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <h5 class="fw-bold mb-3">Purchase Items</h5>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Select</th>
                                    <th>Product</th>
                                    <th>Purchased Qty</th>
                                    <th>Already Returned</th>
                                    <th>Available Qty</th>
                                    <th>Return Qty</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($items as $key => $item)
                                    <tr wire:key="purchase-item-{{ $key }}">
                                        <td>
                                            <input type="checkbox" wire:model.live="items.{{ $key }}.selected"
                                                class="form-check-input" @disabled($item['available_quantity'] <= 0)>
                                        </td>

                                        <td>
                                            <div class="fw-semibold">
                                                {{ $item['product_name'] }}
                                            </div>
                                        </td>

                                        <td>{{ $item['purchased_quantity'] }}</td>
                                        <td>{{ $item['already_returned'] }}</td>

                                        <td>
                                            @if ($item['available_quantity'] > 0)
                                                <span class="badge bg-success rounded-pill">
                                                    {{ $item['available_quantity'] }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger rounded-pill">
                                                    Fully Returned
                                                </span>
                                            @endif
                                        </td>

                                        <td style="width: 130px;">
                                            <input type="number"
                                                wire:model.live="items.{{ $key }}.return_quantity"
                                                class="form-control" min="1"
                                                max="{{ $item['available_quantity'] }}" @disabled($item['available_quantity'] <= 0)>
                                        </td>

                                        <td>
                                            Rs {{ number_format($item['unit_price'], 2) }}
                                        </td>

                                        <td>
                                            Rs
                                            {{ number_format((float) $item['return_quantity'] * (float) $item['unit_price'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            Select a purchase to load items.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end mt-4">
                        <h4 class="fw-bold">
                            Total Return Amount:
                            Rs {{ number_format($total_amount, 2) }}
                        </h4>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a wire:navigate href="{{ route('purchases.returns.index') }}"
                            class="btn btn-secondary rounded-pill">
                            Back
                        </a>

                        <button type="submit" class="btn btn-primary rounded-pill px-4" wire:loading.attr="disabled"
                            wire:target="save">

                            <span wire:loading.remove wire:target="save">
                                Save Purchase Return
                            </span>

                            <span wire:loading wire:target="save">
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
