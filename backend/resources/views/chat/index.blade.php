@extends('layouts.app')

@section('title', 'Wada-hadalka (Chat)')

@section('css')
<style>
    :root {
        --chat-bg: #e5ddd5;
        --chat-header-bg: #f0f2f5;
        --incoming-bg: #ffffff;
        --outgoing-bg: #d9fdd3;
        --input-bg: #f0f2f5;
        --sidebar-width: 350px;
    }

    /* Layout Reset */
    .app-main {
        padding: 0 !important;
        margin: 0 !important;
        height: 100vh;
        overflow: hidden;
    }

    /* Main Container */
    .whatsapp-container {
        display: flex;
        height: 100vh;
        width: 100%;
        background: #fff;
        font-family: 'Segoe UI', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }

    /* Left Sidebar */
    .chat-sidebar {
        width: var(--sidebar-width);
        background: #fff;
        border-right: 1px solid #e0e0e0;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.3s ease;
        z-index: 100;
    }

    /* Right Main Chat */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100%;
        background-color: var(--chat-bg);
        background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
        background-repeat: repeat;
        background-blend-mode: soft-light;
        position: relative;
    }

    /* Header Styles */
    .sidebar-header,
    .main-header {
        height: 60px;
        background: var(--chat-header-bg);
        display: flex;
        align-items: center;
        padding: 0 16px;
        flex-shrink: 0;
        border-bottom: 1px solid #d1d7db;
    }

    .main-header {
        justify-content: space-between;
    }

    /* Profile Images */
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
    }

    /* Search Bar */
    .search-box {
        padding: 8px 16px;
        background: #fff;
        border-bottom: 1px solid #f0f2f5;
    }

    .search-input-wrapper {
        background: #f0f2f5;
        border-radius: 8px;
        padding: 6px 16px;
        display: flex;
        align-items: center;
    }

    .search-input {
        border: none;
        background: transparent;
        outline: none;
        width: 100%;
        margin-left: 10px;
        font-size: 14px;
    }

    /* User List */
    .user-list {
        flex: 1;
        overflow-y: auto;
    }

    .user-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f0f2f5;
        transition: background 0.2s;
    }

    .user-item:hover {
        background: #f5f6f6;
    }

    .user-item.active {
        background: #f0f2f5;
    }

    .user-info {
        flex: 1;
        margin-left: 15px;
    }

    .user-name {
        font-size: 16px;
        color: #111b21;
        font-weight: 500;
        margin-bottom: 3px;
    }

    .last-message {
        font-size: 13px;
        color: #667781;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Message Area */
    .message-area {
        flex: 1;
        padding: 20px 5%;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* Bubbles */
    .message-row {
        display: flex;
        margin-bottom: 4px;
        width: 100%;
    }

    .message-row.sent {
        justify-content: flex-end;
    }

    .message-row.received {
        justify-content: flex-start;
    }

    .msg-bubble {
        max-width: 65%;
        padding: 6px 12px 6px 12px;
        /* Top Right Bottom Left */
        border-radius: 7.5px;
        font-size: 14.2px;
        line-height: 19px;
        color: #111b21;
        position: relative;
        box-shadow: 0 1px 0.5px rgba(11, 20, 26, .13);
        word-wrap: break-word;
    }

    .msg-sent {
        background: var(--outgoing-bg);
        border-top-right-radius: 0;
    }

    .msg-received {
        background: var(--incoming-bg);
        border-top-left-radius: 0;
    }

    /* Bubble Tails CSS */
    .msg-sent::before {
        content: "";
        position: absolute;
        top: 0;
        right: -8px;
        width: 8px;
        height: 13px;
        background: linear-gradient(to top left, transparent 50%, var(--outgoing-bg) 50%);
        border-top: 1px solid transparent;
        /* Anti-aliasing hack */
    }

    .msg-received::before {
        content: "";
        position: absolute;
        top: 0;
        left: -8px;
        width: 8px;
        height: 13px;
        background: linear-gradient(to top right, transparent 50%, var(--incoming-bg) 50%);
    }

    /* Meta Info (Time + Ticks) */
    .msg-meta {
        float: right;
        margin-left: 8px;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 3px;
        font-size: 11px;
        color: #667781;
        height: 15px;
    }

    .tick-icon {
        font-size: 14px;
    }

    .tick-read {
        color: #53bdeb;
        /* WhatsApp Blue */
    }

    /* Footer/Input */
    .chat-footer {
        min-height: 62px;
        background: #f0f2f5;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 200;
    }

    .footer-icon {
        color: #54656f;
        font-size: 24px;
        cursor: pointer;
        padding: 8px;
    }

    .input-wrapper {
        flex: 1;
        background: #fff;
        border-radius: 8px;
        padding: 9px 12px;
        display: flex;
        align-items: center;
    }

    .chat-input {
        width: 100%;
        border: none;
        outline: none;
        font-size: 15px;
        font-family: inherit;
        max-height: 100px;
        overflow-y: auto;
    }

    /* Mobile Responsiveness */
    @media (max-width: 900px) {
        :root {
            --sidebar-width: 100%;
        }

        .chat-sidebar {
            position: absolute;
            transform: translateX(0);
        }

        .chat-main {
            position: absolute;
            width: 100%;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        /* Show ChatState */
        .show-chat .chat-sidebar {
            transform: translateX(-100%);
        }

        .show-chat .chat-main {
            transform: translateX(0);
        }

        .message-area {
            padding: 20px 10px;
        }
    }
</style>
@endsection

@section('content')
<div class="whatsapp-container" id="mainContainer">
    <!-- Sidebar -->
    <aside class="chat-sidebar">
        <!-- Header -->
        <div class="sidebar-header">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:40px; height:40px; background:#ddd; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#54656f;">
                    <i class="fa-solid fa-user"></i>
                </div>
                <span style="font-weight:600; color:#41525d;">Chats</span>
            </div>
            <div style="margin-left:auto; display:flex; gap:15px; color:#54656f;">
                <i class="fa-solid fa-circle-notch"></i>
                <i class="fa-solid fa-message"></i>
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </div>
        </div>

        <!-- Search -->
        <div class="search-box">
            <div class="search-input-wrapper">
                <i class="fa-solid fa-search" style="color:#54656f; font-size:14px;"></i>
                <input type="text" class="search-input" placeholder="Search or start new chat">
            </div>
        </div>

        <!-- User List -->
        <div class="user-list" id="usersList">
            <!-- Global Chat -->
            <div class="user-item" onclick="selectUser(null, 'Global Chat', null)">
                <div style="width:49px; height:49px; background:#00a884; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="user-info">
                    <div style="display:flex; justify-content:space-between;">
                        <span class="user-name">Global Chat</span>
                        <span class="last-message" style="font-size:11px;">Now</span>
                    </div>
                    <div class="last-message">Tap to join public room</div>
                </div>
            </div>
            <!-- Dynamic Users -->
            <div style="text-align:center; padding:20px; color:#8696a0;">Updating contacts...</div>
        </div>
    </aside>

    <!-- Main Chat -->
    <main class="chat-main" id="chatMainBg">
        <!-- Chat Header -->
        <div class="main-header" id="mainHeader" style="display:none;">
            <div style="display:flex; align-items:center; gap:15px;">
                <i class="fa-solid fa-arrow-left" style="font-size:20px; color:#54656f; cursor:pointer; display:none;" id="backBtn" onclick="closeChat()"></i>
                <img id="headerAvatar" class="avatar" src="https://via.placeholder.com/40" style="display:none;">
                <div id="headerInitials" style="width:40px; height:40px; border-radius:50%; background:#dfe3e5; display:flex; align-items:center; justify-content:center; color:#54656f; font-weight:bold;">?</div>

                <div style="display:flex; flex-direction:column;">
                    <span id="headerName" style="font-weight:600; color:#111b21; font-size:16px;">Select a contact</span>
                    <span id="headerStatus" style="font-size:13px; color:#667781;"></span>
                </div>
            </div>

            <div style="display:flex; gap:20px; color:#54656f;">
                <i class="fa-solid fa-video"></i>
                <i class="fa-solid fa-phone"></i>
                <i class="fa-solid fa-search"></i>
                <i class="fa-solid fa-ellipsis-vertical" onclick="toggleMenu()"></i>

                <!-- Dropdown -->
                <div id="chatMenu" style="display:none; position:absolute; right:16px; top:50px; background:white; box-shadow:0 4px 12px rgba(0,0,0,0.15); border-radius:6px; z-index:1000; min-width:150px; padding:5px 0;">
                    <div onclick="clearChat()" style="padding:10px 20px; cursor:pointer; font-size:14.5px; color:#111b21;">Clear messages</div>
                    <div onclick="toggleMenu()" style="padding:10px 20px; cursor:pointer; font-size:14.5px; color:#111b21;">Close</div>
                </div>
            </div>
        </div>

        <!-- Before Selection State -->
        <div id="welcomeScreen" style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; border-bottom:6px solid #25d366; background:#f0f2f5; text-align:center; padding:40px;">
            <h1 style="color:#41525d; font-weight:300; margin-bottom:10px;">Somali Police Chat</h1>
            <p style="color:#667781; font-size:14px;">ku soo dhawaada nidaamka wada-hadalka ciidanka.<br>Dooro sarkaal si aad u wada-hadashaan.</p>
        </div>

        <!-- Messages Area -->
        <div class="message-area" id="messageArea" style="display:none;">
        </div>

        <!-- Input Area -->
        <div class="chat-footer" id="chatFooter" style="display:none;">
            <i class="fa-regular fa-face-smile footer-icon"></i>
            <i class="fa-solid fa-paperclip footer-icon"></i>
            <div class="input-wrapper">
                <input type="text" class="chat-input" id="messageInput" placeholder="Type a message" onkeypress="if(event.key === 'Enter') sendMessage()">
            </div>
            <i class="fa-solid fa-microphone footer-icon" id="micBtn"></i>
            <i class="fa-solid fa-paper-plane footer-icon" id="sendBtn" style="display:none; color:#00a884;" onclick="sendMessage()"></i>
        </div>
    </main>
</div>
@endsection

@section('js')
<script>
    let currentReceiverId = null;
    let currentUser = {
        {
            auth() - > id()
        }
    };

    // Initial Load
    fetchUsers();
    setInterval(fetchUsers, 10000);
    setInterval(fetchMessages, 3000);

    // Dynamic Input Icon Toggle
    document.getElementById('messageInput').addEventListener('input', function(e) {
        if (this.value.trim().length > 0) {
            document.getElementById('micBtn').style.display = 'none';
            document.getElementById('sendBtn').style.display = 'block';
        } else {
            document.getElementById('micBtn').style.display = 'block';
            document.getElementById('sendBtn').style.display = 'none';
        }
    });

    function toggleMenu() {
        let menu = document.getElementById('chatMenu');
        menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
    }

    function selectUser(id, name, avatar) {
        currentReceiverId = id;

        // UI Updates
        document.getElementById('welcomeScreen').style.display = 'none';
        document.getElementById('mainHeader').style.display = 'flex';
        document.getElementById('messageArea').style.display = 'flex';
        document.getElementById('chatFooter').style.display = 'flex';
        document.getElementById('messageArea').innerHTML = ''; // Clear prior

        // Mobile Logic
        document.getElementById('mainContainer').classList.add('show-chat');
        if (window.innerWidth <= 900) {
            document.getElementById('backBtn').style.display = 'block';
        }

        // Header Info
        document.getElementById('headerName').innerText = name;
        if (avatar && avatar !== 'null') {
            document.getElementById('headerAvatar').src = '/storage/' + avatar;
            document.getElementById('headerAvatar').style.display = 'block';
            document.getElementById('headerInitials').style.display = 'none';
        } else {
            document.getElementById('headerAvatar').style.display = 'none';
            document.getElementById('headerInitials').style.display = 'flex';
            document.getElementById('headerInitials').innerText = name.charAt(0);
        }

        fetchMessages(true);
    }

    function closeChat() {
        document.getElementById('mainContainer').classList.remove('show-chat');
        currentReceiverId = null;
    }

    function fetchUsers() {
        fetch("{{ route('chat.users') }}")
            .then(res => res.json())
            .then(users => {
                let html = `
            <div class="user-item ${currentReceiverId === null ? 'active' : ''}" onclick="selectUser(null, 'Global Chat', null)">
                <div style="width:49px; height:49px; background:#00a884; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="user-info">
                    <div style="display:flex; justify-content:space-between;">
                        <span class="user-name">Global Chat</span>
                    </div>
                    <div class="last-message">Public Room</div>
                </div>
            </div>`;

                users.forEach(user => {
                    let initial = user.name.charAt(0);
                    let avatar = user.profile_image ?
                        `<img src="/storage/${user.profile_image}" style="width:49px; height:49px; border-radius:50%; object-fit:cover;">` :
                        `<div style="width:49px; height:49px; background:#dfe3e5; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#54656f; font-weight:bold; font-size:20px;">${initial}</div>`;

                    let activeClass = currentReceiverId === user.id ? 'active' : '';

                    html += `
                <div class="user-item ${activeClass}" onclick="selectUser(${user.id}, '${user.name.replace("'", "\\'")}', '${user.profile_image}')">
                    ${avatar}
                    <div class="user-info">
                        <div style="display:flex; justify-content:space-between;">
                            <span class="user-name">${user.name}</span>
                            <span class="last-message" style="font-size:11px; color:${user.is_online ? '#25d366' : '#667781'};">
                                ${user.is_online ? 'online' : user.last_seen}
                            </span>
                        </div>
                        <div class="last-message" style="display:flex; justify-content:space-between;">
                             <span>Tap to chat</span>
                             ${user.unread_count > 0 ? `<div style="background:#25d366; color:white; border-radius:50%; width:20px; height:20px; display:flex; align-items:center; justify-content:center; font-size:10px;">${user.unread_count}</div>` : ''}
                        </div>
                    </div>
                </div>`;
                });
                document.getElementById('usersList').innerHTML = html;
            });
    }

    function fetchMessages(forceScroll = false) {
        if (!document.getElementById('chatFooter').offsetParent) return; // Only if chat open

        let url = "{{ route('chat.messages') }}";
        if (currentReceiverId) url += `?receiver_id=${currentReceiverId}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                let container = document.getElementById('messageArea');
                let html = '';

                if (data.length === 0) {
                    container.innerHTML = `<div style="text-align:center; margin-top:50px; background:rgba(255,255,255,0.9); padding:10px; border-radius:8px; display:inline-block; align-self:center; font-size:13px; color:#54656f;">Messages are end-to-end encrypted. No one outside of this chat, not even WhatsApp, can read or listen to them.</div>`;
                    return;
                }

                // Group by Date could be nice, but stick to list first
                data.forEach(msg => {
                    let isSent = msg.sender_id === currentUser;
                    let time = new Date(msg.created_at).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    // Tick Logic
                    let tickIcon = '<i class="fa-solid fa-check tick-icon"></i>'; // One check (Sent)
                    if (msg.is_read) tickIcon = '<i class="fa-solid fa-check-double tick-icon tick-read"></i>'; // Blue double (Read)
                    else if (msg.delivered_at) tickIcon = '<i class="fa-solid fa-check-double tick-icon"></i>'; // Grey double (Delivered)

                    html += `
                <div class="message-row ${isSent ? 'sent' : 'received'}">
                    <div class="msg-bubble ${isSent ? 'msg-sent' : 'msg-received'}">
                        ${!isSent && !currentReceiverId ? `<strong style="color:#d35400; font-size:12px; display:block;">${msg.sender.name}</strong>` : ''}
                        ${msg.message}
                        <div class="msg-meta">
                            <span>${time}</span>
                            ${isSent ? tickIcon : ''}
                        </div>
                    </div>
                </div>`;
                });

                // Only update html if changed to avoid flicker (simple check length?)
                // For now replace all
                let isAtBottom = container.scrollHeight - container.scrollTop === container.clientHeight;
                container.innerHTML = html;

                if (forceScroll || isAtBottom) {
                    container.scrollTop = container.scrollHeight;
                }
            });
    }

    function sendMessage() {
        let input = document.getElementById('messageInput');
        let text = input.value.trim();
        if (!text) return;

        fetch("{{ route('chat.send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                message: text,
                receiver_id: currentReceiverId
            })
        }).then(() => {
            input.value = '';
            // Reset icons
            document.getElementById('micBtn').style.display = 'block';
            document.getElementById('sendBtn').style.display = 'none';
            fetchMessages(true);
        });
    }

    async function clearChat() {
        if (!confirm('Clear all messages in this chat?')) return;
        // reuse existing route
        await fetch("{{ route('chat.clear') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                receiver_id: currentReceiverId
            })
        });
        fetchMessages(true);
        toggleMenu();
    }
</script>
@endsection