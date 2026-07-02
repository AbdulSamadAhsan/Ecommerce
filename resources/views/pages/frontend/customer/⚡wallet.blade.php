<?php

use Livewire\Component;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public $balance;

    public array $transactions = [];
    public function mount()
    {
        $this->balance = auth('customer')->user()->customer->wallet->balance;
        $this->transactions = auth('customer')->user()->customer->wallet->transactions->toArray();
    }
};
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">My Wallet</h2>

    <div class="row g-4">
        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="bg-primary text-white rounded-4 p-4 mb-4 shadow-sm">
                <p class="mb-1">Available Balance</p>
                <h1 class="fw-bold mb-0">Rs {{ number_format($balance) }}</h1>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Wallet Transactions</h4>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <span
                                                class="badge {{ $transaction['type'] === 'debit' ? 'bg-danger' : 'bg-success' }}">
                                                {{ ucfirst($transaction['type']) }}
                                            </span>
                                        </td>
                                        <td>Rs {{ number_format($transaction['amount']) }}</td>
                                        <td>{{ date('d-F-Y', strtotime($transaction['created_at'])) }}</td>
                                        <td>{{ date('h:i a', strtotime($transaction['created_at'])) }}</td>
                                        <td>{{ $transaction['description'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
