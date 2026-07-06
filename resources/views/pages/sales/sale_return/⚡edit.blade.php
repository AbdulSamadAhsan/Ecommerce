<?php

use Livewire\Component;
use App\Models\SalesReturn;
use Illuminate\Support\Facades\DB;
new class extends Component {
    public $salesReturnId;
    public $reason = '';
    public $status = 'pending';

    public function mount($id)
    {
        $return = SalesReturn::findOrFail($id);

        $this->salesReturnId = $return->id;
        $this->reason = $return->reason;
        $this->status = $return->status;
        if ($return->status == 'approved') {
            return redirect()->route('sales_return.index');
        }
    }

    protected $rules = [
        'reason' => 'nullable|max:255',
        'status' => 'required|string',
    ];

    public function update()
    {
        $this->validate();
        $saleReturn = SalesReturn::findOrFail($this->salesReturnId);

        if ($this->status == 'approved') {
            DB::transaction(function () use ($saleReturn) {
                $saleReturn->item->product->stockmovement()->create([
                    'product_id' => $saleReturn->item->product->id,
                    'warehouse_id' => $saleReturn->item->product->warehouse_id,
                    'supplier_id' => $saleReturn->item->product->supplier_id,
                    'stock_before' => $saleReturn->item->product->quantity,
                    'stock_after' => (float) $saleReturn->item->quantity + (float) $saleReturn->item->product->quantity,
                    'type' => 'return',
                    'quantity' => $saleReturn->item->quantity,
                ]);
                $saleReturn->item->product->stocks()->updateOrCreate(
                    [
                        'product_id' => $saleReturn->item->product->id,
                        'warehouse_id' => $saleReturn->item->product->warehouse_id,
                    ],
                    [
                        'quantity' => (float) $saleReturn->item->quantity + (float) $saleReturn->item->product->quantity,
                        'minimum_stock' => $saleReturn->item->product->minimum_stock,
                    ],
                );
                $saleReturn->item->product()->update([
                    'quantity' => (float) $saleReturn->item->quantity + (float) $saleReturn->item->product->quantity,
                ]);
                $amounttopup = (float) $saleReturn->total_amount;
                $wallet = $saleReturn->sale->customer->wallet;

                $wallet->transactions()->create([
                    'amount' => $amounttopup,
                    'reference_id' => rand(111, 999) . time(),
                    'type' => 'credit',
                    'description' => 'Amount Refunded',
                ]);
                $wallet->increment('balance', (float) $amounttopup);
            });
        }

        $saleReturn->update([
            'status' => $this->status,
        ]);

        session()->flash('success', 'Sales return updated successfully.');

        return redirect()->route('sales_return.index');
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Edit Sales Return</h4>
    </div>

    <div class="card-body">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form wire:submit="update">

            <div class="mb-3">
                <label class="form-label">Reason</label>
                <input type="text" wire:model.live="reason" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select wire:model.live="status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="declined">Rejected</option>

                </select>
            </div>


            <div class="d-flex justify-content-between">
                <a href="{{ route('sales_return.index') }}" class="btn btn-secondary rounded-pill">
                    Back
                </a>

                <button class="btn btn-warning rounded-pill">
                    Update Sales Return
                </button>
            </div>

        </form>
    </div>
</div>
