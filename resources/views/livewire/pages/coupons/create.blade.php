<?php

use App\Models\Coupon;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component {
    public string $code = '';
    public string $title = '';
    public string $discount_type = 'percentage';
    public string $discount_value = '';
    public string $minimum_order_amount = '0';
    public ?string $start_date = null;
    public ?string $end_date = null;
    public ?string $usage_limit = null;
    public bool $status = true;

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'min:2', 'max:100', 'unique:coupons,code'],
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0'],
            'minimum_order_amount' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'boolean'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($validated['discount_type'] === 'percentage' && (float) $validated['discount_value'] > 100) {
            $this->addError('discount_value', 'Percentage discount cannot be greater than 100%.');
            return null;
        }

        $validated['code'] = Str::upper($validated['code']);
        $validated['minimum_order_amount'] = $validated['minimum_order_amount'] ?: 0;
        $validated['usage_limit'] = $validated['usage_limit'] ?: null;
        $validated['used_count'] = 0;

        Coupon::create($validated);

        session()->flash('success', 'Coupon created successfully.');

        return redirect()->route('coupons.index');
    }
};

?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Add Coupon</h3>
            <p class="text-muted mb-0">Create discount coupon for your store.</p>
        </div>
        <a href="{{ route('coupons.index') }}" class="btn btn-light rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Coupon Code</label>
                        <input type="text" wire:model.live="code" class="form-control rounded-pill" placeholder="EIDSALE20">
                        @error('code') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" wire:model.live="title" class="form-control rounded-pill" placeholder="Eid Sale Discount">
                        @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Discount Type</label>
                        <select wire:model.live="discount_type" class="form-select rounded-pill">
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed</option>
                        </select>
                        @error('discount_type') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Discount Value</label>
                        <input type="number" step="0.01" wire:model.live="discount_value" class="form-control rounded-pill" placeholder="20">
                        @error('discount_value') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Minimum Order Amount</label>
                        <input type="number" step="0.01" wire:model.live="minimum_order_amount" class="form-control rounded-pill" placeholder="0">
                        @error('minimum_order_amount') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Start Date</label>
                        <input type="date" wire:model.live="start_date" class="form-control rounded-pill">
                        @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">End Date</label>
                        <input type="date" wire:model.live="end_date" class="form-control rounded-pill">
                        @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Usage Limit</label>
                        <input type="number" wire:model.live="usage_limit" class="form-control rounded-pill" placeholder="Leave empty for unlimited">
                        @error('usage_limit') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" wire:model.live="status" id="couponStatus">
                            <label class="form-check-label fw-semibold" for="couponStatus">Active</label>
                        </div>
                        @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('coupons.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <span wire:loading.remove wire:target="save">Save Coupon</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
