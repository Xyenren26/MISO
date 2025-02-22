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
// Open both accordions by default
document.querySelectorAll('.accordion h4').forEach((accordion, index) => {
    const list = accordion.nextElementSibling;
    list.style.display = 'block';  // Make sure both accordions are open
    accordion.classList.add('active');
});


    // Event listener for selecting users
usersList.forEach(user => {
    user.addEventListener('click', async () => {
        const userId = user.dataset.userId;

        // Fetch ticket status before allowing selection
        const isRestricted = await checkTicketStatus(userId);
        if (isRestricted) {
            alert(`❌ You cannot select ${user.querySelector('span').textContent}, the ticket is marked as "Complete".`);
            return;
        }

        selectedUserId = userId;
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

    // FOR RESTRICTION OF SENDING MESSAGE IN TECHNICAL VIEW. IF THE PROBLEM SUBMITTED BY END USER IS RESOLVED
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
            .then(response => {
                if (response.status === 403) {  
                    // ❌ Conversation expired - show alert and disable only this user's chat input
                    alert(`❌ You cannot message ${selectedUserName}, this conversation has expired.`);
                    
                    // Disable only for this user
                    document.querySelector(`.user[data-user-id="${selectedUserId}"]`).classList.add('disabled');
                    
                    // Stop further execution
                    return Promise.reject("Conversation expired");
                }
                return response.json();
            })
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
                activeUsersList.innerHTML = users.map(user => `<li>${user.first_name} ${user.last_name} (${user.email})</li>`).join('');
            })
            .catch(console.error);
    }

    setInterval(fetchActiveUsers, 30000);
    fetchActiveUsers();

    function updateUserStatuses() {
        fetch('/chat/active-users')
            .then(response => response.json())
            .then(users => {
                document.querySelectorAll('.user-status').forEach(statusElement => {
                    const userId = statusElement.id.replace('status-', '');
                    const user = users.find(u => u.employee_id === parseInt(userId));
                    statusElement.classList.toggle('online', user);
                    statusElement.classList.toggle('offline', !user);
                });
            })
            .catch(console.error);
    }

    setInterval(updateUserStatuses, 5000);
    updateUserStatuses();

        //if the ticket are complete the chat area between tech and end user wil be restricted - bagong lagay ni rogelio
        document.addEventListener('DOMContentLoaded', () => {
            applyStoredRestrictions();  // ✅ Apply restrictions from localStorage on page load
            updateUserRestrictions();   // ✅ Update restrictions immediately
        
            // ✅ Keep updating restrictions every 10 seconds
            setInterval(updateUserRestrictions, 10000);
        });
        
        async function checkTicketStatus(userId) {
            try {
                const response = await fetch(`/chat/ticket-status/${userId}`);
                const data = await response.json();
                const userElement = document.querySelector(`.user[data-user-id="${userId}"]`);
                if (!userElement) return false;
        
                const indicator = userElement.querySelector('.ticket-status') || document.createElement('span');
                indicator.classList.add('ticket-status');
        
                if (data.status === "Complete") {
                    userElement.classList.add('disabled');
                    indicator.textContent = " ❌";
                    indicator.style.color = "red";
                    userElement.appendChild(indicator);
                    saveRestriction(userId);
                    toggleChatAccess(true);  // 🔴 Disable chat input
                    return true;
                } else {
                    userElement.classList.remove('disabled');
                    indicator.textContent = "";
                    removeRestriction(userId);
                    toggleChatAccess(false);  // 🟢 Enable chat input
                    return false;
                }
            } catch (error) {
                console.error("Error fetching ticket status:", error);
                return false;
            }
        }
        
        
        async function updateUserRestrictions() {
            const users = document.querySelectorAll('.user');
            for (const user of users) {
                const userId = user.dataset.userId;
                await checkTicketStatus(userId);
            }
        }
        
        // ✅ Save restricted users to localStorage
        function saveRestriction(userId) {
            let restrictedUsers = JSON.parse(localStorage.getItem('restrictedUsers')) || [];
            if (!restrictedUsers.includes(userId)) {
                restrictedUsers.push(userId);
            }
            localStorage.setItem('restrictedUsers', JSON.stringify(restrictedUsers));
        }
        
        // ✅ Remove restriction when ticket is reopened
        function removeRestriction(userId) {
            let restrictedUsers = JSON.parse(localStorage.getItem('restrictedUsers')) || [];
            restrictedUsers = restrictedUsers.filter(id => id !== userId);
            localStorage.setItem('restrictedUsers', JSON.stringify(restrictedUsers));
        }
        
        // ✅ Apply restrictions from localStorage on page load
// ✅ Apply restrictions from localStorage on page load
function applyStoredRestrictions() {
    let restrictedUsers = JSON.parse(localStorage.getItem('restrictedUsers')) || [];
    restrictedUsers.forEach(userId => {
        const userElement = document.querySelector(`.user[data-user-id="${userId}"]`);
        if (userElement) {
            userElement.classList.add('disabled');
            userElement.style.pointerEvents = "none"; 
            userElement.style.opacity = "0.5"; 

            let indicator = userElement.querySelector('.ticket-status');
            if (!indicator) {
                indicator = document.createElement('span');
                indicator.classList.add('ticket-status');
                indicator.textContent = " ❌";
                indicator.style.color = "red";
                userElement.appendChild(indicator);
            }

            // 🔴 Disable chat if the selected user is restricted
            if (selectedUserId === userId) {
                toggleChatAccess(true);
            }
        }
    });
}


        // Run every 10 seconds to check updates
        setInterval(updateUserRestrictions, 10000);
        updateUserRestrictions();

        function toggleChatAccess(isRestricted) {
            chatInput.disabled = isRestricted;
            sendMessageButton.disabled = isRestricted;
            chatInput.style.backgroundColor = isRestricted ? '#ddd' : ''; // Grey out input
            chatInput.placeholder = isRestricted ? "❌ Ticket is complete. You cannot send messages." : "Type a message...";
        }
        
        
    
});
