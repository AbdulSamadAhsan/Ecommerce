<?php

use Livewire\Component;
use App\Models\Institution;
use Illuminate\Validation\Rule;

new class extends Component {
    public int $id;

    public string $name = '';
    public string $type = '';
    public string $city = '';
    public string $country = '';
    public string $address = '';
    public int $status = 1;

    public function mount($id): void
    {
        $institution = Institution::findOrFail($id);

        $this->id = $institution->id;
        $this->name = $institution->name;
        $this->type = $institution->type ?? '';
        $this->city = $institution->city ?? '';
        $this->country = $institution->country ?? 'Pakistan';
        $this->address = $institution->address ?? '';
        $this->status = $institution->status;
    }

    public function update(): void
    {
        $this->validate([
            'name' => ['required', 'min:2', 'max:255', Rule::unique('institutions', 'name')->ignore($this->id)],
            'country' => 'required|max:255',
            'status' => 'required|boolean',
        ]);

        Institution::findOrFail($this->id)->update([
            'name' => $this->name,
            'type' => $this->type,
            'city' => $this->city,
            'country' => $this->country,
            'address' => $this->address,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Institution updated successfully.');

        $this->redirectRoute('institutions.index', navigate: true);
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Edit Institution</h4>
    </div>

    <div class="card-body">
        <form wire:submit.prevent="update">
            <div class="mb-3">
                <label class="form-label">Institution Name</label>
                <input type="text" wire:model.live="name" class="form-control">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Type</label>
                <select wire:model="type" class="form-select">
                    <option value="">Select Type</option>
                    <option value="school">School</option>
                    <option value="college">College</option>
                    <option value="university">University</option>
                    <option value="institute">Institute</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">City</label>
                <input type="text" wire:model="city" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Country</label>
                <input type="text" wire:model="country" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea wire:model="address" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>
                <select wire:model="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <button class="btn btn-primary rounded-pill">
                Update Institution
            </button>
        </form>
    </div>
</div>
