<?php

use Livewire\Component;
use App\Models\Expense;

new class extends Component {
    public string $search = '';

    public array $expenses = [];

    public function mount(): void
    {
        $this->loadExpenses();
    }

    public function updatedSearch(): void
    {
        $this->loadExpenses();
    }

    public function loadExpenses(): void
    {
        $this->expenses = Expense::with('expenseCategory')
            ->when($this->search, function ($query) {
                $query
                    ->whereHas('expenseCategory', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('payment_method', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get()
            ->toArray();
    }

    public function delete($id): void
    {
        Expense::findOrFail($id)->delete();

        $this->loadExpenses();

        session()->flash('success', 'Expense deleted successfully.');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Expenses</h3>
            <p class="text-muted mb-0">Manage all expenses</p>
        </div>

        <a href="{{ route('expenses.create') }}" class="btn btn-primary rounded-pill">
            Add Expense
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4" placeholder="Search expense...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Expense Date</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>#{{ $expense['id'] }}</td>

                            <td>
                                {{ $expense['expense_category']['name'] ?? 'N/A' }}
                            </td>

                            <td>
                                Rs. {{ number_format($expense['amount'], 2) }}
                            </td>

                            <td>
                                {{ $expense['expense_date'] }}
                            </td>

                            <td>
                                {{ ucfirst($expense['payment_method']) }}
                            </td>

                            <td>
                                <span
                                    class="badge 
                                    @if ($expense['status'] === 'paid') bg-success
                                    @elseif ($expense['status'] === 'pending') bg-warning text-dark
                                    @else bg-danger @endif">
                                    {{ ucfirst($expense['status']) }}
                                </span>
                            </td>

                            <td>
                                {{ $expense['description'] ?? '-' }}
                            </td>

                            <td>
                                <button wire:click="delete({{ $expense['id'] }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No expenses found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
