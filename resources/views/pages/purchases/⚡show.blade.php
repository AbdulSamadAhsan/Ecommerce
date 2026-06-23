<?php

use Livewire\Component;
use App\Models\Purchase;

new class extends Component {
    public int $purchaseId;

    public array $purchase = [];

    public function mount($id): void
    {
        $this->purchaseId = $id;

        $this->purchase = Purchase::findOrFail($id)->toArray();
    }
};
?>

<div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Purchase Detail</h3>
            <p class="text-muted mb-0">
                Purchase #{{ $purchase['purchase_no'] }}
            </p>
        </div>

        <a href="{{ route('purchases.history') }}" class="btn btn-secondary rounded-pill">
            Back
        </a>
    </div>

    <div class="dashboard-card">

        <div class="row">

            <div class="col-md-4 mb-4">
                <label class="fw-bold">ID</label>
                <div>#{{ $purchase['id'] }}</div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Invoice No</label>
                <div>{{ $purchase['purchase_no'] }}</div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Purchase Date</label>
                <div>{{ $purchase['purchase_date'] }}</div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Total Amount</label>
                <div>
                    {{ number_format($purchase['total_amount'], 2) }}
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Paid Amount</label>
                <div>
                    {{ number_format($purchase['paid_amount'], 2) }}
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Due Amount</label>
                <div>
                    {{ number_format($purchase['due_amount'], 2) }}
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Payment Status</label>
                <div>
                    <span
                        class="badge
                        @if ($purchase['payment_status'] == 'paid') bg-success
                        @elseif($purchase['payment_status'] == 'partial')
                            bg-warning text-dark
                        @else
                            bg-danger @endif">
                        {{ ucfirst($purchase['payment_status']) }}
                    </span>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <label class="fw-bold">Status</label>
                <div>
                    <span
                        class="badge
                        @if ($purchase['status'] == 'completed') bg-success
                        @elseif($purchase['status'] == 'cancelled')
                            bg-danger
                        @else
                            bg-secondary @endif">
                        {{ ucfirst($purchase['status']) }}
                    </span>
                </div>
            </div>

            <div class="col-md-12">
                <label class="fw-bold">Notes</label>

                <div class="border rounded p-3 bg-light">
                    {{ $purchase['notes'] ?: 'No Notes Available' }}
                </div>
            </div>

        </div>

    </div>

</div>
