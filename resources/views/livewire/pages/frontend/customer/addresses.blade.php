<?php

use App\Models\CustomerAddress;
use Livewire\Volt\Component;

new class extends Component {
    public string $full_name = '';
    public string $phone = '';
    public string $address_line_1 = '';
    public string $address_line_2 = '';
    public string $city = '';
    public string $state = '';
    public string $postal_code = '';
    public string $country = 'Pakistan';
    public bool $is_default = false;
    public ?int $editingAddressId = null;

    public function mount(): void
    {
        $user = auth('customer')->user();

        $this->full_name = $user?->name ?? '';
        $this->phone = $user?->customer?->phone ?? '';
    }

    protected function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'min:2', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address_line_1' => ['required', 'string', 'min:5', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:30'],
            'country' => ['required', 'string', 'max:100'],
            'is_default' => ['boolean'],
        ];
    }

    public function saveAddress(): void
    {
        $validated = $this->validate();
        $user = auth('customer')->user();

        if (! $user) {
            $this->redirectRoute('customer.login', navigate: true);
            return;
        }

        $customerId = $user->customer?->id;

        if ($this->is_default) {
            CustomerAddress::where('user_id', $user->id)->update(['is_default' => false]);
        }

        $address = CustomerAddress::updateOrCreate(
            [
                'id' => $this->editingAddressId,
                'user_id' => $user->id,
            ],
            array_merge($validated, [
                'user_id' => $user->id,
                'customer_id' => $customerId,
            ])
        );

        if (! CustomerAddress::where('user_id', $user->id)->where('is_default', true)->exists()) {
            $address->update(['is_default' => true]);
        }

        $this->resetForm();
        session()->flash('success', 'Address saved successfully.');
    }

    public function editAddress(int $id): void
    {
        $address = $this->addressQuery()->findOrFail($id);

        $this->editingAddressId = $address->id;
        $this->full_name = $address->full_name;
        $this->phone = $address->phone;
        $this->address_line_1 = $address->address_line_1;
        $this->address_line_2 = $address->address_line_2 ?? '';
        $this->city = $address->city;
        $this->state = $address->state ?? '';
        $this->postal_code = $address->postal_code ?? '';
        $this->country = $address->country;
        $this->is_default = (bool) $address->is_default;
    }

    public function setDefault(int $id): void
    {
        $user = auth('customer')->user();
        $address = $this->addressQuery()->findOrFail($id);

        CustomerAddress::where('user_id', $user->id)->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        session()->flash('success', 'Default address updated.');
    }

    public function deleteAddress(int $id): void
    {
        $address = $this->addressQuery()->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        $user = auth('customer')->user();

        if ($wasDefault) {
            CustomerAddress::where('user_id', $user->id)->latest()->first()?->update(['is_default' => true]);
        }

        if ($this->editingAddressId === $id) {
            $this->resetForm();
        }

        session()->flash('success', 'Address deleted successfully.');
    }

    public function resetForm(): void
    {
        $user = auth('customer')->user();

        $this->editingAddressId = null;
        $this->full_name = $user?->name ?? '';
        $this->phone = $user?->customer?->phone ?? '';
        $this->address_line_1 = '';
        $this->address_line_2 = '';
        $this->city = '';
        $this->state = '';
        $this->postal_code = '';
        $this->country = 'Pakistan';
        $this->is_default = false;
        $this->resetValidation();
    }

    private function addressQuery()
    {
        return CustomerAddress::where('user_id', auth('customer')->id());
    }

    public function with(): array
    {
        return [
            'addresses' => $this->addressQuery()->latest()->get(),
        ];
    }
};
?>

<x-layouts.ecommerce>
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4 text-center bg-primary text-white">
                        <div class="rounded-circle bg-white text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width:70px;height:70px;">
                            <i class="bi bi-person-circle fs-1"></i>
                        </div>
                        <h5 class="fw-bold mb-1">My Account</h5>
                        <p class="small mb-0 opacity-75">Manage orders and profile</p>
                    </div>

                    <div class="list-group list-group-flush">
                        <a wire:navigate href="{{ route('customer.dashboard') }}" class="list-group-item list-group-item-action py-3">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a wire:navigate href="{{ route('customer.orders') }}" class="list-group-item list-group-item-action py-3">
                            <i class="bi bi-bag-check me-2"></i> Order History
                        </a>
                        <a wire:navigate href="{{ route('customer.addresses') }}" class="list-group-item list-group-item-action py-3 active">
                            <i class="bi bi-geo-alt me-2"></i> Saved Addresses
                        </a>
                        <a wire:navigate href="{{ route('customer.profile') }}" class="list-group-item list-group-item-action py-3">
                            <i class="bi bi-person-gear me-2"></i> Profile
                        </a>
                        <a wire:navigate href="{{ route('customer.wishlist') }}" class="list-group-item list-group-item-action py-3">
                            <i class="bi bi-heart me-2"></i> Wishlist
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Saved Addresses</h2>
                        <p class="text-muted mb-0">Save your delivery addresses for faster checkout.</p>
                    </div>
                    <a wire:navigate href="{{ route('customer.orders') }}" class="btn btn-light rounded-pill px-4">
                        <i class="bi bi-clock-history me-1"></i> Order History
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success border-0 rounded-4 shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="row g-4">
                    <div class="col-xl-5">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white border-0 p-4 pb-0">
                                <h5 class="fw-bold mb-1">
                                    <i class="bi bi-geo-alt-fill text-primary me-1"></i>
                                    {{ $editingAddressId ? 'Update Address' : 'Add New Address' }}
                                </h5>
                                <p class="text-muted small mb-0">Fill delivery address details.</p>
                            </div>

                            <div class="card-body p-4">
                                <form wire:submit.prevent="saveAddress">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Full Name</label>
                                        <input type="text" wire:model.live="full_name" class="form-control rounded-3 @error('full_name') is-invalid @enderror" placeholder="Customer name">
                                        @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Phone</label>
                                        <input type="text" wire:model.live="phone" class="form-control rounded-3 @error('phone') is-invalid @enderror" placeholder="03xxxxxxxxx">
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Address Line 1</label>
                                        <input type="text" wire:model.live="address_line_1" class="form-control rounded-3 @error('address_line_1') is-invalid @enderror" placeholder="House, street, area">
                                        @error('address_line_1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Address Line 2 <span class="text-muted">(optional)</span></label>
                                        <input type="text" wire:model.live="address_line_2" class="form-control rounded-3 @error('address_line_2') is-invalid @enderror" placeholder="Apartment, floor, landmark">
                                        @error('address_line_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">City</label>
                                            <input type="text" wire:model.live="city" class="form-control rounded-3 @error('city') is-invalid @enderror" placeholder="Karachi">
                                            @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">State</label>
                                            <input type="text" wire:model.live="state" class="form-control rounded-3 @error('state') is-invalid @enderror" placeholder="Sindh">
                                            @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-1">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Postal Code</label>
                                            <input type="text" wire:model.live="postal_code" class="form-control rounded-3 @error('postal_code') is-invalid @enderror" placeholder="75300">
                                            @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Country</label>
                                            <input type="text" wire:model.live="country" class="form-control rounded-3 @error('country') is-invalid @enderror">
                                            @error('country') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <div class="form-check form-switch my-4">
                                        <input class="form-check-input" type="checkbox" role="switch" id="isDefaultAddress" wire:model.live="is_default">
                                        <label class="form-check-label fw-semibold" for="isDefaultAddress">Set as default address</label>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                            <span wire:loading.remove wire:target="saveAddress">
                                                <i class="bi bi-save me-1"></i> {{ $editingAddressId ? 'Update Address' : 'Save Address' }}
                                            </span>
                                            <span wire:loading wire:target="saveAddress">Saving...</span>
                                        </button>

                                        @if ($editingAddressId)
                                            <button type="button" wire:click="resetForm" class="btn btn-light rounded-pill px-4">Cancel</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="fw-bold mb-1">My Addresses</h5>
                                    <p class="text-muted small mb-0">{{ $addresses->count() }} saved address{{ $addresses->count() === 1 ? '' : 'es' }}</p>
                                </div>
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                    <i class="bi bi-truck me-1"></i> Delivery
                                </span>
                            </div>

                            <div class="card-body p-4 pt-0">
                                @forelse ($addresses as $address)
                                    <div class="border rounded-4 p-3 mb-3 {{ $address->is_default ? 'border-primary bg-primary-subtle' : 'bg-white' }}">
                                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                            <div>
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <h6 class="fw-bold mb-0">{{ $address->full_name }}</h6>
                                                    @if ($address->is_default)
                                                        <span class="badge bg-primary rounded-pill">Default</span>
                                                    @endif
                                                </div>

                                                <p class="mb-1 text-muted">
                                                    <i class="bi bi-telephone me-1"></i> {{ $address->phone }}
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-geo-alt me-1 text-primary"></i>
                                                    {{ $address->address_line_1 }}
                                                    @if ($address->address_line_2)
                                                        , {{ $address->address_line_2 }}
                                                    @endif
                                                </p>
                                                <p class="mb-0 text-muted small">
                                                    {{ $address->city }}@if($address->state), {{ $address->state }}@endif
                                                    @if($address->postal_code) - {{ $address->postal_code }}@endif,
                                                    {{ $address->country }}
                                                </p>
                                            </div>

                                            <div class="d-flex flex-md-column gap-2 align-self-start">
                                                @unless ($address->is_default)
                                                    <button type="button" wire:click="setDefault({{ $address->id }})" class="btn btn-sm btn-outline-primary rounded-pill">
                                                        Default
                                                    </button>
                                                @endunless
                                                <button type="button" wire:click="editAddress({{ $address->id }})" class="btn btn-sm btn-light rounded-pill">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" wire:click="deleteAddress({{ $address->id }})" wire:confirm="Are you sure you want to delete this address?" class="btn btn-sm btn-outline-danger rounded-pill">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                                            <i class="bi bi-geo-alt fs-1 text-primary"></i>
                                        </div>
                                        <h5 class="fw-bold">No address saved</h5>
                                        <p class="text-muted mb-0">Add your first delivery address using the form.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.ecommerce>
