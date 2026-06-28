<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    use WithPagination;

    public $cartCount = 0;

    public $categories = [];
    public $brands = [];
    public $products = [];

    public string $search = '';

    public function mount()
    {
        $this->loadCategories();
        $this->loadBrands();
        $this->loadProducts();
        $this->refreshCartCount();
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->loadProducts();
    }

    public function loadCategories()
    {
        $this->categories = Category::latest()->get();
    }

    public function loadBrands()
    {
        $this->brands = Brand::latest()->get();
    }

    public function loadProducts()
    {
        $search = trim($this->search);

        $this->products = Product::query()
            ->with(['brand', 'category'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('brand', function ($brandQuery) use ($search) {
                            $brandQuery->where('title', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->get();
    }

    private function getCurrentCart()
    {
        $session_id = session()->getId();

        $user_id = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;

        return Cart::when(
            $user_id,
            function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            },
            function ($query) use ($session_id) {
                $query->where('session_id', $session_id);
            },
        )->first();
    }

    private function refreshCartCount()
    {
        $cart = $this->getCurrentCart();

        $this->cartCount = $cart ? CartItem::where('cart_id', $cart->id)->sum('quantity') : 0;

        $this->dispatch('cart-updated', count: $this->cartCount);
    }

    public function addToCart($product_id)
    {
        $cart = $this->getCurrentCart();

        if ($cart) {
            $exists = CartItem::where('cart_id', $cart->id)->where('product_id', $product_id)->exists();

            if ($exists) {
                $this->addError('cart', 'Product is already in your cart.');
                return;
            }
        }

        $product = Product::findOrFail($product_id);

        if ($product->quantity <= 0) {
            $this->addError('cart', 'Product is out of stock.');
            return;
        }

        if (!$cart) {
            $cart = Cart::create([
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
                'user_id' => Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null,
            ]);
        }

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price_after_discount,
        ]);

        $this->resetErrorBag('cart');

        session()->flash('success', 'Product added to cart successfully.');

        $this->refreshCartCount();
    }

    public function rendering($view): void
    {
        $view->layout('components.layouts.ecommerce', [
            'cartCount' => $this->cartCount,
        ]);
    }
};
?>

<div class="page-wrapper">

    <style>
        .hero {
            background: linear-gradient(135deg, #0f172a, #2563eb);
            border-radius: 35px;
            color: #fff;
            overflow: hidden;
        }

        .hero h1 {
            font-size: clamp(2.2rem, 5vw, 4.5rem);
            font-weight: 900;
            line-height: 1.05;
        }

        .hero p {
            color: #dbeafe;
            font-size: 1.1rem;
        }

        .hero-img {
            max-height: 420px;
            object-fit: contain;
            filter: drop-shadow(0 30px 45px rgba(0, 0, 0, .35));
        }

        .search-box {
            border-radius: 50px;
            padding: 14px 22px;
            border: none;
        }

        .category-card,
        .product-card,
        .feature-card,
        .brand-card {
            border: none;
            border-radius: 26px;
            transition: all .3s ease;
            background: #fff;
        }

        .category-card:hover,
        .product-card:hover,
        .feature-card:hover,
        .brand-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 45px rgba(15, 23, 42, .13);
        }

        .product-img {
            height: 230px;
            object-fit: cover;
            border-radius: 22px;
            background: #f1f5f9;
        }

        .price {
            color: #2563eb;
            font-size: 22px;
            font-weight: 900;
        }

        .old-price {
            color: #94a3b8;
            text-decoration: line-through;
        }

        .badge-custom {
            position: absolute;
            top: 18px;
            left: 18px;
            border-radius: 50px;
            padding: 7px 14px;
        }

        .section-title {
            font-weight: 900;
            color: #0f172a;
        }

        .promo {
            background: linear-gradient(135deg, #111827, #1d4ed8);
            border-radius: 35px;
            color: white;
        }

        @media(max-width: 768px) {
            .hero {
                text-align: center;
                border-radius: 25px;
            }

            .hero-img {
                max-height: 280px;
            }

            .product-img {
                height: 190px;
            }
        }
    </style>

    <div class="container">

        <section class="hero p-4 p-lg-5 mb-5">
            <div class="row align-items-center g-5">

                <div class="col-lg-6">
                    <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3">
                        New Tech Collection 2026
                    </span>

                    <h1>Buy Modern Gadgets At Best Prices</h1>

                    <p class="mt-3">
                        Discover premium laptops, headphones, smart watches, cameras, mobiles and gaming accessories.
                    </p>

                    <div class="bg-white rounded-pill p-2 d-flex mt-4 shadow-sm">
                        <input type="text" wire:model.live.debounce.500ms="search" class="form-control search-box"
                            placeholder="Search by product, brand or category...">

                        <button type="button" wire:click="loadProducts" class="btn btn-primary rounded-pill px-4">
                            Search
                        </button>
                    </div>
                </div>

                <div class="col-lg-6 text-center">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=900"
                        class="img-fluid hero-img rounded-4" alt="Ecommerce">
                </div>

            </div>
        </section>

        <section class="mb-5">
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="feature-card shadow-sm p-4 text-center h-100">
                        <i class="bi bi-truck fs-1 text-primary"></i>
                        <h5 class="fw-bold mt-3">Fast Delivery</h5>
                        <p class="text-muted mb-0">Quick shipping across all major cities.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card shadow-sm p-4 text-center h-100">
                        <i class="bi bi-shield-check fs-1 text-primary"></i>
                        <h5 class="fw-bold mt-3">Secure Payment</h5>
                        <p class="text-muted mb-0">Safe checkout and protected transactions.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card shadow-sm p-4 text-center h-100">
                        <i class="bi bi-arrow-repeat fs-1 text-primary"></i>
                        <h5 class="fw-bold mt-3">Easy Returns</h5>
                        <p class="text-muted mb-0">Simple return policy for selected products.</p>
                    </div>
                </div>

            </div>
        </section>

        <section class="mb-5">
            <h2 class="section-title mb-1">Popular Categories</h2>
            <p class="text-muted mb-4">Shop by your favorite category</p>

            <div class="row g-4" wire:poll.10s="loadCategories">
                @foreach ($categories as $category)
                    <div class="col-6 col-md-4 col-lg-2" wire:key="category-{{ $category->id }}">
                        <div class="card category-card shadow-sm p-4 text-center h-100">
                            <h6 class="fw-bold mb-0">
                                {{ $category->name ?? $category->title }}
                            </h6>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mb-5">
            <h2 class="section-title mb-1">Top Brands</h2>
            <p class="text-muted mb-4">Shop products from trusted brands</p>

            <div class="row g-4" wire:poll.10s="loadBrands">
                @foreach ($brands as $brand)
                    <div class="col-6 col-md-4 col-lg-2" wire:key="brand-{{ $brand->id }}">
                        <div class="card brand-card shadow-sm p-4 text-center h-100">
                            <h6 class="fw-bold mb-0">
                                {{ $brand->title ?? $brand->name }}
                            </h6>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mb-5">
            <h2 class="section-title mb-1">Featured Products</h2>
            <p class="text-muted mb-4">Best selling products this week</p>

            @error('cart')
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @enderror

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4" wire:poll.2s="loadProducts">
                @forelse ($products as $product)
                    <div class="col-sm-6 col-lg-3" wire:key="product-{{ $product->id }}">
                        <div class="card product-card shadow-sm p-3 h-100 position-relative">

                            @if (!empty($product->badge))
                                <span class="badge bg-danger badge-custom">
                                    {{ $product->badge }}
                                </span>
                            @endif

                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="product-img w-100"
                                    alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/400x300?text=No+Image" class="product-img w-100"
                                    alt="No Image">
                            @endif

                            <div class="card-body px-1 pb-1">

                                <h5 class="fw-bold">
                                    {{ $product->name }}
                                </h5>

                                <div class="mb-2 text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>

                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="price">
                                        {{ number_format($product->price_after_discount) }} Rs
                                    </span>

                                    @if ($product->discount > 0)
                                        <span class="old-price">
                                            {{ number_format($product->selling_price) }} Rs
                                        </span>
                                    @endif
                                </div>

                                <a wire:navigate href="{{ route('product.detail', $product->id) }}"
                                    class="text-decoration-none">
                                    <h5 class="fw-bold text-primary">
                                        View Detail
                                    </h5>
                                </a>

                                <button wire:click="addToCart({{ $product->id }})" wire:loading.attr="disabled"
                                    wire:target="addToCart({{ $product->id }})"
                                    class="btn btn-primary w-100 rounded-pill" @disabled($product->quantity <= 0)>

                                    <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                        <i class="bi bi-cart-plus me-1"></i>
                                        {{ $product->quantity <= 0 ? 'Out of Stock' : 'Add to Cart' }}
                                    </span>

                                    <span wire:loading wire:target="addToCart({{ $product->id }})">
                                        Adding...
                                    </span>
                                </button>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            No products found.
                        </div>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="promo p-4 p-lg-5 mb-5 text-center">
            <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3">
                Limited Time Offer
            </span>

            <h2 class="fw-bold display-6">
                Get Up To 40% Off On Electronics
            </h2>

            <p class="text-light">
                Upgrade your setup with premium devices and accessories.
            </p>
        </section>

    </div>
</div>
