<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use App\Models\ShippingMethod;
use App\Models\Coupon;
use App\Models\Tax;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Cart;
use App\Models\SaleItem;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\Wallet;

use Barryvdh\DomPDF\Facade\Pdf;

new class extends Component {
    public $cart;
    public $cartData;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $city = '';

    public string $paymentMethod = 'cash';
    public string $remainingPaymentMethod = 'cash';

    public float $walletBalance = 0;
    public float $walletUsedAmount = 0;
    public float $remainingAmount = 0;

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

    public $invoice_no = '';

    public string $cardHolderName = '';
    public string $cardNumber = '';
    public string $cardExpiry = '';
    public string $cardCvv = '';
    public $bank_name;
    public $transaction_id;
    public $transfer_date;
    public $iban;
    public $branch_name;
    public $remainingPaymentcardHolderName;
    public string $remainingPaymentcardNumber = '';
    public string $remainingPaymentcardExpiry = '';
    public string $remainingPaymentcardCvv = '';
    public $remainingPaymentcardbank_name;
    public $remainingPaymentcardtransaction_id;
    public $remainingPaymentcardtransfer_date;
    public $remainingPaymentcardiban;
    public $remainingPaymentcardbranch_name;
    public function generateInvoiceNo()
    {
        $lastSale = Sale::latest('id')->first();
        $nextId = $lastSale ? $lastSale->id + 1 : 1;

        return 'SALE-' . now()->format('Ymd') . '-' . date('Hi') . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public function mount()
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login');
        }

        $user = Auth::guard('customer')->user();
        $customer = $user->customer;

        $this->cartData = Cart::with(['items.product'])
            ->where('user_id', Auth::guard('customer')->id())
            ->first();

        if (!$this->cartData) {
            return redirect()->route('front');
        }

        $this->cart = $this->cartData->items;

        $this->email = $user->email;
        $this->name = $user->name;
        $this->phone = $customer->phone ?? '';

        $wallet = Wallet::firstOrCreate(['customer_id' => $customer->id], ['balance' => 0]);

        $this->walletBalance = (float) $wallet->balance;

        $address = $customer->addresses()->where('is_default', true)->first();

        if ($address) {
            $this->city = $address->city ?? '';
            $this->address = trim(($address->address_line_1 ?? '') . ' ' . ($address->address_line_2 ?? '') . ' ' . ($address->province ?? '') . ' ' . ($address->city ?? ''));
        }

        $this->invoice_no = $this->generateInvoiceNo();

        $this->shippingMethods = ShippingMethod::where('is_active', 1)
            ->orderBy('cost')
            ->get()
            ->map(
                fn($method) => [
                    'id' => $method->id,
                    'name' => $method->name,
                    'cost' => (float) $method->cost,
                    'shipping_category' => $method->shipping_category ?? null,
                ],
            )
            ->toArray();

        $express = collect($this->shippingMethods)->firstWhere('shipping_category', 'express');

        $this->shippingMethod = (string) ($express['id'] ?? ($this->shippingMethods[0]['id'] ?? ''));

        $tax = Tax::where('is_active', 1)->where('category', 'sales')->first();

        if ($tax) {
            $this->taxId = $tax->id;
            $this->taxName = $tax->name;
            $this->taxRate = (float) $tax->rate;
        }

        $this->calculateWalletPayment();
    }

    public function getCartCountProperty(): int
    {
        return collect($this->cart)->sum('quantity');
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->cart)->sum(fn($item) => $item->price * $item->quantity);
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
        if ($this->paymentMethod !== 'card') {
            $this->reset(['cardHolderName', 'cardNumber', 'cardExpiry', 'cardCvv']);
        }

        $this->calculateWalletPayment();
    }

    public function updatedShippingMethod(): void
    {
        $this->calculateWalletPayment();
    }

    public function updatedRemainingPaymentMethod(): void
    {
        $this->calculateWalletPayment();
    }

    public function calculateWalletPayment(): void
    {
        $this->walletUsedAmount = 0;
        $this->remainingAmount = 0;

        if ($this->paymentMethod !== 'wallet') {
            return;
        }

        $this->walletUsedAmount = min($this->walletBalance, $this->total);
        $this->remainingAmount = max(0, $this->total - $this->walletUsedAmount);
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

        $coupon = Coupon::where('code', $code)->where('is_active', 1)->first();

        if (!$coupon) {
            $this->couponError = 'Invalid coupon code.';
            return;
        }

        if ($coupon->expires_at && now()->gt($coupon->expires_at)) {
            $this->couponError = 'This coupon has expired.';
            return;
        }

        $minimumAmount = $coupon->minimum_order_amount ?? ($coupon->minimum_amount ?? 0);

        if ($this->subtotal < $minimumAmount) {
            $this->couponError = 'Minimum order amount is ' . number_format($minimumAmount, 2);
            return;
        }

        if ($coupon->discount_type === 'percentage') {
            $this->discount = ($this->subtotal * $coupon->discount_value) / 100;
        } else {
            $this->discount = $coupon->discount_value;
        }

        $this->discount = min($this->discount, $this->subtotal);
        $this->couponId = $coupon->id;
        $this->couponCode = $code;
        $this->couponMessage = 'Coupon applied successfully.';

        $this->calculateWalletPayment();
    }

    public function removeCoupon(): void
    {
        $this->couponId = null;
        $this->couponCode = '';
        $this->discount = 0;
        $this->couponMessage = '';
        $this->couponError = '';

        $this->calculateWalletPayment();
    }

    private function finalPaymentMethod(): string
    {
        if ($this->paymentMethod !== 'wallet') {
            return $this->paymentMethod;
        }

        if ($this->remainingAmount <= 0) {
            return 'wallet';
        }

        return 'wallet+' . $this->remainingPaymentMethod;
    }

    private function finalPaymentStatus(): string
    {
        if ($this->paymentMethod === 'wallet') {
            return $this->remainingAmount <= 0 ? 'paid' : 'partial';
        }

        return in_array($this->paymentMethod, ['card', 'bank'], true) ? 'paid' : 'pending';
    }

    private function paidAmount(): float
    {
        if ($this->paymentMethod === 'wallet') {
            return $this->walletUsedAmount;
        }

        return in_array($this->paymentMethod, ['card', 'bank'], true) ? $this->total : 0;
    }

    private function dueAmount(): float
    {
        return max(0, $this->total - $this->paidAmount());
    }

    public function placeOrder()
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required|min:10',
            'address' => 'required|min:10',
            'city' => 'required',
            'paymentMethod' => 'required|in:cash,card,bank,wallet',
            'shippingMethod' => 'required|exists:shipping_methods,id',
        ];

        if ($this->paymentMethod === 'card') {
            $rules += [
                'cardHolderName' => 'required|min:3',
                'cardNumber' => 'required|min:13|max:19',
                'cardExpiry' => 'required|min:4|max:10',
                'cardCvv' => 'required|min:3|max:4',
            ];
        }

        if ($this->paymentMethod === 'wallet' && $this->remainingAmount > 0) {
            $rules += [
                'remainingPaymentMethod' => 'required|in:cash,card,bank',
            ];
        }

        $this->validate($rules);

        $order = null;
        $invoice = null;

        $fileName = "invoices/{$this->invoice_no}.pdf";
        $directory = storage_path('app/public/invoices');
        $fullPath = storage_path('app/public/' . $fileName);

        try {
            DB::transaction(function () use (&$order, &$invoice, $fileName, $directory, $fullPath) {
                $customer = Auth::guard('customer')->user()->customer;

                $wallet = Wallet::where('customer_id', $customer->id)->lockForUpdate()->firstOrFail();

                $this->walletBalance = (float) $wallet->balance;
                $this->calculateWalletPayment();

                if ($this->paymentMethod === 'wallet' && $this->walletUsedAmount <= 0) {
                    throw new \Exception('Your wallet balance is empty.');
                }

                $sale = Sale::create([
                    'customer_id' => $customer->id,
                    'invoice_no' => $this->invoice_no,
                    'subtotal' => $this->subtotal,
                    'total_amount' => $this->total,
                    'tax' => $this->taxAmount,
                    'shipping_cost' => $this->shipping,
                    'sale_type' => 'online',
                    'discount' => $this->discount,
                    'payment_method' => $this->finalPaymentMethod(),
                    'payment_status' => $this->finalPaymentStatus(),
                    'paid_amount' => $this->paidAmount(),
                    'due_amount' => $this->dueAmount(),
                ]);

                if ($this->paymentMethod === 'wallet') {
                    $wallet->decrement('balance', $this->walletUsedAmount);

                    $wallet->transactions()->create([
                        'amount' => $this->walletUsedAmount,
                        'type' => 'debit',
                        'reference_type' => 'sale',
                        'reference_id' => $sale->id,
                        'description' => 'Payment for invoice ' . $this->invoice_no,
                    ]);
                }

                $order = Order::create([
                    'sale_id' => $sale->id,
                    'city' => $this->city,
                    'address' => $this->address,
                    'order_date' => now(),

                    'coupon_code' => $this->couponCode ?: null,
                ]);

                foreach ($this->cart as $item) {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->firstOrFail();

                    if ($product->quantity < $item->quantity) {
                        throw new \Exception($product->name . ' stock is not enough.');
                    }

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->price,
                        'total_price' => $item->price * $item->quantity,
                    ]);

                    $stockBefore = $product->quantity;
                    $stockAfter = $stockBefore - $item->quantity;

                    StockMovement::create([
                        'quantity' => $item->quantity,
                        'product_id' => $item->product_id,
                        'warehouse_id' => $product->warehouse_id,
                        'supplier_id' => $product->supplier_id,
                        'type' => 'sale',
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockAfter,
                        'reference_no' => $sale->invoice_no,
                    ]);

                    Stock::updateOrCreate(
                        [
                            'product_id' => $item->product_id,
                            'warehouse_id' => $product->warehouse_id,
                        ],
                        [
                            'quantity' => $stockAfter,
                            'minimum_stock' => $product->minimum_stock,
                        ],
                    );

                    $product->update([
                        'quantity' => $stockAfter,
                    ]);
                }

                $tracking = 'TRK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));

                Shipment::create([
                    'order_id' => $order->id,
                    'shipping_method_id' => $this->shippingMethod,
                    'tracking_number' => $tracking,

                    'status' => 'pending',
                ]);

                $invoice = Invoice::create([
                    'sale_id' => $sale->id,
                    'invoice_date' => now(),
                ]);

                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                $order->load(['sale.customer', 'sale.items.product', 'shipment.shippingMethod', 'invoice']);

                $pdf = Pdf::loadView('pdf.invoice', compact('order'));
                $pdf->save($fullPath);

                if (!File::exists($fullPath)) {
                    throw new \Exception('Invoice PDF was not created.');
                }

                $invoice->update([
                    'pdf_path' => $fileName,
                ]);

                $this->cartData->items()->delete();
                $this->cartData->delete();
            });

            $this->cart = [];
            $this->removeCoupon();

            session()->flash('success', 'Order placed successfully.');

            return redirect()->route('customer.orders.show', [
                'order' => $order->id,
            ]);
        } catch (\Exception $e) {
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }

            session()->flash('error', $e->getMessage());
            return;
        }
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

    @if (session('error'))
        <div class="alert alert-danger rounded-4">
            {{ session('error') }}
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
                            <input type="text" wire:model="name" readonly class="form-control rounded-pill">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" wire:model="email" readonly class="form-control rounded-pill">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" wire:model="phone" readonly class="form-control rounded-pill">
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
                            <input class="form-check-input" type="radio" wire:model.live="paymentMethod"
                                value="cash" id="cash">
                            <label class="form-check-label" for="cash">Cash on Delivery</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" wire:model.live="paymentMethod"
                                value="card" id="card">
                            <label class="form-check-label" for="card">Card Payment</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" wire:model.live="paymentMethod"
                                value="bank" id="bank">
                            <label class="form-check-label" for="bank">Bank Transfer</label>
                        </div>
                        <div class="form-check mb-4">

                            <input class="form-check-input" type="radio" wire:model.live="paymentMethod"
                                value="wallet" id="wallet" @disabled($walletBalance <= 0)>

                            <label class="form-check-label {{ $walletBalance <= 0 ? 'text-muted' : '' }}"
                                for="wallet">

                                Wallet Payment

                                <span class="text-muted">
                                    (Balance: Rs {{ number_format($walletBalance, 2) }})
                                </span>

                                @if ($walletBalance <= 0)
                                    <span class="badge bg-danger ms-2">
                                        Insufficient Balance
                                    </span>
                                @endif

                            </label>

                        </div>


                        @if ($paymentMethod === 'wallet')
                            <div class="border rounded-4 p-4 mb-4 bg-light">
                                <h5 class="fw-bold mb-3">Wallet Payment</h5>

                                <div class="d-flex justify-content-between mb-2">
                                    <span>Wallet Balance</span>
                                    <strong>Rs {{ number_format($walletBalance, 2) }}</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span>Wallet Used</span>
                                    <strong class="text-success">Rs {{ number_format($walletUsedAmount, 2) }}</strong>
                                </div>

                                <div class="d-flex justify-content-between mb-3">
                                    <span>Remaining Amount</span>
                                    <strong class="{{ $remainingAmount > 0 ? 'text-danger' : 'text-success' }}">
                                        Rs {{ number_format($remainingAmount, 2) }}
                                    </strong>
                                </div>

                                @if ($remainingAmount > 0)
                                    <label class="form-label fw-semibold">
                                        Pay remaining amount with
                                    </label>

                                    <select wire:model.live="remainingPaymentMethod" class="form-select rounded-pill">
                                        <option value="cash">Cash on Delivery</option>
                                        <option value="card">Card Payment</option>
                                        <option value="bank">Bank Transfer</option>
                                    </select>
                                    @if ($remainingPaymentMethod == 'card')
                                        <div class="border rounded-4 p-4 mt-4 mb-4 bg-light">
                                            <h5 class="fw-bold mb-3">Card Details</h5>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Card Holder Name</label>
                                                <input type="text" wire:model="remainingPaymentcardHolderName"
                                                    class="form-control rounded-pill">
                                                @error('cardHolderName')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Card Number</label>
                                                <input type="text" wire:model="remainingPaymentcardNumber"
                                                    class="form-control rounded-pill">
                                                @error('cardNumber')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Expiry Date</label>
                                                    <input type="text" wire:model="remainingPaymentcardExpiry"
                                                        class="form-control rounded-pill" placeholder="MM/YY">
                                                    @error('cardExpiry')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">CVV</label>
                                                    <input type="password" wire:model="remainingPaymentcardCvv"
                                                        class="form-control rounded-pill">
                                                    @error('cardCvv')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($remainingPaymentMethod == 'bank')
                                        <div class="border rounded-4 p-4 mt-4 mb-4 bg-light">
                                            <h5 class="fw-bold mb-3">Bank Details</h5>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Bank Name</label>
                                                <input type="text" wire:model="remainingPaymentbank_name"
                                                    class="form-control rounded-pill">
                                                @error('bank_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>



                                            <div class="row">


                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Branch Name</label>
                                                    <input type="text" wire:model="remainingPaymentbranch_name"
                                                        class="form-control rounded-pill" placeholder="MM/YY">
                                                    @error('cardExpiry')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Transaction ID</label>
                                                    <input type="text" wire:model="remainingPaymenttransaction_id"
                                                        class="form-control rounded-pill">
                                                    @error('transaction_id')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">IBAN</label>
                                                    <input type="text" wire:model="remainingPaymentiban"
                                                        class="form-control rounded-pill" placeholder="MM/YY">
                                                    @error('iban')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-semibold">Transfer Date</label>
                                                    <input type="date" wire:model="remainingPaymenttransfer_date"
                                                        class="form-control rounded-pill">
                                                    @error('transfer_date')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-success rounded-4 mb-0">
                                        Full order amount will be paid from wallet.
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($paymentMethod === 'card')
                            <div class="border rounded-4 p-4 mb-4 bg-light">
                                <h5 class="fw-bold mb-3">Card Details</h5>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Card Holder Name</label>
                                    <input type="text" wire:model="cardHolderName"
                                        class="form-control rounded-pill">
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
                                        <input type="text" wire:model="cardExpiry"
                                            class="form-control rounded-pill" placeholder="MM/YY">
                                        @error('cardExpiry')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">CVV</label>
                                        <input type="password" wire:model="cardCvv"
                                            class="form-control rounded-pill">
                                        @error('cardCvv')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($paymentMethod === 'bank')
                            <div class="border rounded-4 p-4 mb-4 bg-light">
                                <h5 class="fw-bold mb-3">Bank Details</h5>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Bank Name</label>
                                    <input type="text" wire:model="bank_name" class="form-control rounded-pill">
                                    @error('bank_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>



                                <div class="row">


                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Branch Name</label>
                                        <input type="text" wire:model="branch_name"
                                            class="form-control rounded-pill" placeholder="MM/YY">
                                        @error('cardExpiry')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Transaction ID</label>
                                        <input type="text" wire:model="transaction_id"
                                            class="form-control rounded-pill">
                                        @error('transaction_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">IBAN</label>
                                        <input type="text" wire:model="iban" class="form-control rounded-pill"
                                            placeholder="MM/YY">
                                        @error('iban')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Transfer Date</label>
                                        <input type="date" wire:model="transfer_date"
                                            class="form-control rounded-pill">
                                        @error('transfer_date')
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
                            <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                            <strong>{{ number_format($item->price * $item->quantity, 2) }}</strong>
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

                        @if ($paymentMethod === 'wallet')
                            <div class="d-flex justify-content-between mt-2 text-success">
                                <span>Wallet Used</span>
                                <strong>- {{ number_format($walletUsedAmount, 2) }}</strong>
                            </div>

                            @if ($remainingAmount > 0)
                                <div class="d-flex justify-content-between mt-2 text-danger">
                                    <span>Remaining Payable</span>
                                    <strong>{{ number_format($remainingAmount, 2) }}</strong>
                                </div>
                            @endif
                        @endif

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
