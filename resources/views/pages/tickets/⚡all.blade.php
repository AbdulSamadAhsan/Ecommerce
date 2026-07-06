<?php

use Livewire\Component;
use App\Models\CustomerSupportTicket;

new class extends Component {
    public string $search = '';
    public array $tickets = [];

    public function mount(): void
    {
        $this->loadTickets();
    }

    public function updatedSearch(): void
    {
        $this->loadTickets();
    }

    public function loadTickets(): void
    {
        $this->tickets = CustomerSupportTicket::with('customer')
            ->when($this->search, function ($query) {
                $query
                    ->where('ticket_no', 'like', '%' . $this->search . '%')
                    ->orWhere('subject', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%')
                    ->orWhere('priority', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->get()
            ->toArray();
    }

    public function delete($id): void
    {
        CustomerSupportTicket::findOrFail($id)->delete();

        $this->loadTickets();

        session()->flash('success', 'Ticket deleted successfully.');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Customer Support Tickets</h3>
            <p class="text-muted mb-0">Manage customer complaints and support requests</p>
        </div>


    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4" placeholder="Search ticket...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Ticket No</th>
                        <th>Customer</th>
                        <th>Subject</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>#{{ $ticket['id'] }}</td>
                            <td>{{ $ticket['ticket_no'] }}</td>
                            <td>{{ $ticket['customer']['name'] ?? 'Guest' }}</td>
                            <td>{{ $ticket['subject'] }}</td>

                            <td>
                                <span
                                    class="badge
                                    @if ($ticket['priority'] === 'urgent') bg-danger
                                    @elseif ($ticket['priority'] === 'high') bg-warning text-dark
                                    @elseif ($ticket['priority'] === 'medium') bg-info
                                    @else bg-secondary @endif">
                                    {{ ucfirst($ticket['priority']) }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="badge
                                    @if ($ticket['status'] === 'resolved') bg-success
                                    @elseif ($ticket['status'] === 'closed') bg-dark
                                    @elseif ($ticket['status'] === 'pending') bg-warning text-dark
                                    @else bg-primary @endif">
                                    {{ ucfirst($ticket['status']) }}
                                </span>
                            </td>

                            <td>{{ \Carbon\Carbon::parse($ticket['created_at'])->format('d M Y') }}</td>

                            <td>
                                <a href="{{ route('customer-support-tickets.show', $ticket['id']) }}"
                                    class="btn btn-sm btn-info text-white rounded-pill">
                                    View
                                </a>

                                <a href="{{ route('customer-support-tickets.edit', $ticket['id']) }}"
                                    class="btn btn-sm btn-warning rounded-pill">
                                    Reply/Edit
                                </a>

                                <button wire:click="delete({{ $ticket['id'] }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No tickets found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
