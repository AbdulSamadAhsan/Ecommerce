<?php

use Livewire\Component;
use App\Models\PurchaseReturn;

new class extends Component {
    public PurchaseReturn $purchaseReturn;

    public function mount($id): void
    {
        $this->purchaseReturn = PurchaseReturn::with(['purchase', 'supplier', 'warehouse', 'items.product'])->findOrFail($id);
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Purchase Return Details</h3>
            <p class="text-muted mb-0">View complete purchase return information</p>
        </div>

        <a wire:navigate href="{{ route('purchases.returns.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                Return #{{ $purchaseReturn->return_no }}
            </h4>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <strong>Purchase:</strong>
                    #{{ $purchaseReturn->purchase_id }}
                </div>

                <div class="col-md-4">
                    <strong>Supplier:</strong>
                    {{ $purchaseReturn->supplier->user->name ?? 'N/A' }}
                </div>

                <div class="col-md-4">
                    <strong>Warehouse:</strong>
                    {{ $purchaseReturn->warehouse->name ?? 'N/A' }}
                </div>

                <div class="col-md-4">
                    <strong>Status:</strong>
                    <span
                        class="badge rounded-pill
                        @if ($purchaseReturn->status === 'approved') bg-success
                        @elseif($purchaseReturn->status === 'rejected') bg-danger
                        @else bg-warning text-dark @endif">
                        {{ ucfirst($purchaseReturn->status) }}
                    </span>
                </div>

                <div class="col-md-4">
                    <strong>Total Amount:</strong>
                    Rs {{ number_format($purchaseReturn->total_amount, 2) }}
                </div>

                <div class="col-md-4">
                    <strong>Date:</strong>
                    {{ $purchaseReturn->created_at->format('d M Y h:i A') }}
                </div>

                <div class="col-md-12">
                    <strong>Reason:</strong>
                    <div class="border rounded-4 p-3 bg-light mt-2">
                        {{ $purchaseReturn->reason ?? 'N/A' }}
                    </div>
                </div>

                @if ($purchaseReturn->notes)
                    <div class="col-md-12">
                        <strong>Notes:</strong>
                        <div class="border rounded-4 p-3 bg-light mt-2">
                            {{ $purchaseReturn->notes }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Returned Items</h5>

            <span class="badge bg-primary rounded-pill">
                {{ $purchaseReturn->items->count() }} Items
            </span>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Returned Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($purchaseReturn->items as $item)
                        <tr>
                            <td>#{{ $item->id }}</td>

                            <td>

                                <p> {{ $item->product->name ?? 'Deleted Product' }}</p>

                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rs {{ number_format($item->unit_price, 2) }}</td>
                            <td>Rs {{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                No returned items found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr class="table-light">
                        <th colspan="4" class="text-end">Grand Total</th>
                        <th>Rs {{ number_format($purchaseReturn->total_amount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
