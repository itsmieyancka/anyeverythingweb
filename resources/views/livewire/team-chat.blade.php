<div class="chat chat-start space-y-4 p-6 max-w-md mx-auto bg-base-200 rounded-lg shadow-md flex flex-col h-[600px]">
    <!-- Messages container -->
    <div class="overflow-y-auto flex-1 space-y-4 mb-4">
        <div class="chat chat-start items-start space-x-3">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full ring ring-primary ring-offset-2 ring-offset-base-100">
                    <img
                        alt="Alice avatar"
                        src="https://i.pravatar.cc/100?u=alice"
                    />
                </div>
            </div>
            <div class="chat-bubble bg-primary text-primary-content max-w-xs hover:bg-primary-focus transition-colors duration-300">
                Just reviewed the new running shoes — customers love the new design and comfort!
            </div>
        </div>

        <div class="chat chat-start items-start space-x-3">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full ring ring-secondary ring-offset-2 ring-offset-base-100">
                    <img
                        alt="Bob avatar"
                        src="https://i.pravatar.cc/100?u=bob"
                    />
                </div>
            </div>
            <div class="chat-bubble bg-secondary text-secondary-content max-w-xs hover:bg-secondary-focus transition-colors duration-300">
                Great! I updated the product page with new photos and detailed specs this morning.
            </div>
        </div>

        <div class="chat chat-start items-start space-x-3">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full ring ring-accent ring-offset-2 ring-offset-base-100">
                    <img
                        alt="Carol avatar"
                        src="https://i.pravatar.cc/100?u=carol"
                    />
                </div>
            </div>
            <div class="chat-bubble bg-accent text-accent-content max-w-xs hover:bg-accent-focus transition-colors duration-300">
                Heads up: we got some negative reviews on the wireless earbuds. Let’s brainstorm fixes.
            </div>
        </div>

        <div class="chat chat-start items-start space-x-3">
            <div class="chat-image avatar">
                <div class="w-10 rounded-full ring ring-info ring-offset-2 ring-offset-base-100">
                    <img
                        alt="Dave avatar"
                        src="https://i.pravatar.cc/100?u=dave"
                    />
                </div>
            </div>
            <div class="chat-bubble bg-info text-info-content max-w-xs hover:bg-info-focus transition-colors duration-300">
                I’ve contacted the supplier about the delayed shipments. Expecting updates by tomorrow.
            </div>
        </div>
    </div>

    <!-- Input form -->
    <form class="flex gap-2" onsubmit="event.preventDefault(); sendMessage();">
        <input
            id="chatInput"
            type="text"
            placeholder="Type your message here..."
            class="input input-bordered flex-1"
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

        // For demo, just alert or console log. Replace with your actual sending logic.
        console.log('Sending message:', message);

        // Clear input after sending
        input.value = '';
    }
</script>
