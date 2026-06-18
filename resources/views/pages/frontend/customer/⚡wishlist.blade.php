<?php

use Livewire\Component;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public array $items = [
        [
            'id' => 1,
            'name' => 'MacBook Pro M3',
            'price' => 450000,
            'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=900',
            'stock' => 'In Stock',
        ],
        [
            'id' => 2,
            'name' => 'Wireless Headphones',
            'price' => 149,

            'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=900',
            'stock' => 'Out of Stock',
        ],
        [
            'id' => 3,
            'name' => 'Smart Watch Ultra',
            'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=900',
            'price' => 35000,

            'stock' => 'In Stock',
        ],
    ];

    public function removeItem(int $id): void
    {
        $this->items = array_values(array_filter($this->items, fn($item) => $item['id'] !== $id));

        session()->flash('success', 'Item removed from wishlist.');
    }

    public function addToCart(int $id): void
    {
        session()->flash('success', 'Item added to cart successfully.');
    }
};
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">My Wishlist</h2>

    <div class="row g-4">
        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">

            @if (session('success'))
                <div class="alert alert-success rounded-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (count($items) > 0)
                <div class="row g-4">
                    @foreach ($items as $item)
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                                <img src="{{ $item['image'] }}" class="card-img-top" alt="{{ $item['name'] }}"
                                    style="height: 180px; object-fit: cover;">

                                <div class="card-body">
                                    <h5 class="fw-bold">
                                        {{ $item['name'] }}
                                    </h5>

                                    <p class="fw-bold text-primary mb-1">
                                        Rs {{ number_format($item['price']) }}
                                    </p>

                                    <span
                                        class="badge {{ $item['stock'] === 'In Stock' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $item['stock'] }}
                                    </span>
                                </div>

                                <div class="card-footer bg-white border-0 d-flex gap-2">
                                    <button wire:click="addToCart({{ $item['id'] }})"
                                        class="btn btn-sm btn-primary rounded-pill flex-fill"
                                        @disabled($item['stock'] !== 'In Stock')>
                                        <i class="bi bi-cart-plus"></i>
                                        Cart
                                    </button>

                                    <button wire:click="removeItem({{ $item['id'] }})"
                                        class="btn btn-sm btn-outline-danger rounded-pill">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-heart fs-1 text-muted"></i>
                        <h4 class="fw-bold mt-3">Your wishlist is empty</h4>
                        <p class="text-muted">Save products you like and view them here later.</p>

                        <a wire:navigate href="{{ route('front') }}" class="btn btn-primary rounded-pill px-4">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
