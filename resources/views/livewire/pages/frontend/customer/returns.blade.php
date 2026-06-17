<?php

use Livewire\Component;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')]
class extends Component {
    public array $returns = [
        ['id' => 1, 'order_id' => 1001, 'item' => 'Wireless Mouse', 'reason' => 'Wrong item received', 'status' => 'Pending', 'date' => '2026-06-18'],
        ['id' => 2, 'order_id' => 998, 'item' => 'Headphones', 'reason' => 'Damaged product', 'status' => 'Approved', 'date' => '2026-06-12'],
    ];
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
                                @foreach ($returns as $return)
                                    <tr>
                                        <td>#{{ $return['id'] }}</td>
                                        <td>#{{ $return['order_id'] }}</td>
                                        <td>{{ $return['item'] }}</td>
                                        <td>{{ $return['reason'] }}</td>
                                        <td>
                                            <span class="badge {{ $return['status'] === 'Approved' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $return['status'] }}
                                            </span>
                                        </td>
                                        <td>{{ $return['date'] }}</td>
                                    </tr>
                                @endforeach
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
