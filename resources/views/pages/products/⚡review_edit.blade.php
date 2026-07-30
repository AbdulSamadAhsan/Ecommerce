<?php

use Livewire\Component;
use App\Models\Review;

new class extends Component {
    public Review $review;

    public int $reviewId;

    public $status;

    public $is_approved;

    public function mount($id): void
    {
        $this->review = Review::with(['product', 'customer.user', 'sale'])->findOrFail($id);

        $this->reviewId = $this->review->id;

        $this->status = $this->review->status;
        $this->is_approved = $this->review->is_approved;
    }

    public function update(): void
    {
        $validated = $this->validate([
            'status' => ['required'],
            'is_approved' => ['required'],
        ]);

        $this->review->update($validated);

        session()->flash('success', 'Review updated successfully.');

        $this->redirect(route('products.review'), navigate: true);
    }
};
?>

<div class="dashboard-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Edit Review</h3>
            <p class="text-muted mb-0">Only Status and Approval can be updated.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="update">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label class="form-label">Product</label>
                <input type="text" class="form-control" value="{{ $review->product->name ?? 'N/A' }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Customer</label>
                <input type="text" class="form-control" value="{{ $review->customer->user->name ?? 'N/A' }}"
                    readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Customer Email</label>
                <input type="text" class="form-control" value="{{ $review->customer->user->email ?? 'N/A' }}"
                    readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Rating</label>
                <input type="text" class="form-control" value="{{ $review->rating }}/5" readonly>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">Review</label>
                <textarea class="form-control" rows="5" readonly>{{ $review->review }}</textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>

                <select wire:model="status" class="form-select">
                    <option value="">Select Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>

                @error('status')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Approval Status</label>

                <select wire:model="is_approved" class="form-select">
                    <option value="1">Approved</option>
                    <option value="0">Pending</option>
                </select>

                @error('is_approved')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Created At</label>
                <input type="text" class="form-control" value="{{ $review->created_at->format('d M Y h:i A') }}"
                    readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Last Updated</label>
                <input type="text" class="form-control" value="{{ $review->updated_at->format('d M Y h:i A') }}"
                    readonly>
            </div>

        </div>

        <div class="mt-4 d-flex gap-2">

            <button type="submit" class="btn btn-primary rounded-pill">
                Update Review
            </button>

            <a href="{{ route('products.review') }}" wire:navigate class="btn btn-secondary rounded-pill">
                Cancel
            </a>

        </div>

    </form>

</div>
