<?php

use Livewire\Component;
use App\Models\Shift;

new class extends Component {
    public string $search = '';

    public array $shifts = [];

    public function mount(): void
    {
        $this->loadShifts();
    }

    public function updatedSearch(): void
    {
        $this->loadShifts();
    }

    public function loadShifts(): void
    {
        $this->shifts = Shift::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
            ->latest()
            ->get()
            ->toArray();
    }
};

?>

<div>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">Shifts</h3>
            <p class="text-muted mb-0">
                Manage employee shifts
            </p>
        </div>

        <a href="{{ route('shifts.create') }}" class="btn btn-primary rounded-pill">
            Add Shift
        </a>

    </div>

    <div class="dashboard-card">

        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4" placeholder="Search shift...">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead class="table-light">

                    <tr>
                        <th>ID</th>
                        <th>Shift Name</th>
                        <th>Reporting Time</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse ($shifts as $shift)
                        <tr>

                            <td>#{{ $shift['id'] }}</td>

                            <td>{{ $shift['name'] }}</td>

                            <td>{{ \Carbon\Carbon::parse($shift['reporting_time'])->format('h:i A') }}</td>

                            <td>

                                <a href="{{ route('shifts.edit', $shift['id']) }}"
                                    class="btn btn-sm btn-info rounded-pill text-white">
                                    Edit
                                </a>



                                <button class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="text-center text-muted py-4">
                                No shifts found.
                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
