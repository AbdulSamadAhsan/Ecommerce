<?php

use Livewire\Component;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public array $stats = [
        'orders' => 12,
        'returns' => 2,
        'wallet' => 12000,
        'pending' => 3,
    ];
};
?>

<div class="container py-5">
    <h2 class="fw-bold mb-4">Customer Dashboard</h2>

    <div class="row g-4">
        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body">
                            <i class="bi bi-bag-check-fill fs-2 text-primary"></i>
                            <h3 class="fw-bold mt-2">{{ $stats['orders'] }}</h3>
                            <p class="text-muted mb-0">Total Orders</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body">
                            <i class="bi bi-clock-history fs-2 text-warning"></i>
                            <h3 class="fw-bold mt-2">{{ $stats['pending'] }}</h3>
                            <p class="text-muted mb-0">Pending Orders</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body">
                            <i class="bi bi-arrow-return-left fs-2 text-danger"></i>
                            <h3 class="fw-bold mt-2">{{ $stats['returns'] }}</h3>
                            <p class="text-muted mb-0">Returns</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body">
                            <i class="bi bi-wallet2 fs-2 text-success"></i>
                            <h3 class="fw-bold mt-2">Rs {{ number_format($stats['wallet']) }}</h3>
                            <p class="text-muted mb-0">Wallet</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-body">
                    <h4 class="fw-bold">Welcome Back</h4>
                    <p class="text-muted mb-0">Manage your orders, returns, wallet balance and profile from your account
                        dashboard.</p>
                </div>
            </div>
        </div>
    </div>
</div>
