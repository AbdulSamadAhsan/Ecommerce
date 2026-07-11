<?php

use Livewire\Component;
use App\Models\ShippingMethod;
use Illuminate\Validation\Rule;

new class extends Component {
    public $shippingMethodId;
    public $name = '';
    public $cost = 0;
    public $estimated_days = '';
    public $description = '';
    public $status = 1;

    public function mount($id)
    {
        $method = ShippingMethod::findOrFail($id);

        $this->shippingMethodId = $method->id;
        $this->name = $method->name;
        $this->cost = $method->cost;
        $this->estimated_days = $method->estimated_days;
        $this->description = $method->description;
        $this->status = $method->is_active;
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'min:2', 'max:255'],
            'cost' => 'required|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:1',
            'description' => 'nullable|max:1000',
            'status' => 'required|boolean',
        ];
    }

    public function update()
    {
        $this->validate();

        ShippingMethod::findOrFail($this->shippingMethodId)->update([
            'name' => $this->name,
            'cost' => $this->cost,
            'estimated_days' => $this->estimated_days ?: null,
            'description' => $this->description,
            'is_active' => $this->status,
        ]);

        session()->flash('success', 'Shipping method updated successfully.');
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Edit Shipping Method</h4>
    </div>

    <div class="card-body">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form wire:submit="update">

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
                <label class="form-label">Status</label>
                <select wire:model.live="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('shipping-methods.index') }}" class="btn btn-secondary rounded-pill">
                    Back
                </a>

                <button class="btn btn-warning rounded-pill">
                    Update Shipping Method
                </button>
            </div>

        </form>
    </div>
</div>
