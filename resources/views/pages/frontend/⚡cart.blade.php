<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $cart;
    public int $cartCount = 0;

    public function mount(): void
    {
        $this->loadCart();
    }

    public function getCurrentCart()
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

    public function loadCart(): void
    {
        $this->cart = $this->getCurrentCart();

        $this->cartCount = $this->cart ? CartItem::where('cart_id', $this->cart->id)->sum('quantity') : 0;

        $this->dispatch('cart-updated', count: $this->cartCount);
    }

    public function increase($itemId): void
    {
        $item = CartItem::with('product')->findOrFail($itemId);

        $item->increment('quantity');

        $this->loadCart();
    }

    public function decrease($itemId): void
    {
        $item = CartItem::findOrFail($itemId);

        if ($item->quantity <= 1) {
            $item->delete();
        } else {
            $item->decrement('quantity');
        }

        $this->loadCart();
    }

    public function remove($itemId): void
    {
        CartItem::findOrFail($itemId)->delete();

        $this->loadCart();
    }

    public function clearCart(): void
    {
        $cart = $this->getCurrentCart();

        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
        }

        $this->loadCart();
    }

    public function getItemsProperty()
    {
        if (!$this->cart) {
            return collect();
        }

        return CartItem::with('product')->where('cart_id', $this->cart->id)->get();
    }

    public function getTotalProperty(): float
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function rendering($view): void
    {
        $this->loadCart();

        $view->layout('components.layouts.ecommerce', [
            'cartCount' => $this->cartCount,
        ]);
    }
};
?>

<div class="container py-5">

    <h2 class="fw-bold mb-4">Shopping Cart</h2>

    @if ($this->items->count())

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">

                @foreach ($this->items as $item)
                    <div class="row align-items-center border-bottom py-3" wire:key="cart-item-{{ $item->id }}">

                        <div class="col-md-2 mb-3 mb-md-0">
                            <img src="{{ asset('storage/' . $item->product->image) }}" class="img-fluid rounded-4"
                                alt="{{ $item->product->name }}">
                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">
                            <h5 class="fw-bold">
                                {{ $item->product->name }}
                            </h5>

                            <p class="text-muted mb-0">
                                Rs. {{ number_format($item->price, 2) }}
                            </p>
                        </div>

                        <div class="col-md-3 mb-3 mb-md-0">
                            <button wire:click="decrease({{ $item->id }})" class="btn btn-outline-primary btn-sm">
                                -
                            </button>

                            <span class="mx-3 fw-bold">
                                {{ $item->quantity }}
                            </span>

                            <button wire:click="increase({{ $item->id }})" class="btn btn-outline-primary btn-sm">
                                +
                            </button>
                        </div>

                        <div class="col-md-2 fw-bold mb-3 mb-md-0">
                            Rs. {{ number_format($item->price * $item->quantity, 2) }}
                        </div>

                        <div class="col-md-1">
                            <button wire:click="remove({{ $item->id }})" class="btn btn-danger btn-sm rounded-pill">
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
                            Total: Rs. {{ number_format($this->total, 2) }}
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
