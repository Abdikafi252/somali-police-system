@extends('layouts.app')

@section('title', 'Wada-hadalka (Chat)')

@section('css')
<style>
    .chat-container {
        display: flex;
        height: calc(100vh - 140px);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }
    
    .chat-sidebar {
        width: 340px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-right: 1px solid rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
    }

    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: rgba(255, 255, 255, 0.98);
    }

    .user-list {
        flex: 1;
        overflow-y: auto;
        padding: 12px;
    }

    .user-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 6px;
        position: relative;
        background: transparent;
    }

    .user-item:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%);
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    .user-item.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
        transform: translateX(4px);
    }

    .user-item.active .user-name,
    .user-item.active .user-status {
        color: white !important;
    }

    .status-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #bdc3c7;
        border: 3px solid white;
        position: absolute;
        bottom: 14px;
        left: 44px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .status-dot.online {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7); }
        50% { box-shadow: 0 0 0 6px rgba(46, 204, 113, 0); }
    }

    .message-area {
        flex: 1;
        padding: 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
        background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
    }

    .message {
        max-width: 65%;
        padding: 14px 20px;
        border-radius: 20px;
        position: relative;
        font-size: 0.95rem;
        line-height: 1.6;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        animation: messageSlide 0.3s ease-out;
    }

    @keyframes messageSlide {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message.sent {
        align-self: flex-end;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-bottom-right-radius: 6px;
    }

    .message.received {
        align-self: flex-start;
        background: white;
        color: var(--sidebar-bg);
        border-bottom-left-radius: 6px;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .chat-header {
        padding: 20px 24px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(to right, rgba(255,255,255,0.95) 0%, rgba(248,249,250,0.95) 100%);
        backdrop-filter: blur(10px);
    }

    .chat-input-area {
        padding: 20px 24px;
        background: white;
        border-top: 1px solid rgba(0,0,0,0.05);
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .chat-input {
        flex: 1;
        padding: 14px 24px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 30px;
        outline: none;
        background: #f8f9fa;
        transition: all 0.3s;
        font-size: 0.95rem;
    }

    .chat-input:focus {
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }

    .send-btn {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .send-btn:hover {
        transform: scale(1.1) rotate(15deg);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    }

    .send-btn:active {
        transform: scale(0.95);
    }

    .badge {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
        border-radius: 12px;
        padding: 4px 10px;
        font-size: 0.7rem;
        font-weight: 700;
        margin-left: auto;
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
    }

    .search-input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border-radius: 14px;
        border: 2px solid rgba(102, 126, 234, 0.15);
        outline: none;
        background: white;
        transition: all 0.3s;
        font-size: 0.9rem;
    }

    .search-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .user-avatar-placeholder {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: white;
        font-size: 1.2rem;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .typing-indicator {
        display: flex;
        gap: 4px;
        padding: 12px 16px;
        background: white;
        border-radius: 20px;
        width: fit-content;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #95a5a6;
        animation: typing 1.4s infinite;
    }

    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-10px); }
    }

    /* Scrollbar Styling */
    .user-list::-webkit-scrollbar,
    .message-area::-webkit-scrollbar {
        width: 6px;
    }

    .user-list::-webkit-scrollbar-track,
    .message-area::-webkit-scrollbar-track {
        background: transparent;
    }

    .user-list::-webkit-scrollbar-thumb,
    .message-area::-webkit-scrollbar-thumb {
        background: rgba(102, 126, 234, 0.3);
        border-radius: 10px;
    }

    .user-list::-webkit-scrollbar-thumb:hover,
    .message-area::-webkit-scrollbar-thumb:hover {
        background: rgba(102, 126, 234, 0.5);
    }
</style>
@endsection

@section('content')
<div class="header" style="margin-bottom: 1.5rem;">
    <h1 style="font-weight: 800; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-family: 'Outfit'; font-size: 2.5rem;">
        💬 WADA-HADALKA (CHAT)
    </h1>
    <p style="color: var(--text-sub); font-size: 1.05rem;">La xiriir saraakiisha kale si toos ah oo degdeg ah.</p>
</div>

<div class="chat-container">
    <!-- Sidebar -->
    <div class="chat-sidebar">
        <div style="padding: 24px; border-bottom: 1px solid rgba(0,0,0,0.05);">
            <div style="position: relative;">
                <i class="fa-solid fa-search" style="position: absolute; left: 16px; top: 14px; color: #667eea; font-size: 0.9rem;"></i>
                <input type="text" id="userSearch" class="search-input" placeholder="🔍 Raadi Sarkaalka...">
            </div>
        </div>
        
        <div class="user-list" id="usersList">
            <!-- Global Chat Option -->
            <div class="user-item active" onclick="loadChat(null, 'Global Chat', null)">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div style="flex: 1;">
                    <div class="user-name" style="font-weight: 800; color: white; font-size: 0.95rem;">Global Chat</div>
                    <div class="user-status" style="font-size: 0.8rem; color: rgba(255,255,255,0.9);">🌐 Public Room</div>
                </div>
            </div>
            
            <!-- Users will be loaded here via JS -->
            <div style="text-align: center; padding: 30px 20px; color: var(--text-sub);">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 1.5rem; color: #667eea;"></i>
                <div style="margin-top: 10px;">Loading users...</div>
            </div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="chat-main">
        <div class="chat-header">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div id="activeUserAvatar">
                    <div class="user-avatar-placeholder">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div>
                    <h3 id="activeUserName" style="margin: 0; font-size: 1.1rem; font-weight: 800; color: var(--sidebar-bg);">Global Chat</h3>
                    <span id="activeUserStatus" style="font-size: 0.8rem; color: #2ecc71; font-weight: 700;">
                        <i class="fa-solid fa-circle" style="font-size: 0.5rem; margin-right: 4px;"></i> Online
                    </span>
                </div>
            </div>
            <div style="display: flex; gap: 12px;">
                <button onclick="startCall()" class="call-trigger" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(102, 126, 234, 0.1); border: none; color: #667eea; cursor: pointer; transition: all 0.3s; display: none;" onmouseover="this.style.background='rgba(102, 126, 234, 0.2)'" onmouseout="this.style.background='rgba(102, 126, 234, 0.1)'">
                    <i class="fa-solid fa-phone"></i>
                </button>
                <button style="width: 40px; height: 40px; border-radius: 50%; background: rgba(102, 126, 234, 0.1); border: none; color: #667eea; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.background='rgba(102, 126, 234, 0.2)'" onmouseout="this.style.background='rgba(102, 126, 234, 0.1)'">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
            </div>
        </div>

        <div class="message-area" id="messageArea">
            <!-- Messages will be loaded here -->
            <div style="text-align: center; color: var(--text-sub); margin-top: 80px;">
                <i class="fa-solid fa-comments" style="font-size: 4rem; color: rgba(102, 126, 234, 0.2); margin-bottom: 20px;"></i>
                <div style="font-size: 1.1rem; font-weight: 600; color: var(--sidebar-bg);">Dooro qof aad la hadasho</div>
                <div style="font-size: 0.9rem; margin-top: 8px;">ama Global Chat isticmaal si aad ula wada hadashid dhammaan.</div>
            </div>
        </div>

        <div class="chat-input-area">
            <button style="width: 44px; height: 44px; border-radius: 50%; background: rgba(102, 126, 234, 0.1); border: none; color: #667eea; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='rgba(102, 126, 234, 0.2)'" onmouseout="this.style.background='rgba(102, 126, 234, 0.1)'">
                <i class="fa-solid fa-paperclip"></i>
            </button>
            <input type="text" class="chat-input" id="messageInput" placeholder="✍️ Qor fariintaada halkan..." onkeypress="if(event.key === 'Enter') sendMessage()">
            <button style="width: 44px; height: 44px; border-radius: 50%; background: rgba(102, 126, 234, 0.1); border: none; color: #667eea; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='rgba(102, 126, 234, 0.2)'" onmouseout="this.style.background='rgba(102, 126, 234, 0.1)'">
                <i class="fa-solid fa-face-smile"></i>
            </button>
            <button class="send-btn" onclick="sendMessage()">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<!-- Calling Modal -->
<div id="callingModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.95); z-index: 10000; align-items: center; justify-content: center; backdrop-filter: blur(20px);">
    <div style="text-align: center; color: white;">
        <div id="callingAvatar" style="margin-bottom: 2rem;">
            <div class="user-avatar-placeholder" style="width: 120px; height: 120px; font-size: 3rem; margin: 0 auto; border: 4px solid #667eea; box-shadow: 0 0 40px rgba(102, 126, 234, 0.5);">?</div>
        </div>
        <h2 id="callingName" style="font-size: 2rem; margin-bottom: 0.5rem; font-weight: 800;">John Doe</h2>
        <div id="callingStatus" style="font-size: 1.2rem; color: #94a3b8; margin-bottom: 3rem; animation: pulse 1.5s infinite;">Dalbashada... (Calling)</div>
        
        <div style="display: flex; gap: 2rem; justify-content: center;">
            <button onclick="endCall()" style="width: 70px; height: 70px; border-radius: 50%; background: #ef4444; border: none; color: white; font-size: 1.5rem; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 20px rgba(239, 68, 68, 0.3);" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fa-solid fa-phone-slash"></i>
            </button>
            <div style="width: 70px; height: 70px; border-radius: 50%; background: #2ecc71; border: none; color: white; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; opacity: 0.5; cursor: not-allowed;">
                <i class="fa-solid fa-microphone"></i>
            </div>
        </div>
    </div>
</div>

<audio id="ringSound" loop preload="auto">
    <source src="https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3" type="audio/mpeg">
</audio>

@endsection

@section('js')
<script>
    let currentReceiverId = null;
    let pollingInterval = null;

    // Load Users on Page Load
    fetchUsers();
    // Start polling users status every 10 seconds
    setInterval(fetchUsers, 10000);

    // Start polling messages every 3 seconds
    setInterval(fetchMessages, 3000);

    function fetchUsers() {
        fetch("{{ route('chat.users') }}")
            .then(response => response.json())
            .then(users => {
                let html = `
                <div class="user-item ${currentReceiverId === null ? 'active' : ''}" onclick="loadChat(null, 'Global Chat', null)">
                    <div class="user-avatar-placeholder">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div style="flex: 1;">
                        <div class="user-name" style="font-weight: 800; color: var(--sidebar-bg); font-size: 0.95rem;">Global Chat</div>
                        <div class="user-status" style="font-size: 0.8rem; color: var(--text-sub);">🌐 Public Room</div>
                    </div>
                </div>`;

                users.forEach(user => {
                    let activeClass = currentReceiverId === user.id ? 'active' : '';
                    let statusClass = user.is_online ? 'online' : '';
                    let unreadBadge = user.unread_count > 0 ? `<span class="badge">${user.unread_count}</span>` : '';
                    let avatar = user.profile_image ? 
                        `<img src="/storage/${user.profile_image}" class="user-avatar">` :
                        `<div class="user-avatar-placeholder">${user.name.charAt(0)}</div>`;

                    // Update active user status in header if this is the one
                    if (currentReceiverId === user.id) {
                        const statusEl = document.getElementById('activeUserStatus');
                        if (user.is_online) {
                            statusEl.innerHTML = '<i class="fa-solid fa-circle" style="font-size: 0.5rem; margin-right: 4px;"></i> Online';
                            statusEl.style.color = '#2ecc71';
                        } else {
                            statusEl.innerHTML = '<i class="fa-solid fa-circle" style="font-size: 0.5rem; margin-right: 4px;"></i> ' + user.last_seen;
                            statusEl.style.color = '#94a3b8';
                        }
                    }

                    html += `
                    <div class="user-item ${activeClass}" onclick="loadChat(${user.id}, '${user.name.replace("'", "\\'")}', '${user.profile_image}')">
                        ${avatar}
                        <div class="status-dot ${statusClass}"></div>
                        <div style="flex: 1;">
                            <div class="user-name" style="font-weight: 800; color: var(--sidebar-bg); font-size: 0.95rem;">${user.name}</div>
                            <div class="user-status" style="font-size: 0.8rem; color: var(--text-sub);">${user.is_online ? '🟢 Online' : '⚫ ' + user.last_seen}</div>
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
             avatarHtml = `<div class="user-avatar-placeholder"><i class="fa-solid fa-users"></i></div>`;
             document.getElementById('activeUserStatus').innerHTML = '<i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> Public Room';
             document.getElementById('activeUserStatus').style.color = '#2ecc71';
             document.querySelector('.call-trigger').style.display = 'none';
        } else {
             if(userImage && userImage !== 'null') {
                avatarHtml = `<img src="/storage/${userImage}" class="user-avatar">`;
             } else {
                avatarHtml = `<div class="user-avatar-placeholder">${userName.charAt(0)}</div>`;
             }
             document.getElementById('activeUserStatus').innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';
             document.querySelector('.call-trigger').style.display = 'flex';
        }
        document.getElementById('activeUserAvatar').innerHTML = avatarHtml;
        
        fetchMessages();
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
                    html = `
                    <div style="text-align: center; color: var(--text-sub); margin-top: 80px;">
                        <i class="fa-solid fa-comments" style="font-size: 4rem; color: rgba(102, 126, 234, 0.2); margin-bottom: 20px;"></i>
                        <div style="font-size: 1.1rem; font-weight: 600; color: var(--sidebar-bg);">Bilaaw wada-hadal cusub...</div>
                        <div style="font-size: 0.9rem; margin-top: 8px;">Qor fariintaada ugu horreysa.</div>
                    </div>`;
                } else {
                    messages.forEach(msg => {
                        let isSent = msg.sender_id === currentUserId;
                        let msgClass = isSent ? 'sent' : 'received';
                        let time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        
                        let senderName = '';
                        if (!isSent && currentReceiverId === null) {
                            senderName = `<div style="font-size: 0.75rem; color: #7f8c8d; margin-bottom: 4px; font-weight: 600;">${msg.sender.name}</div>`;
                        }

                        html += `
                        <div style="display: flex; flex-direction: column; align-items: ${isSent ? 'flex-end' : 'flex-start'};">
                            ${senderName}
                            <div class="message ${msgClass}">
                                ${msg.message}
                                <div style="font-size: 0.7rem; opacity: 0.7; margin-top: 6px; text-align: right;">${time}</div>
                            </div>
                        </div>`;
                    });
                }
                
                let messageArea = document.getElementById('messageArea');
                messageArea.innerHTML = html;
                messageArea.scrollTop = messageArea.scrollHeight;
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
            fetchMessages();
            let area = document.getElementById('messageArea');
            setTimeout(() => area.scrollTop = area.scrollHeight, 100);
        });
    }

    // Search functionality
    document.getElementById('userSearch').addEventListener('input', function(e) {
        let searchTerm = e.target.value.toLowerCase();
        let userItems = document.querySelectorAll('.user-item');
        
        userItems.forEach(item => {
            let userName = item.querySelector('.user-name');
            if (userName) {
                let name = userName.textContent.toLowerCase();
                item.style.display = name.includes(searchTerm) ? 'flex' : 'none';
            }
        });
    });

    function startCall() {
        if (!currentReceiverId) return;
        
        const name = document.getElementById('activeUserName').innerText;
        const avatar = document.getElementById('activeUserAvatar').innerHTML;
        
        document.getElementById('callingName').innerText = name;
        document.getElementById('callingAvatar').innerHTML = avatar.replace('width: 40px; height: 40px;', 'width: 120px; height: 120px; font-size: 3rem; margin: 0 auto; border: 4px solid #667eea; box-shadow: 0 0 40px rgba(102, 126, 234, 0.5);');
        
        document.getElementById('callingModal').style.display = 'flex';
        document.getElementById('ringSound').play();
    }

    function endCall() {
        document.getElementById('callingModal').style.display = 'none';
        document.getElementById('ringSound').pause();
        document.getElementById('ringSound').currentTime = 0;
    }
</script>
@endsection
