@extends('layouts.app')

@section('title', 'Police Chat')

@section('css')
<style>
    /* RESET & VARS */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    :root {
        --app-bg: #ece5dd;
        /* WhatsApp bg color */
        --sidebar-bg: #ffffff;
        --header-bg: #f0f2f5;
        --sent-bg: #d9fdd3;
        /* WhatsApp sent green */
        --recv-bg: #ffffff;
        --primary: #008069;
        /* WhatsApp Green */
        --text: #111b21;
        --text-sub: #667781;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        background: #d1d7db;
        height: 100vh;
        overflow: hidden;
    }

    /* MAIN CONTAINER - 100dvh for mobile stickiness */
    .app-container {
        display: flex;
        width: 100%;
        height: 100dvh;
        /* Dynamic Viewport Height */
        max-width: 1600px;
        margin: 0 auto;
        background: #fff;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.06);
    }

    /* ---- SIDEBAR ---- */
    .sidebar {
        width: 30%;
        /* or fixed px */
        min-width: 320px;
        max-width: 450px;
        background: var(--sidebar-bg);
        border-right: 1px solid #e9edef;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .sidebar-header {
        height: 60px;
        padding: 10px 16px;
        background: var(--header-bg);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-right: 1px solid #d1d7db;
    }

    .profile-thumb {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #dfe3e5;
        overflow: hidden;
    }

    .search-bar {
        padding: 8px 14px;
        border-bottom: 1px solid #f0f2f5;
    }

    .search-input-wrapper {
        background: #f0f2f5;
        border-radius: 8px;
        display: flex;
        align-items: center;
        padding: 6px 12px;
    }

    .search-input-wrapper input {
        border: none;
        background: transparent;
        margin-left: 10px;
        width: 100%;
        outline: none;
        font-size: 14px;
    }

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
    }

    .user-item:hover {
        background: #f5f6f6;
    }

    .user-item.active {
        background: #f0f2f5;
    }

    .u-avatar {
        width: 49px;
        height: 49px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
        background: #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
        color: white;
    }

    .u-info {
        flex: 1;
        overflow: hidden;
    }

    .u-name {
        font-size: 16px;
        color: var(--text);
        margin-bottom: 2px;
    }

    .u-sub {
        font-size: 13px;
        color: var(--text-sub);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: flex;
        justify-content: space-between;
    }

    .u-time {
        font-size: 12px;
        color: #8696a0;
    }

    /* ---- CHAT AREA ---- */
    .chat-area {
        flex: 1;
        display: flex;
        flex-direction: column;
        background-color: var(--app-bg);
        /* Subtle whatsapp pattern overlay could go here */
        position: relative;
    }

    .chat-header {
        height: 60px;
        padding: 0 16px;
        background: var(--header-bg);
        display: flex;
        align-items: center;
        width: 100%;
        z-index: 10;
        border-bottom: 1px solid #d1d7db;
    }

    .chat-header-info {
        flex: 1;
        margin-left: 15px;
        cursor: default;
    }

    .ch-name {
        font-weight: 500;
        font-size: 16px;
        color: var(--text);
    }

    .ch-status {
        font-size: 13px;
        color: var(--text-sub);
    }

    /* MESSAGES - Scrollable */
    .messages-wrapper {
        flex: 1;
        overflow-y: auto;
        /* Scroll here */
        padding: 0 5% 10px 5%;
        /* Padding side */
        display: flex;
        flex-direction: column;
        background-image: linear-gradient(rgba(229, 221, 213, 0.9), rgba(229, 221, 213, 0.9)), url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
        background-repeat: repeat;
    }

    .msg-date-divider {
        align-self: center;
        background: #e1f2fb;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 12px;
        color: #555;
        margin: 10px 0;
        box-shadow: 0 1px 0.5px rgba(0, 0, 0, 0.13);
    }

    .message-row {
        display: flex;
        margin-bottom: 4px;
        flex-direction: column;
    }

    .message-row.sent {
        align-items: flex-end;
    }

    .message-row.recv {
        align-items: flex-start;
    }

    .bubble {
        max-width: 65%;
        padding: 6px 7px 8px 9px;
        border-radius: 8px;
        position: relative;
        font-size: 14.2px;
        line-height: 19px;
        box-shadow: 0 1px 0.5px rgba(0, 0, 0, 0.13);
    }

    .bubble.sent {
        background: var(--sent-bg);
        border-top-right-radius: 0;
    }

    .bubble.recv {
        background: var(--recv-bg);
        border-top-left-radius: 0;
    }

    .bubble-meta {
        float: right;
        margin-top: 4px;
        /* Push down slightly */
        margin-left: 10px;
        font-size: 11px;
        color: #667781;
        display: flex;
        align-items: center;
        gap: 3px;
        position: relative;
        top: 3px;
    }

    /* FOOTER - Sticky Bottom */
    .chat-footer {
        padding: 10px 16px;
        background: var(--header-bg);
        display: flex;
        align-items: center;
        gap: 12px;
        /* Sticky fallback logic handled by flex parent height */
    }

    .icon-btn {
        font-size: 20px;
        color: #54656f;
        cursor: pointer;
    }

    .input-grp {
        flex: 1;
        background: #fff;
        border-radius: 8px;
        padding: 9px 12px;
        display: flex;
        align-items: center;
    }

    .input-grp input {
        width: 100%;
        border: none;
        outline: none;
        font-size: 15px;
    }

    /* MOBILE RESPONSIVE */
    @media (max-width: 900px) {
        .app-container {
            height: 100vh;
            height: 100dvh;
        }

        /* Full screen mobile */
        .sidebar {
            width: 100%;
            max-width: none;
        }

        .chat-area {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 100;
        }

        .chat-area.active {
            transform: translateX(0);
        }

        .back-btn {
            display: block !important;
            margin-right: 10px;
            font-size: 20px;
            cursor: pointer;
        }
    }

    .back-btn {
        display: none;
    }
</style>
<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
<div class="app-container">

    <!-- === LEFT SIDEBAR === -->
    <div class="sidebar">
        <!-- Header -->
        <div class="sidebar-header">
            <div class="profile-thumb">
                <img src="{{ auth()->user()->profile_image ? asset('storage/'.auth()->user()->profile_image) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <div style="display:flex; gap:20px; color:#54656f;">
                <i class="fa-solid fa-users"></i>
                <i class="fa-solid fa-circle-notch"></i>
                <i class="fa-solid fa-message"></i>
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </div>
        </div>

        <!-- Search -->
        <div class="search-bar">
            <div class="search-input-wrapper">
                <i class="fa-solid fa-magnifying-glass" style="color:#54656f; font-size:14px;"></i>
                <input type="text" placeholder="Search or start new chat">
            </div>
        </div>

        <!-- Users List -->
        <div class="user-list" id="usersList">
            <!-- Loading -->
            <div style="padding:20px; text-align:center; color:#667781;">Loading chats...</div>
        </div>
    </div>


    <!-- === RIGHT CHAT AREA === -->
    <div class="chat-area" id="chatArea">
        <!-- Chat Header -->
        <div class="chat-header">
            <i class="fa-solid fa-arrow-left back-btn" onclick="closeChat()"></i>
            <div class="u-avatar" id="headerAvatar" style="width:40px; height:40px; font-size:14px; margin-right:15px;">?</div>
            <div class="chat-header-info">
                <div class="ch-name" id="headerName">User Name</div>
                <div class="ch-status" id="headerStatus">click here for contact info</div>
            </div>
            <div style="display:flex; gap:20px; color:#54656f;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </div>
        </div>

        <!-- Messages Body -->
        <div class="messages-wrapper" id="msgsBox">
            <!-- Messages injected here -->
            <div style="text-align:center; margin-top:50px; color:#555; background:#e0f7fa; padding:10px; border-radius:10px; width:80%; margin: 50px auto;">
                <i class="fa-brands fa-whatsapp" style="font-size:30px; margin-bottom:10px; display:block;"></i>
                Dooro xiriir (contact) si aad u bilowdo wada hadal.<br>
                Booliiska Soomaaliyeed - Wada-hadal Sugan.
            </div>
        </div>

        <!-- Sticky Footer -->
        <div class="chat-footer">
            <i class="fa-regular fa-face-smile icon-btn"></i>
            <i class="fa-solid fa-paperclip icon-btn"></i>

            <div class="input-grp">
                <input type="text" id="msgInput" placeholder="Type a message" onkeydown="if(event.key==='Enter') sendMsg()">
            </div>

            <!-- Send Button -->
            <i class="fa-solid fa-paper-plane icon-btn" style="color:var(--primary);" onclick="sendMsg()"></i>
        </div>
    </div>

</div>
@endsection

@section('js')
<script>
    const authId = {
        {
            auth() - > id()
        }
    };
    let receiverId = null;

    // Load users initially
    loadUsers();
    // Poll users every 10s
    setInterval(loadUsers, 10000);
    // Poll for new messages every 3s
    setInterval(() => loadMessages(false), 3000);

    function loadUsers() {
        fetch("{{ route('chat.users') }}")
            .then(res => res.json())
            .then(users => {
                let html = `
                <div class="user-item ${receiverId===null?'active':''}" onclick="openChat(null, 'Global Chat', null)">
                    <div class="u-avatar" style="background:#008069;">G</div>
                    <div class="u-info">
                        <div class="u-name">Global Chat</div>
                        <div class="u-sub"><span>Public Room</span></div>
                    </div>
                </div>`;

                users.forEach(u => {
                    let avatar = u.profile_image ?
                        `<img src="/storage/${u.profile_image}" class="u-avatar">` :
                        `<div class="u-avatar" style="background:#ccc">${u.name[0]}</div>`;

                    // Simple online indicator check (if last_seen within 5 mins?)
                    let activeClass = (receiverId === u.id) ? 'active' : '';

                    html += `
                    <div class="user-item ${activeClass}" onclick="openChat(${u.id}, '${u.name.replace("'","\\'")}', null)">
                        ${avatar}
                        <div class="u-info">
                            <div class="u-name">${u.name}</div>
                            <div class="u-sub">
                                <span>Recent msg...</span>
                                <span class="u-time">${u.last_seen || ''}</span>
                            </div>
                        </div>
                    </div>`;
                });
                document.getElementById('usersList').innerHTML = html;
            });
    }

    function openChat(id, name, img) {
        receiverId = id;
        document.getElementById('headerName').innerText = name;

        if (id === null) {
            document.getElementById('headerAvatar').innerHTML = "G";
            document.getElementById('headerAvatar').style.background = "#008069";
            document.getElementById('headerStatus').innerText = "online";
        } else {
            document.getElementById('headerAvatar').innerHTML = name[0];
            document.getElementById('headerAvatar').style.background = "#ccc";
            document.getElementById('headerStatus').innerText = "online";
        }

        // Mobile transition
        document.getElementById('chatArea').classList.add('active');

        loadMessages(true);
    }

    function closeChat() {
        document.getElementById('chatArea').classList.remove('active');
        receiverId = null;
    }

    function loadMessages(forceScroll = false) {
        // If query param needed logic for correct route
        let url = "{{ route('chat.messages') }}";
        if (receiverId) url += `?receiver_id=${receiverId}`;

        fetch(url)
            .then(r => r.json())
            .then(msgs => {
                let html = '';
                // Basic date grouping can be added here

                msgs.forEach(m => {
                    let isMe = (m.sender_id === authId);
                    let type = isMe ? 'sent' : 'recv';

                    // Format time: HH:MM
                    let date = new Date(m.created_at);
                    let time = date.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    let checkIcon = isMe ? `<i class="fa-solid fa-check-double" style="color:#53bdeb; font-size:10px;"></i>` : '';

                    html += `
                <div class="message-row ${type}">
                    <div class="bubble ${type}">
                        ${m.message}
                        <span class="bubble-meta">
                            ${time} ${checkIcon}
                        </span>
                    </div>
                </div>`;
                });

                let box = document.getElementById('msgsBox');
                // rudimentary check to see if we should render
                // in real app, compare ID or length
                if (box.innerHTML.length !== html.length || forceScroll) {
                    box.innerHTML = html;
                    if (forceScroll || (box.scrollHeight - box.scrollTop) < box.clientHeight + 200) {
                        box.scrollTop = box.scrollHeight;
                    }
                }
            });
    }

    function sendMsg() {
        let input = document.getElementById('msgInput');
        let txt = input.value.trim();
        if (!txt) return;

        fetch("{{ route('chat.send') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                message: txt,
                receiver_id: receiverId
            })
        }).then(() => {
            input.value = '';
            loadMessages(true);
        });
    }
</script>
@endsection