<?php

use Livewire\Component;

use App\Models\WalletTopupRequest;
new class extends Component {
    public string $search = '';
    public $requests = [];

    public function mount(): void
    {
        $this->loadRequests();
    }

    public function updatedSearch(): void
    {
        $this->loadRequests();
    }

    public function loadRequests(): void
    {
        $this->requests = WalletTopupRequest::with('customer')
            ->when($this->search, function ($query) {
                $query
                    ->where('status', 'like', '%' . $this->search . '%')
                    ->orWhere('payment_method', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->get();
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Wallet Top-up Requests</h3>
            <p class="text-muted mb-0">Manage customer wallet requests</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4" placeholder="Search request...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Reference No</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($requests as $request)
                        <tr>
                            <td>#{{ $request['id'] }}</td>
                            <td>{{ $request->customer->user->name }}</td>
                            <td>Rs. {{ number_format($request['amount'], 2) }}</td>
                            <td>{{ ucfirst($request['payment_method']) }}</td>
                            <td>{{ $request['reference_no'] ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge 
                                    @if ($request['status'] === 'approved') bg-success
                                    @elseif($request['status'] === 'rejected') bg-danger
                                    @else bg-warning text-dark @endif">
                                    {{ ucfirst($request['status']) }}
                                </span>
                            </td>
                            <td>
                                @if ($request['status'] == 'pending')
                                    <a wire:navigate href="{{ route('wallet-topups.edit', $request['id']) }}"
                                        class="btn btn-sm btn-primary rounded-pill">
                                        Edit
                                    </a>
                                @else
                                    <span class="text-muted">Completed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No wallet requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
