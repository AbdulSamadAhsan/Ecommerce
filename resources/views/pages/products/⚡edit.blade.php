<?php

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Brand;
use App\Models\Warehouse;
use App\Models\StockMovement;
use App\Models\Stock;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
new class extends Component {
    use WithFileUploads;

    public $productId;

    public $supplier_id = '';
    public $category_id = '';
    public $brand_id = '';
    public $warehouse_id = '';

    public $name = '';
    public $sku = '';

    public $purchase_price = '';
    public $selling_price = '';
    public $selling_price_after_discount = '';
    public $discount = 0;

    public $quantity = 0;
    public $old_quantity = 0;
    public $minimum_stock = 5;

    public $description = '';
    public $image;
    public $oldImage;

    public $status = 1;

    public function mount($id)
    {
        $product = Product::findOrFail($id);

        $this->productId = $product->id;

        $this->supplier_id = (string) $product->supplier_id;
        $this->category_id = (string) $product->category_id;
        $this->brand_id = (string) $product->brand_id;
        $this->warehouse_id = (string) $product->warehouse_id;

        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->purchase_price = $product->purchase_price;
        $this->selling_price = $product->selling_price;
        $this->discount = $product->discount ?? 0;
        $this->quantity = $product->quantity;
        $this->old_quantity = $product->quantity;
        $this->minimum_stock = $product->minimum_stock;
        $this->description = $product->description;
        $this->status = (string) $product->status;
        $this->oldImage = $product->image;

        $this->calculateDiscountPrice();
    }

    public function calculateDiscountPrice()
    {
        $sellingPrice = (float) $this->selling_price;
        $discount = (float) $this->discount;

        $discountAmount = ($discount / 100) * $sellingPrice;

        $this->selling_price_after_discount = ceil($sellingPrice - $discountAmount);
    }

    public function updated($property)
    {
        if ($property === 'name') {
            $this->sku = Str::slug($this->name);
        }

        if (in_array($property, ['selling_price', 'discount'])) {
            $this->calculateDiscountPrice();
        }
    }

    public function update()
    {
        $this->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|min:2|max:255',

            'sku' => ['required', Rule::unique('products', 'sku')->ignore($this->productId)],

            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'quantity' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
        ]);

        DB::transaction(function () {
            $product = Product::findOrFail($this->productId);

            $imagePath = $this->oldImage;

            if ($this->image) {
                if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                    Storage::disk('public')->delete($this->oldImage);
                }

                $fileName = Str::slug($this->name) . '-' . time() . '.' . $this->image->getClientOriginalExtension();
                $imagePath = $this->image->storeAs('products', $fileName, 'public');
            }

            $stockBefore = $product->quantity;
            $stockAfter = (int) $this->quantity;
            if ($stockAfter < $stockBefore) {
                throw ValidationException::withMessages([
                    'quantity' => "Quantity cannot be less than current stock ({$stockBefore}). Use Sale module to reduce stock.",
                ]);
            }
            $product->update([
                'supplier_id' => $this->supplier_id,
                'category_id' => $this->category_id,
                'brand_id' => $this->brand_id,
                'warehouse_id' => $this->warehouse_id,
                'name' => $this->name,
                'sku' => $this->sku,
                'purchase_price' => $this->purchase_price,
                'selling_price' => $this->selling_price,
                'discount' => $this->discount,
                'quantity' => $this->quantity,
                'minimum_stock' => $this->minimum_stock,
                'description' => $this->description,
                'image' => $imagePath,
                'status' => $this->status,
            ]);

            Stock::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'warehouse_id' => $this->warehouse_id,
                ],
                [
                    'quantity' => $this->quantity,
                    'minimum_stock' => $this->minimum_stock,
                ],
            );

            if ($stockBefore != $stockAfter && $stockAfter < $stockBefore) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'warehouse_id' => $this->warehouse_id,
                    'supplier_id' => $this->supplier_id,
                    'type' => 'adjustment',
                    'quantity' => abs($stockAfter - $stockBefore),
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reference_no' => 'EDIT-' . $product->id,
                ]);
            }
            if ($stockAfter > $stockBefore) {
                $newquantity = abs($stockAfter - $stockBefore);
                dd('Product Purchased');
            }
        });

        session()->flash('success', 'Product updated successfully.');

        return $this->redirectRoute('products.index', navigate: true);
    }

    public function suppliers()
    {
        return Supplier::get();
    }

    public function categories()
    {
        return Category::orderBy('name')->get();
    }

    public function brands()
    {
        return Brand::orderBy('title')->get();
    }

    public function warehouses()
    {
        return Warehouse::orderBy('name')->get();
    }
};
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow border-0">

            <div class="card-header bg-warning">
                <h4 class="mb-0">Edit Product</h4>
            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="update">

                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Supplier</label>

                            <select class="form-select @error('supplier_id') is-invalid @enderror"
                                wire:model="supplier_id">
                                <option value="">Select Supplier</option>

                                @foreach ($this->suppliers() as $supplier)
                                    <option value="{{ $supplier->id }}">
                                        {{ $supplier->user->name ?? $supplier->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Category</label>

                            <select class="form-select @error('category_id') is-invalid @enderror"
                                wire:model="category_id">
                                <option value="">Select Category</option>

                                @foreach ($this->categories() as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Brand</label>

                            <select class="form-select @error('brand_id') is-invalid @enderror" wire:model="brand_id">
                                <option value="">Select Brand</option>

                                @foreach ($this->brands() as $brand)
                                    <option value="{{ $brand->id }}">
                                        {{ $brand->title }}
                                    </option>
                                @endforeach
                            </select>

                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label">Warehouse</label>

                            <select class="form-select @error('warehouse_id') is-invalid @enderror"
                                wire:model="warehouse_id">
                                <option value="">Select Warehouse</option>

                                @foreach ($this->warehouses() as $warehouse)
                                    <option value="{{ $warehouse->id }}">
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('warehouse_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product Name</label>

                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                wire:model.live="name">

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU</label>

                            <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                wire:model="sku" readonly>

                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Purchase Price</label>

                            <input type="number" step="0.01"
                                class="form-control @error('purchase_price') is-invalid @enderror"
                                wire:model.live="purchase_price">

                            @error('purchase_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Selling Price</label>

                            <input type="number" step="0.01"
                                class="form-control @error('selling_price') is-invalid @enderror"
                                wire:model.live="selling_price">

                            @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Discount %</label>

                            <input type="number" step="0.01"
                                class="form-control @error('discount') is-invalid @enderror" wire:model.live="discount">

                            @error('discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Selling Price After Discount</label>

                            <input type="number" step="0.01" class="form-control"
                                wire:model="selling_price_after_discount" readonly>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Quantity</label>

                            <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                wire:model.live="quantity">

                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Minimum Stock</label>

                            <input type="number" class="form-control @error('minimum_stock') is-invalid @enderror"
                                wire:model.live="minimum_stock">

                            @error('minimum_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>

                            <textarea rows="4" class="form-control @error('description') is-invalid @enderror" wire:model.live="description"></textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Current Image</label>

                            <div>
                                @if ($oldImage)
                                    <img src="{{ asset('storage/' . $oldImage) }}" width="150"
                                        class="img-thumbnail">
                                @else
                                    <p class="text-muted mb-0">No image uploaded.</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">New Product Image</label>

                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                wire:model="image">

                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
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
                            <label class="form-label">Status</label>

                            <select class="form-select" wire:model="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            Back
                        </a>

                        <button type="submit" class="btn btn-warning" wire:loading.attr="disabled"
                            wire:target="update,image">

                            <span wire:loading.remove wire:target="update">
                                Update Product
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
