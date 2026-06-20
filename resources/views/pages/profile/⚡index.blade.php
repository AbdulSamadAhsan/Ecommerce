<?php

use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $name = 'Admin User';
    public $email = 'admin@example.com';
    public $phone = '03001234567';
    public $address = 'Karachi, Pakistan';
    public $role = 'Super Admin';

    public $photo;

    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    public function updateProfile(): void
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        session()->flash('profile_success', 'Profile updated successfully.');
    }

    public function changePassword(): void
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|same:new_password_confirmation',
        ]);

        session()->flash('password_success', 'Password changed successfully.');

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }
};

?>

<div>

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold">
                My Profile
            </h3>

            <p class="text-muted mb-0">
                Manage your account information
            </p>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-4">

            <div class="card border-0 shadow mb-4">

                <div class="card-body text-center">

                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="rounded-circle mb-3" width="150" height="150">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}" class="rounded-circle mb-3"
                            width="150" height="150">
                    @endif

                    <h4 class="fw-bold">
                        {{ $name }}
                    </h4>

                    <p class="text-muted">
                        {{ $role }}
                    </p>

                    <input type="file" wire:model="photo" class="form-control">

                </div>

            </div>

            <div class="card border-0 shadow">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Account Status
                    </h5>

                    <p>
                        <span class="badge bg-success">
                            Active
                        </span>
                    </p>

                    <p>
                        <strong>Role:</strong>
                        {{ $role }}
                    </p>

                    <p>
                        <strong>Last Login:</strong><br>
                        {{ now()->subHours(2)->format('d M Y h:i A') }}
                    </p>

                </div>

            </div>

        </div>

        <div class="col-lg-8">

            <div class="card border-0 shadow mb-4">

                <div class="card-header bg-light">

                    <h5 class="mb-0">
                        Personal Information
                    </h5>

                </div>

                <div class="card-body">

                    @if (session()->has('profile_success'))
                        <div class="alert alert-success">
                            {{ session('profile_success') }}
                        </div>
                    @endif

                    <form wire:submit="updateProfile">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Full Name
                                </label>

                                <input type="text" wire:model="name" class="form-control">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email" wire:model="email" class="form-control">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Phone
                                </label>

                                <input type="text" wire:model="phone" class="form-control">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Role
                                </label>

                                <input type="text" value="{{ $role }}" class="form-control" disabled>

                            </div>

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Address
                                </label>

                                <textarea wire:model="address" class="form-control" rows="3"></textarea>

                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill">
                            Update Profile
                        </button>

                    </form>

                </div>

            </div>

            <div class="card border-0 shadow">

                <div class="card-header bg-light">

                    <h5 class="mb-0">
                        Change Password
                    </h5>

                </div>

                <div class="card-body">

                    @if (session()->has('password_success'))
                        <div class="alert alert-success">
                            {{ session('password_success') }}
                        </div>
                    @endif

                    <form wire:submit="changePassword">

                        <div class="mb-3">

                            <label class="form-label">
                                Current Password
                            </label>

                            <input type="password" wire:model="current_password" class="form-control">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                New Password
                            </label>

                            <input type="password" wire:model="new_password" class="form-control">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Confirm Password
                            </label>

                            <input type="password" wire:model="new_password_confirmation" class="form-control">

                        </div>

                        <button type="submit" class="btn btn-success rounded-pill">
                            Change Password
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
