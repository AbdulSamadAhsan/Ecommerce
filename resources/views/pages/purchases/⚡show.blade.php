<?php

use Livewire\Component;
use App\Models\Purchase;

new class extends Component {
    public int $purchaseId;

    public $purchase;
    public $purchaseItems;
    public function mount($id): void
    {
        $this->purchaseId = $id;

        $this->purchase = Purchase::with(['items'])->findOrFail($id);
    }
};
?>

<div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Purchase Detail</h3>
            <p class="text-muted mb-0">
                Purchase #{{ $purchase->purchase_no }}
            </p>
        </div>

        <a href="{{ route('purchases.history') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="dashboard-card">

        <div class="row">

            <div class="col-md-4 mb-4">
                <label class="fw-bold">ID</label>
                <div>#{{ $purchase['id'] }}</div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Invoice No</label>
                <div>{{ $purchase['purchase_no'] }}</div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Purchase Date</label>
                <div>{{ $purchase['purchase_date'] }}</div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Total Amount</label>
                <div>
                    {{ number_format($purchase['total_amount'], 2) }}
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Paid Amount</label>
                <div>
                    {{ number_format($purchase['paid_amount'], 2) }}
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Due Amount</label>
                <div>
                    {{ number_format($purchase['due_amount'], 2) }}
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Payment Status</label>
                <div>
                    <span
                        class="badge
                        @if ($purchase['payment_status'] == 'paid') bg-success
                        @elseif($purchase['payment_status'] == 'partial')
                            bg-warning text-dark
                        @else
                            bg-danger @endif">
                        {{ ucfirst($purchase['payment_status']) }}
                    </span>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Status</label>
                <div>
                    <span
                        class="badge
                        @if ($purchase['status'] == 'completed') bg-success
                        @elseif($purchase['status'] == 'cancelled')
                            bg-danger
                        @else
                            bg-secondary @endif">
                        {{ ucfirst($purchase['status']) }}
                    </span>
                </div>
            </div>

            <div class="col-md-12">
                <label class="fw-bold">Notes</label>

                <div class="border rounded p-3 bg-light">
                    {{ $purchase['notes'] ?: 'No Notes Available' }}
                </div>
            </div>
            <div class="dashboard-card">

                <h5 class="fw-bold mb-3">Purchase Items</h5>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Warehouse</th>
                                <th>Qty</th>
                                <th>Unit Cost</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($purchase->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                                    <td>{{ $item->product->warehouse->name ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rs. {{ number_format($item->unit_cost, 2) }}</td>
                                    <td>Rs. {{ number_format($item->total_cost, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No purchase items found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>




        </div>

    </div>

</div>
