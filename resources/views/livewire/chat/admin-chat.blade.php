<div wire:poll.3s>
    <style>
        .chat-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
            overflow: hidden;
        }

        .chat-sidebar {
            height: 72vh;
            overflow-y: auto;
            border-right: 1px solid #e5e7eb;
        }

        .chat-window {
            height: 72vh;
            display: flex;
            flex-direction: column;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            background: #f8fafc;
        }

        .message-bubble {
            max-width: 75%;
            border-radius: 18px;
            padding: 12px 15px;
            line-height: 1.45;
            word-wrap: break-word;
        }

        .message-admin {
            background: #2563eb;
            color: #fff;
            border-bottom-right-radius: 4px;
        }

        .message-user {
            background: #fff;
            color: #111827;
            border: 1px solid #e5e7eb;
            border-bottom-left-radius: 4px;
        }

        .thread-button {
            border: 0;
            width: 100%;
            text-align: left;
            background: transparent;
            border-bottom: 1px solid #f1f5f9;
            padding: 15px;
        }

        .thread-button:hover,
        .thread-button.active {
            background: #eff6ff;
        }

        @media (max-width: 768px) {
            .chat-sidebar,
            .chat-window {
                height: auto;
            }
        }
    </style>

    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Customer Chat</h3>
            <p class="text-muted mb-0">Reply to customers in real time using Livewire polling.</p>
        </div>

        @if ($this->selectedThread)
            <div class="d-flex gap-2">
                @if ($this->selectedThread->status === 'open')
                    <button type="button" wire:click="closeThread" class="btn btn-outline-danger rounded-pill">
                        <i class="bi bi-x-circle me-1"></i>
                        Close Chat
                    </button>
                @else
                    <button type="button" wire:click="reopenThread" class="btn btn-outline-success rounded-pill">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Reopen Chat
                    </button>
                @endif
            </div>
        @endif
    </div>

    <div class="chat-card">
        <div class="row g-0">
            <div class="col-md-4 col-lg-3 chat-sidebar">
                <div class="p-3 border-bottom bg-white">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control rounded-pill mb-2"
                        placeholder="Search customer...">

                    <select wire:model.live="status" class="form-select rounded-pill">
                        <option value="open">Open Chats</option>
                        <option value="closed">Closed Chats</option>
                        <option value="all">All Chats</option>
                    </select>
                </div>

                @forelse ($this->threads as $thread)
                    <button type="button" wire:click="selectThread({{ $thread->id }})"
                        class="thread-button {{ $selectedThreadId === $thread->id ? 'active' : '' }}">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div class="flex-grow-1">
                                <div class="fw-bold">
                                    {{ $thread->user?->name ?? 'User #' . $thread->user_id }}
                                </div>
                                <small class="text-muted d-block">
                                    {{ $thread->user?->email ?? 'No email' }}
                                </small>
                            </div>

                            @if ($thread->unread_count > 0)
                                <span class="badge bg-danger rounded-pill">{{ $thread->unread_count }}</span>
                            @endif
                        </div>

                        <div class="small text-muted mt-2">
                            {{ \Illuminate\Support\Str::limit($thread->latestMessage?->message ?? 'No messages yet', 55) }}
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">
                                {{ $thread->last_message_at?->diffForHumans() ?? $thread->created_at?->diffForHumans() }}
                            </small>
                            <span class="badge {{ $thread->status === 'open' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($thread->status) }}
                            </span>
                        </div>
                    </button>
                @empty
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-chat-dots fs-2 d-block mb-2"></i>
                        No chat found.
                    </div>
                @endforelse
            </div>

            <div class="col-md-8 col-lg-9">
                @if ($this->selectedThread)
                    <div class="chat-window">
                        <div class="p-3 border-bottom bg-white d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold mb-0">
                                    {{ $this->selectedThread->user?->name ?? 'Customer' }}
                                </h5>
                                <small class="text-muted">
                                    {{ $this->selectedThread->user?->email ?? 'No email' }}
                                </small>
                            </div>
                            <span class="badge {{ $this->selectedThread->status === 'open' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($this->selectedThread->status) }}
                            </span>
                        </div>

                        <div class="chat-messages p-3">
                            @forelse ($this->messages as $chatMessage)
                                @php($mine = $chatMessage->sender_id === auth()->id())

                                <div class="d-flex mb-3 {{ $mine ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div class="message-bubble {{ $mine ? 'message-admin' : 'message-user' }}">
                                        <div>{{ $chatMessage->message }}</div>
                                        <small class="d-block mt-1 {{ $mine ? 'text-white-50' : 'text-muted' }}">
                                            {{ $chatMessage->sender?->name ?? 'User' }} ·
                                            {{ $chatMessage->created_at->format('d M, h:i A') }}
                                        </small>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-chat-left-text fs-1 d-block mb-2"></i>
                                    No messages yet.
                                </div>
                            @endforelse
                        </div>

                        <form wire:submit.prevent="sendMessage" class="p-3 bg-white border-top">
                            <div class="input-group">
                                <textarea wire:model.defer="message" class="form-control" rows="2" placeholder="Type admin reply..."></textarea>
                                <button class="btn btn-primary px-4" type="submit">
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                            @error('message')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </form>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-chat-square-dots fs-1 d-block mb-3"></i>
                        Select a chat to start replying.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
