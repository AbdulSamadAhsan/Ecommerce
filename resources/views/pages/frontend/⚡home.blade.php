<?php

use Livewire\Component;
use App\Models\Product;
new class extends Component {
    public int $cartCount = 0;

    public array $categories = [['name' => 'Laptops'], ['name' => 'Mobiles'], ['name' => 'Headphones'], ['name' => 'Cameras'], ['name' => 'Smart Watches'], ['name' => 'Gaming']];

    public array $brands = [['name' => 'Apple'], ['name' => 'Samsung'], ['name' => 'Dell'], ['name' => 'HP'], ['name' => 'Sony'], ['name' => 'Canon']];

    public $products = [
        [
            'id' => 1,
            'name' => 'MacBook Pro M3',
            'price' => 1299,
            'old' => 1499,
            'img' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=900',
            'badge' => 'Hot',
        ],
        [
            'id' => 2,
            'name' => 'Wireless Headphones',
            'price' => 149,
            'old' => 199,
            'img' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=900',
            'badge' => 'Sale',
        ],
        [
            'id' => 3,
            'name' => 'Smart Watch Ultra',
            'img' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=900',
            'price' => 99,
            'old' => 140,

            'badge' => 'New',
        ],
        [
            'id' => 4,
            'name' => 'Gaming Controller',
            'price' => 79,
            'old' => 110,
            'img' => 'https://images.unsplash.com/photo-1605901309584-818e25960a8f?w=900',
            'badge' => 'Best',
        ],
    ];
    public function mount()
    {
        $this->products = Product::get();
    }
    public function addToCart(): void
    {
        $this->cartCount++;

        $this->dispatch('cart-updated', count: $this->cartCount);
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
                        <input type="text" class="form-control search-box" placeholder="Search products...">

                        <button class="btn btn-primary rounded-pill px-4">
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

            <div class="row g-4">
                @foreach ($categories as $category)
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="card category-card shadow-sm p-4 text-center h-100">
                            <h6 class="fw-bold mb-0">
                                {{ $category['name'] }}
                            </h6>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mb-5">
            <h2 class="section-title mb-1">Top Brands</h2>
            <p class="text-muted mb-4">Shop products from trusted brands</p>

            <div class="row g-4">
                @foreach ($brands as $brand)
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="card brand-card shadow-sm p-4 text-center h-100">
                            <h6 class="fw-bold mb-0">
                                {{ $brand['name'] }}
                            </h6>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mb-5">
            <h2 class="section-title mb-1">Featured Products</h2>
            <p class="text-muted mb-4">Best selling products this week</p>

            <div class="row g-4">
                @foreach ($products as $product)
                    <div class="col-sm-6 col-lg-3">
                        <div class="card product-card shadow-sm p-3 h-100 position-relative">

                            <span class="badge bg-danger badge-custom">
                                {{ $product['badge'] }}
                            </span>

                            <img src="{{ 'storage/' . $product['image'] }}" class="product-img w-100"
                                alt="{{ $product['name'] }}">

                            <div class="card-body px-1 pb-1">

                                <h5 class="fw-bold">
                                    {{ $product['name'] }}
                                </h5>

                                <div class="mb-2 text-warning">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>

                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="price">{{ $product['purchase_price'] }}</span>
                                    <span class="old-price">{{ $product['selling_price'] }}</span>
                                </div>
                                <a wire:navigate href="{{ route('product.detail', $product['id']) }}"
                                    class="text-decoration-none text-dark">
                                    <h5 class="fw-bold text-primary">
                                        View Detail
                                    </h5>
                                </a>
                                <button wire:click="addToCart" class="btn btn-primary w-100 rounded-pill">
                                    <i class="bi bi-cart-plus me-1"></i>
                                    Add to Cart
                                </button>

                            </div>
                        </div>
                    </div>
                @endforeach
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
