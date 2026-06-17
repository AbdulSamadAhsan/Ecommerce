<?php

use Livewire\Component;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')]
class extends Component {
    public array $orders = [
        ['id' => 1001, 'date' => '2026-06-18', 'total' => 185000, 'status' => 'Delivered', 'payment' => 'Paid'],
        ['id' => 1002, 'date' => '2026-06-17', 'total' => 45000, 'status' => 'Processing', 'payment' => 'Pending'],
        ['id' => 1003, 'date' => '2026-06-15', 'total' => 78000, 'status' => 'Shipped', 'payment' => 'Paid'],
    ];
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
                                        <td class="fw-semibold">#{{ $order['id'] }}</td>
                                        <td>{{ $order['date'] }}</td>
                                        <td>Rs {{ number_format($order['total']) }}</td>
                                        <td><span class="badge bg-success">{{ $order['status'] }}</span></td>
                                        <td>{{ $order['payment'] }}</td>
                                        <td>
                                            <a wire:navigate href="{{ route('customer.order.detail', $order['id']) }}" class="btn btn-sm btn-outline-primary rounded-pill">
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
