<?php

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $supplier_id = '';
    public $category_id = '';

    public $name = '';
    public $sku = '';

    public $purchase_price = '';
    public $selling_price = '';

    public $quantity = 0;
    public $minimum_stock = 5;

    public $description = '';
    public $image;

    public $status = 1;

    public function save()
    {
        $validated = $this->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|min:2|max:255',
            'sku' => 'required|unique:products,sku',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
        ]);

        $imagePath = null;

        if ($this->image) {
            $fileName = Str::slug($this->name) . '-' . time() . '.' . $this->image->getClientOriginalExtension();

            $imagePath = $this->image->storeAs('products', $fileName, 'public');
        }

        Product::create([
            'supplier_id' => $this->supplier_id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'sku' => $this->sku,
            'purchase_price' => $this->purchase_price,
            'selling_price' => $this->selling_price,
            'quantity' => $this->quantity,
            'minimum_stock' => $this->minimum_stock,
            'description' => $this->description,
            'image' => $imagePath,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Product created successfully.');

        $this->reset();

        $this->quantity = 0;
        $this->minimum_stock = 5;
        $this->status = 1;
    }

    public function suppliers()
    {
        return Supplier::get();
    }

    public function categories()
    {
        return Category::orderBy('name')->get();
    }
};

?>

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">

                <h4 class="mb-0">
                    Add Product
                </h4>

            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="save">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Supplier
                            </label>

                            <select class="form-select @error('supplier_id') is-invalid @enderror"
                                wire:model="supplier_id">

                                <option value="">
                                    Select Supplier
                                </option>

                                @foreach ($this->suppliers() as $supplier)
                                    <option value="{{ $supplier->id }}">
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('supplier_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Category
                            </label>

                            <select class="form-select @error('category_id') is-invalid @enderror"
                                wire:model="category_id">

                                <option value="">
                                    Select Category
                                </option>

                                @foreach ($this->categories() as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('category_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Product Name
                            </label>

                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                wire:model.live="name" placeholder="Enter product name">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                SKU
                            </label>

                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                wire:model.live="sku" placeholder="Enter SKU">

                            @error('sku')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Purchase Price
                            </label>

                            <input type="number" step="0.01"
                                class="form-control @error('purchase_price') is-invalid @enderror"
                                wire:model.live="purchase_price">

                            @error('purchase_price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Selling Price
                            </label>

                            <input type="number" step="0.01"
                                class="form-control @error('selling_price') is-invalid @enderror"
                                wire:model.live="selling_price">

                            @error('selling_price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Quantity
                            </label>

                            <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                wire:model.live="quantity">

                            @error('quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Minimum Stock
                            </label>

                            <input type="number" class="form-control @error('minimum_stock') is-invalid @enderror"
                                wire:model.live="minimum_stock">

                            @error('minimum_stock')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea rows="4" class="form-control @error('description') is-invalid @enderror" wire:model.live="description"></textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Product Image
                            </label>

                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                wire:model="image">

                            @error('image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div wire:loading wire:target="image" class="mt-2 text-primary">
                                Uploading image...
                            </div>

                            @if ($image)
                                <div class="mt-3">
                                    <img src="{{ $image->temporaryUrl() }}" width="150" class="img-thumbnail">
                                </div>
                            @endif

                        </div>

                        <div class="col-md-6 mb-3">

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

                    </div>

                    <div class="d-flex justify-content-end">

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                            wire:target="save,image">

                            <span wire:loading.remove wire:target="save">
                                Save Product
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
