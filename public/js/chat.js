document.addEventListener('DOMContentLoaded', () => {
    const usersList = document.querySelectorAll('.user');
    const chatInput = document.getElementById('chat-input');
    const sendMessageButton = document.getElementById('send-message');
    const messagesContainer = document.getElementById('messages');
    const chatHeader = document.getElementById('chat-header');
    let selectedUserId = null;
    let selectedUserName = null;
    let pollInterval;
    const visibleTimestamps = new Set();

    

    // Accordion functionality
    document.querySelectorAll('.accordion h4').forEach(accordion => {
        accordion.addEventListener('click', () => {
            const list = accordion.nextElementSibling;
            list.style.display = list.style.display === 'none' ? 'block' : 'none';
            accordion.classList.toggle('active');
        });
        accordion.nextElementSibling.style.display = 'none';
    });

    // Event listener for selecting users
    usersList.forEach(user => {
        user.addEventListener('click', () => {
            selectedUserId = user.dataset.userId;
            selectedUserName = user.querySelector('span').textContent;
            chatHeader.textContent = `Chatting with ${selectedUserName}`;
            clearInterval(pollInterval);
            loadMessages(selectedUserId);
            pollInterval = setInterval(() => loadMessages(selectedUserId), 2000);
        });
    });

    // Send message event
    sendMessageButton.addEventListener('click', sendMessage);
    chatInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendMessage() {
        const message = chatInput.value.trim();
        if (message && selectedUserId) {
            fetch('/chat/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message, receiver_id: selectedUserId })
            })
            .then(response => response.json())
            .then(() => {
                chatInput.value = '';
                loadMessages(selectedUserId);
            })
            .catch(console.error);
        }
    }

    function loadMessages(receiverId) {
        fetch(`/chat/fetch-messages/${receiverId}`)
            .then(response => response.json())
            .then(messages => {
                const wasAtBottom = messagesContainer.scrollHeight - messagesContainer.scrollTop <= messagesContainer.clientHeight + 10;
                messagesContainer.innerHTML = '';

                messages.forEach(msg => {
                    const msgElement = document.createElement('div');
                    const isSentByMe = msg.sender_id !== parseInt(receiverId);
                    msgElement.className = `message ${isSentByMe ? 'me' : 'them'}`;

                    const formattedTimestamp = msg.timestamp 
                        ? new Date(msg.timestamp).toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true })
                        : '';

                    const msgId = msg.id || `${msg.sender_id}-${msg.timestamp}`;

                    msgElement.innerHTML = `
                        <div class="message-content">${msg.message}</div>
                        <div class="timestamp" style="display: ${visibleTimestamps.has(msgId) ? 'block' : 'none'};">
                            ${formattedTimestamp}
                        </div>
                    `;

                    msgElement.addEventListener('click', () => {
                        const timestampElement = msgElement.querySelector('.timestamp');
                        timestampElement.style.display = timestampElement.style.display === 'block' ? 'none' : 'block';

                        if (timestampElement.style.display === 'block') {
                            visibleTimestamps.add(msgId);
                        } else {
                            visibleTimestamps.delete(msgId);
                        }
                    });

                    messagesContainer.appendChild(msgElement);
                });

                if (wasAtBottom) {
                    scrollToBottom();
                } else {
                    checkScrollButton();
                }

                clearUnreadBadge(receiverId);
                markMessagesAsRead(receiverId);
            })
            .catch(console.error);
    }

    function checkScrollButton() {
        document.getElementById('scrollButton').style.display =
            messagesContainer.scrollHeight - messagesContainer.scrollTop <= messagesContainer.clientHeight + 10 ? 'none' : 'block';
    }

    function scrollToBottom() {
        messagesContainer.scrollTo({ top: messagesContainer.scrollHeight, behavior: 'smooth' });
    }

    document.getElementById('scrollButton').addEventListener('click', scrollToBottom);
    messagesContainer.addEventListener('scroll', checkScrollButton);

    function clearUnreadBadge(userId) {
        const badge = document.querySelector(`.user[data-user-id="${userId}"] .badge`);
        if (badge) {
            badge.textContent = '';
            badge.style.display = 'none';
        }
    }

    function markMessagesAsRead(receiverId) {
        fetch(`/chat/mark-read/${receiverId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).catch(console.error);
    }

    function updateUnreadBadges() {
        fetch('/chat/unread-count', {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(response => response.json())
        .then(data => {
            document.querySelectorAll('.user').forEach(user => {
                const userId = user.getAttribute('data-user-id');
                const unreadCount = data.find(item => item.sender_id == userId)?.unread_count || 0;
                let badge = user.querySelector('.badge') || document.createElement('span');
                badge.classList.add('badge');
                user.appendChild(badge);
                badge.textContent = unreadCount > 0 ? unreadCount : '';
                badge.style.display = unreadCount > 0 ? 'inline-block' : 'none';
            });
        })
        .catch(console.error);
    }

    setInterval(updateUnreadBadges, 5000);
    updateUnreadBadges();

    function fetchActiveUsers() {
        fetch('/chat/active-users')
            .then(response => response.json())
            .then(users => {
                const activeUsersList = document.querySelector('#active-users-list');
                activeUsersList.innerHTML = users.map(user => `<li>${user.name} (${user.email})</li>`).join('');
            })
            .catch(console.error);
    }

    setInterval(fetchActiveUsers, 30000);
    fetchActiveUsers();

    function updateUserStatuses() {
        fetch('/get-user-status')
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('.user-status').forEach(statusElement => {
                    const userId = statusElement.id.replace('status-', '');
                    statusElement.classList.toggle('online', data.onlineUsers.includes(parseInt(userId)));
                    statusElement.classList.toggle('offline', !data.onlineUsers.includes(parseInt(userId)));
                });
            })
            .catch(console.error);
    }

    setInterval(updateUserStatuses, 10000);
    updateUserStatuses();
    
    
});
