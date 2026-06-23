<?php

use Livewire\Component;
use App\Models\Purchase;

new class extends Component {
    public string $search = '';
    public array $purchases = [];

    public function mount(): void
    {
        $this->loadPurchases();
    }

    public function updatedSearch(): void
    {
        $this->loadPurchases();
    }

    public function loadPurchases(): void
    {
        $this->purchases = Purchase::query()
            ->when($this->search, function ($query) {
                $query->where('purchase_no', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get()
            ->toArray();
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Purchase History</h3>
            <p class="text-muted mb-0">Manage purchase records</p>
        </div>
    </div>

    <div class="dashboard-card">

        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search invoice number...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Invoice No</th>
                        <th>Purchase Date</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Due Amount</th>
                        <th>Payment Status</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td>#{{ $purchase['id'] }}</td>

                            <td>{{ $purchase['purchase_no'] }}</td>

                            <td>{{ $purchase['purchase_date'] }}</td>

                            <td>{{ number_format($purchase['total_amount'], 2) }}</td>

                            <td>{{ number_format($purchase['paid_amount'], 2) }}</td>

                            <td>{{ number_format($purchase['due_amount'], 2) }}</td>

                            <td>
                                <span
                                    class="badge
                                    @if ($purchase['payment_status'] == 'paid') bg-success
                                    @elseif($purchase['payment_status'] == 'partial') bg-warning text-dark
                                    @else bg-danger @endif">
                                    {{ ucfirst($purchase['payment_status']) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('purchases.show', $purchase['id']) }}"
                                    class="btn btn-sm btn-info rounded-pill text-white">
                                    View
                                </a>
                            </td </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No purchase history found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
