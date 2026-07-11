<?php

use Livewire\Component;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Deliveryboy;
use App\Models\DeliveryAssignment as DeliveryBoyAssignment;
new class extends Component {
    public $order_id = '';
    public $shipment_id = '';
    public $deliveryboy_id = '';
    public $assigned_date = '';
    public $status = 'assigned';
    public $notes = '';

    public $shipments = [];
    public $deliveryboys = [];

    public function mount()
    {
        $this->assigned_date = now()->format('Y-m-d');

        $this->shipments = Shipment::with('order')->doesntHave('deliveryassign')->latest()->get();
        $this->deliveryboys = Deliveryboy::where('status', 1)->latest()->get();
    }

    protected $rules = [
        'shipment_id' => 'nullable|exists:shipments,id',
        'deliveryboy_id' => 'required|exists:delivery_boys,id',

        'status' => 'required|string',
        'notes' => 'nullable|max:1000',
    ];

    public function save()
    {
        $this->validate();

        DeliveryBoyAssignment::create([
            'shipment_id' => $this->shipment_id ?: null,
            'delivery_boy_id' => $this->deliveryboy_id,
            'assigned_at' => date('Y-m-d H:i:s'),
            'status' => $this->status,
            'remarks' => $this->notes,
        ]);

        if ($this->shipment_id) {
            Shipment::find($this->shipment_id)?->update([
                'status' => 'out_for_delivery',
            ]);
        }

        session()->flash('success', 'Delivery boy assigned successfully.');

        return $this->redirectRoute('delivery-boy-assignments.index', navigate: true);
    }
};
?>

<div class="card shadow border-0">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Assign Delivery Boy</h4>
    </div>

    <div class="card-body">
        <form wire:submit="save">



            <div class="mb-3">
                <label class="form-label">Shipment</label>
                <select wire:model.live="shipment_id" class="form-select">
                    <option value="">Select Shipment</option>

                    @foreach ($shipments as $shipment)
                        <option value="{{ $shipment->id }}">
                            Shipment #{{ $shipment->id }} - {{ $shipment->tracking_number }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Delivery Boy</label>
                <select wire:model.live="deliveryboy_id"
                    class="form-select @error('deliveryboy_id') is-invalid @enderror">
                    <option value="">Select Delivery Boy</option>

                    @foreach ($deliveryboys as $boy)
                        <option value="{{ $boy->id }}">
                            {{ $boy->name }} - {{ $boy->phone }}
                        </option>
                    @endforeach
                </select>

                @error('deliveryboy_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>



            <div class="mb-3">
                <label class="form-label">Status</label>
                <select wire:model.live="status" class="form-select">
                    <option value="assigned">Assigned</option>
                    <option value="picked">Picked</option>
                    <option value="delivered">Delivered</option>
                    <option value="in_transit">On Way</option>
                    <option value="Failed">Failed</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Notes</label>
                <textarea wire:model.live="notes" rows="4" class="form-control"></textarea>
            </div>

            <button class="btn btn-primary rounded-pill">
                Save Assignment
            </button>

        </form>
    </div>
</div>
