@extends('layouts.app')

@section('title', 'Wada-hadalka (Messenger)')

@section('css')
<style>
    :root {
        --messenger-blue: #0084ff;
        --messenger-gray: #f0f2f5;
        --bubble-received: #e4e6eb;
        --text-primary: #050505;
        --text-secondary: #65676b;
    }

    /* Messenger Wrapper */
    .messenger-wrapper {
        display: flex;
        height: 100vh;
        background: #fff;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        overflow: hidden;
    }

    /* Sidebar */
    .ms-sidebar {
        width: 360px;
        border-right: 1px solid rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .ms-header {
        padding: 10px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 60px;
    }

    .ms-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .ms-icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--messenger-gray);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
        border: none;
        font-size: 16px;
        color: var(--text-primary);
    }

    .ms-icon-btn:hover {
        background: #d8dadf;
    }

    .ms-search {
        padding: 4px 16px 12px;
    }

    .ms-search-input-wrapper {
        background: var(--messenger-gray);
        border-radius: 20px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
    }

    .ms-search-input {
        border: none;
        background: transparent;
        margin-left: 8px;
        width: 100%;
        outline: none;
        font-size: 15px;
    }

    /* User List */
    .ms-user-list {
        flex: 1;
        overflow-y: auto;
        padding: 8px;
    }

    .ms-user-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.2s;
        position: relative;
    }

    .ms-user-item:hover {
        background: rgba(0, 0, 0, 0.05);
    }

    .ms-user-item.active {
        background: rgba(45, 136, 255, 0.1);
        /* Light Blue Select */
    }

    .ms-avatar-wrapper {
        position: relative;
        margin-right: 12px;
    }

    .ms-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
    }

    .ms-status-dot {
        width: 14px;
        height: 14px;
        background: #31a24c;
        border: 2px solid #fff;
        border-radius: 50%;
        position: absolute;
        bottom: 2px;
        right: 2px;
    }

    .ms-user-info {
        flex: 1;
        overflow: hidden;
    }

    .ms-user-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 15px;
        margin-bottom: 2px;
    }

    .ms-user-meta {
        font-size: 13px;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Main Chat Area */
    .ms-chat {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .ms-chat-header {
        height: 60px;
        padding: 0 16px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        z-index: 10;
    }

    .ms-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 2px;
        /* Close grouping */
    }

    /* Bubbles */
    .ms-msg-row {
        display: flex;
        margin-bottom: 2px;
        align-items: flex-end;
    }

    .ms-msg-row.sent {
        justify-content: flex-end;
    }

    .ms-msg-row.received {
        justify-content: flex-start;
    }

    /* Avatar for received msg group */
    .ms-msg-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        margin-right: 8px;
        margin-bottom: 5px;
    }

    .ms-bubble {
        padding: 8px 12px;
        border-radius: 18px;
        font-size: 15px;
        line-height: 1.4;
        max-width: 70%;
        word-wrap: break-word;
        position: relative;
    }

    .ms-bubble.sent {
        background: var(--messenger-blue);
        color: white;
        border-bottom-right-radius: 4px;
        /* Gradient Optional */
        /* background: linear-gradient(to right, #0084ff, #0099ff); */
    }

    .ms-bubble.received {
        background: var(--bubble-received);
        color: var(--text-primary);
        border-bottom-left-radius: 4px;
    }

    /* Footer */
    .ms-footer {
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
    }

    .ms-input-wrapper {
        flex: 1;
        background: var(--messenger-gray);
        border-radius: 20px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
    }

    .ms-input {
        width: 100%;
        border: none;
        background: transparent;
        outline: none;
        font-size: 15px;
    }

    .ms-footer-icon {
        color: var(--messenger-blue);
        font-size: 20px;
        cursor: pointer;
        padding: 6px;
        border-radius: 50%;
        transition: background 0.2s;
    }

    .ms-footer-icon:hover {
        background: var(--messenger-gray);
    }

    /* Mobile Responsiveness */
    @media (max-width: 900px) {
        .ms-sidebar {
            width: 100%;
        }

        .ms-chat {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            z-index: 200;
        }

        .ms-chat.active {
            transform: translateX(0);
        }

        .ms-back-btn {
            display: block !important;
            margin-right: 10px;
            font-size: 20px;
            color: var(--messenger-blue);
            cursor: pointer;
        }
    }

    .ms-back-btn {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="messenger-wrapper">
    <!-- Sidebar -->
    <div class="ms-sidebar" id="msSidebar">
        <!-- Header -->
        <div class="ms-header">
            <h1 class="ms-title">Chats</h1>
            <div style="display:flex; gap:10px;">
                <button class="ms-icon-btn"><i class="fa-solid fa-camera"></i></button>
                <button class="ms-icon-btn"><i class="fa-solid fa-pen-to-square"></i></button>
            </div>
        </div>

        <!-- Search -->
        <div class="ms-search">
            <div class="ms-search-input-wrapper">
                <i class="fa-solid fa-search" style="color: #65676b; margin-right:5px;"></i>
                <input type="text" class="ms-search-input" placeholder="Search Messenger">
            </div>
        </div>

        <!-- Active Users (Stories/Heads placeholder) -->
        <div style="padding: 10px 16px; display:flex; gap:15px; overflow-x:auto;">
            <!-- Add logic if needed, for now hidden or simple -->
        </div>

        <!-- User List -->
        <div class="ms-user-list" id="usersList">
            <!-- Global Chat -->
            <div class="ms-user-item active" onclick="selectUser(null, 'Global Chat', null)">
                <div class="ms-avatar-wrapper">
                    <div style="width:56px; height:56px; border-radius:50%; background:var(--messenger-blue); display:flex; align-items:center; justify-content:center; color:white; font-size:24px;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="ms-user-info">
                    <div class="ms-user-name">Global Chat</div>
                    <div class="ms-user-meta">Public Room ΓÇó Now</div>
                </div>
            </div>

            <div style="text-align:center; padding:20px; color:var(--text-secondary);">Loading...</div>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="ms-chat" id="msChat">
        <!-- Chat Header -->
        <div class="ms-chat-header" id="chatHeader" style="display:none;">
            <div style="display:flex; align-items:center;">
                <i class="fa-solid fa-arrow-left ms-back-btn" onclick="closeChat()"></i>
                <img id="chatAvatar" class="ms-avatar" src="" style="width:40px; height:40px; margin-right:10px; display:none;">
                <div id="chatInitials" style="width:40px; height:40px; border-radius:50%; background:var(--messenger-gray); display:flex; align-items:center; justify-content:center; font-weight:bold; margin-right:10px; color:black;">?</div>
                <div>
                    <div class="ms-user-name" id="chatName" style="font-size:16px;">Name</div>
                    <div class="ms-user-meta" id="chatStatus" style="font-size:12px;">Active now</div>
                </div>
            </div>
            <div style="display:flex; gap:15px;">
                <i class="fa-solid fa-phone ms-footer-icon"></i>
                <i class="fa-solid fa-video ms-footer-icon"></i>
                <i class="fa-solid fa-circle-info ms-footer-icon" onclick="toggleInfo()"></i>

                <!-- Simple dropdown for clear -->
                <div id="infoMenu" style="display:none; position:absolute; right:20px; top:60px; background:white; box-shadow:0 2px 10px rgba(0,0,0,0.2); border-radius:8px; padding:10px; z-index:100;">
                    <div onclick="clearChat()" style="cursor:pointer; color:red; font-size:14px;">Delete Chat</div>
                </div>
            </div>
        </div>

        <!-- Welcome Placeholder -->
        <div id="welcomePlaceholder" style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; color:var(--text-secondary);">
            <i class="fa-brands fa-facebook-messenger" style="font-size:64px; color:var(--messenger-blue); margin-bottom:20px;"></i>
            <h2>Wada-hadalka (Messenger)</h2>
            <p>Dooro qof si aad u wada hadashaan.</p>
        </div>

        <!-- Messages -->
        <div class="ms-messages" id="msgsArea" style="display:none;">
            <!-- Msgs -->
        </div>

        <!-- Footer -->
        <div class="ms-footer" id="chatFooter" style="display:none;">
            <i class="fa-solid fa-circle-plus ms-footer-icon"></i>
            <i class="fa-solid fa-image ms-footer-icon"></i>
            <i class="fa-solid fa-note-sticky ms-footer-icon"></i>
            <div class="ms-input-wrapper">
                <input type="text" class="ms-input" id="msInput" placeholder="Aa" onkeypress="if(event.key === 'Enter') sendMsg()">
                <i class="fa-solid fa-face-smile" style="color:#0084ff; cursor:pointer;"></i>
            </div>
            <i class="fa-solid fa-paper-plane ms-footer-icon" id="sendIcon" onclick="sendMsg()"></i>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    let currentReceiverId = null;
    let authId = {
        {
            auth() - > id()
        }
    };

    // Polls
    setInterval(loadUsers, 10000);
    setInterval(loadMessages, 3000);
    loadUsers();

    function loadUsers() {
        fetch("{{ route('chat.users') }}")
            .then(r => r.json())
            .then(users => {
                let html = `
            <div class="ms-user-item ${currentReceiverId === null ? 'active' : ''}" onclick="selectUser(null, 'Global Chat', null)">
                <div class="ms-avatar-wrapper">
                    <div style="width:56px; height:56px; border-radius:50%; background:var(--messenger-blue); display:flex; align-items:center; justify-content:center; color:white; font-size:24px;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="ms-user-info">
                    <div class="ms-user-name">Global Chat</div>
                    <div class="ms-user-meta">Public Room</div>
                </div>
            </div>`;

                users.forEach(u => {
                    let active = currentReceiverId === u.id ? 'active' : '';
                    let avatar = u.profile_image ?
                        `<img src="/storage/${u.profile_image}" class="ms-avatar">` :
                        `<div class="ms-avatar" style="background:#dfe3e5; display:flex; align-items:center; justify-content:center; font-weight:bold;">${u.name[0]}</div>`;

                    let onlineDot = u.is_online ? `<div class="ms-status-dot"></div>` : '';

                    html += `
                <div class="ms-user-item ${active}" onclick="selectUser(${u.id}, '${u.name.replace("'", "\\'")}', '${u.profile_image}')">
                    <div class="ms-avatar-wrapper">
                        ${avatar}
                        ${onlineDot}
                    </div>
                    <div class="ms-user-info">
                        <div class="ms-user-name">${u.name}</div>
                        <div class="ms-user-meta">${u.is_online ? 'Active Now' : u.last_seen}</div>
                    </div>
                </div>`;
                });
                document.getElementById('usersList').innerHTML = html;
            });
    }

    function selectUser(id, name, img) {
        currentReceiverId = id;
        document.getElementById('welcomePlaceholder').style.display = 'none';
        document.getElementById('chatHeader').style.display = 'flex';
        document.getElementById('msgsArea').style.display = 'flex';
        document.getElementById('chatFooter').style.display = 'flex';
        document.getElementById('msChat').classList.add('active');

        // Set Info
        document.getElementById('chatName').innerText = name;
        if (img && img !== 'null') {
            document.getElementById('chatAvatar').src = '/storage/' + img;
            document.getElementById('chatAvatar').style.display = 'block';
            document.getElementById('chatInitials').style.display = 'none';
        } else {
            document.getElementById('chatAvatar').style.display = 'none';
            document.getElementById('chatInitials').style.display = 'flex';
            document.getElementById('chatInitials').innerText = name.charAt(0);
        }

        loadMessages(true);
    }

    function closeChat() {
        document.getElementById('msChat').classList.remove('active');
        // Optional: Reset state or keep it
    }

    function loadMessages(forceScroll = false) {
        if (!document.getElementById('chatFooter').offsetParent) return;

        let url = "{{ route('chat.messages') }}";
        if (currentReceiverId) url += `?receiver_id=${currentReceiverId}`;

        fetch(url).then(r => r.json()).then(msgs => {
            let html = '';
            let container = document.getElementById('msgsArea');

            msgs.forEach((m, index) => {
                let isSent = m.sender_id === authId;

                // Show avatar only for received messages logic could be added (if different sender in group)
                // For now simple bubbles

                html += `
                 <div class="ms-msg-row ${isSent ? 'sent' : 'received'}">
                     <div class="ms-bubble ${isSent ? 'sent' : 'received'}" title="${new Date(m.created_at).toLocaleTimeString()}">
                        ${m.message}
                     </div>
                 </div>`;
            });

            if (html !== container.innerHTML) {
                container.innerHTML = html;
                if (forceScroll || (container.scrollHeight - container.scrollTop <= container.clientHeight + 100)) {
                    container.scrollTop = container.scrollHeight;
                }
            }
        });
    }

    function sendMsg() {
        let txt = document.getElementById('msInput').value.trim();
        if (!txt) return;

        fetch("{{ route('chat.send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                message: txt,
                receiver_id: currentReceiverId
            })
        }).then(() => {
            document.getElementById('msInput').value = '';
            loadMessages(true);
        });
    }

    function toggleInfo() {
        let m = document.getElementById('infoMenu');
        m.style.display = m.style.display === 'block' ? 'none' : 'block';
    }

    async function clearChat() {
        if (!confirm('Delete conversation?')) return;
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
        loadMessages(true);
        toggleInfo();
    }
</script>
@endsection