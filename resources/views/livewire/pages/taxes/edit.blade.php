<?php

use App\Models\Tax;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component {
    public int $taxId;
    public string $name = '';
    public string $type = 'percentage';
    public string $rate = '';
    public string $description = '';
    public bool $status = true;

    public function mount(int $id): void
    {
        $tax = Tax::findOrFail($id);
        $this->taxId = $tax->id;
        $this->name = $tax->name;
        $this->type = $tax->type;
        $this->rate = (string) $tax->rate;
        $this->description = (string) $tax->description;
        $this->status = (bool) $tax->status;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255', 'unique:taxes,name,' . $this->taxId],
            'type' => ['required', 'in:percentage,fixed'],
            'rate' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'boolean'],
        ];
    }

    public function update()
    {
        $validated = $this->validate();

        if ($validated['type'] === 'percentage' && (float) $validated['rate'] > 100) {
            $this->addError('rate', 'Percentage tax cannot be greater than 100%.');
            return null;
        }

        Tax::findOrFail($this->taxId)->update($validated);

        session()->flash('success', 'Tax updated successfully.');

        return redirect()->route('taxes.index');
    }
};

?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Edit Tax</h3>
            <p class="text-muted mb-0">Update existing tax rule.</p>
        </div>
        <a href="{{ route('taxes.index') }}" class="btn btn-light rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form wire:submit.prevent="update">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tax Name</label>
                        <input type="text" wire:model.live="name" class="form-control rounded-pill">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Type</label>
                        <select wire:model.live="type" class="form-select rounded-pill">
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed</option>
                        </select>
                        @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Rate</label>
                        <input type="number" step="0.01" wire:model.live="rate" class="form-control rounded-pill">
                        @error('rate') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea wire:model.live="description" class="form-control rounded-4" rows="4"></textarea>
                        @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" wire:model.live="status" id="taxStatus">
                            <label class="form-check-label fw-semibold" for="taxStatus">Active</label>
                        </div>
                        @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('taxes.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <span wire:loading.remove wire:target="update">Update Tax</span>
                        <span wire:loading wire:target="update">Updating...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
