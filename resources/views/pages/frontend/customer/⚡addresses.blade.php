<?php

use Livewire\Component;
use App\Models\CustomerAddress;
new #[\Livewire\Attributes\Layout('components.layouts.ecommerce')] class extends Component {
    public string $address_line_1 = '';
    public string $address_line_2 = '';
    public string $city = '';
    public string $province = '';
    public string $postal_code = '';
    public string $country = 'Pakistan';
    public bool $is_default = false;
    public ?int $editingAddressId = null;

    public function mount(): void
    {
        $user = auth('customer')->user();
    }

    protected function rules(): array
    {
        return [
            'address_line_1' => ['required', 'string', 'min:5', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:30'],
            'country' => ['required', 'string', 'max:100'],
            'is_default' => ['boolean'],
        ];
    }

    public function saveAddress(): void
    {
        $validated = $this->validate();
        $user = auth('customer')->user();

        if (!$user) {
            $this->redirectRoute('customer.login', navigate: true);
            return;
        }

        $customerId = $user->customer?->id;

        if ($this->is_default) {
            CustomerAddress::where('customer_id', $customerId)->update(['is_default' => false]);
        }

        $address = CustomerAddress::updateOrCreate(
            [
                'id' => $this->editingAddressId,
                'customer_id' => $customerId,
            ],
            array_merge($validated, [
                'customer_id' => $customerId,
            ]),
        );

        if (!CustomerAddress::where('customer_id', $user->id)->where('is_default', true)->exists()) {
            $address->update(['is_default' => true]);
        }

        $this->resetForm();
        session()->flash('success', 'Address saved successfully.');
    }

    public function editAddress(int $id): void
    {
        $address = $this->addressQuery()->findOrFail($id);

        $this->editingAddressId = $address->id;

        $this->address_line_1 = $address->address_line_1;
        $this->address_line_2 = $address->address_line_2 ?? '';
        $this->city = $address->city;
        $this->province = $address->province ?? '';
        $this->postal_code = $address->postal_code ?? '';
        $this->country = $address->country;
        $this->is_default = (bool) $address->is_default;
    }

    public function setDefault(int $id): void
    {
        $user = auth('customer')->user()->customer;
        $address = $this->addressQuery()->findOrFail($id);

        CustomerAddress::where('customer_id', $user->id)->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        session()->flash('success', 'Default address updated.');
    }

    public function deleteAddress(int $id): void
    {
        $address = $this->addressQuery()->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        $user = auth('customer')->user()->customer;

        if ($wasDefault) {
            CustomerAddress::where('customer_id', $user->id)
                ->latest()
                ->first()
                ?->update(['is_default' => true]);
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
        return CustomerAddress::where('customer_id', auth('customer')->user()->customer->id);
    }

    public function with(): array
    {
        return [
            'addresses' => $this->addressQuery()->latest()->get(),
        ];
    } //
};
?>

<div class="container py-5">

    <div class="row g-4">

        <div class="col-lg-3">
            @include('livewire.pages.frontend.customer.sidebar')
        </div>

        <div class="col-lg-9">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Saved Addresses</h2>
                    <p class="text-muted mb-0">Manage your delivery addresses.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success border-0 rounded-4 shadow-sm">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-4">
                    <h4 class="fw-bold mb-0">
                        {{ $editingAddressId ? 'Update Address' : 'Add New Address' }}
                    </h4>
                </div>

                <div class="card-body">
                    <form wire:submit.prevent="saveAddress">

                        <div class="row g-3">

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Address Line 1</label>
                                <input type="text" wire:model.live="address_line_1"
                                    class="form-control rounded-3 @error('address_line_1') is-invalid @enderror"
                                    placeholder="House, street, area">

                                @error('address_line_1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Address Line 2</label>
                                <input type="text" wire:model.live="address_line_2"
                                    class="form-control rounded-3 @error('address_line_2') is-invalid @enderror"
                                    placeholder="Apartment, floor, landmark">

                                @error('address_line_2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">City</label>
                                <input type="text" wire:model.live="city"
                                    class="form-control rounded-3 @error('city') is-invalid @enderror"
                                    placeholder="Karachi">

                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Province</label>
                                <input type="text" wire:model.live="province"
                                    class="form-control rounded-3 @error('province') is-invalid @enderror"
                                    placeholder="Sindh">

                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Postal Code</label>
                                <input type="text" wire:model.live="postal_code"
                                    class="form-control rounded-3 @error('postal_code') is-invalid @enderror"
                                    placeholder="75300">

                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Country</label>
                                <input type="text" wire:model.live="country"
                                    class="form-control rounded-3 @error('country') is-invalid @enderror">

                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="isDefaultAddress"
                                        wire:model.live="is_default">

                                    <label class="form-check-label fw-semibold" for="isDefaultAddress">
                                        Set as default address
                                    </label>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex gap-2 mt-4">

                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <span wire:loading.remove wire:target="saveAddress">
                                    <i class="bi bi-save me-1"></i>
                                    {{ $editingAddressId ? 'Update Address' : 'Save Address' }}
                                </span>

                                <span wire:loading wire:target="saveAddress">
                                    Saving...
                                </span>
                            </button>

                            @if ($editingAddressId)
                                <button type="button" wire:click="resetForm" class="btn btn-light rounded-pill px-4">
                                    Cancel
                                </button>
                            @endif

                        </div>

                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-header bg-white border-0 py-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">My Addresses</h4>
                        <p class="text-muted small mb-0">
                            {{ $addresses->count() }} saved address{{ $addresses->count() === 1 ? '' : 'es' }}
                        </p>
                    </div>

                    <span class="badge bg-primary rounded-pill px-3">
                        Delivery
                    </span>
                </div>

                <div class="card-body">

                    @forelse ($addresses as $address)
                        <div
                            class="border rounded-4 p-4 mb-4 {{ $address->is_default ? 'border-primary bg-primary-subtle' : '' }}">

                            <div class="d-flex justify-content-between gap-3">

                                <div>
                                    <h5 class="fw-bold mb-2">
                                        {{ $address->full_name ?? 'Delivery Address' }}

                                        @if ($address->is_default)
                                            <span class="badge bg-primary rounded-pill ms-2">
                                                Default
                                            </span>
                                        @endif
                                    </h5>

                                    <p class="mb-1">
                                        <i class="bi bi-geo-alt text-primary me-1"></i>
                                        {{ $address->address_line_1 }}

                                        @if ($address->address_line_2)
                                            , {{ $address->address_line_2 }}
                                        @endif
                                    </p>

                                    <p class="mb-1 text-muted">
                                        {{ $address->city }}

                                        @if ($address->province)
                                            , {{ $address->province }}
                                        @endif

                                        @if ($address->postal_code)
                                            - {{ $address->postal_code }}
                                        @endif

                                        , {{ $address->country }}
                                    </p>
                                </div>

                                <div class="d-flex flex-column gap-2">

                                    @unless ($address->is_default)
                                        <button type="button" wire:click="setDefault({{ $address->id }})"
                                            class="btn btn-sm btn-outline-primary rounded-pill">
                                            Default
                                        </button>
                                    @endunless

                                    <button type="button" wire:click="editAddress({{ $address->id }})"
                                        class="btn btn-sm btn-light rounded-pill">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button type="button" wire:click="deleteAddress({{ $address->id }})"
                                        wire:confirm="Are you sure you want to delete this address?"
                                        class="btn btn-sm btn-outline-danger rounded-pill">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </div>

                            </div>

                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-geo-alt fs-1 text-primary"></i>

                            <h5 class="fw-bold mt-3">
                                No address saved
                            </h5>

                            <p class="text-muted mb-0">
                                Add your first delivery address using the form above.
                            </p>
                        </div>
                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>
