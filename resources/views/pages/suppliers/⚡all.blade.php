<?php

use Livewire\Component;
use App\Models\Supplier;
new class extends Component {
    public string $search = '';

    public $suppliers;

    public function mount()
    {
        $this->suppliers = Supplier::get();
    }
};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Suppliers</h3>
            <p class="text-muted mb-0">Manage all suppliers</p>
        </div>

        <a href="{{ route('suppliers.create') }}" class="btn btn-primary rounded-pill">
            Add Supplier
        </a>
    </div>

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4" placeholder="Search supplier...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Supplier</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td>#{{ $supplier['id'] }}</td>
                            <td>{{ $supplier->user->name }}</td>
                            <td>{{ $supplier['email'] }}</td>
                            <td>{{ $supplier['phone'] }}</td>
                            <td>
                                <span class="badge {{ $supplier['status'] ? 'bg-success' : 'bg-danger' }}">
                                    {{ $supplier['status'] ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('suppliers.show', $supplier['id']) }}"
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
