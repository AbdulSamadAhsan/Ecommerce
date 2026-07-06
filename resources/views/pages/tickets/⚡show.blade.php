<?php

use Livewire\Component;
use App\Models\CustomerSupportTicket;

new class extends Component {
    public CustomerSupportTicket $ticket;

    public function mount($id)
    {
        $this->ticket = CustomerSupportTicket::with('customer')->findOrFail($id);
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Ticket Details</h3>

        <a href="{{ route('customer-support-tickets.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $ticket->ticket_no }}</h5>
        </div>

        <div class="card-body">
            <p><strong>Customer:</strong> {{ $ticket->customer->name ?? 'Guest' }}</p>
            <p><strong>Subject:</strong> {{ $ticket->subject }}</p>

            <p>
                <strong>Priority:</strong>
                <span class="badge bg-info">{{ ucfirst($ticket->priority) }}</span>
            </p>

            <p>
                <strong>Status:</strong>
                <span class="badge bg-primary">{{ ucfirst($ticket->status) }}</span>
            </p>

            <p><strong>Message:</strong></p>
            <div class="border rounded p-3 bg-light mb-3">
                {{ $ticket->message }}
            </div>

            <p><strong>Admin Reply:</strong></p>
            <div class="border rounded p-3 bg-light">
                {{ $ticket->admin_reply ?: 'No reply yet.' }}
            </div>

            @if ($ticket->resolved_at)
                <p class="mt-3">
                    <strong>Resolved At:</strong>
                    {{ \Carbon\Carbon::parse($ticket->resolved_at)->format('d M Y h:i A') }}
                </p>
            @endif
        </div>
    </div>
</div>
