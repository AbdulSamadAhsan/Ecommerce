<?php

use Livewire\Component;
use App\Models\Coupon;

new class extends Component {
    public Coupon $coupon;

    public function mount($id)
    {
        $this->coupon = Coupon::findOrFail($id);
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Coupon Details</h3>

        <a href="{{ route('coupons.index') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $coupon->code }}</h5>
        </div>

        <div class="card-body">
            <p><strong>ID:</strong> #{{ $coupon->id }}</p>
            <p><strong>Code:</strong> {{ $coupon->code }}</p>
            <p><strong>Type:</strong> {{ ucfirst($coupon->type) }}</p>
            <p><strong>Value:</strong>
                {{ $coupon->type === 'percentage' ? $coupon->value . '%' : 'Rs. ' . number_format($coupon->value, 2) }}
            </p>
            <p><strong>Usage Limit:</strong> {{ $coupon->usage_limit ?? 'Unlimited' }}</p>
            <p><strong>Expiry Date:</strong> {{ $coupon->expiry_date ?? '-' }}</p>

            <p>
                <strong>Status:</strong>
                <span class="badge {{ $coupon->status ? 'bg-success' : 'bg-danger' }}">
                    {{ $coupon->status ? 'Active' : 'Inactive' }}
                </span>
            </p>
        </div>
    </div>
</div>
