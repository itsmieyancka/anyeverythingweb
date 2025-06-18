<div class="p-4 bg-base-200 rounded-lg shadow-md h-[450px] flex flex-col">
    <div class="flex space-x-2 mb-2">
        <label for="receiver">To:</label>
        <select wire:model="receiverId" class="select select-sm select-bordered">
            @foreach($admins as $admin)
                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
            @endforeach
        </select>
    </div>

    <div id="chatMessages" class="flex-1 overflow-y-auto space-y-4 p-2 bg-base-100 rounded">
        @foreach($chatMessages as $chat)
            <div class="chat {{ $chat->user_id === auth()->id() ? 'chat-end' : 'chat-start' }}">
                <div class="chat-image avatar">
                    <div class="w-10 rounded-full">
                        <img src="https://i.pravatar.cc/100?u={{ $chat->sender->email }}" />
                    </div>
                </div>
                <div class="chat-bubble">
                    <strong>{{ $chat->sender->name }}:</strong> {{ $chat->message }}
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-2 flex">
        <input wire:model="message" type="text" class="input input-bordered input-sm flex-1" placeholder="Type your message..." />
        <button wire:click="sendMessage" class="btn btn-sm btn-primary ml-2">Send</button>
    </div>
</div>


