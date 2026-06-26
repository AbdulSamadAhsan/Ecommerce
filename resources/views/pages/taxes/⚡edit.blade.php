<?php

use Livewire\Component;
use App\Models\Tax;
use Illuminate\Validation\Rule;

new class extends Component {
    public $taxId;
    public $name = '';
    public $rate = '';
    public $type = 'percentage';
    public $status = 1;

    public function mount($id)
    {
        $tax = Tax::findOrFail($id);

        $this->taxId = $tax->id;
        $this->name = $tax->name;
        $this->rate = $tax->rate;
        $this->type = $tax->type;
        $this->status = $tax->status;
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'min:2', 'max:255', Rule::unique('taxes', 'name')->ignore($this->taxId)],
            'rate' => 'required|numeric|min:0',
            'type' => 'required|string',
            'status' => 'required|boolean',
        ];
    }

    public function update()
    {
        $this->validate();

        Tax::findOrFail($this->taxId)->update([
            'name' => $this->name,
            'rate' => $this->rate,
            'type' => $this->type,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Tax updated successfully.');
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Edit Tax</h4>
    </div>

    <div class="card-body">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form wire:submit="update">

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

            <div class="mb-4">
                <label class="form-label">Status</label>
                <select wire:model.live="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('taxes.index') }}" class="btn btn-secondary rounded-pill">
                    Back
                </a>

                <button class="btn btn-warning rounded-pill">
                    Update Tax
                </button>
            </div>

        </form>
    </div>
</div>
