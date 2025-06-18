<div class="flex flex-col h-screen max-h-[600px] max-w-md mx-auto bg-base-200 rounded-lg shadow-md">
    <!-- Messages container -->
    <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-4">
        <!-- Existing messages... -->
        <div class="chat chat-start items-start space-x-3">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full ring ring-primary ring-offset-2 ring-offset-base-100">
                    <img alt="Alice" src="https://i.pravatar.cc/100?u=alice" />
                </div>
            </div>
            <div class="chat-bubble bg-primary text-primary-content max-w-xs hover:bg-primary-focus transition-colors duration-300">
                Just reviewed the new running shoes — customers love the new design and comfort!
            </div>
        </div>
        <!-- Add more sample messages here if needed -->
    </div>

    <!-- Input form -->
    <form class="p-4 border-t flex gap-2" onsubmit="event.preventDefault(); sendMessage();">
        <input
            id="chatInput"
            type="text"
            placeholder="Type your message..."
            class="input input-bordered w-full"
            autocomplete="off"
            required
        />
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>

<script>
    function sendMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();
        if (!message) return;

        const chatMessages = document.getElementById('chatMessages');

        // Create new message bubble
        const wrapper = document.createElement('div');
        wrapper.className = 'chat chat-end items-start space-x-3';

        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble bg-neutral text-neutral-content max-w-xs hover:bg-neutral-focus transition-colors duration-300';
        bubble.textContent = message;

        wrapper.appendChild(bubble);
        chatMessages.appendChild(wrapper);

        // Auto scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Clear input
        input.value = '';
    }
</script>
