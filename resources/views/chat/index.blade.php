@extends('layouts.app')

@section('title', 'WhatsApp Police Clone')

@section('css')
<style>
    /* Premium WhatsApp Redesign */
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
        transition: 0.3s ease;
    }

    .pane-header {
        height: 60px;
        padding: 10px 16px;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #d1d7db;
    }

    .search-box {
        padding: 8px 12px;
        background: #fff;
        border-bottom: 1px solid #f0f2f5;
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
    }

    .messages-viewport {
        flex: 1;
        overflow-y: auto;
        padding: 20px 7%;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .msg-bubble {
        max-width: 65%;
        padding: 6px 7px 8px 9px;
        border-radius: 8px;
        font-size: 14.5px;
        position: relative;
        box-shadow: 0 1px 0.5px rgba(0,0,0,0.13);
        word-wrap: break-word;
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

    .call-controls {
        position: absolute;
        bottom: 50px;
        display: flex;
        gap: 20px;
        z-index: 100;
    }

    .ctrl-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }

    .ctrl-decline { background: #ea0038; }
    .ctrl-accept { background: #06d755; }
    .ctrl-mute { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); }

    /* Media Previews */
    .media-card { max-width: 300px; border-radius: 8px; overflow: hidden; margin-bottom: 5px; border: 1px solid #eee; }
    .media-card img, .media-card video { width: 100%; display: block; }

    /* Recording Meter */
    .voice-pulse {
        width: 12px;
        height: 12px;
        background: #ea0038;
        border-radius: 50%;
        animation: pulse 1s infinite;
        margin-right: 10px;
    }
    @keyframes pulse { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.2); } 100% { opacity: 1; transform: scale(1); } }

    /* Typing Indicator */
    .typing-label { color: #06d755; font-size: 13px; font-weight: 600; display: none; }

    /* Emoji Picker Wrap */
    emoji-picker {
        position: absolute;
        bottom: 60px;
        left: 16px;
        z-index: 1000;
        display: none;
    }

    @media (max-width: 768px) {
        .contacts-pane { width: 100%; position: absolute; height: 100%; }
        .contacts-pane.hidden { transform: translateX(-100%); }
        .conversation-pane { width: 100%; position: absolute; height: 100%; }
        .conversation-pane.hidden { transform: translateX(100%); }
    }
</style>
@endsection

@section('content')
<div class="chat-wrapper">
    <!-- Left: Contacts -->
    <div class="contacts-pane" id="contactsPane">
        <div class="pane-header">
            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=00a884&color=fff" style="width:40px;height:40px;border-radius:50%;">
            <div style="display:flex; gap: 20px; color: #54656f; font-size: 20px;">
                <i class="fa-solid fa-circle-notch"></i>
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
            <!-- Rendered via JS -->
        </div>
    </div>

    <!-- Right: Conversation -->
    <div class="conversation-pane hidden" id="conversationPane">
        <div id="noChatSelected" style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center;">
            <img src="https://static.whatsapp.net/rsrc.php/v3/y6/r/wa669ae5zba.png" style="width:350px;">
            <h1 style="color:#41525d; font-weight:300; margin-top:20px;">WhatsApp for Police</h1>
            <p style="color:#667781; font-size:14px; padding:0 20px;">U dir oo ka hel fariimaha, sawirada, iyo wicitaanada meel kasta.</p>
        </div>

        <div id="activeChat" style="display:none; flex:1; flex-direction:column;">
            <div class="pane-header">
                <div style="display:flex; align-items:center; gap:12px;">
                    <i class="fa-solid fa-arrow-left" onclick="showContacts()" style="display:none;" id="mobileBackBtn"></i>
                    <div id="activeUserAvatar"></div>
                    <div>
                        <div id="activeUserName" style="font-weight:600; color:#111b21;">Name</div>
                        <div id="activeUserStatus" style="font-size:12px; color:#667781;">online</div>
                        <div class="typing-label" id="typingIndicator">is typing...</div>
                    </div>
                </div>
                <div style="display:flex; gap:25px; color:#54656f; font-size:18px;">
                    <i class="fa-solid fa-video" onclick="initiateCall('video')" style="cursor:pointer;"></i>
                    <i class="fa-solid fa-phone" onclick="initiateCall('audio')" style="cursor:pointer;"></i>
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </div>
            </div>

            <div class="messages-viewport" id="messageArea"></div>

            <emoji-picker id="emojiPicker"></emoji-picker>

            <div class="input-dock">
                <i class="fa-regular fa-face-smile" id="emojiToggle" style="font-size:24px; color:#54656f; cursor:pointer;"></i>
                <label for="fileInput" style="margin:0; cursor:pointer;">
                    <i class="fa-solid fa-plus" style="font-size:20px; color:#54656f;"></i>
                    <input type="file" id="fileInput" hidden onchange="handleFileSelect(event)">
                </label>
                
                <div class="input-container" id="inputContainer">
                    <input type="text" id="messageInput" placeholder="Qor fariin..." onkeyup="handleKeyPress(event)">
                </div>

                <div id="voiceUI" style="display:none; align-items:center; flex:1; padding-left:15px;">
                    <div class="voice-pulse"></div>
                    <span id="recordDuration">0:00</span>
                    <i class="fa-solid fa-trash" onclick="cancelRecord()" style="margin-left:auto; color:#ea0038; cursor:pointer;"></i>
                </div>

                <i class="fa-solid fa-microphone" id="micBtn" onclick="toggleRecording()" style="font-size:22px; color:#54656f; cursor:pointer;"></i>
                <i class="fa-solid fa-paper-plane" id="sendBtn" onclick="sendMessage()" style="font-size:22px; color:#00a884; cursor:pointer; display:none;"></i>
            </div>
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
        <div id="callAvatarLarge" style="width:160px; height:160px; border-radius:50%; margin-bottom:20px; border:4px solid #06d755; padding:5px;">
            <img src="" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
        </div>
        <h2 id="callTargetName" style="font-size:2.5rem; margin-bottom:10px;">Name</h2>
        <p id="callStatusMessage" style="font-size:1.2rem; color:#8696a0;">Wicitaan ayaa socda...</p>
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

    // --- Init ---
    document.addEventListener('DOMContentLoaded', () => {
        setupPeer();
        fetchUsers();
        startSync();
        setupEmoji();
    });

    function setupPeer() {
        peer = new Peer('SPD-' + MY_ID);
        peer.on('call', call => {
            if(localStream) {
                call.answer(localStream);
                setupCallHandlers(call);
            }
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
                    document.getElementById('callStatusMessage').innerText = "Wicitaanku waa socdaa...";
                }
            });
    }

    // --- Users ---
    function fetchUsers() {
        fetch("{{ route('chat.users') }}").then(r => r.json()).then(users => {
            let html = '';
            users.forEach(u => {
                const avatar = u.profile_image ? `<img src="/storage/${u.profile_image}" style="width:49px;height:49px;border-radius:50%;">` : 
                              `<div style="width:49px;height:49px;border-radius:50%;background:#dfe5e7;display:flex;align-items:center;justify-content:center;font-weight:700;">${u.name.charAt(0)}</div>`;
                html += `
                <div onclick="openChat(${u.id}, '${u.name}', '${u.profile_image}')" style="display:flex; padding:12px 16px; gap:12px; cursor:pointer; background:${u.id==currentReceiverId ? '#f0f2f5': '#fff'}; border-bottom:1px solid #f2f2f2;">
                    ${avatar}
                    <div style="flex:1;">
                        <div style="display:flex; justify-content:space-between;">
                            <span style="font-weight:500;">${u.name}</span>
                            <span style="font-size:12px; color:#667781;">${u.last_seen || ''}</span>
                        </div>
                        <div style="font-size:13px; color:#667781; display:flex; justify-content:space-between;">
                            <span>${u.is_online ? '<span style="color:#06d755">online</span>' : 'Riix si aad u bilowdid'}</span>
                            ${u.unread_count > 0 ? `<span style="background:#06d755; color:#fff; border-radius:50%; padding:2px 7px; font-size:11px;">${u.unread_count}</span>` : ''}
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
        document.getElementById('noChatSelected').style.display = 'none';
        document.getElementById('activeChat').style.display = 'flex';
        document.getElementById('conversationPane').classList.remove('hidden');
        document.getElementById('activeUserName').innerText = name;
        
        const avatarHtml = img ? `<img src="/storage/${img}" style="width:40px;height:40px;border-radius:50%;">` : 
                          `<div style="width:40px;height:40px;border-radius:50%;background:#00a884;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;">${name.charAt(0)}</div>`;
        document.getElementById('activeUserAvatar').innerHTML = avatarHtml;

        if(window.innerWidth <= 768) {
            document.getElementById('contactsPane').classList.add('hidden');
            document.getElementById('mobileBackBtn').style.display = 'block';
        }
        loadMessages();
    }

    function showContacts() {
        document.getElementById('contactsPane').classList.remove('hidden');
        document.getElementById('conversationPane').classList.add('hidden');
    }

    function loadMessages() {
        fetch(`{{ route('chat.messages') }}?receiver_id=${currentReceiverId}`).then(r => r.json()).then(messages => {
            let html = '';
            messages.forEach(m => {
                const isSent = m.sender_id == MY_ID;
                const time = new Date(m.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
                
                let content = m.message;
                if(m.type === 'image') content = `<div class="media-card"><img src="/storage/${m.file_path}"></div>` + (m.message ? `<div>${m.message}</div>` : '');
                if(m.type === 'video') content = `<div class="media-card"><video src="/storage/${m.file_path}" controls></video></div>`;
                if(m.type === 'audio') content = `<audio src="/storage/${m.file_path}" controls style="height:35px; width:220px;"></audio>`;

                let status = '<i class="fa-solid fa-check" style="color:#8696a0"></i>';
                if(m.read_at) status = '<i class="fa-solid fa-check-double" style="color:#53bdeb"></i>';
                else if(m.delivered_at) status = '<i class="fa-solid fa-check-double" style="color:#8696a0"></i>';

                html += `
                <div class="msg-bubble ${isSent ? 'msg-sent' : 'msg-received'}">
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
        });
    }

    async function sendMessage(file = null) {
        const input = document.getElementById('messageInput');
        const msg = input.value.trim();
        if(!msg && !file) return;

        let fd = new FormData();
        fd.append('receiver_id', currentReceiverId);
        if(msg) fd.append('message', msg);
        if(file) fd.append('file', file);

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

    // --- Voice Recording ---
    async function toggleRecording() {
        if(!isRecording) {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];
            mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
            mediaRecorder.onstop = () => {
                const blob = new Blob(audioChunks, { type: 'audio/ogg; codecs=opus' });
                sendMessage(new File([blob], "voice.ogg", { type: 'audio/ogg' }));
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

        const call = await fetch("{{ route('chat.call.initiate') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ receiver_id: currentReceiverId, call_type: type, signal: 'SPD-'+MY_ID })
        }).then(r => r.json());
        activeCallData = call;
    }

    function showIncomingCall(call) {
        document.getElementById('callWindow').style.display = 'flex';
        document.getElementById('callTargetName').innerText = call.caller.name;
        document.getElementById('callStatusMessage').innerText = "Wicitaan cusub...";
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

        const call = peer.call(activeCallData.caller_signal, localStream);
        setupCallHandlers(call);

        await fetch("{{ route('chat.call.respond') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ call_id: activeCallData.id, status: 'accepted', signal: 'SPD-'+MY_ID })
        });
    }

    function setupCallHandlers(call) {
        call.on('stream', remoteStream => {
            document.getElementById('remoteVideo').srcObject = remoteStream;
            document.getElementById('callStatusMessage').innerText = "Wicitaanku waa socdaa...";
        });
        call.on('close', terminateCall);
    }

    function terminateCall() {
        if(activeCallData) fetch("{{ route('chat.call.end') }}", { method:"POST", body:JSON.stringify({call_id:activeCallData.id}), headers:{"Content-Type":"application/json","X-CSRF-TOKEN":"{{csrf_token()}}"}});
        if(localStream) localStream.getTracks().forEach(t => t.stop());
        document.getElementById('callWindow').style.display = 'none';
        document.getElementById('ringSound').pause();
        activeCallData = null;
        // location.reload(); // Removed to avoid UI flicker, just reset state
    }

    // --- Emoji ---
    function setupEmoji() {
        const picker = document.querySelector('emoji-picker');
        const toggle = document.getElementById('emojiToggle');
        toggle.onclick = () => picker.style.display = picker.style.display === 'block' ? 'none' : 'block';
        picker.addEventListener('emoji-click', e => {
            document.getElementById('messageInput').value += e.detail.unicode;
            handleKeyPress();
        });
    }
</script>
@endsection
