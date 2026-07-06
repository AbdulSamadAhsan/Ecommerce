<?php

use App\Models\Category;
use Livewire\Component;
use Illuminate\Validation\Rule;

new class extends Component {
    public $category_id;

    public $name = '';
    public $description = '';
    public $status = 1;

    public function mount($id)
    {
        $category = Category::findOrFail($id);

        $this->category_id = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->status = (int) $category->status;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'min:2', 'max:255', Rule::unique('categories', 'name')->ignore($this->category_id)],
            'description' => 'nullable|max:1000',
            'status' => 'required|boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'Category name is required.',
        'name.min' => 'Category name must contain at least 2 characters.',
        'name.unique' => 'Category already exists.',
        'description.max' => 'Description must not exceed 1000 characters.',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function update()
    {
        $this->validate();

        $category = Category::findOrFail($this->category_id);

        $category->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Category updated successfully.');
    }
};

?>

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    Edit Category
                </h4>
            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="update">

                    <div class="mb-3">

                        <label class="form-label">
                            Category Name
                        </label>

                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter category name" wire:model.live="name">

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea class="form-control @error('description') is-invalid @enderror" rows="4"
                            placeholder="Enter category description" wire:model.live="description"></textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select class="form-select @error('status') is-invalid @enderror" wire:model.live="status">

                            <option value="1">
                                Active
                            </option>

                            <option value="0">
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="d-flex justify-content-end">

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="update"
                            @disabled($errors->has('name') || empty($name))>

                            <span wire:loading.remove wire:target="update">
                                Update Category
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
