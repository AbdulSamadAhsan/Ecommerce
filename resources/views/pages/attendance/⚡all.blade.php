<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;
use App\Models\Attendance;

new class extends Component {
    use WithPagination;

    public $attendance_id = null;
    public $employee_id = '';
    public $attendance_date = '';
    public $check_in = '';
    public $check_out = '';
    public $status = 'present';
    public $remarks = '';

    public $search = '';
    public $filter_status = '';

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->attendance_date = now()->format('Y-m-d');
    }

    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:present,absent,late,half_day,leave',
            'remarks' => 'nullable|string',
        ];
    }

    public function save()
    {
        $this->validate();

        Attendance::updateOrCreate(
            [
                'employee_id' => $this->employee_id,
                'attendance_date' => $this->attendance_date,
            ],
            [
                'check_in' => $this->check_in ?: null,
                'check_out' => $this->check_out ?: null,
                'status' => $this->status,
                'remarks' => $this->remarks,
            ],
        );

        session()->flash('success', 'Attendance saved successfully.');

        $this->resetForm();
    }

    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);

        $this->attendance_id = $attendance->id;
        $this->employee_id = $attendance->employee_id;
        $this->attendance_date = $attendance->attendance_date->format('Y-m-d');
        $this->check_in = $attendance->check_in;
        $this->check_out = $attendance->check_out;
        $this->status = $attendance->status;
        $this->remarks = $attendance->remarks;
    }

    public function delete($id)
    {
        Attendance::findOrFail($id)->delete();

        session()->flash('success', 'Attendance deleted successfully.');
    }

    public function resetForm()
    {
        $this->attendance_id = null;
        $this->employee_id = '';
        $this->attendance_date = now()->format('Y-m-d');
        $this->check_in = '';
        $this->check_out = '';
        $this->status = 'present';
        $this->remarks = '';

        $this->resetValidation();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filter_status = '';
        $this->resetPage();
    }

    public function with()
    {
        return [
            'employees' => Employee::with('user')->latest()->get(),

            'attendances' => Attendance::with('employee.user')
                ->when($this->search, function ($query) {
                    $query
                        ->whereHas('employee.user', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('employee', function ($q) {
                            $q->where('phone', 'like', '%' . $this->search . '%')->orWhere('designation', 'like', '%' . $this->search . '%');
                        });
                })
                ->when($this->filter_status, function ($query) {
                    $query->where('status', $this->filter_status);
                })
                ->latest()
                ->paginate(10),
        ];
    }
};
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Employee Attendance</h4>
            <p class="text-muted mb-0">Manage employee daily attendance</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    {{ $attendance_id ? 'Update Attendance' : 'Mark Attendance' }}
                </h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Employee</label>
                        <select wire:model="employee_id" class="form-control rounded-pill">
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->user->name ?? ($employee->name ?? 'N/A') }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" wire:model="attendance_date" class="form-control rounded-pill">
                        @error('attendance_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select wire:model="status" class="form-control rounded-pill">
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="half_day">Half Day</option>
                            <option value="leave">Leave</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Check In</label>
                        <input type="time" wire:model="check_in" class="form-control rounded-pill">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Check Out</label>
                        <input type="time" wire:model="check_out" class="form-control rounded-pill">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea wire:model="remarks" class="form-control rounded-4"></textarea>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary rounded-pill">
                    {{ $attendance_id ? 'Update' : 'Save' }}
                </button>

                <button type="button" wire:click="resetForm" class="btn btn-warning rounded-pill">
                    Reset
                </button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center g-2">
                <div class="col-md-5">
                    <h5 class="mb-0 fw-bold">Attendance List</h5>
                </div>

                <div class="col-md-4">
                    <input type="text" wire:model.live="search" class="form-control rounded-pill"
                        placeholder="Search employee">
                </div>

                <div class="col-md-2">
                    <select wire:model.live="filter_status" class="form-control rounded-pill">
                        <option value="">All Status</option>
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                        <option value="late">Late</option>
                        <option value="half_day">Half Day</option>
                        <option value="leave">Leave</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button wire:click="resetFilters" class="btn btn-secondary rounded-pill w-100">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body table-responsive pt-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($attendances as $attendance)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $attendance->employee->user->name ?? ($attendance->employee->name ?? 'N/A') }}</td>
                            <td>{{ date('d-F-Y', strtotime($attendance->attendance_date)) }}</td>
                            <td>{{ $attendance->check_in ?? '-' }}</td>
                            <td>{{ $attendance->check_out ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info text-dark rounded-pill">
                                    {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                </span>
                            </td>
                            <td>{{ $attendance->remarks ?? '-' }}</td>
                            <td class="text-end">


                                <button wire:click="edit({{ $attendance->id }})"
                                    class="btn btn-sm btn-info rounded-pill">
                                    Edit
                                </button>

                                <button wire:click="delete({{ $attendance->id }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No attendance found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>

</div>
