<?php

use Livewire\Component;
use App\Models\SalesReturn;

new class extends Component {
    public SalesReturn $salesReturn;

    public function mount($id)
    {
        $this->salesReturn = SalesReturn::with(['order', 'customer', 'items.product'])->findOrFail($id);
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Sales Return Details</h3>

        <a href="{{ route('sales_return.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $salesReturn->return_no }}</h5>
        </div>

        <div class="card-body">
            <p><strong>Order:</strong> #{{ $salesReturn->order_id }}</p>
            <p><strong>Customer:</strong> {{ $salesReturn->customer->name ?? 'Walk-in Customer' }}</p>
            <p><strong>Total Amount:</strong> Rs. {{ number_format($salesReturn->total_amount, 2) }}</p>
            <p><strong>Reason:</strong> {{ $salesReturn->reason ?? '-' }}</p>

            <p>
                <strong>Status:</strong>
                <span class="badge bg-info">
                    {{ ucfirst($salesReturn->status) }}
                </span>
            </p>

            <p><strong>Notes:</strong></p>
            <div class="border rounded p-3 bg-light">
                {{ $salesReturn->notes ?: 'No notes available.' }}
            </div>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-light">
            <h5 class="mb-0">Returned Items</h5>
        </div>

        <div class="card-body table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($salesReturn->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rs. {{ number_format($item->price, 2) }}</td>
                            <td>Rs. {{ number_format($item->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No items found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
