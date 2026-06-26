<?php

use Livewire\Component;
use App\Models\Coupon;
use Illuminate\Validation\Rule;

new class extends Component {
    public $couponId;
    public $code = '';
    public $type = 'percentage';
    public $value = '';
    public $usage_limit = '';
    public $expiry_date = '';
    public $status = 1;

    public function mount($id)
    {
        $coupon = Coupon::findOrFail($id);

        $this->couponId = $coupon->id;
        $this->code = $coupon->code;
        $this->type = $coupon->discount_type;
        $this->value = $coupon->discount_value;
        $this->usage_limit = $coupon->usage_limit;
        $this->expiry_date = $coupon->expiry_date;
        $this->status = $coupon->is_active;
    }

    protected function rules()
    {
        return [
            'code' => ['required', 'min:2', 'max:100', Rule::unique('coupons', 'code')->ignore($this->couponId)],
            'type' => 'required|string',
            'value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expiry_date' => 'nullable|date',
            'status' => 'required|boolean',
        ];
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function update()
    {
        $this->validate();

        Coupon::findOrFail($this->couponId)->update([
            'code' => strtoupper($this->code),
            'discount_type' => $this->type,
            'discount_value' => $this->value,
            'usage_limit' => $this->usage_limit ?: null,
            'expiry_date' => $this->expiry_date ?: null,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Coupon updated successfully.');
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Edit Coupon</h4>
    </div>

    <div class="card-body">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form wire:submit="update">

            <div class="mb-3">
                <label class="form-label">Coupon Code</label>
                <input type="text" wire:model.live="code" class="form-control @error('code') is-invalid @enderror">
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Discount Type</label>
                <select wire:model.live="type" class="form-select">
                    <option value="percentage">Percentage</option>
                    <option value="fixed">Fixed Amount</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Discount Value</label>
                <input type="number" step="0.01" wire:model.live="value"
                    class="form-control @error('value') is-invalid @enderror">
                @error('value')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Usage Limit</label>
                <input type="number" wire:model.live="usage_limit"
                    class="form-control @error('usage_limit') is-invalid @enderror">
                @error('usage_limit')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Expiry Date</label>
                <input type="date" wire:model.live="expiry_date"
                    class="form-control @error('expiry_date') is-invalid @enderror">
                @error('expiry_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>
                <select wire:model.live="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('coupons.index') }}" class="btn btn-secondary rounded-pill">
                    Back
                </a>

                <button class="btn btn-warning rounded-pill">
                    Update Coupon
                </button>
            </div>

        </form>
    </div>
</div>
