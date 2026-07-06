<?php

use Livewire\Component;
use App\Models\SalesReturn;

new class extends Component {
    public string $search = '';
    public $salesReturns;

    public function mount(): void
    {
        $this->loadSalesReturns();
    }

    public function updatedSearch(): void
    {
        $this->loadSalesReturns();
    }

    public function loadSalesReturns(): void
    {
        $this->salesReturns = SalesReturn::with(['order', 'customer'])
            ->when($this->search, function ($query) {
                $query
                    ->where('return_no', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->get();
    }

    public function delete($id): void
    {
        SalesReturn::findOrFail($id)->delete();

        $this->loadSalesReturns();

        session()->flash('success', 'Sales return deleted successfully.');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Sales Returns</h3>
            <p class="text-muted mb-0">Manage customer product returns</p>
        </div>

    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4" placeholder="Search return...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Return No</th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($salesReturns as $return)
                        <tr>
                            <td>#{{ $return['id'] }}</td>
                            <td>{{ $return['return_no'] }}</td>
                            <td>#{{ $return['order']['id'] ?? 'N/A' }}</td>
                            <td>{{ $return->order->sale->customer->user->name ?? 'Walk-in Customer' }}</td>
                            <td>Rs. {{ number_format($return['total_amount'], 2) }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst($return['status']) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('sales_return.show', $return['id']) }}"
                                    class="btn btn-sm btn-info text-white rounded-pill">
                                    View
                                </a>
                                @if (!in_array($return->status, ['approved', 'declined']))
                                    <a href="{{ route('sales_return.edit', $return['id']) }}"
                                        class="btn btn-sm btn-warning rounded-pill">
                                        Edit
                                    </a>
                                @endif

                                <button wire:click="delete({{ $return['id'] }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No sales returns found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
