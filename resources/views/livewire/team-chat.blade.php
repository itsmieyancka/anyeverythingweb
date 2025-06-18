<div class="flex flex-col h-screen max-w-md mx-auto bg-base-200 rounded-lg shadow-md overflow-hidden">
    <!-- Messages container -->
    <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-4">
        <!-- Admin message -->
        <div class="chat chat-start items-start">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full ring ring-secondary ring-offset-2 ring-offset-base-100">
                    <img alt="Admin" src="https://i.pravatar.cc/100?u=admin" />
                </div>
            </div>
            <div class="chat-bubble bg-secondary text-secondary-content max-w-xs hover:bg-secondary-focus transition-colors duration-300">
                Hie guys, good work today
            </div>
        </div>

        <!-- Example customer message -->
        <div class="chat chat-start items-start">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full ring ring-primary ring-offset-2 ring-offset-base-100">
                    <img alt="Alice" src="https://i.pravatar.cc/100?u=alice" />
                </div>
            </div>
            <div class="chat-bubble bg-primary text-primary-content max-w-xs hover:bg-primary-focus transition-colors duration-300">
                Just reviewed the new running shoes — customers love the new design and comfort!
            </div>
        </div>
    </div>

    <!-- Input form -->
    <form class="p-4 border-t border-base-300 flex gap-2 bg-base-100" onsubmit="event.preventDefault(); sendMessage();">
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

        const wrapper = document.createElement('div');
        wrapper.className = 'chat chat-end items-start';

        const avatar = document.createElement('div');
        avatar.className = 'chat-image avatar';
        avatar.innerHTML = `
            <div class="w-10 rounded-full ring ring-accent ring-offset-2 ring-offset-base-100">
                <img alt="User" src="https://i.pravatar.cc/100?u=user" />
            </div>
        `;

        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble bg-neutral text-neutral-content max-w-xs hover:bg-neutral-focus transition-colors duration-300';
        bubble.textContent = message;

        wrapper.appendChild(bubble);
        wrapper.appendChild(avatar);
        chatMessages.appendChild(wrapper);

        chatMessages.scrollTop = chatMessages.scrollHeight;

        input.value = '';
    }
</script>

