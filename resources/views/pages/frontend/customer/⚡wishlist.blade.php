<?php

use Livewire\Component;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\wishlistItem;
new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public $cart;
    public $items;
    public $itemsCart = [];
    public $cartCount = 0;
    public function getCurrentCart()
    {
        $this->cart = Cart::where('user_id', auth('customer')->user()->id)->first();
        if ($this->cart) {
            $this->itemsCart = $this->cart->items()->pluck('product_id')->toArray();
            $this->cartCount = $this->cart->items()->sum('quantity');
        }
        $this->dispatch('cart-updated', count: $this->cartCount);
    }
    public function getWishlist()
    {
        $this->items = auth('customer')->user()->customer->wishlists->flatMap(fn($wishlist) => $wishlist->items);
    }
    public function mount()
    {
        $this->getWishlist();
        $this->getCurrentCart();
    }
    public function removeItem(int $id): void
    {
        WishlistItem::where('id', $id)->delete();
        $this->getWishlist();
        session()->flash('success', 'Item removed from wishlist.');
    }

    public function getItemsCartProperty()
    {
        if (!$this->cart) {
            return collect();
        }

        return CartItem::with('product')->where('cart_id', $this->cart->id)->get();
    }

    public function addToCart(int $id): void
    {
        $wishlistItem = WishlistItem::find($id);
        $product = $wishlistItem->product;

        $cart = Cart::firstOrCreate([
            'ip_address' => request()->ip(),
            'user_id' => auth('customer')->user()->id,
        ]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price_after_discount,
        ]);
        $wishlistItem->delete();
        $this->getWishlist();
        $this->getCurrentCart();
        session()->flash('success', ucfirst($product->name) . ' added to cart successfully.');
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
            @if (session('error'))
                <div class="alert alert-danger rounded-4">
                    {{ session('error') }}
                </div>
            @endif

            @if (count($items) > 0)
                <div class="row g-4">
                    @foreach ($items as $item)
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                                <img src="{{ asset('storage/' . $item->product->image) }}" class="card-img-top"
                                    alt="{{ $item->product->name }}" style="height: 180px; object-fit: cover;">

                                <div class="card-body">
                                    <h5 class="fw-bold">
                                        {{ $item->product->name }}
                                    </h5>

                                    <p class="fw-bold text-primary mb-1">
                                        Rs {{ number_format($item->product->price_after_discount) }}
                                    </p>

                                    <span class="badge {{ $item->product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $item->product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </div>

                                <div class="card-footer bg-white border-0 d-flex gap-2">



                                    <button wire:click="addToCart({{ $item->id }})"
                                        class="btn btn-sm btn-primary rounded-pill flex-fill"
                                        @disabled($item->product->stock < 0 || in_array($item->product->id, $this->itemsCart))>
                                        <i class="bi bi-cart-plus"></i>
                                        @if (!in_array($item->product->id, $this->itemsCart))
                                            Cart
                                        @else
                                            Already In Cart
                                        @endif
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
