
function escapeHTML(str) {
    return str.replace(/[&<>"'`=\/]/g, function (s) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;',
            '`': '&#x60;',
            '=': '&#x3D;'
        }[s];
    });
}

function formatDate(date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    const hours = String(d.getHours()).padStart(2, '0');
    const minutes = String(d.getMinutes()).padStart(2, '0');
    const seconds = String(d.getSeconds()).padStart(2, '0');
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

document.addEventListener('DOMContentLoaded', function() {
    let loading = false;
    let lastMessageId = null;

    fetchMessages();

    document.getElementById('chat-form').addEventListener('submit', function(event) {
        event.preventDefault();
        let formData = new FormData(this);
        sendMessage(formData);
    });

    document.getElementById('message').addEventListener('keypress', function(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            document.getElementById('chat-form').dispatchEvent(new Event('submit'));
        }
    });

    async function fetchMessages() {
        if (loading) return;
        loading = true;

        let chatBox = document.getElementById('chat-box');
        let loadingIndicator = document.getElementById('chat-loading');
        loadingIndicator.style.display = 'block';

        try {
            const response = await fetch(`/chat/messages?last_message_id=${lastMessageId || ''}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const messages = await response.json();

            let initialScrollHeight = chatBox.scrollHeight;

            messages.forEach(function(message) {
                let messageHtml = `<div class="chat-message ${parseInt(message.sender.id) === parseInt(window.chatConfig.userId) ? 'user-message' : ''}">
                    <img src="${escapeHTML(message.sender.photo)}" alt="${escapeHTML(message.sender.name)}">
                    <div class="message-content">
                        <strong><a href="/users/${message.sender.id}">${escapeHTML(message.sender.name)}</a>&nbsp;</strong> ${escapeHTML(message.message)}
                        <div class="chat-date">${escapeHTML(formatDate(message.created_at))}</div>
                    </div>
                </div>`;
                chatBox.insertAdjacentHTML('afterbegin', messageHtml);
            });

            if (messages.length > 0) {
                lastMessageId = messages[messages.length - 1].id;
            }

            chatBox.scrollTop = chatBox.scrollHeight - initialScrollHeight;

            loading = false;
            loadingIndicator.style.display = 'none';
        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
            loading = false;
            loadingIndicator.style.display = 'none';
        }
    }

    async function sendMessage(formData) {
        let chatBox = document.getElementById('chat-box');

        let messageContent = formData.get('message').trim();

        if (!messageContent) {
            alert('Message cannot be empty.');
            return;
        }

        if (messageContent.length > 1000) {
            alert('Message cannot be longer than 1000 characters.');
            return;
        }

        let messageHtml = `<div class="chat-message user-message">
            <img src="${escapeHTML(window.chatConfig.userImage)}" alt="${escapeHTML(window.chatConfig.userName)}">
            <div class="message-content">
                <strong><a href="/users/${window.chatConfig.userId}">${escapeHTML(window.chatConfig.userName)}</a>&nbsp;</strong> ${escapeHTML(messageContent)}
                <div class="chat-date">${formatDate(new Date())}</div>
            </div>
        </div>`;
        chatBox.innerHTML += messageHtml;
        chatBox.scrollTop = chatBox.scrollHeight;
        
        document.getElementById('message').value = '';

        try {
            const response = await fetch(window.chatConfig.sendMessageUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': window.chatConfig.csrfToken
                }
            });
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            await response.json();
        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
        }
    }

    const pusher = new Pusher(window.chatConfig.pusherAppKey, {
        cluster: window.chatConfig.pusherCluster,
        encrypted: true
    });

    const channel = pusher.subscribe('lbaw24153');
    channel.bind('chat_message', function(data) {
        if (parseInt(data.sender_id) === parseInt(window.chatConfig.userId)) {
            return;
        }
        let chatBox = document.getElementById('chat-box');
        let messageHtml = `<div class="chat-message">
            <img src="${escapeHTML(data.sender_image)}" alt="${escapeHTML(data.sender_name)}">
            <div class="message-content">
                <strong><a href="/users/${data.sender_id}">${escapeHTML(data.sender_name)}</a>&nbsp;</strong> ${escapeHTML(data.message)}
                <div class="chat-date">${formatDate(data.created_at)}</div>
            </div>
        </div>`;
        chatBox.innerHTML += messageHtml;
        chatBox.scrollTop = chatBox.scrollHeight;
    });

    document.getElementById('chat-box').addEventListener('scroll', function() {
        if (this.scrollTop === 0 && !loading) {
            let chatBox = document.getElementById('chat-box');
            let loadingIndicator = document.getElementById('chat-loading');
            chatBox.insertBefore(loadingIndicator, chatBox.firstChild);
            loadingIndicator.style.display = 'block';
            fetchMessages();
        }
    });
});