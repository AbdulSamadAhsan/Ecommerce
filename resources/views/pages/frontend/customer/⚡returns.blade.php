<?php

use Livewire\Component;
use App\Models\SalesReturn;
new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public $returns = [['id' => 1, 'order_id' => 1001, 'item' => 'Wireless Mouse', 'reason' => 'Wrong item received', 'status' => 'Pending', 'date' => '2026-06-18'], ['id' => 2, 'order_id' => 998, 'item' => 'Headphones', 'reason' => 'Damaged product', 'status' => 'Approved', 'date' => '2026-06-12']];

    public function mount(): void
    {
        $customerId = auth('customer')->user()->customer->id;

        $this->returns = SalesReturn::with(['sale', 'items.product'])
            ->whereHas('sale', function ($query) use ($customerId) {
                $query->where('customer_id', $customerId);
            })
            ->latest()
            ->get();
    }
};
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">Return Requests</h2>

    <div class="row g-4">
        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">My Returns</h4>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Return ID</th>
                                    <th>Order</th>
                                    <th>Item</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($returns as $return)

                                    @foreach ($return->items as $item)
                                        <tr>
                                            <td>#{{ $return->id }}</td>

                                            <td>{{ $return->sale->invoice_no }}</td>

                                            <td>{{ $item->product->name }}</td>

                                            <td>{{ $return->reason }}</td>

                                            <td>
                                                @php
                                                    $color = match (strtolower($return->status)) {
                                                        'approved' => 'success',
                                                        'rejected' => 'danger',
                                                        'pending' => 'warning text-dark',
                                                        default => 'secondary',
                                                    };
                                                @endphp

                                                <span class="badge bg-{{ $color }}">
                                                    {{ ucfirst($return->status) }}
                                                </span>
                                            </td>

                                            <td>{{ $return->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach

                                @empty

                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No return requests found.
                                        </td>
                                    </tr>

                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info rounded-4 mt-3 mb-0">
                        To request a new return, open an order detail page and submit return request for the item.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
