<?php

use Livewire\Component;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public int|float|string $amount = '';

    public string $payment_method = 'cash';

    public string $transaction_id = '';

    // Card fields
    public string $card_holder_name = '';
    public string $card_number = '';
    public string $card_expiry = '';
    public string $card_cvv = '';

    // Easypaisa / JazzCash fields
    public string $mobile_account_name = '';
    public string $mobile_account_number = '';

    // Bank transfer fields
    public string $bank_name = '';
    public string $account_title = '';
    public string $account_number = '';
    public string $iban = '';

    public function updatedPaymentMethod(): void
    {
        $this->reset(['transaction_id', 'card_holder_name', 'card_number', 'card_expiry', 'card_cvv', 'mobile_account_name', 'mobile_account_number', 'bank_name', 'account_title', 'account_number', 'iban']);
    }

    public function addWalletBalance(): void
    {
        $rules = [
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required',
        ];

        if ($this->payment_method === 'card') {
            $rules += [
                'card_holder_name' => 'required|string|min:3',
                'card_number' => 'required|string|min:13|max:19',
                'card_expiry' => 'required|string|max:10',
                'card_cvv' => 'required|string|min:3|max:4',
            ];
        }

        if (in_array($this->payment_method, ['easypaisa', 'jazzcash'])) {
            $rules += [
                'mobile_account_name' => 'required|string|min:3',
                'mobile_account_number' => 'required|string|min:11|max:15',
                'transaction_id' => 'required|string|max:255',
            ];
        }

        if ($this->payment_method === 'bank_transfer') {
            $rules += [
                'bank_name' => 'required|string|min:3',
                'account_title' => 'required|string|min:3',
                'account_number' => 'required|string|min:5',
                'iban' => 'required|string|min:15|max:34',
                'transaction_id' => 'required|string|max:255',
            ];
        }

        if ($this->payment_method === 'cash') {
            $rules += [
                'transaction_id' => 'nullable|string|max:255',
            ];
        }

        $this->validate($rules);

        session()->flash('success', 'Wallet top-up request submitted successfully.');

        $this->reset(['amount', 'transaction_id', 'card_holder_name', 'card_number', 'card_expiry', 'card_cvv', 'mobile_account_name', 'mobile_account_number', 'bank_name', 'account_title', 'account_number', 'iban']);

        $this->payment_method = 'cash';
    }
};
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">Add Wallet Balance</h2>

    <div class="row g-4">
        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success rounded-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="addWalletBalance">

                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" wire:model="amount" class="form-control rounded-pill"
                                placeholder="Enter amount">
                            @error('amount')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select wire:model.live="payment_method" class="form-select rounded-pill">
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="easypaisa">Easypaisa</option>
                                <option value="jazzcash">JazzCash</option>
                                <option value="card">Card</option>
                            </select>
                            @error('payment_method')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        @if ($payment_method === 'card')
                            <div class="border rounded-4 p-4 mb-3">
                                <h5 class="fw-bold mb-3">
                                    Card Details
                                </h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Card Holder Name</label>
                                        <input type="text" wire:model="card_holder_name"
                                            class="form-control rounded-pill" placeholder="Name on card">
                                        @error('card_holder_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Card Number</label>
                                        <input type="text" wire:model="card_number" class="form-control rounded-pill"
                                            placeholder="0000 0000 0000 0000">
                                        @error('card_number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Expiry Date</label>
                                        <input type="text" wire:model="card_expiry" class="form-control rounded-pill"
                                            placeholder="MM/YY">
                                        @error('card_expiry')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">CVV</label>
                                        <input type="password" wire:model="card_cvv" class="form-control rounded-pill"
                                            placeholder="123">
                                        @error('card_cvv')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (in_array($payment_method, ['easypaisa', 'jazzcash']))
                            <div class="border rounded-4 p-4 mb-3">
                                <h5 class="fw-bold mb-3">
                                    {{ ucfirst($payment_method) }} Account Details
                                </h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Account Name</label>
                                        <input type="text" wire:model="mobile_account_name"
                                            class="form-control rounded-pill" placeholder="Account holder name">
                                        @error('mobile_account_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Mobile Account Number</label>
                                        <input type="text" wire:model="mobile_account_number"
                                            class="form-control rounded-pill" placeholder="03XXXXXXXXX">
                                        @error('mobile_account_number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Transaction ID</label>
                                        <input type="text" wire:model="transaction_id"
                                            class="form-control rounded-pill" placeholder="Enter transaction ID">
                                        @error('transaction_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($payment_method === 'bank_transfer')
                            <div class="border rounded-4 p-4 mb-3">
                                <h5 class="fw-bold mb-3">
                                    Bank Transfer Details
                                </h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Bank Name</label>
                                        <input type="text" wire:model="bank_name" class="form-control rounded-pill"
                                            placeholder="Bank name">
                                        @error('bank_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Account Title</label>
                                        <input type="text" wire:model="account_title"
                                            class="form-control rounded-pill" placeholder="Account title">
                                        @error('account_title')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Account Number</label>
                                        <input type="text" wire:model="account_number"
                                            class="form-control rounded-pill" placeholder="Account number">
                                        @error('account_number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">IBAN</label>
                                        <input type="text" wire:model="iban" class="form-control rounded-pill"
                                            placeholder="PK00 XXXX 0000 0000 0000 0000">
                                        @error('iban')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Transaction ID / Reference No</label>
                                        <input type="text" wire:model="transaction_id"
                                            class="form-control rounded-pill"
                                            placeholder="Enter bank transaction reference">
                                        @error('transaction_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($payment_method === 'cash')
                            <div class="mb-3">
                                <label class="form-label">Reference No</label>
                                <input type="text" wire:model="transaction_id" class="form-control rounded-pill"
                                    placeholder="Optional">
                                @error('transaction_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @endif

                        <button class="btn btn-primary rounded-pill px-4">
                            Submit Top-Up Request
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
