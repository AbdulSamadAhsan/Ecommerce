<?php

use Livewire\Component;

new class extends Component {
    public array $cart = [
        1 => [
            'id' => 1,
            'name' => 'MacBook Pro M3',
            'price' => 1299,
            'img' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=900',
            'quantity' => 1,
        ],
        2 => [
            'id' => 2,
            'name' => 'Wireless Headphones',
            'price' => 149,
            'img' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=900',
            'quantity' => 2,
        ],
    ];

    public function increase(int $id): void
    {
        if (isset($this->cart[$id])) {
            $this->cart[$id]['quantity']++;
        }
    }

    public function decrease(int $id): void
    {
        if (isset($this->cart[$id])) {
            $this->cart[$id]['quantity']--;

            if ($this->cart[$id]['quantity'] <= 0) {
                unset($this->cart[$id]);
            }
        }
    }

    public function remove(int $id): void
    {
        unset($this->cart[$id]);
        $this->dispatch('cart-updated', count: count($this->cart));
    }

    public function clearCart(): void
    {
        $this->cart = [];
    }

    public function getTotalProperty(): float
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function getCartCountProperty(): int
    {
        return collect($this->cart)->sum('quantity');
    }

    public function rendering($view): void
    {
        $this->dispatch('cart-updated', count: count($this->cart));
        $view->layout('components.layouts.ecommerce', [
            'cartCount' => count($this->cart),
        ]);
    }
};
?>

<div class="container py-5">

    <h2 class="fw-bold mb-4">
        Shopping Cart
    </h2>

    @if (count($cart))

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body">

                @foreach ($cart as $item)
                    <div class="row align-items-center border-bottom py-3" wire:key="cart-{{ $item['id'] }}">

                        <div class="col-md-2 mb-3 mb-md-0">

                            <img src="{{ $item['img'] }}" class="img-fluid rounded-4" alt="{{ $item['name'] }}">

                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">

                            <h5 class="fw-bold">
                                {{ $item['name'] }}
                            </h5>

                            <p class="text-muted mb-0">
                                ${{ number_format($item['price'], 2) }}
                            </p>

                        </div>

                        <div class="col-md-3 mb-3 mb-md-0">

                            <button wire:click="decrease({{ $item['id'] }})" class="btn btn-outline-primary btn-sm">
                                -
                            </button>

                            <span class="mx-3 fw-bold">
                                {{ $item['quantity'] }}
                            </span>

                            <button wire:click="increase({{ $item['id'] }})" class="btn btn-outline-primary btn-sm">
                                +
                            </button>

                        </div>

                        <div class="col-md-2 fw-bold mb-3 mb-md-0">

                            ${{ number_format($item['price'] * $item['quantity'], 2) }}

                        </div>

                        <div class="col-md-1">

                            <button wire:click="remove({{ $item['id'] }})" class="btn btn-danger btn-sm rounded-pill">

                                <i class="bi bi-trash"></i>

                            </button>

                        </div>

                    </div>
                @endforeach

                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">

                    <button wire:click="clearCart" class="btn btn-outline-danger rounded-pill px-4">

                        Clear Cart

                    </button>

                    <div class="text-end">

                        <h4 class="fw-bold">

                            Total:
                            ${{ number_format($this->total, 2) }}

                        </h4>

                        <a wire:navigate href="{{ route('checkout') }}" class="btn btn-primary rounded-pill px-5 mt-2">
                            Checkout
                        </a>

                    </div>

                </div>

            </div>

        </div>
    @else
        <div class="text-center py-5">

            <i class="bi bi-cart-x display-1 text-muted"></i>

            <h4 class="fw-bold mt-3">
                Your cart is empty
            </h4>

            <a wire:navigate href="{{ route('front') }}" class="btn btn-primary rounded-pill px-5 mt-3">

                Continue Shopping

            </a>

        </div>

    @endif

</div>
