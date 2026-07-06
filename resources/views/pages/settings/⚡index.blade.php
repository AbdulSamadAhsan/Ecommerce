<?php

use Livewire\Component;
use App\Models\Setting;

new class extends Component {
    public $setting;

    public float $max_cash_order_amount = 0;
    public float $cancellation_penalty = 0;
    public int $cancellation_window = 15;

    public function mount(): void
    {
        $this->setting = Setting::firstOrCreate([]);

        $this->max_cash_order_amount = $this->setting->max_cash_order_amount ?? 0;

        $this->cancellation_penalty = $this->setting->cancellation_penalty ?? 0;

        $this->cancellation_window = $this->setting->cancellation_window ?? 15;
    }

    protected function rules(): array
    {
        return [
            'max_cash_order_amount' => 'required|numeric|min:0',

            'cancellation_penalty' => 'required|numeric|min:0',

            'cancellation_window' => 'required|integer|min:1',
        ];
    }

    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    public function save(): void
    {
        $this->validate();

        $this->setting->update([
            'max_cash_order_amount' => $this->max_cash_order_amount,

            'cancellation_penalty' => $this->cancellation_penalty,

            'cancellation_window' => $this->cancellation_window,
        ]);

        session()->flash('success', 'Settings updated successfully.');
    }
};

?>


<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0">


            <div class="card-header bg-primary text-white">

                <h4 class="mb-0">
                    Order Settings
                </h4>

            </div>


            <div class="card-body">


                @if (session()->has('success'))
                    <div class="alert alert-success">

                        {{ session('success') }}

                    </div>
                @endif



                <form wire:submit.prevent="save">


                    <div class="row">


                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Max Cash Order Amount
                            </label>


                            <input type="number" step="0.01" wire:model.live="max_cash_order_amount"
                                class="form-control @error('max_cash_order_amount') is-invalid @enderror">


                            @error('max_cash_order_amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>





                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Cancellation Penalty
                            </label>


                            <input type="number" step="0.01" wire:model.live="cancellation_penalty"
                                class="form-control @error('cancellation_penalty') is-invalid @enderror">


                            @error('cancellation_penalty')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror


                        </div>





                        <div class="col-md-4 mb-3">


                            <label class="form-label">
                                Cancellation Window (Minutes)
                            </label>


                            <input type="number" wire:model.live="cancellation_window"
                                class="form-control @error('cancellation_window') is-invalid @enderror">


                            @error('cancellation_window')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror


                        </div>



                    </div>




                    <div class="d-flex justify-content-end">


                        <button class="btn btn-primary rounded-pill px-4" wire:loading.attr="disabled">


                            <span wire:loading.remove>
                                Save Settings
                            </span>


                            <span wire:loading>
                                Saving...
                            </span>


                        </button>


                    </div>



                </form>


            </div>


        </div>

    </div>

</div>
