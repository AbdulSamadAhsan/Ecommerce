<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Supplier;
use App\Models\SupplierPayment;

new class extends Component {
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';

    public string $supplier = '';

    public string $payment_method = '';

    public string $from_date = '';

    public string $to_date = '';

    /*
    |--------------------------------------------------------------------------
    | Reset Pagination
    |--------------------------------------------------------------------------
    */

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSupplier()
    {
        $this->resetPage();
    }

    public function updatingPaymentMethod()
    {
        $this->resetPage();
    }

    public function updatingFromDate()
    {
        $this->resetPage();
    }

    public function updatingToDate()
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        $payment = SupplierPayment::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Restore Purchase Amount
        |--------------------------------------------------------------------------
        */

        $purchase = $payment->purchase;

        if ($purchase) {
            $purchase->paid_amount -= $payment->amount;

            if ($purchase->paid_amount < 0) {
                $purchase->paid_amount = 0;
            }

            $purchase->due_amount += $payment->amount;

            if ($purchase->paid_amount == 0) {
                $purchase->payment_status = 'pending';
            } elseif ($purchase->due_amount == 0) {
                $purchase->payment_status = 'paid';
            } else {
                $purchase->payment_status = 'partial';
            }

            $purchase->save();
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Expense
        |--------------------------------------------------------------------------
        */

        \App\Models\Expense::where('purchase_id', $payment->purchase_id)->where('amount', $payment->amount)->where('category', 'Supplier Payment')->delete();

        $payment->delete();

        session()->flash('success', 'Supplier payment deleted successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Clear Filters
    |--------------------------------------------------------------------------
    */

    public function clearFilters()
    {
        $this->reset(['search', 'supplier', 'payment_method', 'from_date', 'to_date']);
    }

    /*
    |--------------------------------------------------------------------------
    | With
    |--------------------------------------------------------------------------
    */

    public function with(): array
    {
        $query = SupplierPayment::query()

            ->with(['supplier', 'purchase'])

            ->when($this->search, function ($query) {
                $query

                    ->where('transaction_id', 'like', '%' . $this->search . '%')

                    ->orWhereHas('supplier', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })

                    ->orWhereHas('purchase', function ($q) {
                        $q->where('purchase_no', 'like', '%' . $this->search . '%');
                    });
            })

            ->when($this->supplier, function ($query) {
                $query->where('supplier_id', $this->supplier);
            })

            ->when($this->payment_method, function ($query) {
                $query->where('payment_method', $this->payment_method);
            })

            ->when($this->from_date, function ($query) {
                $query->whereDate('payment_date', '>=', $this->from_date);
            })

            ->when($this->to_date, function ($query) {
                $query->whereDate('payment_date', '<=', $this->to_date);
            })

            ->latest();

        return [
            /*
            |--------------------------------------------------------------------------
            | Table
            |--------------------------------------------------------------------------
            */

            'payments' => $query->paginate(15),

            /*
            |--------------------------------------------------------------------------
            | Filters
            |--------------------------------------------------------------------------
            */

            'suppliers' => Supplier::where('status', 1)->get(),

            /*
            |--------------------------------------------------------------------------
            | Dashboard Cards
            |--------------------------------------------------------------------------
            */

            'totalPayments' => (clone $query)->sum('amount'),

            'todayPayments' => SupplierPayment::whereDate('payment_date', today())->sum('amount'),

            'thisMonthPayments' => SupplierPayment::whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->sum('amount'),

            'cashPayments' => (clone $query)

                ->where('payment_method', 'cash')

                ->sum('amount'),

            'bankPayments' => (clone $query)

                ->where('payment_method', 'bank')

                ->sum('amount'),

            'cardPayments' => (clone $query)

                ->where('payment_method', 'card')

                ->sum('amount'),
        ];
    }
};
?>
<div>
    <div class="row">

        <div class="col-md-4 mb-3">

            <div class="card shadow border-0 bg-primary text-white">

                <div class="card-body">

                    <h6>Total Payments</h6>

                    <h3 class="fw-bold">

                        Rs {{ number_format($totalPayments, 2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="card shadow border-0 bg-success text-white">

                <div class="card-body">

                    <h6>Today's Payments</h6>

                    <h3 class="fw-bold">

                        Rs {{ number_format($todayPayments, 2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="card shadow border-0 bg-info text-white">

                <div class="card-body">

                    <h6>This Month</h6>

                    <h3 class="fw-bold">

                        Rs {{ number_format($thisMonthPayments, 2) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card shadow border-0">

                <div class="card-body">

                    <h6>Cash Payments</h6>

                    <h4 class="fw-bold text-success">

                        Rs {{ number_format($cashPayments, 2) }}

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow border-0">

                <div class="card-body">

                    <h6>Bank Payments</h6>

                    <h4 class="fw-bold text-primary">

                        Rs {{ number_format($bankPayments, 2) }}

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow border-0">

                <div class="card-body">

                    <h6>Card Payments</h6>

                    <h4 class="fw-bold text-warning">

                        Rs {{ number_format($cardPayments, 2) }}

                    </h4>

                </div>

            </div>

        </div>

    </div>

    <div class="card shadow border-0">

        <div class="card-header bg-primary text-white">

            <div class="d-flex justify-content-between align-items-center">

                <h4 class="mb-0">

                    Supplier Payments

                </h4>

                <div>

                    <a wire:navigate href="{{ route('suppliers.payment.create') }}" class="btn btn-light btn-sm">

                        <i class="bi bi-plus-circle"></i>

                        Add Payment

                    </a>

                </div>

            </div>

        </div>

        <div class="card-body">

            @if (session()->has('success'))
                <div class="alert alert-success">

                    {{ session('success') }}

                </div>
            @endif

            <div class="row mb-4">

                <div class="col-md-3">

                    <label class="form-label">

                        Search

                    </label>

                    <input type="text" wire:model.live.debounce.500ms="search" class="form-control"
                        placeholder="Invoice, Supplier...">

                </div>

                <div class="col-md-2">

                    <label class="form-label">

                        Supplier

                    </label>

                    <select wire:model.live="supplier" class="form-select">

                        <option value="">

                            All

                        </option>

                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">

                                {{ $supplier->user->name }}

                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label">

                        Method

                    </label>

                    <select wire:model.live="payment_method" class="form-select">

                        <option value="">All</option>

                        <option value="cash">Cash</option>

                        <option value="bank">Bank</option>

                        <option value="card">Card</option>

                        <option value="cheque">Cheque</option>

                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label">

                        From

                    </label>

                    <input type="date" wire:model.live="from_date" class="form-control">

                </div>

                <div class="col-md-2">

                    <label class="form-label">

                        To

                    </label>

                    <input type="date" wire:model.live="to_date" class="form-control">

                </div>

                <div class="col-md-1 d-flex align-items-end">

                    <button wire:click="clearFilters" class="btn btn-secondary w-100">

                        Clear

                    </button>

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Date</th>

                            <th>Supplier</th>

                            <th>Purchase</th>

                            <th>Method</th>

                            <th>Amount</th>

                            <th>Transaction</th>

                            <th width="220">

                                Action

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($payments as $payment)
                            <tr>

                                <td>

                                    {{ $payment->id }}

                                </td>

                                <td>

                                    {{ date('d M Y', strtotime($payment->payment_date)) }}

                                </td>

                                <td>

                                    {{ $payment->supplier->user->name }}

                                </td>

                                <td>

                                    {{ $payment->purchase->purchase_no }}

                                </td>

                                <td>

                                    <span class="badge bg-info">

                                        {{ ucfirst($payment->payment_method) }}

                                    </span>

                                </td>

                                <td>

                                    Rs {{ number_format($payment->amount, 2) }}

                                </td>

                                <td>

                                    {{ $payment->transaction_id ?: '-' }}

                                </td>

                                <td>

                                    <div class="btn-group">





                                        <button wire:click="delete({{ $payment->id }})"
                                            wire:confirm="Delete this payment?" class="btn btn-sm btn-danger">

                                            Delete

                                        </button>

                                    </div>

                                </td>

                            </tr>
                        @empty

                            <tr>

                                <td colspan="8" class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bi bi-credit-card display-4 d-block mb-3"></i>

                                        <h5 class="fw-bold">

                                            No supplier payments found.

                                        </h5>

                                        <p class="mb-0">

                                            Try changing the search criteria or create a new supplier payment.

                                        </p>

                                    </div>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                    <tfoot>

                        <tr class="table-light">

                            <th colspan="5" class="text-end">

                                Total

                            </th>

                            <th>

                                Rs {{ number_format($payments->sum('amount'), 2) }}

                            </th>

                            <th colspan="2"></th>

                        </tr>

                    </tfoot>

                </table>

            </div>

            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">

                <div>

                    <small class="text-muted">

                        Showing

                        {{ $payments->firstItem() ?? 0 }}

                        to

                        {{ $payments->lastItem() ?? 0 }}

                        of

                        {{ $payments->total() }}

                        payments

                    </small>

                </div>

                <div>

                    {{ $payments->links() }}

                </div>

            </div>

        </div>

    </div>

</div>
