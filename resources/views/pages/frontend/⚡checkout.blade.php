<?php

use Livewire\Component;

new class extends Component {
    public array $cart = [
        1 => [
            'id' => 1,
            'name' => 'MacBook Pro M3',
            'price' => 1299,
            'quantity' => 1,
        ],
        2 => [
            'id' => 2,
            'name' => 'Wireless Headphones',
            'price' => 149,
            'quantity' => 2,
        ],
    ];

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $city = '';
    public string $paymentMethod = 'cod';

    public function getCartCountProperty(): int
    {
        return collect($this->cart)->sum('quantity');
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function getShippingProperty(): float
    {
        return $this->subtotal > 0 ? 20 : 0;
    }

    public function getTotalProperty(): float
    {
        return $this->subtotal + $this->shipping;
    }

    public function placeOrder(): void
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required|min:10',
            'address' => 'required|min:10',
            'city' => 'required',
            'paymentMethod' => 'required',
        ]);

        $this->cart = [];

        session()->flash('success', 'Order placed successfully.');
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

    <h2 class="fw-bold mb-4">Checkout</h2>

    @if (session('success'))
        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-4">Billing Details</h4>

                    <form wire:submit="placeOrder">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" wire:model="name" class="form-control rounded-pill">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" wire:model="email" class="form-control rounded-pill">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" wire:model="phone" class="form-control rounded-pill">
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" wire:model="city" class="form-control rounded-pill">
                            @error('city')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea wire:model="address" class="form-control rounded-4" rows="4"></textarea>
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <h5 class="fw-bold mt-4 mb-3">Payment Method</h5>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" wire:model="paymentMethod" value="cod"
                                id="cod">
                            <label class="form-check-label" for="cod">
                                Cash on Delivery
                            </label>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="radio" wire:model="paymentMethod" value="bank"
                                id="bank">
                            <label class="form-check-label" for="bank">
                                Bank Transfer
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-5">
                            Place Order
                        </button>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-4">Order Summary</h4>

                    @foreach ($cart as $item)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>
                                {{ $item['name'] }} × {{ $item['quantity'] }}
                            </span>
                            <strong>
                                ${{ number_format($item['price'] * $item['quantity'], 2) }}
                            </strong>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between mt-3">
                        <span>Subtotal</span>
                        <strong>${{ number_format($this->subtotal, 2) }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mt-2">
                        <span>Shipping</span>
                        <strong>${{ number_format($this->shipping, 2) }}</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fs-5">
                        <strong>Total</strong>
                        <strong>${{ number_format($this->total, 2) }}</strong>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
