<?php

use App\Models\Tax;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component {
    public Tax $tax;

    public function mount(int $id): void
    {
        $this->tax = Tax::findOrFail($id);
    }
};

?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Tax Detail</h3>
            <p class="text-muted mb-0">View tax rule information.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('taxes.edit', $tax->id) }}" class="btn btn-warning rounded-pill px-4">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>
            <a href="{{ route('taxes.index') }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="text-muted small">Tax Name</div>
                    <div class="fw-bold fs-5">{{ $tax->name }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Status</div>
                    <span class="badge {{ $tax->status ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-2">
                        {{ $tax->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Type</div>
                    <div class="text-capitalize fw-semibold">{{ $tax->type }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Rate</div>
                    <div class="fw-semibold">{{ $tax->type === 'percentage' ? $tax->rate . '%' : number_format($tax->rate, 2) }}</div>
                </div>
                <div class="col-md-12">
                    <div class="text-muted small">Description</div>
                    <div>{{ $tax->description ?: 'No description added.' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Created At</div>
                    <div>{{ $tax->created_at?->format('d M Y h:i A') }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Updated At</div>
                    <div>{{ $tax->updated_at?->format('d M Y h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
