<?php

use Livewire\Component;
use App\Models\Order;

new class extends Component {
    public Order $order;

    public function mount($id): void
    {
        $this->order = Order::with(['customer.user', 'sale.customer.user', 'sale.items.product', 'shipment.shippingMethod', 'shipment.deliveryassign.deliveryBoy.user'])->findOrFail($id);
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Order Details</h3>
            <p class="text-muted mb-0">View full order information</p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('order.invoice.report', $order->id) }}" class="btn btn-danger rounded-pill">
                Download Report
            </a>

            <a href="{{ route('orders.index') }}" class="btn btn-secondary rounded-pill">
                Back
            </a>
        </div>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Order #{{ $order->id }}</h5>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <strong>Invoice No:</strong>
                    {{ $order->sale->invoice_no ?? '-' }}
                </div>

                <div class="col-md-4">
                    <strong>Order Date:</strong>
                    {{ $order->order_date ? date('d M Y', strtotime($order->order_date)) : $order->created_at->format('d M Y') }}
                </div>

                <div class="col-md-4">
                    <strong>Tracking No:</strong>
                    {{ $order->shipment->tracking_number ?? 'No tracking number provided' }}
                </div>

                <div class="col-md-4">
                    <strong>Order Status:</strong>
                    <span class="badge bg-info rounded-pill">
                        {{ ucfirst(str_replace('_', ' ', $order->shipment->status ?? 'pending')) }}
                    </span>
                </div>

                <div class="col-md-4">
                    <strong>Payment Status:</strong>
                    <span class="badge bg-secondary rounded-pill">
                        {{ ucfirst($order->sale->payment_status ?? 'pending') }}
                    </span>
                </div>

                <div class="col-md-4">
                    <strong>Payment Method:</strong>
                    {{ ucfirst($order->sale->payment_method ?? '-') }}
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Customer Information</h5>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <strong>Name:</strong>
                    {{ $order->sale->customer->user->name ?? ($order->customer->user->name ?? 'Walk-in Customer') }}
                </div>

                <div class="col-md-6">
                    <strong>Email:</strong>
                    {{ $order->sale->customer->user->email ?? ($order->customer->user->email ?? '-') }}
                </div>

                <div class="col-md-6">
                    <strong>Phone:</strong>
                    {{ $order->sale->customer->phone ?? ($order->customer->phone ?? '-') }}
                </div>

                <div class="col-md-6">
                    <strong>City:</strong>
                    {{ $order->city ?? '-' }}
                </div>

                <div class="col-md-12">
                    <strong>Shipping Address:</strong>
                    {{ $order->address ?? '-' }}
                </div>
            </div>
        </div>
    </div>
    @if ($order->shipment)
        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Shipment Information</h5>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <strong>Shipping Method:</strong>
                        {{ $order->shipment->shippingMethod->name ?? '-' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Tracking Number:</strong>
                        {{ $order->shipment->tracking_number ?? 'Shippment Not Assigned Yet' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <span class="badge bg-info rounded-pill">
                            {{ ucfirst(str_replace('_', ' ', $order->shipment->status ?? 'pending')) }}
                        </span>
                    </div>

                    <div class="col-md-6">
                        <strong>Expected Delivery:</strong>
                        {{ $order?->shipment?->expected_delivery ? date('d M Y h:i A', strtotime($order->shipment->expected_delivery)) : '-' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Packed At:</strong>
                        {{ $order?->shipment?->packed_at ? date('d M Y h:i A', strtotime($order->shipment->packed_at)) : '-' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Shipped At:</strong>
                        {{ $order?->shipment?->shipped_at ? date('d M Y h:i A', strtotime($order->shipment->shipped_at)) : '-' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Delivered At:</strong>
                        {{ $order?->shipment?->delivered_at ? date('d M Y h:i A', strtotime($order->shipment->delivered_at)) : '-' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Cancelled At:</strong>
                        {{ $order?->shipment?->cancelled_at ? date('d M Y h:i A', strtotime($order->shipment->cancelled_at)) : '-' }}
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($order->shipment && $order->shipment->deliveryassign)
        @php
            $assignment = $order?->shipment?->deliveryassign;
            $deliveryBoy = $assignment->deliveryBoy;
        @endphp

        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Assigned Delivery Boy</h5>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <strong>Name:</strong>
                        {{ $deliveryBoy->user->name ?? 'N/A' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Email:</strong>
                        {{ $deliveryBoy->user->email ?? 'N/A' }}
                    </div>

                    <div class="col-md-6">
                        <strong>CNIC:</strong>
                        {{ $deliveryBoy->cnic ?? '-' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Vehicle Type:</strong>
                        {{ ucfirst($deliveryBoy->vehicle_type ?? '-') }}
                    </div>

                    <div class="col-md-6">
                        <strong>Vehicle Number:</strong>
                        {{ $deliveryBoy->vehicle_number ?? '-' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Assigned At:</strong>
                        {{ $assignment->assigned_at ? date('d M Y h:i A', strtotime($assignment->assigned_at)) : '-' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Picked At:</strong>
                        {{ $assignment->picked_at ? date('d M Y h:i A', strtotime($assignment->picked_at)) : '-' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Delivered At:</strong>
                        {{ $assignment->delivered_at ? date('d M Y h:i A', strtotime($assignment->delivered_at)) : '-' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Assignment Status:</strong>
                        <span class="badge bg-info rounded-pill">
                            {{ ucfirst(str_replace('_', ' ', $assignment->status ?? 'assigned')) }}
                        </span>
                    </div>

                    @if ($assignment->failed_reason)
                        <div class="col-md-12">
                            <strong>Failed Reason:</strong>
                            {{ $assignment->failed_reason }}
                        </div>
                    @endif

                    @if ($assignment->remarks)
                        <div class="col-md-12">
                            <strong>Remarks:</strong>
                            {{ $assignment->remarks }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-light d-flex justify-content-between">
            <h5 class="mb-0">Order Items</h5>
            <span class="badge bg-primary rounded-pill">
                {{ $order->sale->items->count() }} Items /
                Qty {{ $order->sale->items->sum('quantity') }}
            </span>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($order->sale->items as $item)
                        <tr>
                            <td>#{{ $item->id }}</td>
                            <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                            <td>Rs. {{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rs. {{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No items found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Payment Summary</h5>
        </div>

        <div class="card-body">
            <table class="table align-middle mb-0">
                <tbody>
                    <tr>
                        <th>Subtotal</th>
                        <td class="text-end">Rs. {{ number_format($order->sale->subtotal ?? 0, 2) }}</td>
                    </tr>

                    <tr>
                        <th>Discount</th>
                        <td class="text-end text-success">Rs. {{ number_format($order->sale->discount ?? 0, 2) }}</td>
                    </tr>

                    <tr>
                        <th>Tax</th>
                        <td class="text-end">Rs. {{ number_format($order->sale->tax ?? 0, 2) }}</td>
                    </tr>

                    <tr>
                        <th>Shipping</th>
                        <td class="text-end">Rs. {{ number_format($order->sale->shipping_cost ?? 0, 2) }}</td>
                    </tr>

                    <tr>
                        <th>Paid Amount</th>
                        <td class="text-end">Rs. {{ number_format($order->sale->paid_amount ?? 0, 2) }}</td>
                    </tr>

                    <tr>
                        <th>Due Amount</th>
                        <td class="text-end text-danger">Rs. {{ number_format($order->sale->due_amount ?? 0, 2) }}</td>
                    </tr>

                    <tr class="table-light">
                        <th>Grand Total</th>
                        <th class="text-end">Rs. {{ number_format($order->sale->total_amount ?? 0, 2) }}</th>
                    </tr>
                </tbody>
            </table>

            <p class="mt-4 mb-2"><strong>Notes:</strong></p>
            <div class="border rounded p-3 bg-light">
                {{ $order->notes ?: 'No notes available.' }}
            </div>
            @if ($order?->shipment?->status == 'cancelled')
                <p class="mt-4 mb-2"><strong>Cancellation Reason:</strong></p>
                <div class="border rounded p-3 bg-light">
                    {{ $order->cancellation_reason ?: 'No Cancellation Reason.' }}
                </div>
            @endif
        </div>
    </div>
</div>
