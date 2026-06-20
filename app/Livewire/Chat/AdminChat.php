<?php

namespace App\Livewire\Chat;

use App\Models\ChatThread;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminChat extends Component
{
    public ?int $selectedThreadId = null;
    public string $message = '';
    public string $search = '';
    public string $status = 'open';

    public function mount(): void
    {
        $this->authorizeAdmin();

        $this->selectedThreadId = ChatThread::query()
            ->orderByDesc('last_message_at')
            ->latest()
            ->value('id');

        $this->markSelectedThreadAsRead();
    }

    public function selectThread(int $threadId): void
    {
        $this->authorizeAdmin();

        $this->selectedThreadId = $threadId;

        $thread = ChatThread::find($threadId);

        if ($thread && ! $thread->admin_id) {
            $thread->update(['admin_id' => Auth::id()]);
        }

        $this->markSelectedThreadAsRead();
    }

    public function sendMessage(): void
    {
        $this->authorizeAdmin();

        $validated = $this->validate([
            'message' => ['required', 'string', 'max:5000'],
            'selectedThreadId' => ['required', 'integer', 'exists:chat_threads,id'],
        ]);

        $thread = ChatThread::findOrFail($validated['selectedThreadId']);

        $thread->messages()->create([
            'sender_id' => Auth::id(),
            'message' => trim($validated['message']),
        ]);

        $thread->update([
            'admin_id' => Auth::id(),
            'status' => 'open',
            'last_message_at' => now(),
        ]);

        $this->reset('message');
    }

    public function closeThread(): void
    {
        $this->authorizeAdmin();

        if (! $this->selectedThreadId) {
            return;
        }

        ChatThread::whereKey($this->selectedThreadId)->update(['status' => 'closed']);
    }

    public function reopenThread(): void
    {
        $this->authorizeAdmin();

        if (! $this->selectedThreadId) {
            return;
        }

        ChatThread::whereKey($this->selectedThreadId)->update(['status' => 'open']);
    }

    public function getThreadsProperty()
    {
        return ChatThread::query()
            ->with(['user', 'latestMessage.sender'])
            ->withCount([
                'messages as unread_count' => function ($query) {
                    $query->whereNull('read_at')
                        ->where('sender_id', '!=', Auth::id());
                },
            ])
            ->when($this->status !== 'all', function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->search !== '', function ($query) {
                $search = '%' . trim($this->search) . '%';

                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search);
                });
            })
            ->orderByRaw('last_message_at IS NULL')
            ->orderByDesc('last_message_at')
            ->latest()
            ->get();
    }

    public function getSelectedThreadProperty(): ?ChatThread
    {
        if (! $this->selectedThreadId) {
            return null;
        }

        return ChatThread::query()
            ->with(['user', 'admin'])
            ->find($this->selectedThreadId);
    }

    public function getMessagesProperty()
    {
        if (! $this->selectedThreadId) {
            return collect();
        }

        return ChatThread::find($this->selectedThreadId)?->messages()
            ->with('sender')
            ->oldest()
            ->get() ?? collect();
    }

    private function markSelectedThreadAsRead(): void
    {
        if (! $this->selectedThreadId) {
            return;
        }

        ChatThread::find($this->selectedThreadId)?->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', Auth::id())
            ->update(['read_at' => now()]);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
    }

    public function render()
    {
        $this->markSelectedThreadAsRead();

        return view('livewire.chat.admin-chat')
            ->layout('layouts.app');
    }
}
