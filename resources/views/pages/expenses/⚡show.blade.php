<?php

use Livewire\Component;
use App\Models\Expense;

new class extends Component {
    public Expense $expense;

    public function mount($id): void
    {
        $this->expense = Expense::with('expenseCategory')->findOrFail($id);
    }
};
?>

<div>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">Expense Details</h3>
            <p class="text-muted mb-0">
                View expense information
            </p>
        </div>

        <a href="{{ route('expenses.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                Expense #{{ $expense->id }}
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="fw-semibold text-muted">Expense Category</label>
                    <div class="fs-5">
                        {{ $expense->expenseCategory->name ?? 'N/A' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold text-muted">Amount</label>
                    <div class="fs-5 fw-bold text-success">
                        Rs. {{ number_format($expense->amount, 2) }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold text-muted">Expense Date</label>
                    <div>
                        {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold text-muted">Payment Method</label>
                    <div>
                        {{ ucfirst($expense->payment_method) }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold text-muted">Status</label>
                    <div>

                        <span
                            class="badge
                            @if ($expense->status == 'paid') bg-success
                            @elseif($expense->status == 'pending')
                                bg-warning text-dark
                            @else
                                bg-danger @endif">

                            {{ ucfirst($expense->status) }}

                        </span>

                    </div>
                </div>

                <div class="col-12">
                    <label class="fw-semibold text-muted">Description</label>

                    <div class="border rounded p-3 bg-light">
                        {{ $expense->description ?: 'No description available.' }}
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
