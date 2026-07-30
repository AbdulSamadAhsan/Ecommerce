<?php

use Livewire\Component;
use App\Models\ContactUs;

new class extends Component {
    public ContactUs $contact;

    public function mount(int $id): void
    {
        $this->contact = ContactUs::findOrFail($id);

        if ($this->contact->status === 'unread') {
            $this->contact->update([
                'status' => 'read',
            ]);

            $this->contact->refresh();
        }
    }

    public function markAsRead(): void
    {
        $this->contact->update([
            'status' => 'read',
        ]);

        $this->contact->refresh();

        session()->flash('success', 'Message marked as read.');
    }

    public function markAsUnread(): void
    {
        $this->contact->update([
            'status' => 'unread',
        ]);

        $this->contact->refresh();

        session()->flash('success', 'Message marked as unread.');
    }

    public function delete(): void
    {
        $this->contact->delete();

        session()->flash('success', 'Message deleted successfully.');

        $this->redirect(route('contact-us.index'), navigate: true);
    }
};

?>

<div class="row">

    <div class="col-lg-4 mb-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Customer Information</h5>
            </div>

            <div class="card-body">

                <table class="table table-borderless mb-0">

                    <tr>
                        <th width="35%">Name</th>
                        <td>{{ $contact->name }}</td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td>
                            <a href="mailto:{{ $contact->email }}">
                                {{ $contact->email }}
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <th>Phone</th>
                        <td>{{ $contact->phone ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>

                            @switch($contact->status)
                                @case('unread')
                                    <span class="badge bg-danger">Unread</span>
                                @break

                                @case('read')
                                    <span class="badge bg-warning text-dark">Read</span>
                                @break

                                @case('replied')
                                    <span class="badge bg-success">Replied</span>
                                @break

                                @default
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($contact->status) }}
                                    </span>
                            @endswitch

                        </td>
                    </tr>

                    <tr>
                        <th>Received</th>
                        <td>{{ $contact->created_at->format('d M Y h:i A') }}</td>
                    </tr>

                </table>

            </div>

        </div>

    </div>

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Message Details
                </h5>

                <a href="{{ route('contact-us.index') }}" wire:navigate class="btn btn-light btn-sm">

                    <i class="bi bi-arrow-left"></i>

                    Back

                </a>

            </div>

            <div class="card-body">

                <div class="mb-4">
                    <label class="fw-bold mb-2">Subject</label>

                    <div class="border rounded p-3 bg-light">
                        {{ $contact->subject }}
                    </div>
                </div>

                <div>
                    <label class="fw-bold mb-2">Customer Message</label>

                    <div class="border rounded p-3 bg-light" style="min-height:220px; ">
                        {{ $contact->message }}
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
