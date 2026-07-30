<?php

use Livewire\Component;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseReturnPayment;

new class extends Component {
    public int $id;

    public string $return_no = '';
    public string $supplier_name = '';
    public string $warehouse_name = '';
    public string $reason = '';
    public string $notes = '';
    public string $status = '';

    public float $total_amount = 0;

    public array $items = [];

    public function mount($id)
    {
        $this->id = $id;

        $return = PurchaseReturn::with(['purchase.supplier', 'items.product.warehouse'])->findOrFail($id);

        $this->return_no = $return->return_no;

        $this->reason = $return->reason ?? '';

        $this->notes = $return->notes ?? '';

        $this->status = $return->status;

        $this->supplier_name = $return->purchase->supplier->user->name ?? 'N/A';

        $warehouse = $return->items->pluck('product.warehouse')->filter()->first();

        $this->warehouse_name = $warehouse->name ?? 'N/A';

        foreach ($return->items as $item) {
            $this->items[$item->id] = [
                'product_id' => $item->product_id,

                'product_name' => $item->product->name ?? 'Deleted',

                'return_quantity' => $item->quantity,

                'unit_price' => (float) $item->unit_price,

                'total_price' => $item->quantity * $item->unit_price,
            ];
        }

        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total_amount = collect($this->items)->sum('total_price');
    }

    public function update()
    {
        $this->validate([
            'reason' => 'required|min:5',

            'notes' => 'nullable',

            'status' => 'required|in:pending,approved,declined',
        ]);

        DB::transaction(function () {
            $return = PurchaseReturn::findOrFail($this->id);

            if ($this->status == 'approved') {
                PurchaseReturnPayment::create([
                    'purchase_return_id' => $return->id,
                    'amount' => $return->total_amount,
                    'supplier_id' => $return->purchase->supplier->id,
                ]);
                foreach ($return->Items as $item) {
                    $product = $item->product;
                    $newquantity = (float) $product->quantity - (float) $item->quantity;
                    $product->stocks()->updateOrCreate(
                        ['product_id' => $product->id, 'warehouse_id' => $product->warehouse_id],
                        [
                            'quantity' => $newquantity,
                        ],
                    );
                    $product->stockmovement()->create([
                        'supplier_id' => $product->supplier_id,
                        'warehouse_id' => $product->warehouse_id,
                        'product_id' => $product->id,
                        'quantity' => $item->quantity,
                        'stock_before' => $product->quantity,
                        'stock_after' => $newquantity,
                        'type' => 'purchase_return',
                    ]);

                    $product->decrement('quantity', $item->quantity);
                }
            }
            $return->update([
                'reason' => $this->reason,

                'status' => $this->status,
            ]);
        });

        session()->flash('success', 'Purchase return updated successfully');

        return redirect()->route('purchases.returns.index');
    }
};

?>


<div class="row">

    <div class="col-lg-12">


        <div class="card shadow border-0">


            <div class="card-header bg-primary text-white">

                <h4>Edit Purchase Return</h4>

            </div>


            <div class="card-body">


                @if (session()->has('success'))
                    <div class="alert alert-success">

                        {{ session('success') }}

                    </div>
                @endif



                <form wire:submit.prevent="update">



                    <div class="row">


                        <div class="col-md-4 mb-3">

                            <label>Return No</label>

                            <input class="form-control" value="{{ $return_no }}" readonly>

                        </div>



                        <div class="col-md-4 mb-3">

                            <label>Supplier</label>

                            <input class="form-control" value="{{ $supplier_name }}" readonly>

                        </div>



                        <div class="col-md-4 mb-3">

                            <label>Warehouse</label>

                            <input class="form-control" value="{{ $warehouse_name }}" readonly>

                        </div>


                    </div>



                    <div class="mb-3">

                        <label>Status</label>

                        <select wire:model="status" class="form-select">

                            <option value="pending">
                                Pending
                            </option>
                            <option value="approved">
                                Approved
                            </option>

                            <option value="declined">
                                Rejected
                            </option>

                        </select>

                    </div>




                    <div class="mb-3">

                        <label>Reason</label>

                        <textarea wire:model="reason" class="form-control">
</textarea>

                    </div>



                    <div class="mb-3">

                        <label>Notes</label>

                        <textarea wire:model="notes" class="form-control">
</textarea>

                    </div>



                    <hr>


                    <h5 class="fw-bold">
                        Returned Products
                    </h5>



                    <table class="table table-bordered">


                        <thead>

                            <tr>

                                <th>Product</th>

                                <th>Qty</th>

                                <th>Price</th>

                                <th>Total</th>

                            </tr>

                        </thead>



                        <tbody>


                            @forelse($items as $item)
                                <tr>

                                    <td>

                                        {{ $item['product_name'] }}

                                    </td>


                                    <td>

                                        {{ $item['return_quantity'] }}

                                    </td>


                                    <td>

                                        Rs {{ number_format($item['unit_price'], 2) }}

                                    </td>


                                    <td>

                                        Rs {{ number_format($item['total_price'], 2) }}

                                    </td>


                                </tr>


                            @empty


                                <tr>

                                    <td colspan="4" class="text-center">

                                        No Returned Products

                                    </td>

                                </tr>
                            @endforelse


                        </tbody>



                        <tfoot>


                            <tr>

                                <th colspan="3" class="text-end">

                                    Grand Total

                                </th>


                                <th>

                                    Rs {{ number_format($total_amount, 2) }}

                                </th>


                            </tr>


                        </tfoot>



                    </table>



                    <div class="text-end">


                        <button class="btn btn-primary rounded-pill px-4">

                            Update Return

                        </button>


                    </div>



                </form>


            </div>


        </div>


    </div>


</div>
