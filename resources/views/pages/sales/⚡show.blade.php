<?php

use Livewire\Component;
use App\Models\Sale;

new class extends Component {
    public int $saleId;

    public $sale;

    public function mount($id): void
    {
        $this->saleId = $id;

        $this->sale = Sale::with(['customer', 'items.product', 'orderNumber'])->findOrFail($id);
    }
};
?>

<div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Sale Detail</h3>
            <p class="text-muted mb-0">
                Invoice #{{ $sale->invoice_no }}
            </p>
        </div>

        <a href="{{ route('sales.history') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="dashboard-card mb-4">

        <div class="row">

            <div class="col-md-4 mb-4">
                <label class="fw-bold">ID</label>
                <div>#{{ $sale->id }}</div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Invoice No</label>
                <div>{{ $sale->invoice_no }}</div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Customer</label>
                <div>{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Subtotal</label>
                <div>Rs. {{ number_format($sale->subtotal, 2) }}</div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Discount</label>
                <div>Rs. {{ number_format($sale->discount ?? 0, 2) }}</div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Tax</label>
                <div>Rs. {{ number_format($sale->tax ?? 0, 2) }}</div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Total Amount</label>
                <div class="fw-bold text-success">
                    Rs. {{ number_format($sale->total_amount, 2) }}
                </div>
            </div>

            @if ($sale->order)
                <div class="col-md-4 mb-4">
                    <label class="fw-bold">Order Status</label>
                    <div>
                        <span class="badge bg-info">
                            {{ ucfirst($sale->order->order_status) }}
                        </span>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <label class="fw-bold">Order Date</label>
                    <div>{{ $sale->order->order_date }}</div>
                </div>

                <div class="col-md-12">
                    <label class="fw-bold">Address</label>
                    <div class="border rounded p-3 bg-light">
                        {{ $sale->order->address ?: 'No address available' }}
                    </div>
                </div>
            @endif

        </div>

    </div>

    <div class="dashboard-card">

        <h5 class="fw-bold mb-3">Sale Items</h5>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($sale->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rs. {{ number_format($item->unit_price, 2) }}</td>
                            <td>Rs. {{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No sale items found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
