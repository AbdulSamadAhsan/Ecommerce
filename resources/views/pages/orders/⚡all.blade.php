<?php

use Livewire\Component;
use App\Models\Order;

new class extends Component {
    public string $search = '';
    public $orders;

    public function mount(): void
    {
        $this->loadOrders();
    }

    public function updatedSearch(): void
    {
        $this->loadOrders();
    }

    public function loadOrders(): void
    {
        $this->orders = Order::with(['customer.user', 'shipment', 'sale'])
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';

                $query->where(function ($q) use ($search) {
                    $q->whereHas('shipment', function ($shipment) use ($search) {
                        $shipment->where('status', 'like', $search);
                    })
                        ->orWhereHas('sale', function ($sale) use ($search) {
                            $sale->where('payment_status', 'like', $search)->orWhere('total_amount', 'like', $search);
                        })
                        ->orWhereHas('customer.user', function ($user) use ($search) {
                            $user->where('name', 'like', $search);
                        });
                });
            })
            ->latest()
            ->get();
    }

    public function delete($id): void
    {
        Order::findOrFail($id)->delete();

        $this->loadOrders();

        session()->flash('success', 'Order deleted successfully.');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Orders</h3>
            <p class="text-muted mb-0">Manage customer orders</p>
        </div>


        <a href="{{ route('order.report') }}" class="btn btn-primary rounded-pill">
            Order Report
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4" placeholder="Search order...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>

                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>#{{ $order['id'] }}</td>

                            <td>Rs. {{ number_format($order->sale->total_amount ?? 0, 2) }}</td>
                            <td><span class="badge bg-info">{{ ucfirst($order->shipment->status) }}</span></td>
                            <td><span class="badge bg-info">{{ ucfirst($order->sale->payment_method) }}</span></td>
                            <td><span class="badge bg-secondary">{{ ucfirst($order->sale->payment_status) }}</span></td>
                            <td>

                                <a href="{{ route('orders.show', $order['id']) }}"
                                    class="btn btn-sm btn-info text-white rounded-pill">View</a>
                                @if (($order->shipment?->status ?? 'Pending') !== 'Delivered')
                                    <a href="{{ route('orders.edit', $order->id) }}"
                                        class="btn btn-sm btn-warning rounded-pill">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
