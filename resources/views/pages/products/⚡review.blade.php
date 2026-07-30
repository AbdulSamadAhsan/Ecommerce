<?php

use Livewire\Component;
use App\Models\Review;

new class extends Component {
    public string $search = '';

    public $reviews;

    public function mount(): void
    {
        $this->loadReviews();
    }

    public function updatedSearch(): void
    {
        $this->loadReviews();
    }

    public function loadReviews(): void
    {
        $this->reviews = Review::with(['product', 'customer.user', 'sale'])
            ->when($this->search, function ($query) {
                $query
                    ->where('review', 'like', '%' . $this->search . '%')
                    ->orWhereHas('product', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('customer.user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->get()
            ->toArray();
    }
};

?>
<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Reviews</h3>
            <p class="text-muted mb-0">Manage customer reviews</p>
        </div>
    </div>

    <div class="dashboard-card">

        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search review, customer or product...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                        <th>Approved</th>
                        <th>Sale ID</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reviews as $review)
                        <tr>

                            <td>#{{ $review['id'] }}</td>

                            <td>{{ $review['product']['name'] ?? 'N/A' }}</td>

                            <td>{{ $review['customer']['user']['name'] ?? 'N/A' }}</td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $review['rating'] }}/5
                                </span>
                            </td>

                            <td>{{ Str::limit($review['review'], 50) }}</td>

                            <td>
                                <span class="badge {{ $review['status'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $review['status'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge {{ $review['is_approved'] ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $review['is_approved'] ? 'Approved' : 'Pending' }}
                                </span>
                            </td>

                            <td>{{ $review['sale_id'] ?? '-' }}</td>

                            <td>{{ \Carbon\Carbon::parse($review['created_at'])->format('d M Y') }}</td>

                            <td>

                                <a class="btn btn-sm btn-primary rounded-pill"
                                    href="{{ route('products.review.edit', $review['id']) }}">Edit</a>
                                <button class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                No reviews found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
