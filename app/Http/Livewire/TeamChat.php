<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

class TeamChat extends Component
{
    public $message;
    public $messages = [];

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = ChatMessage::with('user')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values()
            ->all(); // Ensure it's a plain array, not a Collection
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|max:500',
        ]);

        ChatMessage::create([
            'user_id' => Auth::id(),
            'message' => $this->message,
        ]);

        $this->message = '';
        $this->loadMessages(); // Refresh chat after sending
    }

    public function render()
    {
        return view('livewire.team-chat');
    }
}
