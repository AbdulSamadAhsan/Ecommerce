<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $payment_status = '';
    public $status = '';
    public $from_date = '';
    public $to_date = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'payment_status', 'status', 'from_date', 'to_date']);

        $this->resetPage();
    }

    public function delete($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        session()->flash('success', 'Sale deleted successfully.');
    }

    public function with()
    {
        return [
            'sales' => Sale::query()
                ->with(['customer', 'items.product'])
                ->when($this->search, function ($query) {
                    $query->where('invoice_no', 'like', '%' . $this->search . '%')->orWhereHas('customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')->orWhere('phone', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->payment_status, function ($query) {
                    $query->where('payment_status', $this->payment_status);
                })
                ->when($this->status, function ($query) {
                    $query->where('status', $this->status);
                })
                ->when($this->from_date, function ($query) {
                    $query->whereDate('created_at', '>=', $this->from_date);
                })
                ->when($this->to_date, function ($query) {
                    $query->whereDate('created_at', '<=', $this->to_date);
                })
                ->latest()
                ->paginate(10),
        ];
    }
};
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Sales History</h4>
            <p class="text-muted mb-0">View all sales records and invoices</p>
        </div>

        <a href="{{ route('sales.create') }}" class="btn btn-primary">
            Create Sale
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" wire:model.live="search" class="form-control"
                        placeholder="Invoice, customer, phone">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Payment</label>
                    <select wire:model.live="payment_status" class="form-control">
                        <option value="">All</option>
                        <option value="paid">Paid</option>
                        <option value="partial">Partial</option>
                        <option value="unpaid">Unpaid</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select wire:model.live="status" class="form-control">
                        <option value="">All</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">From</label>
                    <input type="date" wire:model.live="from_date" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label">To</label>
                    <input type="date" wire:model.live="to_date" class="form-control">
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" wire:click="resetFilters" class="btn btn-secondary w-100">
                        Reset
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">All Sales</h5>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Total Items</th>
                        <th>Total Amount</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th width="140">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($sales as $sale)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                <strong>{{ $sale->invoice_no ?? 'INV-' . $sale->id }}</strong>
                            </td>

                            <td>
                                {{ $sale->customer->name ?? ($sale->customer->user->name ?? 'Walk-in Customer') }}
                                <br>
                                <small class="text-muted">
                                    {{ $sale->customer->phone ?? '' }}
                                </small>
                            </td>

                            <td>
                                {{ $sale->items->sum('quantity') }}
                            </td>

                            <td>
                                Rs. {{ number_format($sale->total_amount ?? ($sale->total ?? 0), 2) }}
                            </td>

                            <td>
                                Rs. {{ number_format($sale->paid_amount ?? 0, 2) }}
                            </td>

                            <td>
                                Rs. {{ number_format($sale->due_amount ?? 0, 2) }}
                            </td>

                            <td>
                                @if (($sale->payment_status ?? '') === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif (($sale->payment_status ?? '') === 'partial')
                                    <span class="badge bg-warning text-dark">Partial</span>
                                @else
                                    <span class="badge bg-danger">Unpaid</span>
                                @endif
                            </td>

                            <td>
                                @if (($sale->status ?? '') === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif (($sale->status ?? '') === 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>

                            <td>
                                {{ $sale->created_at?->format('d M Y') }}
                            </td>

                            <td>
                                <button type="button" wire:click="delete({{ $sale->id }})"
                                    wire:confirm="Are you sure?" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                No sales found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $sales->links() }}
            </div>

        </div>
    </div>

</div>
