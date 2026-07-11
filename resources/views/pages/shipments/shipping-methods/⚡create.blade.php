<?php

use Livewire\Component;
use App\Models\ShippingMethod;

new class extends Component {
    public $name = '';
    public $cost = 0;
    public $estimated_days = '';
    public $description = '';
    public $status = 1;
    public $shipping_category = 'Express';
    protected $rules = [
        'name' => 'required|min:2|max:255|unique:shipping_methods,name',
        'cost' => 'required|numeric|min:0',
        'estimated_days' => 'nullable|integer|min:0',
        'description' => 'nullable|max:1000',
        'status' => 'required|boolean',
    ];

    public function save()
    {
        $this->validate();

        ShippingMethod::create([
            'name' => $this->name,
            'cost' => $this->cost,
            'estimated_days' => $this->estimated_days ?: null,
            'description' => $this->description,
            'is_active' => $this->status,
            'shipping_category' => $this->shipping_category,
        ]);

        session()->flash('success', 'Shipping method added successfully.');

        return $this->redirectRoute('shipping-methods.index', navigate: true);
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Add Shipping Method</h4>
    </div>

    <div class="card-body">
        <form wire:submit="save">

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" wire:model.live="name" class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Cost</label>
                <input type="number" step="0.01" wire:model.live="cost"
                    class="form-control @error('cost') is-invalid @enderror">
                @error('cost')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Estimated Days</label>
                <input type="number" wire:model.live="estimated_days"
                    class="form-control @error('estimated_days') is-invalid @enderror">
                @error('estimated_days')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea wire:model.live="description" rows="4" class="form-control"></textarea>
            </div>



            <div class="mb-4">
                <label class="form-label">Shipping Category</label>
                <select wire:model.live="shipping_category" class="form-select">
                    <option value="Express">Express</option>
                    <option value="NextDay">Inactive</option>
                    <option value="Urgent">Urgent</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label">Status</label>
                <select wire:model.live="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <button class="btn btn-primary rounded-pill">Save Shipping Method</button>

        </form>
    </div>
</div>
