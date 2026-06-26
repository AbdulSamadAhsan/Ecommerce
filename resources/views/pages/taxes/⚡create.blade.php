<?php

use Livewire\Component;
use App\Models\Tax;

new class extends Component {
    public $name = '';
    public $rate = '';
    public $type = 'percentage';
    public $status = 1;
    public $tax_category = 'sales';

    protected $rules = [
        'name' => 'required|min:2|max:255|unique:taxes,name',
        'rate' => 'required|numeric|min:0',
        'type' => 'required|string',
        'status' => 'required|boolean',
    ];

    public function save()
    {
        $this->validate();

        Tax::create([
            'name' => $this->name,
            'rate' => $this->rate,
            'type' => $this->type,
            'category' => $this->tax_category,
            'is_active' => $this->status,
        ]);

        $this->reset(['name', 'rate']);
        $this->type = 'percentage';
        $this->status = 1;

        session()->flash('success', 'Tax added successfully.');
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Add Tax</h4>
    </div>

    <div class="card-body">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form wire:submit="save">

            <div class="mb-3">
                <label class="form-label">Tax Name</label>
                <input type="text" wire:model.live="name" class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Rate</label>
                <input type="number" step="0.01" wire:model.live="rate"
                    class="form-control @error('rate') is-invalid @enderror">
                @error('rate')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Type</label>
                <select wire:model.live="type" class="form-select">
                    <option value="percentage">Percentage</option>
                    <option value="fixed">Fixed</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select wire:model.live="tax_category" class="form-select">
                    <option value="sales">Sales</option>
                    <option value="salary">salary</option>
                    <option value="product">Product</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label">Status</label>
                <select wire:model.live="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <button class="btn btn-primary rounded-pill">
                Save Tax
            </button>

        </form>
    </div>
</div>
