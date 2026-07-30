<?php

use Livewire\Component;
use App\Models\Shift;
new class extends Component {
    public string $name;
    public $reporting_time;
    public $shift_id;
    public function mount($id)
    {
        $shift = Shift::find($id);
        $this->name = $shift->name;
        $this->reporting_time = $shift->reporting_time;
        $this->shift_id = $id;
    }
    public function update()
    {
        Shift::where('id', $this->shift_id)->update([
            'name' => $this->name,
            'reporting_time' => $this->reporting_time,
        ]);
        return redirect()->route('shifts.index');
    }
};
?>

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    Edit Shift
                </h4>
            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="update">

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

                    <div class="d-flex justify-content-end">

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                            wire:target="update">

                            <span wire:loading.remove wire:target="update">
                                Update Shift
                            </span>

                            <span wire:loading wire:target="update">
                                Updating...
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
