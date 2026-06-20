<?php

use Livewire\Component;
use App\Models\Education;
use App\Models\Institution;

new class extends Component {
    public string $institution_id = '';
    public string $degree = '';
    public string $short_code = '';

    public int $status = 1;

    public $institutions = [];

    public function mount(): void
    {
        $this->institutions = Institution::where('status', 1)->get();
    }

    public function save(): void
    {
        $this->validate([
            'institution_id' => 'required|exists:institutions,id',
            'degree' => 'required|min:2|max:255',
            'short_code' => 'nullable|max:255',

            'status' => 'required|boolean',
        ]);

        Education::create([
            'institution_id' => $this->institution_id,
            'name' => $this->degree,
            'short_code' => $this->short_code,

            'status' => $this->status,
        ]);

        session()->flash('success', 'Education added successfully.');

        $this->redirectRoute('educations.index', navigate: true);
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Add Education</h4>
    </div>

    <div class="card-body">
        <form wire:submit.prevent="save">
            <div class="mb-3">
                <label class="form-label">Institution</label>
                <select wire:model.live="institution_id" class="form-select">
                    <option value="">Select Institution</option>
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}">
                            {{ $institution->name }}
                        </option>
                    @endforeach
                </select>
                @error('institution_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Degree Name</label>
                <input type="text" wire:model.live="degree" class="form-control">
                @error('degree')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Short Code</label>
                <input type="text" wire:model="short_code" class="form-control">
            </div>



            <div class="mb-4">
                <label class="form-label">Status</label>
                <select wire:model="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <button class="btn btn-primary rounded-pill">
                Save Education
            </button>
        </form>
    </div>
</div>
