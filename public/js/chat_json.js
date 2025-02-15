    // Fetch messages from the server
    function loadMessages(receiverId) {
        fetch(`/chat/fetch-messages/${receiverId}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(messages => {
            const messageContainer = document.getElementById('messages');
            messageContainer.innerHTML = ''; // Clear existing messages

            messages.forEach(msg => {
                messageContainer.innerHTML += `<p><strong>${msg.sender_id}</strong>: ${msg.message}</p>`;
            });
        });
    }

    // Send a new message
    document.getElementById('send-message').addEventListener('click', function () {
        const messageInput = document.getElementById('chat-input');
        const message = messageInput.value;
        const receiverId = 123; // Replace with dynamic receiverId

        fetch('/chat/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message: message, receiver_id: receiverId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                messageInput.value = '';
                loadMessages(receiverId); // Reload messages
            }
        });
    });


    