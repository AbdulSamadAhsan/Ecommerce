<?php

use App\Models\Coupon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function toggleStatus(int $id): void
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['status' => ! $coupon->status]);
        session()->flash('success', 'Coupon status updated successfully.');
    }

    public function delete(int $id): void
    {
        Coupon::findOrFail($id)->delete();
        session()->flash('success', 'Coupon deleted successfully.');
    }

    public function coupons()
    {
        return Coupon::query()
            ->when($this->search, function ($query) {
                $query->where('code', 'like', '%' . $this->search . '%')
                    ->orWhere('title', 'like', '%' . $this->search . '%')
                    ->orWhere('discount_type', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);
    }
};

?>

<div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Coupons</h3>
            <p class="text-muted mb-0">Manage discount coupons for customers and orders.</p>
        </div>
        <a href="{{ route('coupons.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-circle me-1"></i> Add Coupon
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control rounded-pill" placeholder="Search code, title or type...">
                </div>
                <div class="col-md-4">
                    <select wire:model.live="perPage" class="form-select rounded-pill">
                        <option value="10">10 Records</option>
                        <option value="25">25 Records</option>
                        <option value="50">50 Records</option>
                    </select>
                </div>
            </div>

            @php($coupons = $this->coupons())

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Title</th>
                            <th>Discount</th>
                            <th>Usage</th>
                            <th>Validity</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $coupon)
                            <tr wire:key="coupon-{{ $coupon->id }}">
                                <td>{{ $coupon->id }}</td>
                                <td><span class="badge bg-dark rounded-pill px-3 py-2">{{ $coupon->code }}</span></td>
                                <td class="fw-semibold">{{ $coupon->title }}</td>
                                <td>{{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : number_format($coupon->discount_value, 2) }}</td>
                                <td>{{ $coupon->used_count }} / {{ $coupon->usage_limit ?: 'Unlimited' }}</td>
                                <td>
                                    {{ $coupon->start_date?->format('d M Y') ?: 'Any' }} -
                                    {{ $coupon->end_date?->format('d M Y') ?: 'Any' }}
                                </td>
                                <td>
                                    <button type="button" wire:click="toggleStatus({{ $coupon->id }})" class="btn btn-sm {{ $coupon->status ? 'btn-success' : 'btn-secondary' }} rounded-pill">
                                        {{ $coupon->status ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('coupons.show', $coupon->id) }}" class="btn btn-sm btn-light rounded-pill"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-sm btn-warning rounded-pill"><i class="bi bi-pencil-square"></i></a>
                                    <button type="button" wire:click="delete({{ $coupon->id }})" wire:confirm="Delete this coupon?" class="btn btn-sm btn-danger rounded-pill"><i class="bi bi-trash"></i></button>
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

            {{ $coupons->links() }}
        </div>
    </div>
</div>
