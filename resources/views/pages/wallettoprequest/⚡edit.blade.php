<?php

use Livewire\Component;
use App\Models\WalletTopupRequest;
use App\Models\WalletTransaction;

use Illuminate\Support\Facades\DB;

new class extends Component {
    public $requestId;
    public $status = 'pending';
    public $notes = '';

    public function mount($id)
    {
        $request = WalletTopupRequest::find($id);

        $this->requestId = $request->id;
        $this->status = $request->status;
        $this->notes = $request->notes;
    }

    protected $rules = [
        'status' => 'required|string',
        'notes' => 'nullable|max:1000',
    ];

    public function update()
    {
        $this->validate();

        DB::transaction(function () {
            $request = WalletTopupRequest::findOrFail($this->requestId);

            $oldStatus = $request->status;

            $request->update([
                'status' => $this->status,
                'notes' => $this->notes,
            ]);

            if ($oldStatus !== 'approved' && $this->status === 'approved') {
                $customer = $request->customer;

                $wallet = $request->customer->wallet;

                $wallet->increment('balance', (float) $request->amount);
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => 'credit',
                    'amount' => $request->amount,
                    'reference_id' => rand() . $request->id,

                    'description' => 'Wallet top-up approved',
                ]);
            }
        });

        session()->flash('success', 'Wallet request updated successfully.');
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-warning">
        <h4 class="mb-0">Update Wallet Request</h4>
    </div>

    <div class="card-body">

        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form wire:submit="update">

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select wire:model.live="status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Notes</label>
                <textarea wire:model.live="notes" rows="4" class="form-control"></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('wallet-topups.index') }}" class="btn btn-secondary rounded-pill">
                    Back
                </a>

                <button class="btn btn-warning rounded-pill">Update Request</button>
            </div>

        </form>
    </div>
</div>
