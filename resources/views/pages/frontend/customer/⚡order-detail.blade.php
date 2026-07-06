<?php

use Livewire\Component;
use App\Models\Order;
use App\Models\SaleItem;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\Review as ProductReview;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public int $id;

    public $orderData;

    public array $order = [];
    public array $returnReasons = [];
    public array $returnQuantities = [];
    public array $returnAvailability = [];
    public array $reviews = [];

    public function mount($id): void
    {
        $this->id = (int) $id;
        $this->loadOrder();
    }

    private function customerId(): int
    {
        return auth('customer')->user()->customer->id;
    }

    private function loadOrder(): void
    {
        $this->orderData = Order::with(['shipment', 'sale.customer', 'sale.items.product'])
            ->whereHas('sale', function ($q) {
                $q->where('customer_id', $this->customerId());
            })
            ->findOrFail($this->id);

        $items = $this->orderData->sale?->items ?? collect();

        $rawStatus = strtolower($this->orderData->shipment?->getRawOriginal('status') ?? '');
        $isDelivered = $rawStatus === 'delivered';
        $isCancelled = $rawStatus === 'cancelled';

        $this->order = [
            'id' => $this->orderData->id,
            'sale_id' => $this->orderData->sale_id,
            'date' => $this->orderData->order_date ? date('d-F-Y', strtotime($this->orderData->order_date)) : 'N/A',
            'status' => $this->orderData->shipment?->status ?? 'N/A',
            'raw_status' => $rawStatus,
            'payment' => $this->orderData->sale?->payment_status ?? 'N/A',
            'total' => $this->orderData->sale?->total_amount ?? 0,
            'items' => $items,
            'is_delivered' => $isDelivered,
            'is_cancelled' => $isCancelled,
        ];

        foreach ($items as $item) {
            $approvedReturnedQty = $this->approvedReturnedQuantity($item);
            $pendingReturnedQty = $this->pendingReturnedQuantity($item);
            $remainingQty = max(0, $item->quantity - $approvedReturnedQty - $pendingReturnedQty);
            $alreadyReviewed = $this->hasAlreadyReviewed($item);

            $this->returnAvailability[$item->id] = [
                'approved_returned_qty' => $approvedReturnedQty,
                'pending_returned_qty' => $pendingReturnedQty,
                'remaining_qty' => $remainingQty,
                'already_reviewed' => $alreadyReviewed,
                'can_return' => $isDelivered && !$isCancelled && $remainingQty > 0,
                'can_review' => $isDelivered && !$isCancelled && $approvedReturnedQty <= 0 && !$alreadyReviewed,
            ];

            $this->returnReasons[$item->id] ??= '';
            $this->returnQuantities[$item->id] = $remainingQty > 0 ? 1 : 0;

            $this->reviews[$item->id] ??= [
                'rating' => '',
                'review' => '',
            ];
        }
    }

    private function isOrderDelivered(): bool
    {
        return ($this->order['raw_status'] ?? '') == 'delivered';
    }

    private function approvedReturnedQuantity(SaleItem $saleItem): int
    {
        return (int) SalesReturnItem::query()->join('sales_returns', 'sales_return_items.sales_return_id', '=', 'sales_returns.id')->where('sales_returns.sale_id', $saleItem->sale_id)->where('sales_return_items.product_id', $saleItem->product_id)->where('sales_returns.status', 'approved')->sum('sales_return_items.quantity');
    }

    private function pendingReturnedQuantity(SaleItem $saleItem): int
    {
        return (int) SalesReturnItem::query()->join('sales_returns', 'sales_return_items.sales_return_id', '=', 'sales_returns.id')->where('sales_returns.sale_id', $saleItem->sale_id)->where('sales_return_items.product_id', $saleItem->product_id)->where('sales_returns.status', 'pending')->sum('sales_return_items.quantity');
    }

    private function hasAlreadyReviewed(SaleItem $saleItem): bool
    {
        return ProductReview::query()->where('sale_id', $saleItem->sale_id)->where('product_id', $saleItem->product_id)->where('customer_id', $this->customerId())->exists();
    }

    private function getCustomerSaleItem(int $itemId): SaleItem
    {
        return SaleItem::query()
            ->with('sale')
            ->whereHas('sale', function ($q) {
                $q->where('customer_id', $this->customerId());
            })
            ->findOrFail($itemId);
    }

    public function requestReturn(int $itemId): void
    {
        if (!$this->isOrderDelivered()) {
            session()->flash('error', 'Return request is only available after the order is delivered.');
            return;
        }

        $saleItem = $this->getCustomerSaleItem($itemId);

        $approvedReturnedQty = $this->approvedReturnedQuantity($saleItem);
        $pendingReturnedQty = $this->pendingReturnedQuantity($saleItem);
        $remainingQty = max(0, $saleItem->quantity - $approvedReturnedQty - $pendingReturnedQty);

        if ($remainingQty <= 0) {
            session()->flash('error', 'This item already has returned or pending return quantity.');
            return;
        }

        $this->validate([
            "returnQuantities.$itemId" => 'required|integer|min:1|max:' . $remainingQty,
            "returnReasons.$itemId" => 'required|string|min:5|max:1000',
        ]);

        DB::transaction(function () use ($itemId, $saleItem) {
            $returnQuantity = (int) $this->returnQuantities[$itemId];
            $reason = $this->returnReasons[$itemId];
            $totalAmount = $saleItem->unit_price * $returnQuantity;

            $salesReturn = SalesReturn::create([
                'sale_id' => $saleItem->sale_id,
                'return_no' => 'RET-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5)),
                'total_amount' => $totalAmount,
                'reason' => $reason,
                'status' => 'pending',
            ]);

            SalesReturnItem::create([
                'sales_return_id' => $salesReturn->id,
                'product_id' => $saleItem->product_id,
                'quantity' => $returnQuantity,
                'unit_price' => $saleItem->unit_price,
                'total_price' => $totalAmount,
            ]);
        });

        session()->flash('success', 'Return request submitted successfully.');

        $this->returnReasons[$itemId] = '';
        $this->loadOrder();
    }

    public function submitReview(int $itemId): void
    {
        if (!$this->isOrderDelivered()) {
            session()->flash('error', 'You can review this item only after the order is delivered.');
            return;
        }

        $saleItem = $this->getCustomerSaleItem($itemId);

        if ($this->hasAlreadyReviewed($saleItem)) {
            session()->flash('error', 'You have already reviewed this item.');
            return;
        }

        if ($this->approvedReturnedQuantity($saleItem) > 0) {
            session()->flash('error', 'You cannot review an item that has been returned and approved.');
            return;
        }

        $this->validate([
            "reviews.$itemId.rating" => 'required|integer|min:1|max:5',
            "reviews.$itemId.review" => 'required|string|min:5|max:1000',
        ]);

        ProductReview::create([
            'customer_id' => $this->customerId(),
            'product_id' => $saleItem->product_id,
            'sale_id' => $saleItem->sale_id,
            'rating' => $this->reviews[$itemId]['rating'],
            'review' => $this->reviews[$itemId]['review'],
            'status' => 'pending',
        ]);

        session()->flash('success', 'Product review submitted successfully.');

        $this->reviews[$itemId] = [
            'rating' => '',
            'review' => '',
        ];

        $this->loadOrder();
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
                <div class="alert alert-success rounded-4">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger rounded-4">{{ session('error') }}</div>
            @endif

            @if (!($order['is_delivered'] ?? false))
                <div class="alert alert-warning rounded-4">
                    Return and review options are available only after the order is delivered.
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h3 class="fw-bold">Order #{{ $order['id'] ?? '' }}</h3>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <strong>Date</strong>
                            <p>{{ $order['date'] ?? 'N/A' }}</p>
                        </div>

                        <div class="col-md-3">
                            <strong>Status</strong>
                            <p>
                                <span
                                    class="badge {{ $order['is_delivered'] ?? false ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill">
                                    {{ $order['status'] ?? 'N/A' }}
                                </span>
                            </p>
                        </div>

                        <div class="col-md-3">
                            <strong>Payment</strong>
                            <p>{{ ucfirst($order['payment'] ?? 'N/A') }}</p>
                        </div>

                        <div class="col-md-3">
                            <strong>Total</strong>
                            <p>Rs {{ number_format($order['total'] ?? 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Order Items</h4>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Return / Refund</th>
                                    <th>Rating & Review</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse (($order['items'] ?? []) as $item)
                                    <tr wire:key="order-item-{{ $item->id }}">
                                        <td>{{ $item->product->name ?? 'Product not found' }}</td>

                                        <td>Rs {{ number_format($item->unit_price) }}</td>

                                        <td>{{ $item->quantity }}</td>

                                        <td>Rs {{ number_format($item->total_price) }}</td>

                                        <td style="min-width: 280px;">
                                            @if (!($order['is_delivered'] ?? false))
                                                <span class="badge bg-warning text-dark rounded-pill">
                                                    Return unavailable
                                                </span>
                                            @elseif ($returnAvailability[$item->id]['can_return'] ?? false)
                                                <form wire:submit.prevent="requestReturn({{ $item->id }})">
                                                    <small class="text-muted d-block mb-1">
                                                        Returnable Qty:
                                                        {{ $returnAvailability[$item->id]['remaining_qty'] }}
                                                    </small>

                                                    @if (($returnAvailability[$item->id]['pending_returned_qty'] ?? 0) > 0)
                                                        <small class="text-warning d-block mb-1">
                                                            Pending Return Qty:
                                                            {{ $returnAvailability[$item->id]['pending_returned_qty'] }}
                                                        </small>
                                                    @endif

                                                    @if (($returnAvailability[$item->id]['approved_returned_qty'] ?? 0) > 0)
                                                        <small class="text-success d-block mb-2">
                                                            Approved Returned Qty:
                                                            {{ $returnAvailability[$item->id]['approved_returned_qty'] }}
                                                        </small>
                                                    @endif

                                                    <input type="number"
                                                        wire:model="returnQuantities.{{ $item->id }}"
                                                        class="form-control form-control-sm rounded-pill mb-2 @error("returnQuantities.$item->id") is-invalid @enderror"
                                                        min="1"
                                                        max="{{ $returnAvailability[$item->id]['remaining_qty'] }}"
                                                        placeholder="Return quantity">

                                                    @error("returnQuantities.$item->id")
                                                        <small class="text-danger d-block mb-2">{{ $message }}</small>
                                                    @enderror

                                                    <input type="text"
                                                        wire:model="returnReasons.{{ $item->id }}"
                                                        class="form-control form-control-sm rounded-pill mb-2 @error("returnReasons.$item->id") is-invalid @enderror"
                                                        placeholder="Return reason">

                                                    @error("returnReasons.$item->id")
                                                        <small class="text-danger d-block mb-2">{{ $message }}</small>
                                                    @enderror

                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger rounded-pill">
                                                        Request Return
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-secondary rounded-pill">
                                                    Return unavailable
                                                </span>

                                                @if (($returnAvailability[$item->id]['pending_returned_qty'] ?? 0) > 0)
                                                    <small class="text-warning d-block mt-2">
                                                        Return request is pending.
                                                    </small>
                                                @else
                                                    <small class="text-success d-block mt-2">
                                                        Already returned and approved.
                                                    </small>
                                                @endif
                                            @endif
                                        </td>

                                        <td style="min-width: 300px;">
                                            @if (!($order['is_delivered'] ?? false))
                                                <span class="badge bg-warning text-dark rounded-pill">
                                                    Review unavailable
                                                </span>
                                            @elseif ($returnAvailability[$item->id]['already_reviewed'] ?? false)
                                                <span class="badge bg-info text-dark rounded-pill">
                                                    Already Reviewed
                                                </span>
                                            @elseif ($returnAvailability[$item->id]['can_review'] ?? false)
                                                <form wire:submit.prevent="submitReview({{ $item->id }})">
                                                    <select
                                                        class="form-select form-select-sm rounded-pill mb-2 @error("reviews.$item->id.rating") is-invalid @enderror"
                                                        wire:model="reviews.{{ $item->id }}.rating">
                                                        <option value="">Select Rating</option>
                                                        <option value="5">★★★★★ Excellent</option>
                                                        <option value="4">★★★★ Good</option>
                                                        <option value="3">★★★ Average</option>
                                                        <option value="2">★★ Poor</option>
                                                        <option value="1">★ Bad</option>
                                                    </select>

                                                    @error("reviews.$item->id.rating")
                                                        <small class="text-danger d-block mb-2">{{ $message }}</small>
                                                    @enderror

                                                    <textarea class="form-control form-control-sm rounded-4 mb-2 @error("reviews.$item->id.review") is-invalid @enderror"
                                                        rows="2" wire:model="reviews.{{ $item->id }}.review" placeholder="Write your review"></textarea>

                                                    @error("reviews.$item->id.review")
                                                        <small class="text-danger d-block mb-2">{{ $message }}</small>
                                                    @enderror

                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-primary rounded-pill">
                                                        Submit Review
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge bg-secondary rounded-pill">
                                                    Review Unavailable
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            No items found in this order.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
