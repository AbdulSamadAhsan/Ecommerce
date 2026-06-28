<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ShippingMethod;
use App\Models\Coupon;
use App\Models\Tax;

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

    public string $shippingMethod = '';
    public array $shippingMethods = [];

    public ?int $couponId = null;
    public string $couponCode = '';
    public float $discount = 0;
    public string $couponMessage = '';
    public string $couponError = '';

    public ?int $taxId = null;
    public float $taxRate = 0;
    public string $taxName = 'Tax';

    public string $cardHolderName = '';
    public string $cardNumber = '';
    public string $cardExpiry = '';
    public string $cardCvv = '';

    public function mount()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login');
        }
        $this->email = Auth::guard('customer')->user()->email;
        $this->phone = Auth::guard('customer')->user()->customer->phone;
        $this->name = Auth::guard('customer')->user()->name;
        $this->shippingMethods = ShippingMethod::where('is_active', 1)
            ->orderBy('cost')
            ->get()
            ->map(
                fn($method) => [
                    'id' => $method->id,
                    'name' => $method->name,
                    'cost' => (float) $method->cost,
                ],
            )
            ->toArray();

        $this->shippingMethod = (string) ($this->shippingMethods[0]['id'] ?? '');

        $tax = Tax::where('is_active', 1)
            ->where('category', 'sales')

            ->first();

        if ($tax) {
            $this->taxId = $tax->id;
            $this->taxName = $tax->name;
            $this->taxRate = (float) $tax->rate;
        }
    }

    public function getCartCountProperty(): int
    {
        return collect($this->cart)->sum('quantity');
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function getSelectedShippingMethodProperty(): ?array
    {
        return collect($this->shippingMethods)->firstWhere('id', (int) $this->shippingMethod);
    }

    public function getShippingProperty(): float
    {
        if ($this->subtotal <= 0 || empty($this->shippingMethod)) {
            return 0;
        }

        return (float) ($this->selectedShippingMethod['cost'] ?? 0);
    }

    public function getTaxAmountProperty(): float
    {
        $taxableAmount = max(0, $this->subtotal - $this->discount);

        return ($taxableAmount * $this->taxRate) / 100;
    }

    public function getTotalProperty(): float
    {
        return max(0, $this->subtotal + $this->shipping + $this->taxAmount - $this->discount);
    }

    public function updatedPaymentMethod(): void
    {
        if ($this->paymentMethod !== 'bank') {
            $this->reset(['cardHolderName', 'cardNumber', 'cardExpiry', 'cardCvv']);
        }
    }

    public function applyCoupon(): void
    {
        $this->couponError = '';
        $this->couponMessage = '';
        $this->discount = 0;
        $this->couponId = null;

        $code = strtoupper(trim($this->couponCode));

        if ($code === '') {
            $this->couponError = 'Please enter coupon code.';
            return;
        }

        $coupon = Coupon::where('code', $code)->where('status', 1)->first();

        if (!$coupon) {
            $this->couponError = 'Invalid coupon code.';
            return;
        }

        if ($coupon->expires_at && now()->gt($coupon->expires_at)) {
            $this->couponError = 'This coupon has expired.';
            return;
        }

        if ($this->subtotal < $coupon->minimum_amount) {
            $this->couponError = 'Minimum order amount is ' . number_format($coupon->minimum_amount, 2);
            return;
        }

        if ($coupon->type === 'percentage') {
            $this->discount = ($this->subtotal * $coupon->value) / 100;
        } else {
            $this->discount = $coupon->value;
        }

        $this->discount = min($this->discount, $this->subtotal);
        $this->couponId = $coupon->id;
        $this->couponMessage = 'Coupon applied successfully.';
    }

    public function removeCoupon(): void
    {
        $this->couponId = null;
        $this->couponCode = '';
        $this->discount = 0;
        $this->couponMessage = '';
        $this->couponError = '';
    }

    public function placeOrder()
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required|min:10',
            'address' => 'required|min:10',

            'paymentMethod' => 'required|in:cod,bank',
            'shippingMethod' => 'required|exists:shipping_methods,id',
        ];

        if ($this->paymentMethod === 'bank') {
            $rules += [
                'cardHolderName' => 'required|min:3',
                'cardNumber' => 'required|min:13|max:19',
                'cardExpiry' => 'required|min:4|max:10',
                'cardCvv' => 'required|min:3|max:4',
            ];
        }

        $this->validate($rules);

        /*
        Order::create([
            'customer_id' => Auth::guard('customer')->id(),
            'coupon_id' => $this->couponId,
            'tax_id' => $this->taxId,
            'shipping_method_id' => $this->shippingMethod,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'city' => $this->city,
            'address' => $this->address,
            'payment_method' => $this->paymentMethod,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'tax_rate' => $this->taxRate,
            'tax_amount' => $this->taxAmount,
            'shipping_cost' => $this->shipping,
            'total' => $this->total,
        ]);
        */

        $this->cart = [];
        $this->removeCoupon();

        $this->reset(['name', 'email', 'phone', 'address', 'city', 'cardHolderName', 'cardNumber', 'cardExpiry', 'cardCvv']);

        $this->paymentMethod = 'cod';
        $this->shippingMethod = (string) ($this->shippingMethods[0]['id'] ?? '');

        session()->flash('success', 'Order placed successfully.');
        return redirect()->route('customer.orders.show', [
            'order' => 9,
        ]);
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

                    <form wire:submit.prevent="placeOrder">

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
                            <label class="form-label fw-semibold">Address</label>
                            <textarea wire:model="address" class="form-control rounded-4" rows="4"></textarea>
                            @error('address')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <h5 class="fw-bold mt-4 mb-3">Payment Method</h5>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" wire:model.live="paymentMethod"
                                value="cod" id="cod">
                            <label class="form-check-label" for="cod">
                                Cash on Delivery
                            </label>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="radio" wire:model.live="paymentMethod"
                                value="bank" id="bank">
                            <label class="form-check-label" for="bank">
                                Bank / Card Payment
                            </label>
                        </div>

                        @if ($paymentMethod === 'bank')
                            <div class="border rounded-4 p-4 mb-4 bg-light">
                                <h5 class="fw-bold mb-3">Card Details</h5>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Card Holder Name</label>
                                    <input type="text" wire:model="cardHolderName" class="form-control rounded-pill">
                                    @error('cardHolderName')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Card Number</label>
                                    <input type="text" wire:model="cardNumber" class="form-control rounded-pill">
                                    @error('cardNumber')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Expiry Date</label>
                                        <input type="text" wire:model="cardExpiry" class="form-control rounded-pill"
                                            placeholder="MM/YY">
                                        @error('cardExpiry')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">CVV</label>
                                        <input type="password" wire:model="cardCvv" class="form-control rounded-pill">
                                        @error('cardCvv')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

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

                    @forelse ($cart as $item)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                            <strong>{{ number_format($item['price'] * $item['quantity'], 2) }}</strong>
                        </div>
                    @empty
                        <div class="text-muted text-center py-4">
                            Your cart is empty.
                        </div>
                    @endforelse

                    @if (count($cart) > 0)

                        <div class="my-4">
                            <label class="form-label fw-semibold">Shipping Method</label>

                            @forelse ($shippingMethods as $method)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" wire:model.live="shippingMethod"
                                        value="{{ $method['id'] }}" id="shipping_{{ $method['id'] }}">

                                    <label class="form-check-label" for="shipping_{{ $method['id'] }}">
                                        {{ $method['name'] }}
                                        — {{ number_format($method['cost'], 2) }}
                                    </label>
                                </div>
                            @empty
                                <div class="text-danger small">
                                    No shipping method available.
                                </div>
                            @endforelse
                        </div>

                        <div class="my-4">
                            <label class="form-label fw-semibold">Coupon Code</label>

                            <div class="input-group">
                                <input type="text" wire:model="couponCode" class="form-control rounded-start-pill"
                                    placeholder="Enter coupon code">

                                <button type="button" wire:click="applyCoupon"
                                    class="btn btn-primary rounded-end-pill">
                                    Apply
                                </button>
                            </div>

                            @if ($couponMessage)
                                <div class="text-success small mt-2">
                                    {{ $couponMessage }}

                                    <button type="button" wire:click="removeCoupon"
                                        class="btn btn-sm btn-link text-danger p-0 ms-2">
                                        Remove
                                    </button>
                                </div>
                            @endif

                            @if ($couponError)
                                <div class="text-danger small mt-2">
                                    {{ $couponError }}
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <span>Subtotal</span>
                            <strong>{{ number_format($this->subtotal, 2) }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <span>Shipping</span>
                            <strong>{{ number_format($this->shipping, 2) }}</strong>
                        </div>

                        @if ($discount > 0)
                            <div class="d-flex justify-content-between mt-2 text-success">
                                <span>Discount</span>
                                <strong>- {{ number_format($discount, 2) }}</strong>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-2">
                            <span>{{ $taxName }} ({{ $taxRate }}%)</span>
                            <strong>{{ number_format($this->taxAmount, 2) }}</strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between fs-5">
                            <strong>Total</strong>
                            <strong>{{ number_format($this->total, 2) }}</strong>
                        </div>

                    @endif

                </div>
            </div>
        </div>

    </div>

</div>
