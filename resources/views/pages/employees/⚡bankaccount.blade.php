<?php

use Livewire\Component;
use App\Models\Employee;
use App\Models\EmployeeBankAccount;

new class extends Component {};
?>

<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Employee Bank Accounts</h3>
            <p class="text-muted mb-0">Manage employee salary account details</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="dashboard-card mb-4">
        <form wire:submit.prevent="{{ $bank_account_id ? 'update' : 'save' }}">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Employee</label>
                    <select wire:model="employee_id" class="form-control rounded-4">
                        <option value="">Select Employee</option>

                    </select>

                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Bank Name</label>
                    <input type="text" wire:model="bank_name" class="form-control rounded-4">

                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Account Title</label>
                    <input type="text" wire:model="account_title" class="form-control rounded-4">
                    @error('account_title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Account Number</label>
                    <input type="text" wire:model="account_number" class="form-control rounded-4">
                    @error('account_number')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">IBAN</label>
                    <input type="text" wire:model="iban" class="form-control rounded-4">
                    @error('iban')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Branch Name</label>
                    <input type="text" wire:model="branch_name" class="form-control rounded-4">
                    @error('branch_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Branch Code</label>
                    <input type="text" wire:model="branch_code" class="form-control rounded-4">
                    @error('branch_code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Swift Code</label>
                    <input type="text" wire:model="swift_code" class="form-control rounded-4">
                    @error('swift_code')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-4 mb-3 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input type="checkbox" wire:model="is_primary" class="form-check-input" id="is_primary">
                        <label class="form-check-label" for="is_primary">
                            Primary Account
                        </label>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Notes</label>
                    <textarea wire:model="notes" class="form-control rounded-4"></textarea>
                    @error('notes')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary rounded-pill">
                        {{ $bank_account_id ? 'Update Bank Account' : 'Save Bank Account' }}
                    </button>

                    <button type="button" wire:click="resetForm" class="btn btn-warning rounded-pill">
                        Reset
                    </button>
                </div>

            </div>
        </form>
    </div>

    <div class="dashboard-card">
        <input type="text" wire:model.live="search" class="form-control rounded-4 mb-4"
            placeholder="Search bank account...">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Employee</th>
                        <th>Bank</th>
                        <th>Account Title</th>
                        <th>Account Number</th>
                        <th>IBAN</th>
                        <th>Primary</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
