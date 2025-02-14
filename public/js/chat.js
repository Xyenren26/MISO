document.addEventListener('DOMContentLoaded', () => {
    const usersList = document.querySelectorAll('.user');
    const chatInput = document.getElementById('chat-input');
    const sendMessageButton = document.getElementById('send-message');
    const messagesContainer = document.getElementById('messages');
    const chatHeader = document.getElementById('chat-header'); // Reference to chat header
    let selectedUserId = null;
    let selectedUserName = null; // Store selected user's name
    let pollInterval;

    // Accordion functionality
    const accordions = document.querySelectorAll('.accordion h4');

    accordions.forEach(accordion => {
        accordion.addEventListener('click', () => {
            const list = accordion.nextElementSibling;
            list.style.display = list.style.display === 'none' ? 'block' : 'none';
            accordion.classList.toggle('active'); // Add/remove active class
        });
    
        accordion.nextElementSibling.style.display = 'none';
    });
    
    

    // Event listener for each user in the user list
    usersList.forEach(user => {
        user.addEventListener('click', () => {
            selectedUserId = user.dataset.userId;
            selectedUserName = user.querySelector('span').textContent; // Get the name of the selected user
            chatHeader.textContent = `Chatting with ${selectedUserName}`; // Update the header text
            clearInterval(pollInterval); // Clear any previous polling
            loadMessages(selectedUserId);
            pollInterval = setInterval(() => loadMessages(selectedUserId), 2000); // Poll every 2 seconds
        });
    });

    // Event listener for the send message button
    sendMessageButton.addEventListener('click', () => {
        const message = chatInput.value.trim();

        if (message && selectedUserId) {
            fetch(`/chat/send-message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message, receiver_id: selectedUserId })
            })
            .then(response => response.json())
            .then(data => {
                chatInput.value = ''; // Clear the input field
                loadMessages(selectedUserId); // Reload messages after sending
            })
            .catch(error => console.error(error));
        }
    });

    // Function to load messages from the server
    const visibleTimestamps = new Set(); // Set to track visible timestamps

    function loadMessages(receiverId) {
        fetch(`/chat/fetch-messages/${receiverId}`)
            .then(response => response.json())
            .then(messages => {
                messagesContainer.innerHTML = ''; // Clear existing messages
    
                messages.forEach(msg => {
                    const msgElement = document.createElement('div');
                    const isSentByMe = msg.sender_id !== parseInt(receiverId);
                    msgElement.className = `message ${isSentByMe ? 'me' : 'them'}`;
    
                    const timestamp = msg.timestamp ? new Date(msg.timestamp) : null;
                    const formattedTimestamp = timestamp 
                        ? timestamp.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true })
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
                        const isVisible = timestampElement.style.display === 'block';
                        timestampElement.style.display = isVisible ? 'none' : 'block';
    
                        if (isVisible) {
                            visibleTimestamps.delete(msgId);
                        } else {
                            visibleTimestamps.add(msgId);
                        }
                    });
    
                    messagesContainer.appendChild(msgElement);
                });
    
                scrollToBottom();
    
                // Clear the unread badge for the selected user
                clearUnreadBadge(receiverId);
    
                // Notify the server that messages have been read
                markMessagesAsRead(receiverId);
            })
            .catch(error => console.error('Error fetching messages:', error));
    }
    
    // Clear the unread badge for a specific user
    function clearUnreadBadge(userId) {
        const selectedUser = document.querySelector(`.user[data-user-id="${userId}"]`);
        if (selectedUser) {
            const badge = selectedUser.querySelector('.badge');
            if (badge) {
                badge.textContent = '';
                badge.style.display = 'none';
            }
        }
    }
    
    // Notify the server that messages were read
    function markMessagesAsRead(receiverId) {
        fetch(`/chat/mark-read/${receiverId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).catch(error => console.error('Error marking messages as read:', error));
    }
    

    // Add functionality to send the message on Enter key press
    chatInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();  // Prevent the default Enter behavior (new line)
            sendMessageButton.click();  // Trigger send button click
        }
    });

    // Ensure messages container scrolls to the bottom after loading messages
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function updateUnreadBadges() {
        fetch('/chat/unread-count', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            document.querySelectorAll('.user').forEach(user => {
                const userId = user.getAttribute('data-user-id');
                const unreadCount = data.find(item => item.sender_id == userId)?.unread_count || 0;
    
                let badge = user.querySelector('.badge');
                if (!badge) {
                    badge = document.createElement('span');
                    badge.classList.add('badge');
                    user.appendChild(badge);
                }
    
                badge.textContent = unreadCount > 0 ? unreadCount : '';
                badge.style.display = unreadCount > 0 ? 'inline-block' : 'none';
            });
        })
        .catch(error => console.error('Error fetching unread counts:', error));
    }
    
    // Automatically refresh badges every 5 seconds
    setInterval(updateUnreadBadges, 5000);
    updateUnreadBadges();

//Fetch Active Users
    function fetchActiveUsers() {
        fetch('/chat/active-users')
            .then(response => response.json())
            .then(users => {
                const activeUsersList = document.querySelector('#active-users-list');
                activeUsersList.innerHTML = ''; // Clear previous entries

                users.forEach(user => {
                    const userItem = document.createElement('li');
                    userItem.textContent = `${user.name} (${user.email})`;
                    activeUsersList.appendChild(userItem);
                });
            })
            .catch(error => console.error('Error fetching active users:', error));
    }

    setInterval(fetchActiveUsers, 30000); // Refresh every 30 seconds
    fetchActiveUsers();

    //for status indicator if the user are offline or online 
    function updateUserStatuses() {
        fetch('/get-user-status')
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('.user-status').forEach(statusElement => {
                    let userId = statusElement.getAttribute('id').replace('status-', '');
                    if (data.onlineUsers.includes(parseInt(userId))) {
                        statusElement.classList.add('online');
                        statusElement.classList.remove('offline');
                    } else {
                        statusElement.classList.add('offline');
                        statusElement.classList.remove('online');
                    }
                });
            })
            .catch(error => console.error('Error fetching user status:', error));
    }
    
    // Run the function every 10 seconds
    setInterval(updateUserStatuses, 10000);
    updateUserStatuses();
    
    
    
});
