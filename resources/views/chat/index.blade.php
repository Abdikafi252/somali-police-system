@extends('layouts.app')

@section('title', 'WhatsApp Police Clone')

@section('css')
<style>
    /* Premium WhatsApp Redesign - Final Polish & Advanced Animation */
    :root {
        --wa-bg: #efe7dd;
        --wa-header: rgba(240, 242, 245, 0.95);
        --wa-sidebar: #ffffff;
        --wa-green: #00a884;
        --wa-bubble-sent: #dcf8c6;
        --wa-bubble-received: #ffffff;
        --wa-transition: cubic-bezier(0.4, 0, 0.2, 1);
    }

    .chat-wrapper {
        display: flex;
        height: calc(100vh - 120px);
        margin: -1rem;
        background: #f0f2f5;
        position: relative;
        overflow: hidden;
        font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
    }

    /* Contacts Pane */
    .contacts-pane {
        width: 380px;
        border-right: 1px solid #d1d7db;
        display: flex;
        flex-direction: column;
        background: var(--wa-sidebar);
        z-index: 100;
        transition: transform 0.4s var(--wa-transition), width 0.4s var(--wa-transition);
    }

    .pane-header {
        height: 60px;
        padding: 10px 16px;
        background: var(--wa-header);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #d1d7db;
        flex-shrink: 0;
        z-index: 10;
        position: sticky;
        top: 0;
    }

    /* Conversation Pane - "The Body that Moves" */
    .conversation-pane {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: var(--wa-bg) url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
        background-blend-mode: overlay;
        position: relative;
        overflow: hidden;
    }

    .messages-viewport {
        flex: 1;
        overflow-y: auto;
        padding: 20px 7% 40px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        scroll-behavior: smooth;
        scrollbar-width: thin;
    }

    /* Authentic WhatsApp Message Bubbles */
    .msg-bubble {
        max-width: 70%;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 14.5px;
        position: relative;
        box-shadow: 0 1px 0.5px rgba(0,0,0,0.13);
        word-wrap: break-word;
        opacity: 0;
        transform: translateY(20px);
        animation: bubblePop 0.3s forwards var(--wa-transition);
        cursor: pointer;
        transition: transform 0.2s;
    }
    .msg-bubble:hover { transform: scale(1.01); }

    @keyframes bubblePop {
        to { opacity: 1; transform: translateY(0); }
    }

    .msg-sent { align-self: flex-end; background: var(--wa-bubble-sent); border-top-right-radius: 0; }
    .msg-sent::after {
        content: ""; position: absolute; top: 0; right: -8px; width: 0; height: 0;
        border: 8px solid transparent; border-left-color: var(--wa-bubble-sent); border-top-color: var(--wa-bubble-sent);
    }

    .msg-received { align-self: flex-start; background: var(--wa-bubble-received); border-top-left-radius: 0; }
    .msg-received::after {
        content: ""; position: absolute; top: 0; left: -8px; width: 0; height: 0;
        border: 8px solid transparent; border-right-color: var(--wa-bubble-received); border-top-color: var(--wa-bubble-received);
    }

    .msg-info { display: flex; align-items: center; justify-content: flex-end; gap: 4px; font-size: 11px; color: #667781; margin-top: 4px; }

    /* Sticky Date Divider */
    .date-divider {
        align-self: center;
        background: #ffffff;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        color: #54656f;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        margin: 15px 0;
        position: sticky;
        top: 10px;
        z-index: 50;
        font-weight: 600;
        text-transform: uppercase;
    }

    /* Floating Input Bar */
    .input-dock {
        padding: 10px 16px;
        background: var(--wa-header);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
        border-top: 1px solid #d1d7db;
    }

    .input-container { 
        flex: 1; background: #fff; border-radius: 20px; padding: 6px 16px; 
        display: flex; align-items: center; box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .input-container input { border: none; outline: none; width: 100%; font-size: 15px; height: 32px; }

    /* Delete Context Menu */
    .delete-overlay {
        position: absolute; inset: 0; background: rgba(0,0,0,0.2);
        display: none; align-items: center; justify-content: center; z-index: 500;
    }
    .delete-menu {
        background: #fff; border-radius: 12px; padding: 10px 0; width: 220px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2); border: 1px solid #eee;
    }
    .delete-item {
        padding: 12px 20px; cursor: pointer; color: #ef4444; font-size: 14px;
        display: flex; align-items: center; gap: 12px; transition: 0.2s;
    }
    .delete-item:hover { background: #f8fafc; }

    /* Scroll Bottom FAB */
    .scroll-btn {
        position: absolute; bottom: 85px; right: 25px; width: 42px; height: 42px;
        background: #fff; border-radius: 50%; display: none; align-items: center;
        justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.2); cursor: pointer;
        z-index: 20; color: #54656f; transition: transform 0.3s;
    }
    .scroll-btn:hover { transform: scale(1.1); color: var(--wa-green); }

    /* Animations & Transitions */
    .chat-loading {
        position: absolute; inset: 0; background: rgba(255,255,255,0.7);
        backdrop-filter: blur(15px); display: none; align-items: center;
        justify-content: center; flex-direction: column; z-index: 2000;
    }

    #typingIndicator { color: var(--wa-green); font-size: 12px; font-weight: 600; display: none; }

    @media (max-width: 992px) {
        .contacts-pane { width: 100%; }
        .contacts-pane.hidden { transform: translateX(-100%); width: 0; }
        .conversation-pane { position: absolute; inset: 0; transform: translateX(100%); z-index: 100; }
        .conversation-pane.active { transform: translateX(0); }
        #mobileBackBtn { display: block !important; margin-right: 15px; }
    }
</style>
@endsection

@section('content')
<div class="chat-wrapper">
    <!-- Left: Contacts Sidebar -->
    <div class="contacts-pane" id="contactsPane">
        <div class="pane-header">
            <div style="display:flex; align-items:center; gap:12px;">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=00a884&color=fff" style="width:40px;height:40px;border-radius:50%;">
                <span style="font-weight:700; color:#111b21;">Talk-ga Booliska</span>
            </div>
            <div style="display:flex; gap: 20px; color: #54656f; font-size: 18px;">
                <i class="fa-solid fa-rotate-right" style="cursor:pointer;" onclick="fetchUsers()"></i>
                <i class="fa-solid fa-message"></i>
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </div>
        </div>
        <div class="search-box">
            <div class="search-inner">
                <i class="fa-solid fa-magnifying-glass" style="color: #8696a0;"></i>
                <input type="text" id="userSearch" placeholder="Raadi sarkaalka...">
            </div>
        </div>
        <div id="usersList" style="flex:1; overflow-y:auto;">
            <!-- Contacts Injected via JS -->
        </div>
    </div>

    <!-- Right: Conversation View -->
    <div class="conversation-pane" id="conversationPane">
        <div class="chat-loading" id="chatLoading">
            <i class="fa-solid fa-circle-notch fa-spin fa-3x" style="color:var(--wa-green)"></i>
            <p style="margin-top:20px; color:#54656f; font-weight:600;">Soo loading-garaynaya...</p>
        </div>

        <!-- Placeholder -->
        <div id="noChatSelected" style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center;">
            <img src="https://static.whatsapp.net/rsrc.php/v3/y6/r/wa669ae5zba.png" style="width:350px; opacity:0.8;">
            <h1 style="color:#41525d; font-weight:300; margin-top:20px; font-size:32px;">Booliska Soomaaliyeed</h1>
            <p style="color:#667781; font-size:14px; max-width:560px; line-height:22px; padding:0 30px;">Ku xirnow saraakiisha kale adigoo isticmaalaya nidaam sugan oo qarsoodi ah. <br>Fariimahaagu waa kuwo sir u ah adiga iyo qofka aad la hadlaysid.</p>
            <div style="margin-top:auto; padding-bottom:40px; color:#8696a0; font-size:12px;">
                <i class="fa-solid fa-lock"></i> End-to-end encrypted
            </div>
        </div>

        <!-- Active Chat -->
        <div id="activeChat" style="display:none; flex:1; flex-direction:column;">
            <div class="pane-header">
                <div style="display:flex; align-items:center; gap:12px; flex:1;">
                    <i class="fa-solid fa-arrow-left" onclick="showContacts()" style="display:none; cursor:pointer;" id="mobileBackBtn"></i>
                    <div id="activeUserAvatar"></div>
                    <div>
                        <div id="activeUserName" style="font-weight:600; color:#111b21; font-size:16px;">Name</div>
                        <div id="activeUserStatus" style="font-size:12px; color:#667781;">online</div>
                        <div id="typingIndicator">is typing...</div>
                    </div>
                </div>
                <div style="display:flex; gap:25px; color:#54656f; font-size:18px;">
                    <i class="fa-solid fa-video" onclick="initiateCall('video')" style="cursor:pointer;" title="Video Call"></i>
                    <i class="fa-solid fa-phone" onclick="initiateCall('audio')" style="cursor:pointer;" title="Audio Call"></i>
                    <i class="fa-solid fa-ellipsis-vertical" style="cursor:pointer;"></i>
                </div>
            </div>

            <div class="messages-viewport" id="messageArea"></div>
            
            <div class="scroll-btn" id="scrollBottomBtn" onclick="scrollToBottom()">
                <i class="fa-solid fa-chevron-down"></i>
                <span id="unreadBadge" style="position:absolute; top:-5px; right:-5px; background:var(--wa-green); color:#fff; border-radius:50%; width:18px; height:18px; font-size:10px; display:none; align-items:center; justify-content:center;">0</span>
            </div>

            <emoji-picker id="emojiPicker" style="display:none; position:absolute; bottom:70px; left:20px; z-index:100;"></emoji-picker>

            <div class="input-dock">
                <i class="fa-regular fa-face-smile" id="emojiToggle" style="font-size:24px; color:#54656f; cursor:pointer;"></i>
                <label for="fileInput" style="margin:0; cursor:pointer;">
                    <i class="fa-solid fa-paperclip" style="font-size:20px; color:#54656f;"></i>
                    <input type="file" id="fileInput" hidden onchange="handleFileSelect(event)">
                </label>
                
                <div class="input-container" id="inputContainer">
                    <input type="text" id="messageInput" placeholder="Quraal halkan ku qor..." onkeyup="handleKeyPress(event)" autocomplete="off">
                </div>

                <div id="voiceUI" style="display:none; align-items:center; flex:1; padding:0 15px; background:#fff; border-radius:30px; height:44px; box-shadow:0 1px 1px rgba(0,0,0,0.1);">
                    <div class="voice-pulse" style="width:10px; height:10px; background:#ef4444; border-radius:50%; margin-right:10px; animation: pulse 1s infinite;"></div>
                    <span id="recordDuration" style="color:#ef4444; font-weight:700; font-size:14px;">0:00</span>
                    <i class="fa-solid fa-trash-can" onclick="cancelRecord()" style="margin-left:auto; color:#ef4444; cursor:pointer; font-size:18px;"></i>
                </div>

                <i class="fa-solid fa-microphone" id="micBtn" onclick="toggleRecording()" style="font-size:22px; color:#54656f; cursor:pointer;"></i>
                <i class="fa-solid fa-paper-plane" id="sendBtn" onclick="sendMessage()" style="font-size:24px; color:var(--wa-green); cursor:pointer; display:none;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Delete Overlay -->
<div class="delete-overlay" id="deleteOverlay" onclick="closeDeleteMenu()">
    <div class="delete-menu" onclick="event.stopPropagation()">
        <div style="padding: 15px 20px; font-weight: 700; border-bottom: 1px solid #f0f2f5;">Fariintaan ma tirtirtaa?</div>
        <div class="delete-item" id="deleteForEveryoneBtn">
            <i class="fa-solid fa-trash-can"></i> Ka tirtir qof walba
        </div>
        <div class="delete-item" onclick="closeDeleteMenu()" style="color: #667781;">
            <i class="fa-solid fa-x"></i> Iska daa (Cancel)
        </div>
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
    let targetMsgId = null;

    const sentSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2354/2354-preview.mp3');
    const receivedSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3');

    document.addEventListener('DOMContentLoaded', () => {
        setupPeer();
        fetchUsers();
        startSync();
        setupEmoji();

        // Search
        document.getElementById('userSearch').addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.user-item').forEach(u => {
                const name = u.getAttribute('data-name').toLowerCase();
                u.style.display = name.includes(term) ? 'flex' : 'none';
            });
        });

        // Scroll
        document.getElementById('messageArea').addEventListener('scroll', (e) => {
            const btn = document.getElementById('scrollBottomBtn');
            const total = e.target.scrollHeight - e.target.clientHeight;
            if(total - e.target.scrollTop > 400) btn.style.display = 'flex';
            else btn.style.display = 'none';
        });
    });

    function setupPeer() {
        peer = new Peer('SPD-' + MY_ID);
        peer.on('call', call => { incomingPeerCall = call; });
    }

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
                    location.reload(); // Quick reset for call state if needed or show UI
                }
            });
    }

    function fetchUsers() {
        fetch("{{ route('chat.users') }}").then(r => r.json()).then(users => {
            let html = '';
            users.forEach(u => {
                const avatar = u.profile_image ? `/storage/${u.profile_image}` : `https://ui-avatars.com/api/?name=${u.name}&background=00a884&color=fff`;
                html += `
                <div class="user-item" data-name="${u.name}" onclick="openChat(${u.id}, '${u.name}', '${avatar}')" style="display:flex; padding:12px 16px; gap:12px; cursor:pointer; background:${u.id==currentReceiverId ? '#f0f2f5': '#fff'}; border-bottom:1px solid #f2f2f2; transition:0.2s;">
                    <img src="${avatar}" style="width:49px;height:49px;border-radius:50%; object-fit:cover;">
                    <div style="flex:1; min-width:0;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="font-weight:600; font-size:15px; color:#111b21;">${u.name}</span>
                            <span style="font-size:11px; color:#667781;">${u.last_seen || ''}</span>
                        </div>
                        <div style="font-size:13px; color:#667781; display:flex; justify-content:space-between; align-items:center;">
                            <span style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; flex:1;">${u.is_online ? '<span style="color:#00a884; font-weight:700;">online</span>' : 'Riix si aad u bilowdid'}</span>
                            ${u.unread_count > 0 ? `<span style="background:#00a884; color:#fff; border-radius:50%; width:20px; height:20px; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700;">${u.unread_count}</span>` : ''}
                        </div>
                    </div>
                </div>`;
            });
            document.getElementById('usersList').innerHTML = html;
        });
    }

    function openChat(id, name, avatarUrl) {
        if(currentReceiverId === id) return;
        document.getElementById('chatLoading').style.display = 'flex';
        currentReceiverId = id;
        lastMessageCount = 0;
        
        document.getElementById('noChatSelected').style.display = 'none';
        document.getElementById('activeChat').style.display = 'flex';
        document.getElementById('conversationPane').classList.add('active');
        document.getElementById('activeUserName').innerText = name;
        document.getElementById('activeUserAvatar').innerHTML = `<img src="${avatarUrl}" style="width:40px;height:40px;border-radius:50%; object-fit:cover;">`;

        if(window.innerWidth <= 992) document.getElementById('contactsPane').classList.add('hidden');

        loadMessages();
        setTimeout(() => { document.getElementById('chatLoading').style.display = 'none'; }, 600);
    }

    function showContacts() {
        document.getElementById('contactsPane').classList.remove('hidden');
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
                renderMessages(messages);
            }
        });
    }

    function renderMessages(messages) {
        let html = '';
        let lastDate = null;
        messages.forEach(m => {
            const mDate = new Date(m.created_at).toLocaleDateString();
            if(mDate !== lastDate) {
                const label = mDate === new Date().toLocaleDateString() ? 'MAANTA' : (mDate === new Date(Date.now() - 86400000).toLocaleDateString() ? 'SHALAY' : mDate);
                html += `<div class="date-divider">${label}</div>`;
                lastDate = mDate;
            }

            const isSent = m.sender_id == MY_ID;
            const time = new Date(m.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
            
            let content = m.message;
            if(m.is_deleted) {
                content = `<span style="font-style:italic; color:#8696a0;"><i class="fa-solid fa-ban"></i> Fariintaan waa la tirtiray</span>`;
            } else {
                if(m.type === 'image') content = `<div style="border-radius:8px; overflow:hidden; max-width:280px;"><img src="/storage/${m.file_path}" onclick="window.open(this.src)" style="width:100%; cursor:zoom-in;"></div>` + (m.message ? `<div style="padding-top:5px;">${m.message}</div>` : '');
                if(m.type === 'video') content = `<div style="border-radius:8px; overflow:hidden; max-width:300px;"><video src="/storage/${m.file_path}" controls style="width:100%"></video></div>`;
                if(m.type === 'audio') content = `<audio src="/storage/${m.file_path}" controls style="height:35px; width:220px;"></audio>`;
            }

            let status = isSent ? (m.read_at ? '<i class="fa-solid fa-check-double" style="color:#53bdeb"></i>' : (m.delivered_at ? '<i class="fa-solid fa-check-double" style="color:#8696a0"></i>' : '<i class="fa-solid fa-check" style="color:#8696a0"></i>')) : '';

            html += `
            <div class="msg-bubble ${isSent ? 'msg-sent' : 'msg-received'}" oncontextmenu="event.preventDefault(); ${isSent && !m.is_deleted ? `openDeleteMenu(${m.id})` : ''}">
                ${content}
                <div class="msg-info">
                    <span>${time}</span>
                    ${status}
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

    function handleFileSelect(e) { if(e.target.files[0]) sendMessage(e.target.files[0]); }
    function scrollToBottom() { const box = document.getElementById('messageArea'); box.scrollTop = box.scrollHeight; }

    function openDeleteMenu(id) { targetMsgId = id; document.getElementById('deleteOverlay').style.display = 'flex'; }
    function closeDeleteMenu() { document.getElementById('deleteOverlay').style.display = 'none'; targetMsgId = null; }
    document.getElementById('deleteForEveryoneBtn').onclick = async () => {
        if(!targetMsgId) return;
        await fetch("{{ route('chat.delete') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ message_id: targetMsgId })
        });
        closeDeleteMenu();
        loadMessages();
    };

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
                document.getElementById('micBtn').style.color = '#ef4444';
                let start = Date.now();
                recordInterval = setInterval(() => {
                    let ms = Date.now() - start;
                    let s = Math.floor(ms/1000);
                    document.getElementById('recordDuration').innerText = `0:${s.toString().padStart(2, '0')}`;
                }, 1000);
            } catch(e) { alert("Mic error."); }
        } else {
            mediaRecorder.stop();
            cancelRecord();
        }
    }

    function cancelRecord() {
        isRecording = false; clearInterval(recordInterval);
        document.getElementById('voiceUI').style.display = 'none';
        document.getElementById('inputContainer').style.display = 'flex';
        document.getElementById('micBtn').style.color = '#54656f';
        if(mediaRecorder && mediaRecorder.state !== 'inactive') mediaRecorder.stop();
    }

    function setupEmoji() {
        const picker = document.querySelector('emoji-picker');
        const toggle = document.getElementById('emojiToggle');
        if(!picker || !toggle) return;
        toggle.onclick = (e) => { e.stopPropagation(); picker.style.display = picker.style.display === 'block' ? 'none' : 'block'; };
        picker.addEventListener('emoji-click', e => { document.getElementById('messageInput').value += e.detail.unicode; handleKeyPress(); });
        document.body.onclick = () => { picker.style.display = 'none'; };
        picker.onclick = (e) => e.stopPropagation();
    }

    async function initiateCall(type) {
        localStream = await navigator.mediaDevices.getUserMedia({ audio: true, video: type==='video' });
        document.getElementById('activeUserAvatar').style.display = 'none'; // logic placeholder
        // ... Call Logic ...
        alert("Wicitaanku waa bilaawday...");
    }
</script>
@endsection
