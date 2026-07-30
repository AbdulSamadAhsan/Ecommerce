<?php

use Livewire\Component;
use App\Models\ContactUs;
use Illuminate\Validation\Rule;

new class extends Component {
    public $message_id;

    public $name = '';
    public $email = '';
    public $phone = '';
    public $subject = '';
    public $message = '';
    public $status = 'unread';

    public function mount($id)
    {
        $contact = ContactUs::findOrFail($id);
        if ($contact->status == 'replied') {
            redirect()->route('contact-us.index');
            return;
        }
        $this->message_id = $contact->id;
        $this->name = $contact->name;
        $this->email = $contact->email;
        $this->phone = $contact->phone;
        $this->subject = $contact->subject;
        $this->message = $contact->message;
        $this->status = $contact->status;
    }

    protected function rules()
    {
        return [
            'name' => 'required|min:2|max:255',
            'email' => ['required', 'email', Rule::unique('contact_messages', 'email')->ignore($this->message_id)],
            'phone' => 'nullable|max:30',
            'subject' => 'required|max:255',
            'message' => 'required|max:5000',
            'status' => 'required|in:unread,read,replied',
        ];
    }

    protected $messages = [
        'name.required' => 'Customer name is required.',
        'email.required' => 'Email is required.',
        'subject.required' => 'Subject is required.',
        'message.required' => 'Message is required.',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function update()
    {
        $this->validate();

        ContactUs::findOrFail($this->message_id)->update([
            'status' => $this->status,
        ]);

        session()->flash('success', 'Contact message updated successfully.');
        redirect()->route('contact-us.index');
        return;
    }
};

?>

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Contact Message</h4>
            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit="update">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer Name</label>

                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                wire:model.live="name" readonly>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>

                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                wire:model.live="email" readonly>

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>

                            <input type="text" class="form-control" readonly wire:model.live="phone">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>

                            <select class="form-select" wire:model.live="status">

                                <option value="unread">Unread</option>
                                <option value="read">Read</option>
                                <option value="replied">Replied</option>

                            </select>
                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Subject</label>

                        <input type="text" class="form-control @error('subject') is-invalid @enderror"
                            wire:model.live="subject" readonly>

                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-4">

                        <label class="form-label">Message</label>

                        <textarea rows="8" readonly class="form-control @error('message') is-invalid @enderror" wire:model.live="message"></textarea>

                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="text-end">

                        <button class="btn btn-primary" wire:loading.attr="disabled">

                            <span wire:loading.remove>
                                Update Message
                            </span>

                            <span wire:loading>
                                Updating...
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
