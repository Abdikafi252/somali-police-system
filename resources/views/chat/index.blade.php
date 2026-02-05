@extends('layouts.app')

@section('title', 'WhatsApp Police Clone')

@section('css')
<style>
    /* Premium WhatsApp Redesign - Enhanced Animations */
    .chat-wrapper {
        display: flex;
        height: calc(100vh - 120px);
        margin: -1rem;
        background: #f0f2f5;
        position: relative;
        overflow: hidden;
        font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
    }

    .contacts-pane {
        width: 380px;
        border-right: 1px solid #d1d7db;
        display: flex;
        flex-direction: column;
        background: #fff;
        z-index: 10;
        transition: 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .pane-header {
        height: 60px;
        padding: 10px 16px;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #d1d7db;
        flex-shrink: 0;
    }

    .search-box {
        padding: 8px 12px;
        background: #fff;
        border-bottom: 1px solid #f0f2f5;
        flex-shrink: 0;
    }

    .search-inner {
        background: #f0f2f5;
        border-radius: 8px;
        padding: 6px 14px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .search-inner input { border: none; background: transparent; width: 100%; outline: none; font-size: 14px; }

    .conversation-pane {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #efe7dd url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
        background-blend-mode: overlay;
        position: relative;
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.35s ease;
    }

    .messages-viewport {
        flex: 1;
        overflow-y: auto;
        padding: 20px 7%;
        display: flex;
        flex-direction: column;
        gap: 6px;
        scrollbar-width: thin;
        scroll-behavior: smooth;
    }

    /* Message Bubble Slide-In Animation */
    .msg-bubble {
        max-width: 70%;
        padding: 8px 10px;
        border-radius: 8px;
        font-size: 14.5px;
        position: relative;
        box-shadow: 0 1px 0.5px rgba(0,0,0,0.13);
        word-wrap: break-word;
        cursor: pointer;
        opacity: 0;
        transform: translateY(15px);
        animation: bubbleFadeIn 0.25s forwards cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    @keyframes bubbleFadeIn {
        to { opacity: 1; transform: translateY(0); }
    }

    .msg-sent { align-self: flex-end; background: #dcf8c6; border-top-right-radius: 0; }
    .msg-received { align-self: flex-start; background: #fff; border-top-left-radius: 0; }

    .msg-info { display: flex; align-items: center; justify-content: flex-end; gap: 4px; font-size: 11px; color: #667781; margin-top: 4px; }

    .input-dock {
        padding: 10px 16px;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
        z-index: 5;
    }

    .input-container { flex: 1; background: #fff; border-radius: 8px; padding: 9px 12px; display: flex; align-items: center; border: 1px solid transparent; transition: 0.2s; }
    .input-container:focus-within { border-color: #00a884; box-shadow: 0 0 5px rgba(0,168,132,0.1); }
    .input-container input { border: none; outline: none; width: 100%; font-size: 15px; }

    /* Moving Status Text */
    .scrolling-status {
        font-size: 12px;
        color: #667781;
        white-space: nowrap;
        overflow: hidden;
        max-width: 200px;
    }
    .is-scrolling { display: inline-block; animation: marquee 10s linear infinite; }
    @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-100%); } }

    /* Mobile Transition */
    @media (max-width: 992px) {
        .contacts-pane { width: 100%; }
        .conversation-pane { 
            position: fixed;
            inset: 0;
            z-index: 100;
            display: none;
            opacity: 0;
            transform: translateX(100%);
        }
        .conversation-pane.active {
            display: flex;
            opacity: 1;
            transform: translateX(0);
            animation: slideInScreen 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        #mobileBackBtn { display: block !important; cursor: pointer; font-size: 20px; margin-right: 15px; color: #54656f; transition: 0.2s; }
        #mobileBackBtn:active { transform: scale(0.8); color: #00a884; }
    }

    @keyframes slideInScreen {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* Message Shake Effect on New */
    .msg-new { animation: msgShake 0.4s ease-in-out; }
    @keyframes msgShake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-2px); }
        75% { transform: translateX(2px); }
    }

    /* Floating UI Elements */
    .ctrl-circle {
        transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .ctrl-circle:hover { transform: scale(1.15) translateY(-5px); }

    /* User Item Animation */
    .user-item {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .user-item:hover { background: #f8fafc !important; padding-left: 20px !important; }
    .user-item::after { 
        content: ''; 
        position: absolute; 
        left: 0; 
        top: 0; 
        height: 100%; 
        width: 4px; 
        background: #00a884; 
        transform: scaleY(0); 
        transition: 0.2s; 
    }
    .user-item:hover::after { transform: scaleY(1); }

    /* Glass Loading Overlay */
    #loadingChat {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(5px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 100;
        flex-direction: column;
        color: #00a884;
    }

</style>
@endsection

@section('content')
<div class="chat-wrapper">
    <!-- Left: Contacts -->
    <div class="contacts-pane" id="contactsPane">
        <div class="pane-header">
            <div style="display:flex; align-items:center; gap:12px;">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=00a884&color=fff" style="width:40px;height:40px;border-radius:50%;">
                <span style="font-weight:600; color:#111b21;">Talk-ga Booliska</span>
            </div>
            <div style="display:flex; gap: 20px; color: #54656f; font-size: 20px;">
                <i class="fa-solid fa-circle-notch fa-spin-hover" style="cursor:pointer;"></i>
                <i class="fa-solid fa-message" style="cursor:pointer;"></i>
                <i class="fa-solid fa-ellipsis-vertical" style="cursor:pointer;"></i>
            </div>
        </div>
        <div class="search-box">
            <div class="search-inner">
                <i class="fa-solid fa-magnifying-glass" style="color: #8696a0;"></i>
                <input type="text" id="userSearch" placeholder="Raadi sarkaalka...">
            </div>
        </div>
        <div id="usersList" style="flex:1; overflow-y:auto;">
            <div style="padding: 20px; text-align: center; color: #8696a0;">
                <i class="fa-solid fa-circle-notch fa-spin"></i> Loading...
            </div>
        </div>
    </div>

    <!-- Right: Conversation -->
    <div class="conversation-pane" id="conversationPane">
        <div id="loadingChat"><i class="fa-solid fa-spinner fa-3x fa-spin"></i><p style="margin-top:10px;">Fariimaha waa la durayaa...</p></div>
        
        <div id="noChatSelected" style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; background:#f8fafc;">
            <img src="https://static.whatsapp.net/rsrc.php/v3/y6/r/wa669ae5zba.png" style="width:300px; opacity:0.8; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.05));">
            <h1 style="color:#41525d; font-weight:300; margin-top:25px; font-size:35px;">Police Secure Connect</h1>
            <p style="color:#8696a0; font-size:15px; max-width:450px; line-height:1.6;">Waa nidaam sugan oo loo dhisay Ciidanka Booliska Soomaaliyeed. <br>Xiriirkaagu waa sir qarsoodi ah (Fully Encrypted).</p>
        </div>

        <div id="activeChat" style="display:none; flex:1; flex-direction:column;">
            <div class="pane-header">
                <div style="display:flex; align-items:center; gap:12px; flex:1;">
                    <i class="fa-solid fa-chevron-left" onclick="showContacts()" style="display:none;" id="mobileBackBtn"></i>
                    <div id="activeUserAvatar" style="transition:0.3s ease;"></div>
                    <div>
                        <div id="activeUserName" style="font-weight:600; color:#111b21;">Officer</div>
                        <div id="activeUserStatus" class="scrolling-status">online</div>
                        <div class="typing-label" id="typingIndicator">is typing...</div>
                    </div>
                </div>
                <div style="display:flex; gap:25px; color:#54656f; font-size:18px;">
                    <i class="fa-solid fa-video floating-icon" onclick="initiateCall('video')" style="cursor:pointer;"></i>
                    <i class="fa-solid fa-phone floating-icon" onclick="initiateCall('audio')" style="cursor:pointer;"></i>
                    <i class="fa-solid fa-ellipsis-vertical" style="cursor:pointer;"></i>
                </div>
            </div>

            <div class="messages-viewport" id="messageArea"></div>

            <emoji-picker id="emojiPicker"></emoji-picker>

            <div class="input-dock">
                <i class="fa-regular fa-face-smile" id="emojiToggle" style="font-size:24px; color:#54656f; cursor:pointer;"></i>
                <label for="fileInput" style="margin:0; cursor:pointer;">
                    <i class="fa-solid fa-paperclip" style="font-size:22px; color:#54656f; transition:0.2s;"></i>
                    <input type="file" id="fileInput" hidden onchange="handleFileSelect(event)">
                </label>
                
                <div class="input-container" id="inputContainer">
                    <input type="text" id="messageInput" placeholder="Quraal halkan ku qor..." onkeyup="handleKeyPress(event)">
                </div>

                <div id="voiceUI" style="display:none; align-items:center; flex:1; padding-left:15px; background:white; border-radius:8px; height:40px;">
                    <div class="voice-pulse"></div>
                    <span id="recordDuration" style="color:#ef4444; font-weight:700;">0:00</span>
                    <i class="fa-solid fa-trash-can" onclick="cancelRecord()" style="margin-left:auto; color:#ef4444; cursor:pointer; padding-right:10px;"></i>
                </div>

                <i class="fa-solid fa-microphone-lines" id="micBtn" onclick="toggleRecording()" style="font-size:22px; color:#54656f; cursor:pointer;"></i>
                <i class="fa-solid fa-circle-arrow-up" id="sendBtn" onclick="sendMessage()" style="font-size:32px; color:#00a884; cursor:pointer; display:none; filter: drop-shadow(0 4px 8px rgba(0,168,132,0.3));"></i>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Deleting -->
<div class="delete-overlay" id="deleteOverlay" onclick="closeDeleteMenu()">
    <div class="delete-menu" onclick="event.stopPropagation()">
        <div style="padding: 15px 20px; font-weight: 700; font-size: 16px; border-bottom: 1px solid #f1f5f9;">Ma huba fariintaan?</div>
        <div class="delete-item" id="deleteForEveryoneBtn">
            <i class="fa-solid fa-trash-can"></i> Ka tirtir qof walba
        </div>
        <div class="delete-item" onclick="closeDeleteMenu()" style="color: #64748b;">
            <i class="fa-solid fa-rotate-left"></i> Iska daa (Cancel)
        </div>
    </div>
</div>

<!-- Call Overlay -->
<div class="call-window" id="callWindow">
    <div class="video-grid" id="videoGrid">
        <video id="remoteVideo" autoplay playsinline></video>
        <video id="localVideo" autoplay playsinline muted></video>
    </div>

    <div id="audioCallUI" style="text-align:center;">
        <div id="callAvatarLarge" style="width:180px; height:180px; border-radius:50%; margin:0 auto 30px; border:6px solid rgba(6, 215, 85, 0.2); padding:10px;">
            <img src="" style="width:100%; height:100%; border-radius:50%; object-fit:cover; border:4px solid #06d755;">
        </div>
        <h2 id="callTargetName" style="font-size:2.8rem; margin-bottom:15px; font-weight:300;">Name</h2>
        <div id="callStatusIndicator" style="display:flex; align-items:center; justify-content:center; gap:10px;">
            <div class="voice-pulse"></div>
            <p id="callStatusMessage" style="font-size:1.4rem; color:#8696a0; margin:0;">Wicitaanku waa socdaa...</p>
        </div>
    </div>

    <div class="call-controls">
        <div class="ctrl-circle ctrl-mute"><i class="fa-solid fa-microphone-slash"></i></div>
        <div class="ctrl-circle ctrl-accept" id="acceptCallBtn" style="display:none;" onclick="answerIncomingCall()"><i class="fa-solid fa-phone"></i></div>
        <div class="ctrl-circle ctrl-decline" onclick="terminateCall()"><i class="fa-solid fa-phone-slash"></i></div>
    </div>
</div>

<audio id="ringSound" loop src="https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3"></audio>

<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@1.21.1/index.js"></script>
<script src="https://unpkg.com/peerjs@1.5.2/dist/peerjs.min.js"></script>

<script>
    const MY_ID = {{ auth()->id() }};
    let currentReceiverId = null;
    let peer = null;
    let localStream = null;
    let activeCallData = null;
    let mediaRecorder = null;
    let audioChunks = [];
    let recordInterval = null;
    let isRecording = false;
    let lastMessageCount = 0;
    let incomingPeerCall = null;
    let targetMessageId = null;

    // --- Sound Effects ---
    const sentSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2354/2354-preview.mp3');
    const receivedSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3');

    // --- Init ---
    document.addEventListener('DOMContentLoaded', () => {
        setupPeer();
        fetchUsers();
        startSync();
        setupEmoji();
        
        document.getElementById('userSearch').addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            const users = document.querySelectorAll('.user-item');
            users.forEach(u => {
                const name = u.getAttribute('data-name').toLowerCase();
                u.style.display = name.includes(term) ? 'flex' : 'none';
            });
        });
    });

    function setupPeer() {
        peer = new Peer('SPD-' + MY_ID);
        peer.on('call', call => { incomingPeerCall = call; });
    }

    // --- Sync & Heartbeat ---
    function startSync() {
        setInterval(fetchUsers, 10000);
        setInterval(heartbeat, 3000);
    }

    async function heartbeat() {
        const input = document.getElementById('messageInput');
        const typing = input && input.value.length > 0;
        const res = await fetch("{{ route('chat.ping') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ is_typing: typing, receiver_id: currentReceiverId })
        }).then(r => r.json());

        const indicator = document.getElementById('typingIndicator');
        const status = document.getElementById('activeUserStatus');
        if(res.is_typing) {
            if(indicator) indicator.style.display = 'block';
            if(status) status.style.display = 'none';
        } else {
            if(indicator) indicator.style.display = 'none';
            if(status) status.style.display = 'block';
        }

        checkCalls();
        if(currentReceiverId) loadMessages();
    }

    async function checkCalls() {
        fetch("{{ route('chat.call.check') }}")
            .then(r => r.json())
            .then(call => {
                if(call && call.status === 'ringing' && !activeCallData) {
                    activeCallData = call;
                    showIncomingCall(call);
                } else if(call && call.status === 'ended' && activeCallData) {
                    terminateCall();
                } else if(call && call.status === 'accepted' && activeCallData && activeCallData.caller_id === MY_ID) {
                    document.getElementById('callStatusMessage').innerText = "WICITAANKU WAA SOCDAA...";
                }
            });
    }

    // --- Users ---
    function fetchUsers() {
        fetch("{{ route('chat.users') }}").then(r => r.json()).then(users => {
            let html = '';
            users.forEach(u => {
                const avatar = u.profile_image ? `<img src="/storage/${u.profile_image}" style="width:50px;height:50px;border-radius:50%; object-fit:cover;">` : 
                              `<div style="width:50px;height:50px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-weight:700; color:#475569;">${u.name.charAt(0)}</div>`;
                html += `
                <div class="user-item" data-name="${u.name}" onclick="openChat(${u.id}, '${u.name}', '${u.profile_image}')" style="display:flex; padding:12px 16px; gap:12px; cursor:pointer; background:${u.id==currentReceiverId ? '#f0f9ff': '#fff'}; border-bottom:1px solid #f1f5f9; transition:0.3s; margin:2px 8px; border-radius:12px;">
                    ${avatar}
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                            <span style="font-weight:600; color:#1e293b; font-size:15px;">${u.name}</span>
                            <span style="font-size:11px; color:#94a3b8;">${u.last_seen || ''}</span>
                        </div>
                        <div style="font-size:12.5px; color:#64748b; display:flex; justify-content:space-between; align-items:center;">
                            <span style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; flex:1;">${u.is_online ? '<span style="color:#10b981; font-weight:600;">online</span>' : 'Riix si aad u bilowdid'}</span>
                            ${u.unread_count > 0 ? `<span style="background:#10b981; color:#fff; border-radius:10px; padding:2px 8px; font-size:11px; font-weight:700;">${u.unread_count}</span>` : ''}
                        </div>
                    </div>
                </div>`;
            });
            document.getElementById('usersList').innerHTML = html;
        });
    }

    // --- Chat Logic ---
    function openChat(id, name, img) {
        if(currentReceiverId === id) return;
        
        document.getElementById('loadingChat').style.display = 'flex';
        currentReceiverId = id;
        lastMessageCount = 0;
        
        document.getElementById('noChatSelected').style.display = 'none';
        document.getElementById('activeChat').style.display = 'flex';
        document.getElementById('conversationPane').classList.add('active');
        document.getElementById('activeUserName').innerText = name;
        
        const avatarHtml = img ? `<img src="/storage/${img}" style="width:42px;height:42px;border-radius:50%; object-fit:cover; border:2px solid #fff; box-shadow:0 2px 5px rgba(0,0,0,0.1);">` : 
                          `<div style="width:42px;height:42px;border-radius:50%;background:#00a884;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;">${name.charAt(0)}</div>`;
        document.getElementById('activeUserAvatar').innerHTML = avatarHtml;

        loadMessages();
        setTimeout(() => { document.getElementById('loadingChat').style.display = 'none'; }, 600);
    }

    function showContacts() {
        document.getElementById('conversationPane').classList.remove('active');
        currentReceiverId = null;
    }

    function loadMessages() {
        if(!currentReceiverId) return;
        fetch(`{{ route('chat.messages') }}?receiver_id=${currentReceiverId}`).then(r => r.json()).then(messages => {
            if(messages.length !== lastMessageCount) {
                const isNew = messages.length > lastMessageCount;
                if(isNew && messages[messages.length - 1].sender_id != MY_ID && lastMessageCount > 0) receivedSound.play();
                lastMessageCount = messages.length;
                renderMessages(messages, isNew);
            }
        });
    }

    function renderMessages(messages, isNew = false) {
        let html = '';
        messages.forEach((m, idx) => {
            const isSent = m.sender_id == MY_ID;
            const time = new Date(m.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
            const isLast = idx === messages.length - 1;
            
            let content = m.message;
            if(m.is_deleted) {
                content = `<span class="msg-deleted"><i class="fa-solid fa-ban"></i> Fariintaan waa la tirtiray</span>`;
            } else {
                if(m.type === 'image') content = `<div class="media-card" style="border-radius:8px; overflow:hidden;"><img src="/storage/${m.file_path}" onclick="window.open(this.src)" style="cursor:zoom-in;"></div>` + (m.message ? `<div style="margin-top:5px;">${m.message}</div>` : '');
                if(m.type === 'video') content = `<div class="media-card" style="border-radius:8px; overflow:hidden;"><video src="/storage/${m.file_path}" controls></video></div>`;
                if(m.type === 'audio') content = `<audio src="/storage/${m.file_path}" controls style="height:35px; width:220px;"></audio>`;
            }

            let status = '<i class="fa-solid fa-check" style="color:#94a3b8"></i>';
            if(m.read_at) status = '<i class="fa-solid fa-check-double" style="color:#38bdf8"></i>';
            else if(m.delivered_at) status = '<i class="fa-solid fa-check-double" style="color:#94a3b8"></i>';

            const actions = (isSent && !m.is_deleted) ? `<div class="msg-actions" onclick="openDeleteMenu(${m.id})"><i class="fa-solid fa-angle-down"></i></div>` : '';

            html += `
            <div class="msg-bubble ${isSent ? 'msg-sent' : 'msg-received'} ${isNew && isLast ? 'msg-new' : ''}" style="animation-delay: ${idx * 0.05}s">
                ${actions}
                ${content}
                <div class="msg-info">
                    <span>${time}</span>
                    ${isSent ? status : ''}
                </div>
            </div>`;
        });
        const box = document.getElementById('messageArea');
        box.innerHTML = html;
        box.scrollTo({ top: box.scrollHeight, behavior: 'smooth' });
    }

    async function sendMessage(file = null) {
        const input = document.getElementById('messageInput');
        const msg = input.value.trim();
        if(!msg && !file) return;

        let fd = new FormData();
        fd.append('receiver_id', currentReceiverId);
        if(msg) fd.append('message', msg);
        if(file) fd.append('file', file);

        sentSound.play();
        await fetch("{{ route('chat.send') }}", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: fd
        });
        input.value = '';
        handleKeyPress();
        loadMessages();
    }

    function handleKeyPress(e) {
        const val = document.getElementById('messageInput').value;
        const mic = document.getElementById('micBtn');
        const send = document.getElementById('sendBtn');
        if(val.length > 0) {
            mic.style.display = 'none';
            send.style.display = 'block';
        } else {
            mic.style.display = 'block';
            send.style.display = 'none';
        }
        if(e && e.key === 'Enter') sendMessage();
    }

    function handleFileSelect(e) { if(e.target.files[0]) sendMessage(e.target.files[0]); }

    // --- Delete Feature ---
    function openDeleteMenu(id) {
        targetMessageId = id;
        document.getElementById('deleteOverlay').style.display = 'flex';
    }
    function closeDeleteMenu() {
        document.getElementById('deleteOverlay').style.display = 'none';
        targetMessageId = null;
    }
    document.getElementById('deleteForEveryoneBtn').onclick = async () => {
        if(!targetMessageId) return;
        await fetch("{{ route('chat.delete') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ message_id: targetMessageId })
        });
        closeDeleteMenu();
        loadMessages();
    };

    // --- Voice Recording ---
    async function toggleRecording() {
        if(!isRecording) {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];
                mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
                mediaRecorder.onstop = () => {
                    const blob = new Blob(audioChunks, { type: 'audio/ogg; codecs=opus' });
                    sendMessage(new File([blob], "voice.ogg", { type: 'audio/ogg' }));
                    stream.getTracks().forEach(t => t.stop());
                };
                mediaRecorder.start();
                isRecording = true;
                document.getElementById('voiceUI').style.display = 'flex';
                document.getElementById('inputContainer').style.display = 'none';
                document.getElementById('micBtn').style.color = '#10b981';
                let start = Date.now();
                recordInterval = setInterval(() => {
                    let ms = Date.now() - start;
                    let s = Math.floor(ms/1000);
                    document.getElementById('recordDuration').innerText = `0:${s.toString().padStart(2, '0')}`;
                }, 1000);
            } catch(e) { alert("Fadlan ogolow microphone-ka."); }
        } else {
            mediaRecorder.stop();
            cancelRecord();
        }
    }

    function cancelRecord() {
        isRecording = false;
        clearInterval(recordInterval);
        document.getElementById('voiceUI').style.display = 'none';
        document.getElementById('inputContainer').style.display = 'flex';
        document.getElementById('micBtn').style.color = '#54656f';
        if(mediaRecorder && mediaRecorder.state !== 'inactive') mediaRecorder.stop();
    }

    async function initiateCall(type) {
        try {
            localStream = await navigator.mediaDevices.getUserMedia({ audio: true, video: type==='video' });
            document.getElementById('callWindow').style.display = 'flex';
            document.getElementById('callTargetName').innerText = document.getElementById('activeUserName').innerText;
            document.getElementById('callAvatarLarge').querySelector('img').src = document.getElementById('activeUserAvatar').querySelector('img')?.src || '';
            document.getElementById('ringSound').play();

            if(type==='video') {
                document.getElementById('videoGrid').style.display = 'block';
                document.getElementById('localVideo').srcObject = localStream;
            }

            const res = await fetch("{{ route('chat.call.initiate') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ receiver_id: currentReceiverId, call_type: type, signal: 'SPD-'+MY_ID })
            }).then(r => r.json());
            activeCallData = res;
        } catch(e) { alert("Fadlan ogolow camera-ka iyo mic-ka."); }
    }

    function showIncomingCall(call) {
        document.getElementById('callWindow').style.display = 'flex';
        document.getElementById('callTargetName').innerText = call.caller.name;
        document.getElementById('callStatusMessage').innerText = "WICITAAN CUSUB...";
        document.getElementById('acceptCallBtn').style.display = 'flex';
        document.getElementById('ringSound').play();
    }

    async function answerIncomingCall() {
        localStream = await navigator.mediaDevices.getUserMedia({ audio: true, video: activeCallData.call_type==='video' });
        document.getElementById('ringSound').pause();
        document.getElementById('acceptCallBtn').style.display = 'none';
        if(activeCallData.call_type==='video') {
            document.getElementById('videoGrid').style.display = 'block';
            document.getElementById('localVideo').srcObject = localStream;
        }

        if(incomingPeerCall) {
            incomingPeerCall.answer(localStream);
            setupCallHandlers(incomingPeerCall);
        } else {
            const call = peer.call(activeCallData.caller_signal, localStream);
            setupCallHandlers(call);
        }

        await fetch("{{ route('chat.call.respond') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ call_id: activeCallData.id, status: 'accepted', signal: 'SPD-'+MY_ID })
        });
    }

    function setupCallHandlers(call) {
        call.on('stream', remoteStream => {
            document.getElementById('remoteVideo').srcObject = remoteStream;
            document.getElementById('callStatusMessage').innerText = "WICITAANKU WAA SOCDAA...";
            document.getElementById('audioCallUI').style.opacity = '0.3';
        });
        call.on('close', terminateCall);
    }

    function terminateCall() {
        if(activeCallData) fetch("{{ route('chat.call.end') }}", { method:"POST", body:JSON.stringify({call_id:activeCallData.id}), headers:{"Content-Type":"application/json","X-CSRF-TOKEN":"{{csrf_token()}}"}});
        if(localStream) localStream.getTracks().forEach(t => t.stop());
        document.getElementById('callWindow').style.display = 'none';
        document.getElementById('ringSound').pause();
        document.getElementById('audioCallUI').style.opacity = '1';
        activeCallData = null;
        incomingPeerCall = null;
        location.reload(); 
    }

    function setupEmoji() {
        const picker = document.querySelector('emoji-picker');
        const toggle = document.getElementById('emojiToggle');
        if(!picker || !toggle) return;
        toggle.onclick = () => { picker.style.display = picker.style.display === 'block' ? 'none' : 'block'; };
        picker.addEventListener('emoji-click', e => {
            document.getElementById('messageInput').value += e.detail.unicode;
            handleKeyPress();
        });
        document.addEventListener('click', (e) => {
            if(!picker.contains(e.target) && e.target !== toggle) picker.style.display = 'none';
        });
    }
</script>
@endsection
