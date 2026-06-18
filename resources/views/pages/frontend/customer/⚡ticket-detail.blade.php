<?php

use Livewire\Component;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public string $ticketNo;
    public string $reply = '';

    public array $ticket = [];

    public array $messages = [];

    public function mount($ticketNo): void
    {
        $this->ticketNo = $ticketNo;

        $this->ticket = [
            'ticket_no' => $ticketNo,
            'order_no' => '1001',
            'subject' => 'Product Damaged',
            'priority' => 'high',
            'status' => 'open',
            'created_at' => '2026-06-18',
        ];

        $this->messages = [
            [
                'sender' => 'customer',
                'name' => 'Customer',
                'message' => 'My product arrived damaged. Please help.',
                'time' => '2026-06-18 10:30 AM',
            ],
            [
                'sender' => 'support',
                'name' => 'Support Team',
                'message' => 'Please share product pictures and order details.',
                'time' => '2026-06-18 11:00 AM',
            ],
        ];
    }

    public function sendReply(): void
    {
        $this->validate([
            'reply' => 'required|min:3',
        ]);

        $this->messages[] = [
            'sender' => 'customer',
            'name' => 'Customer',
            'message' => $this->reply,
            'time' => now()->format('Y-m-d h:i A'),
        ];

        $this->reply = '';

        session()->flash('success', 'Reply sent successfully.');
    }
};
?>

<div class="container py-5">

    <div class="row g-4">

        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">

            <a wire:navigate href="{{ route('customer.my.support.tickets') }}" class="btn btn-light rounded-pill mb-4">
                <i class="bi bi-arrow-left"></i>
                Back to Tickets
            </a>

            @if (session('success'))
                <div class="alert alert-success rounded-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">

                    <div class="d-flex justify-content-between flex-wrap gap-3">
                        <div>
                            <h3 class="fw-bold mb-1">
                                Ticket {{ $ticket['ticket_no'] }}
                            </h3>

                            <p class="text-muted mb-0">
                                Order #{{ $ticket['order_no'] }} —
                                {{ $ticket['subject'] }}
                            </p>
                        </div>

                        <div>
                            <span class="badge bg-warning text-dark">
                                {{ ucfirst($ticket['priority']) }}
                            </span>

                            <span class="badge bg-danger">
                                {{ ucfirst($ticket['status']) }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">

                    <h4 class="fw-bold mb-4">
                        Conversation
                    </h4>

                    @foreach ($messages as $message)
                        <div class="mb-4 {{ $message['sender'] === 'customer' ? 'text-end' : '' }}">
                            <div class="d-inline-block p-3 rounded-4 shadow-sm
                                {{ $message['sender'] === 'customer' ? 'bg-primary text-white' : 'bg-light' }}"
                                style="max-width: 75%;">

                                <div class="fw-bold mb-1">
                                    {{ $message['name'] }}
                                </div>

                                <div>
                                    {{ $message['message'] }}
                                </div>

                                <small
                                    class="{{ $message['sender'] === 'customer' ? 'text-white-50' : 'text-muted' }}">
                                    {{ $message['time'] }}
                                </small>

                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">

                    <h4 class="fw-bold mb-3">
                        Send Reply
                    </h4>

                    <form wire:submit.prevent="sendReply">

                        <textarea wire:model="reply" rows="4" class="form-control rounded-4 mb-2" placeholder="Type your message..."></textarea>

                        @error('reply')
                            <small class="text-danger d-block mb-2">
                                {{ $message }}
                            </small>
                        @enderror

                        <button class="btn btn-primary rounded-pill px-4">
                            Send Reply
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>

</div>
