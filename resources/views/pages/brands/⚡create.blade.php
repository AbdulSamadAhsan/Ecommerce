<?php

use App\Models\Brand;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $title = '';

    public $logo;
    public $status = 1;

    protected $rules = [
        'title' => 'required|min:2|max:255|unique:brands,title',

        'logo' => 'required|image|max:2048',
        'status' => 'required|boolean',
    ];

    protected $messages = [
        'title.required' => 'Brand name is required.',
        'title.min' => 'Brand name must contain at least 2 characters.',
        'title.unique' => 'Brand already exists.',

        'logo.required' => 'Please select a logo.',
        'logo.image' => 'Please upload a valid image.',
        'logo.max' => 'Image size must not exceed 2MB.',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $validated = $this->validate();

        $extension = $this->logo->getClientOriginalExtension();

        $fileName = Str::slug($this->title) . '-' . time() . '.' . $extension;

        $logoPath = $this->logo->storeAs('brands', $fileName, 'public');

        Brand::create([
            'title' => $this->title,

            'logo' => $logoPath,
            'status' => $this->status,
        ]);

        $this->reset(['title', 'logo']);

        $this->status = 1;

        session()->flash('success', 'Brand added successfully.');
    }
};

?>

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">

                <h4 class="mb-0">
                    Add Brand
                </h4>

            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="save">

                    <div class="mb-3">

                        <label class="form-label">
                            Brand Name
                        </label>

                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                            placeholder="Enter brand name" wire:model.live="title">

                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>



                    <div class="mb-3">

                        <label class="form-label">
                            Brand Logo
                        </label>

                        <input type="file" class="form-control @error('logo') is-invalid @enderror"
                            wire:model.live="logo">

                        @error('logo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div wire:loading wire:target="logo" class="mt-2 text-primary">
                            Uploading image...
                        </div>

                        @if ($logo)
                            <div class="mt-3">
                                <img src="{{ $logo->temporaryUrl() }}" width="150" class="img-thumbnail">
                            </div>
                        @endif

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select class="form-select" wire:model="status">

                            <option value="1">
                                Active
                            </option>

                            <option value="0">
                                Inactive
                            </option>

                        </select>

                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                            wire:target="save,logo" @disabled($errors->has('title') || $errors->has('logo') || empty($title) || empty($logo))>
                            <span wire:loading.remove wire:target="save">
                                Save Brand
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
