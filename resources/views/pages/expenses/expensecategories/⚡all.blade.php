<?php

use Livewire\Component;
use App\Models\ExpenseCategory;

new class extends Component {
    public string $search = '';

    public array $expenseCategories = [];

    public function mount(): void
    {
        $this->loadExpenseCategories();
    }

    public function updatedSearch(): void
    {
        $this->loadExpenseCategories();
    }

    public function loadExpenseCategories(): void
    {
        $this->expenseCategories = ExpenseCategory::withCount('expenses')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get()
            ->toArray();
    }

    public function delete($id): void
    {
        ExpenseCategory::findOrFail($id)->delete();

        $this->loadExpenseCategories();

        session()->flash('success', 'Expense category deleted successfully.');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Expense Categories</h3>
            <p class="text-muted mb-0">Manage expense categories</p>
        </div>

        <a href="{{ route('expense-categories.create') }}" class="btn btn-primary rounded-pill">
            Add Expense Category
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search expense category...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Expenses</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($expenseCategories as $category)
                        <tr>
                            <td>#{{ $category['id'] }}</td>

                            <td>{{ $category['name'] }}</td>



                            <td>
                                <a href="{{ route('expense-categories.show', $category['id']) }}"
                                    class="btn btn-sm btn-info text-white rounded-pill">
                                    View
                                </a>

                                <a href="{{ route('expense-categories.edit', $category['id']) }}"
                                    class="btn btn-sm btn-warning rounded-pill">
                                    Edit
                                </a>

                                <button wire:click="delete({{ $category['id'] }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No expense categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
