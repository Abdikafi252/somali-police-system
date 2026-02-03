@extends('layouts.app')

@section('title', 'Wada-hadalka (Chat)')

@section('css')
<style>
    .chat-container {
        display: flex;
        height: calc(100vh - 140px);
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid var(--border-soft);
    }
    
    .chat-sidebar {
        width: 320px;
        background: #f8f9fc;
        border-right: 1px solid var(--border-soft);
        display: flex;
        flex-direction: column;
    }

    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .user-list {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
    }

    .user-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 5px;
        position: relative;
    }

    .user-item:hover {
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .user-item.active {
        background: white;
        border-left: 4px solid #3498db;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #bdc3c7;
        border: 2px solid white;
        position: absolute;
        bottom: 12px;
        left: 42px;
    }

    .status-dot.online {
        background: #2ecc71;
    }

    .message-area {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 15px;
        background: #fdfdfd;
    }

    .message {
        max-width: 70%;
        padding: 12px 18px;
        border-radius: 18px;
        position: relative;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .message.sent {
        align-self: flex-end;
        background: #3498db;
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message.received {
        align-self: flex-start;
        background: #f1f2f6;
        color: var(--sidebar-bg);
        border-bottom-left-radius: 4px;
    }

    .chat-header {
        padding: 15px 20px;
        border-bottom: 1px solid var(--border-soft);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: white;
    }

    .chat-input-area {
        padding: 20px;
        background: white;
        border-top: 1px solid var(--border-soft);
        display: flex;
        gap: 10px;
    }

    .chat-input {
        flex: 1;
        padding: 12px 20px;
        border: 1px solid var(--border-soft);
        border-radius: 30px;
        outline: none;
        background: #f8f9fa;
        transition: 0.2s;
    }

    .chat-input:focus {
        border-color: #3498db;
        background: white;
        box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
    }

    .send-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #3498db;
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .send-btn:hover {
        background: #2980b9;
        transform: scale(1.05);
    }

    .badge {
        background: #e74c3c;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 0.7rem;
        font-weight: bold;
        margin-left: auto;
    }
</style>
@endsection

@section('content')
<div class="header" style="margin-bottom: 1rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">WADA-HADALKA (CHAT)</h1>
    <p style="color: var(--text-sub);">La xiriir saraakiisha kale si toos ah.</p>
</div>

<div class="chat-container">
    <!-- Sidebar -->
    <div class="chat-sidebar">
        <div style="padding: 20px; border-bottom: 1px solid var(--border-soft);">
            <div style="position: relative;">
                <i class="fa-solid fa-search" style="position: absolute; left: 15px; top: 12px; color: #95a5a6;"></i>
                <input type="text" id="userDetails" placeholder="Raadi Sarkaalka..." 
                    style="width: 100%; padding: 10px 10px 10px 40px; border-radius: 10px; border: 1px solid var(--border-soft); outline: none; background: white;">
            </div>
        </div>
        
        <div class="user-list" id="usersList">
            <!-- Global Chat Option -->
            <div class="user-item active" onclick="loadChat(null, 'Global Chat', null)">
                <div style="width: 40px; height: 40px; border-radius: 12px; background: #e3f2fd; color: #3498db; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <div style="font-weight: 700; color: var(--sidebar-bg); font-size: 0.9rem;">Global Chat</div>
                    <div style="font-size: 0.75rem; color: var(--text-sub);">Public Room</div>
                </div>
            </div>
            
            <!-- Users will be loaded here via JS -->
            <div style="text-align: center; padding: 20px; color: var(--text-sub);">Loading users...</div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="chat-main">
        <div class="chat-header">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div id="activeUserAvatar">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: #e3f2fd; color: #3498db; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div>
                    <h3 id="activeUserName" style="margin: 0; font-size: 1rem; font-weight: 800; color: var(--sidebar-bg);">Global Chat</h3>
                    <span id="activeUserStatus" style="font-size: 0.75rem; color: #2ecc71; font-weight: 600;">• Online</span>
                </div>
            </div>
        </div>

        <div class="message-area" id="messageArea">
            <!-- Messages will be loaded here -->
            <div style="text-align: center; color: var(--text-sub); margin-top: 50px;">
                Dooro qof aad la hadasho ama Global Chat isticmaal.
            </div>
        </div>

        <div class="chat-input-area">
            <input type="text" class="chat-input" id="messageInput" placeholder="Qor fariintaada halkan..." onkeypress="if(event.key === 'Enter') sendMessage()">
            <button class="send-btn" onclick="sendMessage()">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let currentReceiverId = null;
    let pollingInterval = null;

    // Load Users on Page Load
    fetchUsers();
    // Start polling users status every 10 seconds
    setInterval(fetchUsers, 10000); // 10s

    // Start polling messages every 3 seconds
    setInterval(fetchMessages, 3000); // 3s

    function fetchUsers() {
        fetch("{{ route('chat.users') }}")
            .then(response => response.json())
            .then(users => {
                let html = `
                <div class="user-item ${currentReceiverId === null ? 'active' : ''}" onclick="loadChat(null, 'Global Chat', null)">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: #e3f2fd; color: #3498db; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--sidebar-bg); font-size: 0.9rem;">Global Chat</div>
                        <div style="font-size: 0.75rem; color: var(--text-sub);">Public Room</div>
                    </div>
                </div>`;

                users.forEach(user => {
                    let activeClass = currentReceiverId === user.id ? 'active' : '';
                    let statusClass = user.is_online ? 'online' : '';
                    let unreadBadge = user.unread_count > 0 ? `<span class="badge">${user.unread_count}</span>` : '';
                    let avatar = user.profile_image ? 
                        `<img src="/storage/${user.profile_image}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">` :
                        `<div style="width: 40px; height: 40px; border-radius: 50%; background: #f1f2f6; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #7f8c8d;">${user.name.charAt(0)}</div>`;

                    html += `
                    <div class="user-item ${activeClass}" onclick="loadChat(${user.id}, '${user.name.replace("'", "\\'")}', '${user.profile_image}')">
                        ${avatar}
                        <div class="status-dot ${statusClass}"></div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: var(--sidebar-bg); font-size: 0.9rem;">${user.name}</div>
                            <div style="font-size: 0.75rem; color: var(--text-sub);">${user.is_online ? 'Online' : user.last_seen}</div>
                        </div>
                        ${unreadBadge}
                    </div>`;
                });

                document.getElementById('usersList').innerHTML = html;
            });
    }

    function loadChat(userId, userName, userImage) {
        currentReceiverId = userId;
        document.getElementById('activeUserName').innerText = userName;
        
        let avatarHtml = '';
        if (userId === null) {
             avatarHtml = `<div style="width: 40px; height: 40px; border-radius: 12px; background: #e3f2fd; color: #3498db; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-users"></i></div>`;
             document.getElementById('activeUserStatus').innerText = '• Public Room';
        } else {
             if(userImage && userImage !== 'null') {
                avatarHtml = `<img src="/storage/${userImage}" style="width: 40px; height: 40px; border-radius: 12px; object-fit: cover;">`;
             } else {
                avatarHtml = `<div style="width: 40px; height: 40px; border-radius: 12px; background: #f1f2f6; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #7f8c8d;">${userName.charAt(0)}</div>`;
             }
             // Status will update on next poll, but we can set checking...
             document.getElementById('activeUserStatus').innerText = '...';
        }
        document.getElementById('activeUserAvatar').innerHTML = avatarHtml;
        
        fetchMessages();
        // Immediately refresh user list to update active class state
        fetchUsers(); 
    }

    function fetchMessages() {
        let url = "{{ route('chat.messages') }}";
        if (currentReceiverId) {
            url += `?receiver_id=${currentReceiverId}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(messages => {
                let html = '';
                let currentUserId = {{ auth()->id() }};
                
                if(messages.length === 0) {
                    html = `<div style="text-align: center; color: var(--text-sub); margin-top: 50px;">Bilaaw wada-hadal cusub...</div>`;
                } else {
                    messages.forEach(msg => {
                        let isSent = msg.sender_id === currentUserId;
                        let msgClass = isSent ? 'sent' : 'received';
                        let time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        
                        let senderName = '';
                        if (!isSent && currentReceiverId === null) {
                            // In global chat, show sender name
                            senderName = `<div style="font-size: 0.7rem; color: #7f8c8d; margin-bottom: 2px;">${msg.sender.name}</div>`;
                        }

                        html += `
                        <div style="display: flex; flex-direction: column; align-items: ${isSent ? 'flex-end' : 'flex-start'};">
                            ${senderName}
                            <div class="message ${msgClass}">
                                ${msg.message}
                                <div style="font-size: 0.65rem; opacity: 0.7; margin-top: 4px; text-align: right;">${time}</div>
                            </div>
                        </div>`;
                    });
                }
                
                let messageArea = document.getElementById('messageArea');
                let shouldScroll = messageArea.scrollTop + messageArea.clientHeight === messageArea.scrollHeight;
                
                messageArea.innerHTML = html;
                
                // Simple auto-scroll logic (scroll if was at bottom or if it's first load/empty)
                // For better UX, we might want to be smarter, but this is a good start
                // Actually, let's just scroll to bottom always for now as it's a chat
                // Improving: only scroll if near bottom
                // messageArea.scrollTop = messageArea.scrollHeight; 
            });
    }

    function sendMessage() {
        let input = document.getElementById('messageInput');
        let message = input.value.trim();
        
        if (!message) return;

        fetch("{{ route('chat.send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                message: message,
                receiver_id: currentReceiverId
            })
        })
        .then(response => response.json())
        .then(data => {
            input.value = '';
            fetchMessages(); // Refresh immediately
            // Force scroll to bottom
            let area = document.getElementById('messageArea');
            setTimeout(() => area.scrollTop = area.scrollHeight, 100);
        });
    }
</script>
@endsection
