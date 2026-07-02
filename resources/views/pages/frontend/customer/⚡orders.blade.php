<?php

use Livewire\Component;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public $orders;

    public function mount()
    {
        $this->orders = auth('customer')->user()->customer->sales;
    }
};
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">Order History</h2>

    <div class="row g-4">
        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0">My Orders</h4>
                        <span class="badge bg-primary rounded-pill">{{ count($orders) }} Orders</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="fw-semibold">#{{ $order->orderNumber->id }}</td>
                                        <td>{{ date('d-F-Y', strtotime($order->orderNumber->order_date)) }}</td>
                                        <td>Rs {{ number_format($order['total_amount']) }}</td>
                                        <td><span
                                                class="badge bg-success">{{ ucfirst($order->orderNumber->shipment->status) }}</span>
                                        </td>
                                        <td>{{ $order->payment_status }}</td>
                                        <td>
                                            <a wire:navigate
                                                href="{{ route('customer.order.detail', $order->orderNumber->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill">
                                                View Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
