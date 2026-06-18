<?php

use Livewire\Component;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public int $id;

    public string $returnReason = '';

    public array $order = [];

    public array $reviews = [];

    public function mount($id): void
    {
        $this->id = (int) $id;

        $this->order = [
            'id' => $this->id,
            'date' => '2026-06-18',
            'status' => 'Delivered',
            'payment' => 'Paid',
            'total' => 185000,
            'items' => [
                [
                    'id' => 1,
                    'name' => 'MacBook Pro M3',
                    'price' => 160000,
                    'qty' => 1,
                    'subtotal' => 160000,
                ],
                [
                    'id' => 2,
                    'name' => 'Wireless Mouse',
                    'price' => 25000,
                    'qty' => 1,
                    'subtotal' => 25000,
                ],
            ],
        ];

        foreach ($this->order['items'] as $item) {
            $this->reviews[$item['id']] = [
                'rating' => '',
                'review' => '',
            ];
        }
    }

    public function requestReturn(int $itemId): void
    {
        $this->validate([
            'returnReason' => 'required|min:5',
        ]);

        session()->flash('success', 'Return request submitted successfully.');

        $this->returnReason = '';
    }

    public function submitReview(int $itemId): void
    {
        $this->validate([
            "reviews.$itemId.rating" => 'required|integer|min:1|max:5',
            "reviews.$itemId.review" => 'nullable|min:5|max:1000',
        ]);

        session()->flash('success', 'Product review submitted successfully.');

        $this->reviews[$itemId] = [
            'rating' => '',
            'review' => '',
        ];
    }
};
?>

<div class="container py-5">

    <div class="row g-4">

        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">

            <a wire:navigate href="{{ route('customer.orders') }}" class="btn btn-light rounded-pill mb-4">
                <i class="bi bi-arrow-left"></i>
                Back to Orders
            </a>

            @if (session('success'))
                <div class="alert alert-success rounded-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">

                    <h3 class="fw-bold">
                        Order #{{ $order['id'] }}
                    </h3>

                    <div class="row mt-3">

                        <div class="col-md-3">
                            <strong>Date</strong>
                            <p>{{ $order['date'] }}</p>
                        </div>

                        <div class="col-md-3">
                            <strong>Status</strong>
                            <p>{{ $order['status'] }}</p>
                        </div>

                        <div class="col-md-3">
                            <strong>Payment</strong>
                            <p>{{ $order['payment'] }}</p>
                        </div>

                        <div class="col-md-3">
                            <strong>Total</strong>
                            <p>Rs {{ number_format($order['total']) }}</p>
                        </div>

                    </div>

                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">

                    <h4 class="fw-bold mb-3">
                        Order Items
                    </h4>

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Return</th>
                                    <th>Rating & Review</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($order['items'] as $item)
                                    <tr>

                                        <td>
                                            {{ $item['name'] }}
                                        </td>

                                        <td>
                                            Rs {{ number_format($item['price']) }}
                                        </td>

                                        <td>
                                            {{ $item['qty'] }}
                                        </td>

                                        <td>
                                            Rs {{ number_format($item['subtotal']) }}
                                        </td>

                                        <td style="min-width: 220px;">

                                            <form wire:submit.prevent="requestReturn({{ $item['id'] }})">

                                                <input type="text" wire:model="returnReason"
                                                    class="form-control form-control-sm rounded-pill mb-2"
                                                    placeholder="Return reason">

                                                @error('returnReason')
                                                    <small class="text-danger d-block mb-2">
                                                        {{ $message }}
                                                    </small>
                                                @enderror

                                                <button class="btn btn-sm btn-outline-danger rounded-pill">
                                                    Request Return
                                                </button>

                                            </form>

                                        </td>

                                        <td style="min-width: 300px;">

                                            <form wire:submit.prevent="submitReview({{ $item['id'] }})">

                                                <select class="form-select form-select-sm rounded-pill mb-2"
                                                    wire:model="reviews.{{ $item['id'] }}.rating">

                                                    <option value="">
                                                        Select Rating
                                                    </option>

                                                    <option value="5">
                                                        ★★★★★ Excellent
                                                    </option>

                                                    <option value="4">
                                                        ★★★★ Good
                                                    </option>

                                                    <option value="3">
                                                        ★★★ Average
                                                    </option>

                                                    <option value="2">
                                                        ★★ Poor
                                                    </option>

                                                    <option value="1">
                                                        ★ Bad
                                                    </option>

                                                </select>

                                                @error("reviews.{$item['id']}.rating")
                                                    <small class="text-danger d-block mb-2">
                                                        {{ $message }}
                                                    </small>
                                                @enderror

                                                <textarea class="form-control form-control-sm rounded-4 mb-2" rows="2"
                                                    wire:model="reviews.{{ $item['id'] }}.review" placeholder="Write your review"></textarea>

                                                @error("reviews.{$item['id']}.review")
                                                    <small class="text-danger d-block mb-2">
                                                        {{ $message }}
                                                    </small>
                                                @enderror

                                                <button class="btn btn-sm btn-outline-primary rounded-pill">
                                                    Submit Review
                                                </button>

                                            </form>

                                        </td>

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
