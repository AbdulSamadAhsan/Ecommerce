<?php

use App\Models\Coupon;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component {
    public Coupon $coupon;

    public function mount(int $id): void
    {
        $this->coupon = Coupon::findOrFail($id);
    }
};

?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Coupon Detail</h3>
            <p class="text-muted mb-0">View coupon discount and validity information.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-warning rounded-pill px-4">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>
            <a href="{{ route('coupons.index') }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-muted small">Code</div>
                    <span class="badge bg-dark rounded-pill px-3 py-2 fs-6">{{ $coupon->code }}</span>
                </div>
                <div class="col-md-8">
                    <div class="text-muted small">Title</div>
                    <div class="fw-bold fs-5">{{ $coupon->title }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Discount Type</div>
                    <div class="text-capitalize fw-semibold">{{ $coupon->discount_type }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Discount Value</div>
                    <div class="fw-semibold">{{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : number_format($coupon->discount_value, 2) }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Minimum Order</div>
                    <div class="fw-semibold">{{ number_format($coupon->minimum_order_amount, 2) }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Start Date</div>
                    <div>{{ $coupon->start_date?->format('d M Y') ?: 'Any time' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">End Date</div>
                    <div>{{ $coupon->end_date?->format('d M Y') ?: 'No expiry' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Usage</div>
                    <div>{{ $coupon->used_count }} / {{ $coupon->usage_limit ?: 'Unlimited' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Status</div>
                    <span class="badge {{ $coupon->status ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-2">
                        {{ $coupon->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Created At</div>
                    <div>{{ $coupon->created_at?->format('d M Y h:i A') }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Updated At</div>
                    <div>{{ $coupon->updated_at?->format('d M Y h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
