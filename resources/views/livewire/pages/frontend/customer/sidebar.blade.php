<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-body text-center">
        <div class="mb-3">
            <i class="bi bi-person-circle display-4 text-primary"></i>
        </div>
        <h5 class="fw-bold mb-1">{{ auth()->user()->name ?? 'Customer' }}</h5>
        <small class="text-muted">{{ auth()->user()->email ?? 'customer@example.com' }}</small>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="list-group list-group-flush">
        <a wire:navigate href="{{ route('customer.dashboard') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill me-2"></i> Dashboard
        </a>
        <a wire:navigate href="{{ route('customer.orders') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('customer.orders') || request()->routeIs('customer.order.detail') ? 'active' : '' }}">
            <i class="bi bi-bag-check-fill me-2"></i> Order History
        </a>
        <a wire:navigate href="{{ route('customer.returns') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('customer.returns') ? 'active' : '' }}">
            <i class="bi bi-arrow-return-left me-2"></i> Returns
        </a>
        <a wire:navigate href="{{ route('customer.wallet') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('customer.wallet') ? 'active' : '' }}">
            <i class="bi bi-wallet2 me-2"></i> Wallet
        </a>
        <a wire:navigate href="{{ route('customer.profile') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
            <i class="bi bi-person-fill me-2"></i> Profile
        </a>
        <a wire:navigate href="{{ route('customer.wallet.add') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('customer.wallet.add') ? 'active' : '' }}">
            <i class="bi bi-plus-circle-fill me-2"></i> Add Wallet Balance
        </a>
        <a wire:navigate href="{{ route('customer.support.ticket') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('customer.support.ticket') ? 'active' : '' }}">
            <i class="bi bi-headset me-2"></i> Support Ticket
        </a>
        <a wire:navigate href="{{ route('customer.my.support.tickets') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('customer.my.support.tickets') ? 'active' : '' }}">
            <i class="bi bi-ticket-detailed-fill me-2"></i>
            My Support Tickets
        </a>
        <a wire:navigate href="{{ route('customer.wishlist') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('customer.wishlist') ? 'active' : '' }}">
            <i class="bi bi-heart-fill me-2"></i> Wishlist
        </a>
        <a href="#" class="list-group-item list-group-item-action text-danger">
            <i class="bi bi-box-arrow-right me-2"></i>
            Logout
        </a>
    </div>
</div>
