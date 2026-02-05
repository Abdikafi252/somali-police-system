@extends('layouts.app')

@section('title', 'WhatsApp Police Clone')

@section('css')
<style>
    /* Premium WhatsApp Redesign - Full Mobile Responsiveness */
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
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .messages-viewport {
        flex: 1;
        overflow-y: auto;
        padding: 20px 7%;
        display: flex;
        flex-direction: column;
        gap: 4px;
        scrollbar-width: thin;
    }

    .msg-bubble {
        max-width: 65%;
        padding: 6px 7px 8px 9px;
        border-radius: 8px;
        font-size: 14.5px;
        position: relative;
        box-shadow: 0 1px 0.5px rgba(0,0,0,0.13);
        word-wrap: break-word;
        cursor: pointer;
        transition: 0.2s;
    }

    .msg-sent { align-self: flex-end; background: #dcf8c6; border-top-right-radius: 0; }
    .msg-received { align-self: flex-start; background: #fff; border-top-left-radius: 0; }

    .msg-info { display: flex; align-items: center; justify-content: flex-end; gap: 4px; font-size: 11px; color: #667781; margin-top: 2px; }
    .status-icon { font-size: 14px; color: #53bdeb; }

    .input-dock {
        padding: 10px 16px;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .input-container { flex: 1; background: #fff; border-radius: 8px; padding: 9px 12px; display: flex; align-items: center; }
    .input-container input { border: none; outline: none; width: 100%; font-size: 15px; }

    /* Call UI Overlay */
    .call-window {
        position: fixed;
        inset: 0;
        background: radial-gradient(circle at center, #111b21 0%, #0b141a 100%);
        z-index: 9999;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    .video-grid {
        position: relative;
        width: 100%;
        height: 100%;
        display: none;
    }
    #remoteVideo { width: 100%; height: 100%; object-fit: cover; }
    #localVideo {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 120px;
        height: 160px;
        border-radius: 12px;
        border: 2px solid rgba(255,255,255,0.3);
        background: #000;
        object-fit: cover;
    }

    /* Delete Menu Overlay */
    .delete-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.1);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 20;
    }
    .delete-menu {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        padding: 12px 0;
        width: 200px;
    }
    .delete-item {
        padding: 10px 20px;
        cursor: pointer;
        transition: 0.2s;
        font-size: 14px;
        color: #ef4444;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .delete-item:hover { background: #f8fafc; }

    /* Mobile Interaction Fixes */
    @media (max-width: 992px) {
        .contacts-pane { width: 100%; }
        .conversation-pane { 
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 100;
            display: none;
        }
        .conversation-pane.active {
            display: flex;
            animation: slideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #mobileBackBtn { display: block !important; cursor: pointer; font-size: 20px; margin-right: 15px; color: #54656f; }
    }

    @keyframes slideIn {
        from { transform: translateX(100%); }
        to { transform: translateX(0); }
    }

    /* Message Item Hover Actions */
    .msg-bubble:hover .msg-actions { opacity: 1; }
    .msg-actions {
        position: absolute;
        top: 4px;
        right: 4px;
        opacity: 0;
        transition: 0.2s;
        background: rgba(255,255,255,0.9);
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #667781;
    }

    .msg-deleted { font-style: italic; color: #8696a0 !important; }

    /* Custom Scrollbar */
    .messages-viewport::-webkit-scrollbar { width: 6px; }
    .messages-viewport::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }

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
                <i class="fa-solid fa-circle-notch" style="cursor:pointer;" title="Status"></i>
                <i class="fa-solid fa-message" style="cursor:pointer;" title="New Chat"></i>
                <i class="fa-solid fa-ellipsis-vertical" style="cursor:pointer;" title="Menu"></i>
            </div>
        </div>
        <div class="search-box">
            <div class="search-inner">
                <i class="fa-solid fa-magnifying-glass" style="color: #8696a0;"></i>
                <input type="text" id="userSearch" placeholder="Raadi sarkaalka ama lambarka...">
            </div>
        </div>
        <div id="usersList" style="flex:1; overflow-y:auto;">
            <!-- Rendered via JS -->
            <div style="padding: 20px; text-align: center; color: #8696a0;">
                <i class="fa-solid fa-spinner fa-spin"></i> Loading contacts...
            </div>
        </div>
    </div>

    <!-- Right: Conversation -->
    <div class="conversation-pane" id="conversationPane">
        <div id="noChatSelected" style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; background:#f0f2f5;">
            <div style="position:relative; width:400px; max-width:90%;">
                <img src="https://static.whatsapp.net/rsrc.php/v3/y6/r/wa669ae5zba.png" style="width:100%;">
            </div>
            <h1 style="color:#41525d; font-weight:300; margin-top:20px; font-size:32px;">Talk-ga Booliska Soomaaliyeed</h1>
            <p style="color:#667781; font-size:14px; padding:0 20px; max-width:500px; line-height:20px;">Xagaagan waxaad kula xiriiraysaa dhammaan saraakiisha iyo saldhigyada si sugan oo qarsoodi ah. <br>Dhammaan fariimaha waa kuwo sir ah (End-to-end encrypted).</p>
            <div style="margin-top:auto; padding-bottom:40px; color:#8696a0; font-size:12px;">
                <i class="fa-solid fa-lock"></i> Dhammaan xogtaadu waa mid sugan
            </div>
        </div>

        <div id="activeChat" style="display:none; flex:1; flex-direction:column;">
            <div class="pane-header">
                <div style="display:flex; align-items:center; gap:12px; flex:1;">
                    <i class="fa-solid fa-arrow-left" onclick="showContacts()" style="display:none;" id="mobileBackBtn"></i>
                    <div id="activeUserAvatar"></div>
                    <div style="cursor:pointer;" onclick="showUserDetails()">
                        <div id="activeUserName" style="font-weight:600; color:#111b21;">Select Officer</div>
                        <div id="activeUserStatus" style="font-size:12px; color:#667781;">...</div>
                        <div class="typing-label" id="typingIndicator">is typing...</div>
                    </div>
                </div>
                <div style="display:flex; gap:25px; color:#54656f; font-size:18px;">
                    <i class="fa-solid fa-video" onclick="initiateCall('video')" style="cursor:pointer;" title="Video Call"></i>
                    <i class="fa-solid fa-phone" onclick="initiateCall('audio')" style="cursor:pointer;" title="Audio Call"></i>
                    <i class="fa-solid fa-magnifying-glass" style="cursor:pointer;" title="Search messages"></i>
                    <i class="fa-solid fa-ellipsis-vertical" style="cursor:pointer;" title="Chat info"></i>
                </div>
            </div>

            <div class="messages-viewport" id="messageArea"></div>

            <emoji-picker id="emojiPicker"></emoji-picker>

            <div class="input-dock">
                <i class="fa-regular fa-face-smile" id="emojiToggle" style="font-size:24px; color:#54656f; cursor:pointer;" title="Emojis"></i>
                <label for="fileInput" style="margin:0; cursor:pointer;" title="Attach media">
                    <i class="fa-solid fa-plus" style="font-size:20px; color:#54656f;"></i>
                    <input type="file" id="fileInput" hidden onchange="handleFileSelect(event)">
                </label>
                
                <div class="input-container" id="inputContainer">
                    <input type="text" id="messageInput" placeholder="Qor fariin..." onkeyup="handleKeyPress(event)" autocomplete="off">
                </div>

                <div id="voiceUI" style="display:none; align-items:center; flex:1; padding-left:15px;">
                    <div class="voice-pulse"></div>
                    <span id="recordDuration">0:00</span>
                    <i class="fa-solid fa-trash" onclick="cancelRecord()" style="margin-left:auto; color:#ea0038; cursor:pointer;" title="Cancel recording"></i>
                </div>

                <i class="fa-solid fa-microphone" id="micBtn" onclick="toggleRecording()" style="font-size:22px; color:#54656f; cursor:pointer;" title="Voice Message"></i>
                <i class="fa-solid fa-paper-plane" id="sendBtn" onclick="sendMessage()" style="font-size:22px; color:#00a884; cursor:pointer; display:none;" title="Send"></i>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Deleting -->
<div class="delete-overlay" id="deleteOverlay" onclick="closeDeleteMenu()">
    <div class="delete-menu" onclick="event.stopPropagation()">
        <div style="padding: 10px 20px; font-weight: 700; font-size: 16px; border-bottom: 1px solid #eee; margin-bottom: 8px;">Fariinta tirtir?</div>
        <div class="delete-item" id="deleteForEveryoneBtn">
            <i class="fa-solid fa-trash-can"></i> Ka tirtir dhammaan (Everyone)
        </div>
        <div class="delete-item" onclick="closeDeleteMenu()" style="color: #64748b;">
            <i class="fa-solid fa-xmark"></i> Iska daa (Cancel)
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
        <div id="callAvatarLarge" style="width:160px; height:160px; border-radius:50%; margin-bottom:20px; border:4px solid #06d755; padding:5px; box-shadow: 0 0 20px rgba(6, 215, 85, 0.4);">
            <img src="" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
        </div>
        <h2 id="callTargetName" style="font-size:2.5rem; margin-bottom:10px; font-weight:300;">Name</h2>
        <p id="callStatusMessage" style="font-size:1.2rem; color:#8696a0; letter-spacing: 1px;">WICITAAN AYAA SOCDA...</p>
    </div>

    <div class="call-controls">
        <div class="ctrl-circle ctrl-mute" title="Mute Microphone"><i class="fa-solid fa-microphone-slash"></i></div>
        <div class="ctrl-circle ctrl-accept" id="acceptCallBtn" style="display:none;" onclick="answerIncomingCall()" title="Answer"><i class="fa-solid fa-phone"></i></div>
        <div class="ctrl-circle ctrl-decline" onclick="terminateCall()" title="Hang Up"><i class="fa-solid fa-phone-slash"></i></div>
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
        
        // Mobile Search Fix
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
        peer.on('call', call => {
            incomingPeerCall = call;
            // The actual checkCalls() will show the UI based on DB 'ringing' status
        });
    }

    // --- Sync & Heartbeat ---
    function startSync() {
        setInterval(fetchUsers, 10000);
        setInterval(heartbeat, 3000);
    }

    async function heartbeat() {
        const typing = document.getElementById('messageInput').value.length > 0;
        const res = await fetch("{{ route('chat.ping') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ is_typing: typing, receiver_id: currentReceiverId })
        }).then(r => r.json());

        const indicator = document.getElementById('typingIndicator');
        if(res.is_typing) {
            indicator.style.display = 'block';
            document.getElementById('activeUserStatus').style.display = 'none';
        } else {
            indicator.style.display = 'none';
            document.getElementById('activeUserStatus').style.display = 'block';
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
                const avatar = u.profile_image ? `<img src="/storage/${u.profile_image}" style="width:49px;height:49px;border-radius:50%; object-fit:cover;">` : 
                              `<div style="width:49px;height:49px;border-radius:50%;background:#dfe5e7;display:flex;align-items:center;justify-content:center;font-weight:700; color:#51616a;">${u.name.charAt(0)}</div>`;
                html += `
                <div class="user-item" data-name="${u.name}" onclick="openChat(${u.id}, '${u.name}', '${u.profile_image}')" style="display:flex; padding:12px 16px; gap:12px; cursor:pointer; background:${u.id==currentReceiverId ? '#f0f2f5': '#fff'}; border-bottom:1px solid #f2f2f2; transition:0.2s;">
                    ${avatar}
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-weight:500; color:#111b21;">${u.name}</span>
                            <span style="font-size:12px; color:#667781;">${u.last_seen || ''}</span>
                        </div>
                        <div style="font-size:13px; color:#667781; display:flex; justify-content:space-between; align-items:center;">
                            <span style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; flex:1;">${u.is_online ? '<span style="color:#06d755">online</span>' : 'Riix si aad u bilowdid'}</span>
                            ${u.unread_count > 0 ? `<span style="background:#06d755; color:#fff; border-radius:12px; padding:2px 8px; font-size:11px; font-weight:700; margin-left:8px;">${u.unread_count}</span>` : ''}
                        </div>
                    </div>
                </div>`;
            });
            document.getElementById('usersList').innerHTML = html;
        });
    }

    // --- Chat Logic ---
    function openChat(id, name, img) {
        currentReceiverId = id;
        lastMessageCount = 0; // Reset count
        document.getElementById('noChatSelected').style.display = 'none';
        document.getElementById('activeChat').style.display = 'flex';
        document.getElementById('conversationPane').classList.add('active');
        document.getElementById('activeUserName').innerText = name;
        
        const avatarHtml = img ? `<img src="/storage/${img}" style="width:40px;height:40px;border-radius:50%; object-fit:cover;">` : 
                          `<div style="width:40px;height:40px;border-radius:50%;background:#00a884;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;">${name.charAt(0)}</div>`;
        document.getElementById('activeUserAvatar').innerHTML = avatarHtml;

        loadMessages();
    }

    function showContacts() {
        document.getElementById('conversationPane').classList.remove('active');
        currentReceiverId = null;
    }

    function loadMessages() {
        if(!currentReceiverId) return;
        fetch(`{{ route('chat.messages') }}?receiver_id=${currentReceiverId}`).then(r => r.json()).then(messages => {
            if(messages.length > lastMessageCount) {
                const lastMsg = messages[messages.length - 1];
                if(lastMsg.sender_id != MY_ID && lastMessageCount > 0) receivedSound.play();
                lastMessageCount = messages.length;
                renderMessages(messages);
            } else if (messages.length < lastMessageCount) {
                // Someone deleted something
                lastMessageCount = messages.length;
                renderMessages(messages);
            }
        });
    }

    function renderMessages(messages) {
        let html = '';
        messages.forEach(m => {
            const isSent = m.sender_id == MY_ID;
            const time = new Date(m.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
            
            let content = m.message;
            if(m.is_deleted) {
                content = `<span class="msg-deleted"><i class="fa-solid fa-ban"></i> Fariintaan waa la tirtiray</span>`;
            } else {
                if(m.type === 'image') content = `<div class="media-card"><img src="/storage/${m.file_path}" onclick="window.open(this.src)"></div>` + (m.message ? `<div>${m.message}</div>` : '');
                if(m.type === 'video') content = `<div class="media-card"><video src="/storage/${m.file_path}" controls></video></div>`;
                if(m.type === 'audio') content = `<audio src="/storage/${m.file_path}" controls style="height:35px; width:220px;"></audio>`;
            }

            let status = '<i class="fa-solid fa-check" style="color:#8696a0"></i>';
            if(m.read_at) status = '<i class="fa-solid fa-check-double" style="color:#53bdeb"></i>';
            else if(m.delivered_at) status = '<i class="fa-solid fa-check-double" style="color:#8696a0"></i>';

            const actions = (isSent && !m.is_deleted) ? `<div class="msg-actions" onclick="openDeleteMenu(${m.id})"><i class="fa-solid fa-chevron-down"></i></div>` : '';

            html += `
            <div class="msg-bubble ${isSent ? 'msg-sent' : 'msg-received'}">
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
        box.scrollTop = box.scrollHeight;
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
        document.getElementById('micBtn').style.display = val.length > 0 ? 'none' : 'block';
        document.getElementById('sendBtn').style.display = val.length > 0 ? 'block' : 'none';
        if(e && e.key === 'Enter') sendMessage();
    }

    function handleFileSelect(e) {
        if(e.target.files[0]) sendMessage(e.target.files[0]);
    }

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
                document.getElementById('micBtn').style.color = '#06d755';
                let start = Date.now();
                recordInterval = setInterval(() => {
                    let ms = Date.now() - start;
                    let s = Math.floor(ms/1000);
                    document.getElementById('recordDuration').innerText = `0:${s.toString().padStart(2, '0')}`;
                }, 1000);
            } catch(e) { alert("Fadlan ogolow microphone-ka si aad codka u dirto."); }
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

    // --- Calls ---
    async function initiateCall(type) {
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

    // --- Emoji ---
    function setupEmoji() {
        const picker = document.querySelector('emoji-picker');
        const toggle = document.getElementById('emojiToggle');
        toggle.onclick = () => {
            picker.style.display = picker.style.display === 'block' ? 'none' : 'block';
        };
        picker.addEventListener('emoji-click', e => {
            document.getElementById('messageInput').value += e.detail.unicode;
            handleKeyPress();
        });
        // Close on blur
        document.addEventListener('click', (e) => {
            if(!picker.contains(e.target) && e.target !== toggle) picker.style.display = 'none';
        });
    }
</script>
@endsection
