<?php

use Livewire\Component;
use App\Models\Product;
new class extends Component {
    public int $id;

    public int $cartCount = 0;

    public int $quantity = 1;

    public $product;

    public $relatedProducts;

    public array $customerReviews = [];

    public function mount($id): void
    {
        $this->id = (int) $id;

        $products = [
            1 => [
                'id' => 1,
                'name' => 'MacBook Pro M3',
                'price' => 1299,
                'old' => 1499,
                'img' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=900',
                'badge' => 'Hot',
                'category' => 'Laptops',
                'brand' => 'Apple',
                'stock' => 12,
                'sku' => 'MBP-M3-001',
                'description' => 'Powerful MacBook Pro with M3 chip, premium display, long battery life and excellent performance for professionals.',
            ],
            2 => [
                'id' => 2,
                'name' => 'Wireless Headphones',
                'price' => 149,
                'old' => 199,
                'img' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=900',
                'badge' => 'Sale',
                'category' => 'Headphones',
                'brand' => 'Sony',
                'stock' => 25,
                'sku' => 'WH-SONY-002',
                'description' => 'Premium wireless headphones with deep bass, noise isolation and long battery backup.',
            ],
            3 => [
                'id' => 3,
                'name' => 'Smart Watch Ultra',
                'price' => 99,
                'old' => 140,
                'img' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=900',
                'badge' => 'New',
                'category' => 'Smart Watches',
                'brand' => 'Samsung',
                'stock' => 18,
                'sku' => 'SWT-003',
                'description' => 'Smart watch with fitness tracking, notifications, health monitoring and premium design.',
            ],
            4 => [
                'id' => 4,
                'name' => 'Gaming Controller',
                'price' => 79,
                'old' => 110,
                'img' => 'https://images.unsplash.com/photo-1605901309584-818e25960a8f?w=900',
                'badge' => 'Best',
                'category' => 'Gaming',
                'brand' => 'HP',
                'stock' => 30,
                'sku' => 'GAME-004',
                'description' => 'Ergonomic gaming controller with smooth buttons, strong grip and responsive gameplay.',
            ],
        ];

        $reviews = [
            1 => [
                [
                    'name' => 'Ali Khan',
                    'rating' => 5,
                    'date' => '2026-06-18',
                    'review' => 'Excellent laptop. Very fast performance and premium display quality.',
                ],
                [
                    'name' => 'Sara Ahmed',
                    'rating' => 4,
                    'date' => '2026-06-17',
                    'review' => 'Battery timing is great and build quality is impressive.',
                ],
            ],
            2 => [
                [
                    'name' => 'Hassan Raza',
                    'rating' => 5,
                    'date' => '2026-06-16',
                    'review' => 'Sound quality is amazing. Bass is strong and clear.',
                ],
            ],
            3 => [
                [
                    'name' => 'Ayesha Malik',
                    'rating' => 4,
                    'date' => '2026-06-15',
                    'review' => 'Good watch for fitness tracking and notifications.',
                ],
            ],
            4 => [
                [
                    'name' => 'Usman Tariq',
                    'rating' => 5,
                    'date' => '2026-06-14',
                    'review' => 'Perfect controller for gaming. Buttons feel smooth.',
                ],
            ],
        ];

        $this->product = Product::find($this->id);

        $this->customerReviews = $reviews[$this->product['id']] ?? [];

        $this->relatedProducts = Product::where('category_id', $this->product->category_id)->where('id', '!=', $this->product->id)->get();
    }

    public function getAverageRatingProperty(): float
    {
        if (count($this->customerReviews) === 0) {
            return 0;
        }

        return round(collect($this->customerReviews)->avg('rating'), 1);
    }

    public function increaseQuantity(): void
    {
        if ($this->quantity < $this->product['stock']) {
            $this->quantity++;
        }
    }

    public function decreaseQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart(): void
    {
        $this->cartCount += $this->quantity;

        $this->dispatch('cart-updated', count: $this->cartCount);

        session()->flash('success', 'Product added to cart successfully.');
    }

    public function addToWishlist(): void
    {
        session()->flash('success', 'Product added to wishlist.');
    }

    public function rendering($view): void
    {
        $view->layout('components.layouts.ecommerce', [
            'cartCount' => $this->cartCount,
        ]);
    }
};
?>

<div class="container py-5">

    @if (session('success'))
        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-5">

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-3">
                <span class="badge bg-danger position-absolute m-3 rounded-pill">
                    {{ $product['badge'] }}
                </span>

                <img src="{{ asset('storage/' . $product['image']) }}" class="img-fluid rounded-4"
                    style="height: 480px; object-fit: cover; width: 100%;" alt="{{ $product['name'] }}">
            </div>
        </div>

        <div class="col-lg-6">

            <nav class="mb-3">
                <a wire:navigate href="{{ route('front') }}" class="text-decoration-none">
                    Home
                </a>
                /
                <span class="text-muted">
                    {{ $product['name'] }}
                </span>
            </nav>

            <h1 class="fw-bold">
                {{ $product['name'] }}
            </h1>

            <div class="text-warning mb-3 fs-5">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= round($this->averageRating))
                        <i class="bi bi-star-fill"></i>
                    @else
                        <i class="bi bi-star"></i>
                    @endif
                @endfor

                <span class="text-muted small ms-2">
                    {{ $this->averageRating }} / 5
                    ({{ count($customerReviews) }} Reviews)
                </span>
            </div>

            <div class="d-flex align-items-center gap-3 mb-3">
                <h2 class="text-primary fw-bold mb-0">
                    {{ number_format($product['selling_price'], 2) }}
                </h2>

                <h5 class="text-muted text-decoration-line-through mb-0">
                    {{ number_format($product['purchase_price'], 2) }}
                </h5>
            </div>

            <p class="text-muted fs-5">
                {{ $product['description'] }}
            </p>

            <div class="row mb-4">
                <div class="col-6">
                    <strong>SKU:</strong>
                    <span class="text-muted">{{ $product['sku'] }}</span>
                </div>

                <div class="col-6">
                    <strong>Stock:</strong>
                    @if ($product['stock'] > 0)
                        <span class="badge bg-success">
                            In Stock
                        </span>
                    @else
                        <span class="badge bg-danger">
                            Out of Stock
                        </span>
                    @endif
                </div>

                <div class="col-6 mt-2">
                    <strong>Category:</strong>
                    <span class="text-muted">{{ $product->category->name }}</span>
                </div>

                <div class="col-6 mt-2">
                    <strong>Brand:</strong>
                    <span class="text-muted">{{ $product->brand->title }}</span>
                </div>
            </div>

            @if ($product->stock > 0)
                <div class="stock-alert mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-lightning-charge-fill"></i>
                        <span>
                            Hurry! Only <strong>{{ $product->stock }}</strong> pieces left in stock
                        </span>
                    </div>
                </div>
            @endif
            <div class="d-flex align-items-center gap-3 mb-4">

                <div class="btn-group">
                    <button wire:click="decreaseQuantity" class="btn btn-outline-secondary">
                        -
                    </button>

                    <button class="btn btn-light px-4">
                        {{ $quantity }}
                    </button>

                    <button wire:click="increaseQuantity" class="btn btn-outline-secondary">
                        +
                    </button>
                </div>

                <button wire:click="addToCart" class="btn btn-primary rounded-pill px-5" @disabled($product['Stock'] <= 0)>
                    <i class="bi bi-cart-plus me-1"></i>
                    Add to Cart
                </button>

                <button wire:click="addToWishlist" class="btn btn-outline-danger rounded-circle">
                    <i class="bi bi-heart"></i>
                </button>

            </div>

            <div class="card border-0 bg-light rounded-4">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <i class="bi bi-truck fs-3 text-primary"></i>
                            <p class="mb-0 small fw-bold">Fast Delivery</p>
                        </div>

                        <div class="col-md-4">
                            <i class="bi bi-shield-check fs-3 text-primary"></i>
                            <p class="mb-0 small fw-bold">Secure Payment</p>
                        </div>

                        <div class="col-md-4">
                            <i class="bi bi-arrow-repeat fs-3 text-primary"></i>
                            <p class="mb-0 small fw-bold">Easy Return</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm rounded-4 mt-5">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-3">
                Product Description
            </h3>

            <p class="text-muted mb-0">
                {{ $product['description'] }}
            </p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">
                        Customer Reviews
                    </h3>

                    <p class="text-muted mb-0">
                        Real feedback from customers
                    </p>
                </div>

                <div class="text-warning fs-5">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= round($this->averageRating))
                            <i class="bi bi-star-fill"></i>
                        @else
                            <i class="bi bi-star"></i>
                        @endif
                    @endfor

                    <span class="text-muted small ms-2">
                        {{ $this->averageRating }}
                    </span>
                </div>
            </div>

            @forelse ($customerReviews as $review)
                <div class="border-bottom pb-3 mb-3">

                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <div>
                            <h6 class="fw-bold mb-1">
                                {{ $review['name'] }}
                            </h6>

                            <small class="text-muted">
                                {{ $review['date'] }}
                            </small>
                        </div>

                        <div class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review['rating'])
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                    </div>

                    <p class="text-muted mt-2 mb-0">
                        {{ $review['review'] }}
                    </p>

                </div>
            @empty
                <div class="text-center text-muted py-4">
                    No reviews yet for this product.
                </div>
            @endforelse

        </div>
    </div>

    <section class="mt-5">
        <h3 class="fw-bold mb-4">
            Related Products
        </h3>

        <div class="row g-4">
            @foreach ($relatedProducts as $related)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
                        <a wire:navigate href="{{ route('product.detail', $related['id']) }}">
                            <img src="{{ asset('storage/' . $related['image']) }}" class="w-100 rounded-4"
                                style="height: 220px; object-fit: cover;" alt="{{ $related['name'] }}">
                        </a>

                        <div class="card-body px-1">
                            <h5 class="fw-bold">
                                {{ $related['name'] }}
                            </h5>

                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-primary">
                                    {{ $related['selling_price'] }}
                                </span>

                                <span class="text-muted text-decoration-line-through">
                                    {{ $related['purchase_price'] }}
                                </span>
                            </div>

                            <a wire:navigate href="{{ route('product.detail', $related['id']) }}"
                                class="btn btn-outline-primary rounded-pill w-100 mt-3">
                                View Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

</div>
