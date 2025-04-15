let currentReceiverId = null;
let currentReceiverName = '';

document.querySelectorAll('.contact-item').forEach(item => {
    item.addEventListener('click', function() {
        // Update selected contact UI
        document.querySelectorAll('.contact-item').forEach(el => 
            el.classList.remove('bg-gray-100'));
        this.classList.add('bg-gray-100');

        // Update chat header
        currentReceiverName = this.querySelector('h4').textContent.trim();
        document.getElementById('chat-header').innerHTML = `
            <h3 class="text-lg font-semibold">Chat with ${currentReceiverName}</h3>
        `;

        // Set up chat
        currentReceiverId = this.dataset.userId;
        document.getElementById('receiver_id').value = currentReceiverId;
        loadMessages(currentReceiverId);
    });
});

document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    if (!currentReceiverId) return;

    const input = this.querySelector('input[name="message"]');
    const message = input.value.trim();
    if (!message) return;

    const formData = new FormData(this);
    fetch('api/chat.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(() => {
        input.value = '';
        loadMessages(currentReceiverId);
    });
});

function loadMessages(receiverId) {
    fetch(`api/chat.php?user_id=${receiverId}`)
        .then(response => response.json())
        .then(messages => {
            const container = document.getElementById('chat-messages');
            container.innerHTML = messages.reverse().map(msg => `
                <div class="message ${msg.sender_id === currentReceiverId ? 'flex justify-start' : 'flex justify-end'} mb-4">
                    <div class="max-w-[70%] ${msg.sender_id === currentReceiverId ? 'bg-gray-100' : 'bg-blue-500 text-white'} 
                                rounded-lg px-4 py-2 shadow">
                        <p class="break-words">${msg.message}</p>
                        <span class="text-xs ${msg.sender_id === currentReceiverId ? 'text-gray-500' : 'text-blue-100'}">
                            ${new Date(msg.created_at).toLocaleTimeString()}
                        </span>
                    </div>
                </div>
            `).join('');
            container.scrollTop = container.scrollHeight;
        });
}

// Poll for new messages every 5 seconds if a chat is open
setInterval(() => {
    if (currentReceiverId) {
        loadMessages(currentReceiverId);
    }
}, 5000);
