<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PurchaseReturn;

new class extends Component {
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $status = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $purchaseReturn = PurchaseReturn::findOrFail($id);

        if ($purchaseReturn->status === 'approved') {
            session()->flash('error', 'Approved purchase return cannot be deleted.');
            return;
        }

        $purchaseReturn->delete();

        session()->flash('success', 'Purchase return deleted successfully.');
    }

    public function with(): array
    {
        return [
            'purchaseReturns' => PurchaseReturn::with(['purchase', 'supplier', 'warehouse'])
                ->when($this->search, function ($query) {
                    $query->where('return_no', 'like', '%' . $this->search . '%')->orWhereHas('supplier', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->status, fn($query) => $query->where('status', $this->status))
                ->latest()
                ->paginate(10),
        ];
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Purchase Returns</h3>
            <p class="text-muted mb-0">Manage all purchase returns</p>
        </div>

        <a wire:navigate href="{{ route('purchases.returns.create') }}" class="btn btn-primary rounded-pill">
            Add Purchase Return
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="dashboard-card">
        <div class="row mb-4">
            <div class="col-md-6 mb-2">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control rounded-4"
                    placeholder="Search return no or supplier...">
            </div>

            <div class="col-md-3 mb-2">
                <select wire:model.live="status" class="form-select rounded-4">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Return No</th>
                        <th>Purchase</th>
                        <th>Supplier</th>
                        <th>Warehouse</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th width="230">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($purchaseReturns as $return)
                        <tr>
                            <td>#{{ $return->id }}</td>
                            <td class="fw-semibold">{{ $return->return_no }}</td>
                            <td>#{{ $return->purchase_id }}</td>
                            <td>{{ $return->supplier->user->name ?? 'N/A' }}</td>
                            <td>{{ $return->warehouse->name ?? 'N/A' }}</td>
                            <td>Rs {{ number_format($return->total_amount, 2) }}</td>
                            <td>
                                <span
                                    class="badge rounded-pill
                                    @if ($return->status === 'approved') bg-success
                                    @elseif($return->status === 'rejected') bg-danger
                                    @else bg-warning text-dark @endif">
                                    {{ ucfirst($return->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a wire:navigate href="{{ route('purchases.returns.show', $return->id) }}"
                                        class="btn btn-sm btn-secondary rounded-pill">
                                        View
                                    </a>

                                    @if ($return->status !== 'approved')
                                        <a wire:navigate href="{{ route('purchases.returns.edit', $return->id) }}"
                                            class="btn btn-sm btn-primary rounded-pill">
                                            Edit
                                        </a>

                                        <button wire:click="delete({{ $return->id }})" wire:confirm="Are you sure?"
                                            class="btn btn-sm btn-danger rounded-pill">
                                            Delete
                                        </button>
                                    @else
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                No purchase returns found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $purchaseReturns->links() }}
        </div>
    </div>
</div>
