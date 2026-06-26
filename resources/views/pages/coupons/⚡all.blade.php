<?php

use Livewire\Component;
use App\Models\Coupon;

new class extends Component {
    public string $search = '';
    public array $coupons = [];

    public function mount(): void
    {
        $this->loadCoupons();
    }

    public function updatedSearch(): void
    {
        $this->loadCoupons();
    }

    public function loadCoupons(): void
    {
        $this->coupons = Coupon::query()
            ->when($this->search, function ($query) {
                $query->where('code', 'like', '%' . $this->search . '%')->orWhere('discount_type', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get()
            ->toArray();
    }

    public function delete($id): void
    {
        Coupon::findOrFail($id)->delete();

        $this->loadCoupons();

        session()->flash('success', 'Coupon deleted successfully.');
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Coupons</h3>
            <p class="text-muted mb-0">Manage discount coupons</p>
        </div>

        <a href="{{ route('coupons.create') }}" class="btn btn-primary rounded-pill">
            Add Coupon
        </a>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4" placeholder="Search coupon...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Limit</th>
                        <th>Expiry</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($coupons as $coupon)
                        <tr>
                            <td>#{{ $coupon['id'] }}</td>
                            <td>{{ $coupon['code'] }}</td>
                            <td>{{ ucfirst($coupon['discount_type']) }}</td>
                            <td>{{ $coupon['discount_value'] }}</td>
                            <td>{{ $coupon['usage_limit'] ?? 'Unlimited' }}</td>
                            <td>{{ $coupon['expiry_date'] ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $coupon['is_active'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $coupon['is_active'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('coupons.show', $coupon['id']) }}"
                                    class="btn btn-sm btn-info text-white rounded-pill">View</a>
                                <a href="{{ route('coupons.edit', $coupon['id']) }}"
                                    class="btn btn-sm btn-warning rounded-pill">Edit</a>
                                <button wire:click="delete({{ $coupon['id'] }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No coupons found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
