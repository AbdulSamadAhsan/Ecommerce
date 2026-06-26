<?php

use Livewire\Component;
use App\Models\Warehouse;
new class extends Component {
    public string $search = '';

    public $warehouses;
    public function mount()
    {
        $this->warehouses = Warehouse::get();
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Warehouses</h3>
            <p class="text-muted mb-0">Manage all warehouses</p>
        </div>

        <a href="{{ route('warehouses.create') }}" class="btn btn-primary rounded-pill">
            Add Warehouse
        </a>
    </div>

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search warehouse...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>

                        <th>Manager</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($warehouses as $warehouse)
                        <tr>
                            <td>#{{ $warehouse['id'] }}</td>
                            <td>{{ $warehouse->name }}</td>

                            <td>{{ $warehouse->manager->user->name }}</td>
                            <td>
                                <span class="badge {{ $warehouse['status'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $warehouse['status'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('warehouses.show', $warehouse['id']) }}"
                                    class="btn btn-sm btn-info rounded-pill text-white">View</a>

                                <button class="btn btn-sm btn-danger rounded-pill">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
