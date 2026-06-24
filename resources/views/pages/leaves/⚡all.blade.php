<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

new class extends Component {
    use WithPagination;

    public $leave_id = null;

    public $employee_id = '';
    public $leave_type = 'casual';
    public $from_date = '';
    public $to_date = '';
    public $days = 1;
    public $reason = '';
    public $status = 'pending';

    public $search = '';
    public $filter_status = '';

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->from_date = now()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
    }

    public function updatedFromDate()
    {
        $this->calculateDays();
    }

    public function updatedToDate()
    {
        $this->calculateDays();
    }

    public function calculateDays()
    {
        if ($this->from_date && $this->to_date) {
            $from = Carbon::parse($this->from_date);
            $to = Carbon::parse($this->to_date);

            $this->days = $to->gte($from) ? $from->diffInDays($to) + 1 : 1;
        }
    }

    public function rules()
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:sick,casual,annual,emergency,unpaid',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'days' => 'required|integer|min:1',
            'reason' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ];
    }

    public function save()
    {
        $this->calculateDays();
        $this->validate();

        $approvedBy = null;
        $approvedAt = null;

        if (in_array($this->status, ['approved', 'rejected'])) {
            $approvedBy = Auth::id();
            $approvedAt = now();
        }

        Leave::updateOrCreate(
            ['id' => $this->leave_id],
            [
                'employee_id' => $this->employee_id,
                'leave_type' => $this->leave_type,
                'from_date' => $this->from_date,
                'to_date' => $this->to_date,
                'days' => $this->days,
                'reason' => $this->reason,
                'status' => $this->status,
                'approved_by' => $approvedBy,
                'approved_at' => $approvedAt,
            ],
        );

        session()->flash('success', 'Leave saved successfully.');

        $this->resetForm();
    }

    public function edit($id)
    {
        $leave = Leave::findOrFail($id);

        $this->leave_id = $leave->id;
        $this->employee_id = $leave->employee_id;
        $this->leave_type = $leave->leave_type;
        $this->from_date = $leave->from_date;
        $this->to_date = $leave->to_date;
        $this->days = $leave->days;
        $this->reason = $leave->reason;
        $this->status = $leave->status;
    }

    public function delete($id)
    {
        Leave::findOrFail($id)->delete();

        session()->flash('success', 'Leave deleted successfully.');
    }

    public function resetForm()
    {
        $this->leave_id = null;
        $this->employee_id = '';
        $this->leave_type = 'casual';
        $this->from_date = now()->format('Y-m-d');
        $this->to_date = now()->format('Y-m-d');
        $this->days = 1;
        $this->reason = '';
        $this->status = 'pending';

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

            'leaves' => Leave::with(['employee.user', 'approvedBy'])
                ->when($this->search, function ($query) {
                    $query
                        ->whereHas('employee.user', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('employee', function ($q) {
                            $q->where('designation', 'like', '%' . $this->search . '%')->orWhere('phone', 'like', '%' . $this->search . '%');
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
            <h4 class="fw-bold mb-1">Employee Leaves</h4>
            <p class="text-muted mb-0">Manage employee leave requests</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">
                    {{ $leave_id ? 'Update Leave' : 'Create Leave' }}
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
                        <label class="form-label">Leave Type</label>
                        <select wire:model="leave_type" class="form-control rounded-pill">
                            <option value="casual">Casual</option>
                            <option value="sick">Sick</option>
                            <option value="annual">Annual</option>
                            <option value="emergency">Emergency</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                        @error('leave_type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select wire:model="status" class="form-control rounded-pill">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">From Date</label>
                        <input type="date" wire:model.live="from_date" class="form-control rounded-pill">
                        @error('from_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">To Date</label>
                        <input type="date" wire:model.live="to_date" class="form-control rounded-pill">
                        @error('to_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Days</label>
                        <input type="number" wire:model="days" class="form-control rounded-pill" readonly>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Reason</label>
                        <textarea wire:model="reason" class="form-control rounded-4"></textarea>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary rounded-pill">
                    {{ $leave_id ? 'Update Leave' : 'Save Leave' }}
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
                    <h5 class="mb-0 fw-bold">Leave List</h5>
                </div>

                <div class="col-md-4">
                    <input type="text" wire:model.live="search" class="form-control rounded-pill"
                        placeholder="Search employee">
                </div>

                <div class="col-md-2">
                    <select wire:model.live="filter_status" class="form-control rounded-pill">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
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
                        <th>Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Approved By</th>
                        <th>Approved At</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($leaves as $leave)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $leave->employee->user->name ?? ($leave->employee->name ?? 'N/A') }}</td>

                            <td>{{ ucfirst($leave->leave_type) }}</td>

                            <td>{{ $leave->from_date }}</td>

                            <td>{{ $leave->to_date }}</td>

                            <td>{{ $leave->days }}</td>

                            <td>
                                @if ($leave->status === 'approved')
                                    <span class="badge bg-success rounded-pill">Approved</span>
                                @elseif ($leave->status === 'rejected')
                                    <span class="badge bg-danger rounded-pill">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill">Pending</span>
                                @endif
                            </td>

                            <td>{{ $leave->approvedBy->name ?? '-' }}</td>

                            <td>{{ $leave->approved_at ?? '-' }}</td>

                            <td class="text-end">
                                <button wire:click="edit({{ $leave->id }})"
                                    class="btn btn-sm btn-info rounded-pill">
                                    Edit
                                </button>

                                <button wire:click="delete({{ $leave->id }})" wire:confirm="Are you sure?"
                                    class="btn btn-sm btn-danger rounded-pill">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                No leaves found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $leaves->links() }}
            </div>
        </div>
    </div>

</div>
