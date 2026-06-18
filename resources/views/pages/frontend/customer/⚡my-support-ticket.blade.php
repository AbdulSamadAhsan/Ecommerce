<?php

use Livewire\Component;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public array $tickets = [
        [
            'ticket_no' => 'TK-1001',
            'order_no' => '1001',
            'subject' => 'Product Damaged',
            'priority' => 'high',
            'status' => 'open',
            'created_at' => '2026-06-18',
        ],
        [
            'ticket_no' => 'TK-1002',
            'order_no' => '1002',
            'subject' => 'Late Delivery',
            'priority' => 'medium',
            'status' => 'in_progress',
            'created_at' => '2026-06-17',
        ],
        [
            'ticket_no' => 'TK-1003',
            'order_no' => '1003',
            'subject' => 'Refund Request',
            'priority' => 'low',
            'status' => 'resolved',
            'created_at' => '2026-06-15',
        ],
    ];
};
?>

<div class="container py-5">

    <h2 class="fw-bold mb-4">
        My Support Tickets
    </h2>

    <div class="row g-4">

        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>
                                <tr>
                                    <th>Ticket #</th>
                                    <th>Order #</th>
                                    <th>Subject</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($tickets as $ticket)
                                    <tr>

                                        <td>{{ $ticket['ticket_no'] }}</td>

                                        <td>#{{ $ticket['order_no'] }}</td>

                                        <td>{{ $ticket['subject'] }}</td>

                                        <td>

                                            <span class="badge bg-warning text-dark">
                                                {{ ucfirst($ticket['priority']) }}
                                            </span>

                                        </td>

                                        <td>

                                            @if ($ticket['status'] === 'open')
                                                <span class="badge bg-danger">
                                                    Open
                                                </span>
                                            @elseif($ticket['status'] === 'in_progress')
                                                <span class="badge bg-primary">
                                                    In Progress
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    Resolved
                                                </span>
                                            @endif

                                        </td>

                                        <td>{{ $ticket['created_at'] }}</td>

                                        <td>

                                            <a href="{{ route('customer.ticket.detail', $ticket['ticket_no']) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill">
                                                View
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
