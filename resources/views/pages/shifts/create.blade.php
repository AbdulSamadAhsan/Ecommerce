<?php

use Livewire\Component;

new class extends Component {
    public $name;
    public $reporting_time;

    public function save()
    {
        \App\Models\Shift::create([
            'name' => $this->name,
            'reporting_time' => $this->reporting_time,
        ]);
        return redirect()->route('shifts.index');
        dd($this->name, $this->reporting_time);
    }
};
?>

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    Add Shift
                </h4>
            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="save">

                    <div class="mb-3">

                        <label class="form-label">
                            Shift Name
                        </label>

                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            wire:model.live="name" placeholder="Enter shift name">

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Reporting Time
                        </label>

                        <input type="time" class="form-control @error('reporting_time') is-invalid @enderror"
                            wire:model.live="reporting_time">

                        @error('reporting_time')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="text-end">

                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="save">
                                Save Shift
                            </span>

                            <span wire:loading wire:target="save">
                                Saving...
                            </span>
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
