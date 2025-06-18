<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeamChat extends Component
{
    public $message = '';
    public $receiverId;
    public $admins;
    public $chatMessages;

    public function mount()
    {
        $this->admins = User::role('admin')->where('id', '!=', Auth::id())->get();
        $this->receiverId = $this->admins->first()?->id;

        $this->loadMessages();
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string',
            'receiverId' => 'required|exists:users,id',
        ]);

        ChatMessage::create([
            'user_id' => Auth::id(),
            'receiver_id' => $this->receiverId,
            'message' => $this->message,
        ]);

        $this->message = '';
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $authId = Auth::id();

        $this->chatMessages = ChatMessage::with('sender', 'receiver')
            ->where(function ($q) use ($authId) {
                $q->where('user_id', $authId)->orWhere('receiver_id', $authId);
            })
            ->orderBy('created_at')
            ->get();
    }

    public function render()
    {
        return view('livewire.team-chat');
    }
}
