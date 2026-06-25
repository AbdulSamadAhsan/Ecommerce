<?php

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Livewire\Component;

new class extends Component {
    public $expense_category_id = '';
    public $amount = '';
    public $expense_date = '';
    public $payment_method = 'cash';
    public $status = 'pending';
    public $description = '';

    public $expenseCategories = [];

    protected $rules = [
        'expense_category_id' => 'required|exists:expense_categories,id',
        'amount' => 'required|numeric|min:1',
        'expense_date' => 'required|date',
        'payment_method' => 'required|string|max:50',
        'status' => 'required|string|max:50',
        'description' => 'nullable|max:1000',
    ];

    public function mount()
    {
        $this->expense_date = now()->format('Y-m-d');
        $this->expenseCategories = ExpenseCategory::latest()->get();
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $this->validate();

        Expense::create([
            'expense_category_id' => $this->expense_category_id,
            'amount' => $this->amount,
            'expense_date' => $this->expense_date,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'description' => $this->description,
        ]);

        $this->reset(['expense_category_id', 'amount', 'description']);

        $this->expense_date = now()->format('Y-m-d');
        $this->payment_method = 'cash';
        $this->status = 'pending';

        session()->flash('success', 'Expense added successfully.');
    }
};
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add Expense</h4>
            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="save">

                    <div class="mb-3">
                        <label class="form-label">Expense Category</label>

                        <select class="form-select @error('expense_category_id') is-invalid @enderror"
                            wire:model.live="expense_category_id">
                            <option value="">Select Category</option>

                            @foreach ($expenseCategories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('expense_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount</label>

                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                            wire:model.live="amount" placeholder="Enter amount">

                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expense Date</label>

                        <input type="date" class="form-control @error('expense_date') is-invalid @enderror"
                            wire:model.live="expense_date">

                        @error('expense_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>

                        <select class="form-select @error('payment_method') is-invalid @enderror"
                            wire:model.live="payment_method">
                            <option value="cash">Cash</option>
                            <option value="bank">Bank</option>
                            <option value="easypaisa">EasyPaisa</option>
                            <option value="jazzcash">JazzCash</option>
                        </select>

                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>

                        <select class="form-select @error('status') is-invalid @enderror" wire:model.live="status">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="cancelled">Cancelled</option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Description</label>

                        <textarea class="form-control @error('description') is-invalid @enderror" rows="4" wire:model.live="description"
                            placeholder="Enter description"></textarea>

                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">
                                Save Expense
                            </span>

                            <span wire:loading wire:target="save">
                                Saving...
                            </span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
