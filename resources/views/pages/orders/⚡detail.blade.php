<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

new class extends Component {
    public Order $order;

    public function mount(Order $order): void
    {
        $this->order = $order->load(['sale.items.product']);
    }

    public function rendering($view): void
    {
        $view->layout('components.layouts.ecommerce');
    }
};
?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Order Details</h2>

        <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill">
            Continue Shopping
        </a>
    </div>

    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-3">Order #{{ $order->id }}</h4>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Status:</strong>
                            <span class="badge bg-warning text-dark">
                                {{ ucfirst($order->status ?? 'pending') }}
                            </span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Payment:</strong>
                            <span class="badge bg-info text-dark">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Payment Method:</strong>
                            {{ strtoupper($order->payment_method ?? 'COD') }}
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Date:</strong>
                            {{ $order->created_at?->format('d M Y h:i A') }}
                        </div>
                    </div>

                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-3">Order Items</h4>

                    @if ($order->sale)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($order->sale->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name ?? 'Product Deleted' }}</td>

                                            <td>{{ number_format($item->unit_price, 2) }}</td>

                                            <td>{{ $item->quantity }}</td>

                                            <td class="text-end">
                                                {{ number_format($item->unit_price * $item->quantity, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">
                                                No sale items found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning rounded-4 mb-0">
                            No sale found for this order.
                        </div>
                    @endif

                </div>
            </div>

        </div>

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-3">Customer Details</h4>

                    <p class="mb-2">
                        <strong>Name:</strong> {{ $order->name }}
                    </p>

                    <p class="mb-2">
                        <strong>Email:</strong> {{ $order->email }}
                    </p>

                    <p class="mb-2">
                        <strong>Phone:</strong> {{ $order->phone }}
                    </p>

                    <p class="mb-2">
                        <strong>City:</strong> {{ $order->city }}
                    </p>

                    <p class="mb-0">
                        <strong>Address:</strong><br>
                        {{ $order->address }}
                    </p>

                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-3">Payment Summary</h4>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <strong>{{ number_format($order->sale->subtotal ?? 0, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>
                            Shipping
                            @if ($order->shippingMethod)
                                <small class="text-muted">
                                    ({{ $order->shippingMethod->name }})
                                </small>
                            @endif
                        </span>
                        <strong>{{ number_format($order->shipping_cost ?? 0, 2) }}</strong>
                    </div>

                    @if (($order->discount ?? 0) > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>
                                Discount
                                @if ($order->coupon)
                                    <small>({{ $order->coupon->code }})</small>
                                @endif
                            </span>
                            <strong>-{{ number_format($order->sale->discount, 2) }}</strong>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-2">
                        <span>
                            Tax
                            @if ($order->sale->tax)
                                <small class="text-muted">
                                    Sale Tax
                                </small>
                            @endif
                        </span>
                        <strong>{{ number_format($order->sale->tax ?? 0, 2) }}</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fs-5">
                        <strong>Total</strong>
                        <strong>{{ number_format($order->sale->total_amount ?? 0, 2) }}</strong>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>
