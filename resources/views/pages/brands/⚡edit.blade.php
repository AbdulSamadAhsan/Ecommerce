<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

new class extends Component {
    use WithFileUploads;

    public $brandId;

    public $title = '';
    public $description = '';
    public $status = 1;

    public $logo;
    public $oldLogo;

    public function mount($id): void
    {
        $brand = Brand::findOrFail($id);

        $this->brandId = $brand->id;
        $this->title = $brand->title;
        $this->description = $brand->description;
        $this->status = (string) $brand->status;
        $this->oldLogo = $brand->logo;
    }

    protected function rules()
    {
        return [
            'title' => ['required', 'min:2', 'max:255', Rule::unique('brands', 'title')->ignore($this->brandId)],
            'description' => 'nullable|max:1000',
            'status' => 'required|boolean',
            'logo' => 'nullable|image|max:2048',
        ];
    }

    protected $messages = [
        'title.required' => 'Brand title is required.',
        'title.min' => 'Brand title must contain at least 2 characters.',
        'title.unique' => 'Brand already exists.',
        'logo.image' => 'Logo must be an image.',
        'logo.max' => 'Logo must not be greater than 2MB.',
    ];

    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    public function update()
    {
        $this->validate();

        $brand = Brand::findOrFail($this->brandId);

        $logoPath = $this->oldLogo;

        if ($this->logo) {
            if ($this->oldLogo && Storage::disk('public')->exists($this->oldLogo)) {
                Storage::disk('public')->delete($this->oldLogo);
            }

            $fileName = Str::slug($this->title) . '-' . time() . '.' . $this->logo->getClientOriginalExtension();
            $logoPath = $this->logo->storeAs('brands', $fileName, 'public');
        }

        $brand->update([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'logo' => $logoPath,
        ]);

        session()->flash('success', 'Brand updated successfully.');

        return $this->redirectRoute('brands.index', navigate: true);
    }
};
?>

<div class="row">
    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-warning">
                <h4 class="mb-0">Edit Brand</h4>
            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="update">

                    <div class="mb-3">
                        <label class="form-label">Brand Title</label>

                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                            wire:model.live="title" placeholder="Enter brand title">

                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>

                        <textarea rows="4" class="form-control @error('description') is-invalid @enderror" wire:model.live="description"
                            placeholder="Enter brand description"></textarea>

                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Current Logo</label>

                            <div>
                                @if ($oldLogo)
                                    <img src="{{ asset('storage/' . $oldLogo) }}" width="150" class="img-thumbnail">
                                @else
                                    <p class="text-muted mb-0">No logo uploaded.</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">New Logo</label>

                            <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                wire:model="logo">

                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div wire:loading wire:target="logo" class="mt-2 text-primary">
                                Uploading logo...
                            </div>

                            @if ($logo)
                                <div class="mt-3">
                                    <img src="{{ $logo->temporaryUrl() }}" width="150" class="img-thumbnail">
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="mb-4">
                        <label class="form-label">Status</label>

                        <select class="form-select @error('status') is-invalid @enderror" wire:model.live="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">

                        <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                            Back
                        </a>

                        <button type="submit" class="btn btn-warning" wire:loading.attr="disabled"
                            wire:target="update,logo">

                            <span wire:loading.remove wire:target="update">
                                Update Brand
                            </span>

                            <span wire:loading wire:target="update">
                                Updating...
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>
