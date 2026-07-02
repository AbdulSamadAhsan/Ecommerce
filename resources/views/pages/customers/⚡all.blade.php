<?php

use Livewire\Component;
use App\Models\Customer;
use App\Models\Wallet;
new class extends Component {
    public string $search = '';

    public $customers;

    public function mount(): void
    {
        $this->customers = Customer::get();
    }

    public function getFilteredCustomersProperty()
    {
        if (empty($this->search)) {
            return $this->customers;
        }

        return array_filter($this->customers, function ($customer) {
            return str_contains(strtolower($customer->user->name), strtolower($this->search)) || str_contains(strtolower($customer->user->email), strtolower($this->search)) || str_contains(strtolower($customer['phone']), strtolower($this->search));
        });
    }
};

?>

<div>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold mb-1">
                Customers
            </h3>

            <p class="text-muted mb-0">
                Manage all customers
            </p>

        </div>

    </div>

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Total Customers
                    </h6>

                    <h3 class="fw-bold text-primary">
                        {{ count($customers) }}
                    </h3>

                </div>
            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Active Customers
                    </h6>

                    <h3 class="fw-bold text-success">
                        {{ collect($customers)->where('status', 1)->count() }}
                    </h3>

                </div>
            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Total Wallet
                    </h6>

                    <h3 class="fw-bold text-info">
                        Rs {{ number_format($customers->sum(fn($customer) => $customer->wallet?->balance ?? 0), 2) }}
                    </h3>

                </div>
            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Total Orders
                    </h6>

                    <h3 class="fw-bold text-warning">
                        {{ $customers->sum(fn($customer) => $customer->sales->filter(fn($sale) => $sale->orderNumber)->count()) }}
                    </h3>

                </div>
            </div>

        </div>

    </div>

    <div class="card border-0 shadow">

        <div class="card-body">

            <div class="row mb-4">

                <div class="col-md-6">

                    <input type="text" wire:model.live="search" class="form-control rounded-4"
                        placeholder="Search customer...">

                </div>

            </div>

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Wallet</th>
                            <th>Orders</th>
                            <th>Status</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($this->filteredCustomers as $customer)
                            <tr>

                                <td>
                                    #{{ $customer['id'] }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $customer->user->name }}
                                    </div>
                                </td>

                                <td>
                                    {{ $customer->user->email }}
                                </td>

                                <td>
                                    {{ $customer['phone'] }}
                                </td>

                                <td>
                                    Rs {{ number_format($customer->wallet->balance) }}
                                </td>

                                <td>
                                    {{ $customer->sales->filter(fn($sale) => $sale->orderNumber)->count() }}
                                </td>
                                <td>

                                    @if ($customer['status'])
                                        <span class="badge bg-success rounded-pill">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger rounded-pill">
                                            Inactive
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <a href="{{ route('customers.show', $customer['id']) }}"
                                        class="btn btn-sm btn-info rounded-pill">

                                        View

                                    </a>



                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center text-muted py-4">

                                    No customers found.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
