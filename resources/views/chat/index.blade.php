@extends('layouts.app')

@section('title', 'Wada-hadalka (Chat)')

@section('css')
<style>
    /* Responsive Chat Architecture */
    .chat-wrapper {
        display: flex;
        height: calc(100vh - 120px);
        margin: -1rem; /* Fit into main-content padding */
        background: #fff;
        position: relative;
        overflow: hidden;
    }

    /* Contacts Sidebar */
    .contacts-pane {
        width: 360px;
        border-right: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
        transition: all 0.3s ease;
        z-index: 10;
    }

    /* Message Main Pane */
    .conversation-pane {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
        position: relative;
        min-width: 0;
    }

    /* WhatsApp Style Header */
    .pane-header {
        height: 70px;
        padding: 0 1.5rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        shrink: 0;
    }

    .contact-info {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .avatar-wrapper {
        position: relative;
        width: 45px;
        height: 45px;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .online-indicator {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background: #22c55e;
        border: 2px solid #fff;
        border-radius: 50%;
    }

    /* Scrollable Message Area */
    .messages-viewport {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        background: #efe7dd url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
        background-blend-mode: overlay;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    /* Message Bubbles */
    .msg-bubble {
        max-width: 75%;
        padding: 0.6rem 1rem;
        border-radius: 12px;
        font-size: 0.925rem;
        line-height: 1.5;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .msg-sent {
        align-self: flex-end;
        background: #dcf8c6;
        color: #111827;
        border-top-right-radius: 2px;
    }

    .msg-received {
        align-self: flex-start;
        background: #fff;
        color: #111827;
        border-top-left-radius: 2px;
    }

    .msg-time {
        font-size: 0.7rem;
        color: #64748b;
        margin-top: 4px;
        text-align: right;
    }

    /* Input Dock */
    .input-dock {
        padding: 0.8rem 1.5rem;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .input-wrapper {
        flex: 1;
        background: #fff;
        border-radius: 25px;
        padding: 0.5rem 1.25rem;
        display: flex;
        align-items: center;
    }

    .msg-input {
        flex: 1;
        border: none;
        outline: none;
        padding: 0.4rem 0;
        font-size: 0.95rem;
    }

    /* Call Overlays */
    .call-overlay {
        position: fixed;
        inset: 0;
        background: #0f172a;
        z-index: 3000;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    /* Mobile Interaction */
    @media (max-width: 768px) {
        .contacts-pane {
            width: 100%;
            position: absolute;
            height: 100%;
        }
        .contacts-pane.hidden {
            transform: translateX(-100%);
        }
        .conversation-pane {
            width: 100%;
            position: absolute;
            height: 100%;
        }
        .conversation-pane.hidden {
            transform: translateX(100%);
        }
        .back-btn {
            display: block !important;
        }
    }

    .back-btn {
        display: none;
        margin-right: 1rem;
        font-size: 1.2rem;
        cursor: pointer;
    }

    .action-btn {
        color: #64748b;
        font-size: 1.25rem;
        cursor: pointer;
        transition: 0.2s;
    }

    .action-btn:hover { color: var(--accent-blue); }
    .send-btn { color: var(--accent-blue); font-size: 1.4rem; cursor: pointer; }
</style>
@endsection

@section('content')
<div class="chat-wrapper">
    <!-- Contacts Pane -->
    <div class="contacts-pane" id="contactsPane">
        <div class="pane-header" style="background: #f0f2f5;">
            <div class="contact-info">
                <div class="avatar-wrapper">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=3b82f6&color=fff" class="avatar-img">
                </div>
                <span style="font-weight: 700;">My Chats</span>
            </div>
            <div style="display: flex; gap: 1rem;">
                <i class="fa-solid fa-circle-notch action-btn" title="Status"></i>
                <i class="fa-solid fa-message action-btn" title="New Chat"></i>
                <i class="fa-solid fa-ellipsis-vertical action-btn"></i>
            </div>
        </div>

        <!-- Search -->
        <div style="padding: 0.75rem 1rem;">
            <div style="background: #fff; border-radius: 8px; padding: 0.5rem 1rem; display: flex; align-items: center; gap: 1rem; border: 1px solid #e2e8f0;">
                <i class="fa-solid fa-magnifying-glass" style="color: #94a3b8; font-size: 0.85rem;"></i>
                <input type="text" id="userSearch" placeholder="Raadi sarkaalka..." style="border: none; outline: none; width: 100%; font-size: 0.85rem;">
            </div>
        </div>

        <!-- List -->
        <div id="usersList" style="flex: 1; overflow-y: auto;">
            <!-- Loaded via JS -->
            <div style="padding: 2rem; text-align: center; color: #94a3b8;">
                <i class="fa-solid fa-spinner fa-spin"></i> Loading...
            </div>
        </div>
    </div>

    <!-- Conversation Pane -->
    <div class="conversation-pane hidden" id="conversationPane">
        <!-- Default State -->
        <div id="noChatSelected" style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f8fafc; text-align: center; padding: 2rem;">
            <div style="width: 250px; height: 250px; background: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png'); border-radius: 50%; opacity: 0.1; margin-bottom: 2rem;"></div>
            <h2 style="font-weight: 800; color: #1e293b; margin-bottom: 0.5rem;">WhatsApp for Police</h2>
            <p style="color: #64748b; max-width: 350px;">Dooro qof aad la hadasho si aad u bilowdo wada-hadal sugan.</p>
            <div style="margin-top: 2rem; color: #94a3b8; font-size: 0.85rem;">
                <i class="fa-solid fa-lock"></i> End-to-end encrypted
            </div>
        </div>

        <!-- Active Chat State -->
        <div id="activeChat" style="display: none; flex: 1; flex-direction: column;">
            <div class="pane-header">
                <div class="contact-info">
                    <i class="fa-solid fa-arrow-left back-btn" onclick="showContacts()"></i>
                    <div class="avatar-wrapper" id="activeUserAvatar"></div>
                    <div>
                        <div id="activeUserName" style="font-weight: 700; font-size: 1rem;">John Doe</div>
                        <div id="activeUserStatus" style="font-size: 0.75rem; color: #64748b;">Online</div>
                    </div>
                </div>
                <div style="display: flex; gap: 1.5rem; align-items: center;">
                    <i class="fa-solid fa-video action-btn" onclick="initiateCall('video')"></i>
                    <i class="fa-solid fa-phone action-btn" onclick="initiateCall('audio')"></i>
                    <i class="fa-solid fa-magnifying-glass action-btn"></i>
                    <i class="fa-solid fa-ellipsis-vertical action-btn"></i>
                </div>
            </div>

            <div class="messages-viewport" id="messageArea">
                <!-- Messages JS -->
            </div>

            <div class="input-dock">
                <i class="fa-regular fa-face-smile action-btn"></i>
                <i class="fa-solid fa-plus action-btn"></i>
                <div class="input-wrapper">
                    <input type="text" id="messageInput" class="msg-input" placeholder="Qor fariin..." onkeypress="if(event.key==='Enter')sendMessage()">
                </div>
                <i class="fa-solid fa-microphone action-btn" id="micBtn"></i>
                <i class="fa-solid fa-paper-plane send-btn" onclick="sendMessage()" id="sendBtn" style="display: none;"></i>
            </div>
        </div>
    </div>
</div>

<!-- VoIP Call Overlay -->
<div class="call-overlay" id="callOverlay">
    <div style="text-align: center;">
        <div id="callAvatar" style="width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 2rem; border: 4px solid var(--accent-lime); padding: 5px;">
            <img src="" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
        </div>
        <h1 id="callContactName" style="font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem;">Sarkaal</h1>
        <p id="callStatus" style="font-size: 1.1rem; color: #94a3b8;">Wicitaan ayaa socda...</p>
        
        <div style="margin-top: 4rem; display: flex; gap: 2rem;">
            <div onclick="endCall()" style="width: 65px; height: 65px; border-radius: 50%; background: #ef4444; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);">
                <i class="fa-solid fa-phone-slash" style="font-size: 1.5rem;"></i>
            </div>
        </div>
    </div>
</div>

<audio id="ringtone" loop>
    <source src="https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3" type="audio/mpeg">
</audio>

@endsection

@section('js')
<script>
    let currentReceiverId = null;
    let activeCallId = null;

    // View Switching logic
    function showChat(userId, name, img) {
        currentReceiverId = userId;
        document.getElementById('noChatSelected').style.display = 'none';
        document.getElementById('activeChat').style.display = 'flex';
        document.getElementById('activeUserName').innerText = name;
        
        const avatarHtml = img ? `<img src="/storage/${img}" class="avatar-img">` : 
                          `<div style="width:100%;height:100%;border-radius:50%;background:var(--accent-lime);display:flex;align-items:center;justify-content:center;font-weight:800;color:#000;">${name.charAt(0)}</div>`;
        document.getElementById('activeUserAvatar').innerHTML = avatarHtml;

        if(window.innerWidth <= 768) {
            document.getElementById('contactsPane').classList.add('hidden');
            document.getElementById('conversationPane').classList.remove('hidden');
        }
        
        loadMessages();
    }

    function showContacts() {
        document.getElementById('contactsPane').classList.remove('hidden');
        document.getElementById('conversationPane').classList.add('hidden');
    }

    // Input Control
    document.getElementById('messageInput').addEventListener('input', function(e) {
        const hasText = e.target.value.trim().length > 0;
        document.getElementById('micBtn').style.display = hasText ? 'none' : 'block';
        document.getElementById('sendBtn').style.display = hasText ? 'block' : 'none';
    });

    // WhatsApp style User Fetch
    function fetchUsers() {
        fetch("{{ route('chat.users') }}")
            .then(res => res.json())
            .then(users => {
                let html = '';
                users.forEach(u => {
                    const avatar = u.profile_image ? `<img src="/storage/${u.profile_image}" style="width:50px;height:50px;border-radius:50%;">` : 
                                  `<div style="width:50px;height:50px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-weight:700;">${u.name.charAt(0)}</div>`;
                    
                    html += `
                    <div onclick="showChat(${u.id}, '${u.name}', '${u.profile_image}')" style="display:flex;padding:0.75rem 1rem;gap:1rem;cursor:pointer;transition:0.2s;align-items:center;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                        ${avatar}
                        <div style="flex:1;border-bottom:1px solid #f1f5f9;padding-bottom:0.75rem;">
                            <div style="display:flex;justify-content:space-between;">
                                <span style="font-weight:600;">${u.name}</span>
                                <span style="font-size:0.7rem;color:#94a3b8;">${u.last_seen || ''}</span>
                            </div>
                            <div style="font-size:0.8rem;color:#64748b;">${u.is_online ? '🟢 Online' : 'Click to message'}</div>
                        </div>
                    </div>`;
                });
                document.getElementById('usersList').innerHTML = html;
            });
    }

    function loadMessages() {
        if(!currentReceiverId) return;
        fetch(`{{ route('chat.messages') }}?receiver_id=${currentReceiverId}`)
            .then(res => res.json())
            .then(messages => {
                let html = '';
                messages.forEach(m => {
                    const isSent = m.sender_id == {{ auth()->id() }};
                    const time = new Date(m.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
                    html += `
                    <div class="msg-bubble ${isSent ? 'msg-sent' : 'msg-received'}">
                        ${m.message}
                        <div class="msg-time">${time}</div>
                    </div>`;
                });
                const viewport = document.getElementById('messageArea');
                viewport.innerHTML = html;
                viewport.scrollTop = viewport.scrollHeight;
            });
    }

    function sendMessage() {
        const input = document.getElementById('messageInput');
        const msg = input.value.trim();
        if(!msg) return;

        fetch("{{ route('chat.send') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ message: msg, receiver_id: currentReceiverId })
        }).then(() => {
            input.value = '';
            document.getElementById('micBtn').style.display = 'block';
            document.getElementById('sendBtn').style.display = 'none';
            loadMessages();
        });
    }

    // VoIP logic interface
    function initiateCall(type) {
        document.getElementById('callContactName').innerText = document.getElementById('activeUserName').innerText;
        document.getElementById('callOverlay').style.display = 'flex';
        document.getElementById('ringtone').play();
        // Backend integration for signaling would follow
    }

    function endCall() {
        document.getElementById('callOverlay').style.display = 'none';
        document.getElementById('ringtone').pause();
        document.getElementById('ringtone').currentTime = 0;
    }

    fetchUsers();
    setInterval(loadMessages, 4000);
</script>
@endsection
