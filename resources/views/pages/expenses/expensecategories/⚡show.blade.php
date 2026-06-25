<?php

use Livewire\Component;
use App\Models\ExpenseCategory;

new class extends Component {
    public ExpenseCategory $expenseCategory;

    public function mount($id): void
    {
        $this->expenseCategory = ExpenseCategory::withCount('expenses')->findOrFail($id);
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Expense Category Details</h3>
            <p class="text-muted mb-0">View expense category information</p>
        </div>

        <a href="{{ route('expense-categories.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $expenseCategory->name }}</h5>
        </div>

        <div class="card-body">

            <div class="mb-3">
                <strong>ID:</strong>
                #{{ $expenseCategory->id }}
            </div>

            <div class="mb-3">
                <strong>Name:</strong>
                {{ $expenseCategory->name }}
            </div>

            <div class="mb-3">
                <strong>Total Expenses:</strong>
                {{ $expenseCategory->expenses_count }}
            </div>

            <div class="mb-3">
                <strong>Status:</strong>
                <span class="badge {{ $expenseCategory->status ? 'bg-success' : 'bg-danger' }}">
                    {{ $expenseCategory->status ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="mb-3">
                <strong>Description:</strong>
                <div class="border rounded p-3 bg-light mt-2">
                    {{ $expenseCategory->description ?: 'No description available.' }}
                </div>
            </div>

        </div>
    </div>
</div>
