@extends('layouts.app')

@section('title', 'WhatsApp Police Clone')

@section('css')
<style>
    /* WhatsApp Styles Overhaul */
    .chat-wrapper {
        display: flex;
        height: calc(100vh - 120px);
        margin: -1rem;
        background: #f0f2f5;
        position: relative;
        overflow: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Left Pane */
    .contacts-pane {
        width: 400px;
        border-right: 1px solid #d1d7db;
        display: flex;
        flex-direction: column;
        background: #fff;
        z-index: 10;
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
    }

    .search-inner {
        background: #f0f2f5;
        border-radius: 8px;
        padding: 6px 14px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .search-inner input {
        border: none;
        background: transparent;
        width: 100%;
        outline: none;
        font-size: 14px;
    }

    /* Right Pane */
    .conversation-pane {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #efe7dd url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
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

    /* Bubbles */
    .msg-bubble {
        max-width: 65%;
        padding: 6px 7px 8px 9px;
        border-radius: 8px;
        font-size: 14.2px;
        position: relative;
        box-shadow: 0 1px 0.5px rgba(0,0,0,0.13);
        word-wrap: break-word;
    }

    .msg-sent {
        align-self: flex-end;
        background: #dcf8c6;
        border-top-right-radius: 0;
    }

    .msg-received {
        align-self: flex-start;
        background: #fff;
        border-top-left-radius: 0;
    }

    .msg-info {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 4px;
        font-size: 11px;
        color: #667781;
        margin-top: -4px;
    }

    .status-icon {
        font-size: 14px;
        color: #53bdeb; /* Read blue */
    }

    /* Input Area */
    .input-dock {
        padding: 10px 16px;
        background: #f0f2f5;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .input-container {
        flex: 1;
        background: #fff;
        border-radius: 8px;
        padding: 9px 12px;
        display: flex;
        align-items: center;
    }

    .input-container input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 15px;
    }

    /* Floating Call UI */
    .call-window {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: #0b141a;
        z-index: 9999;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #fff;
    }

    .video-container {
        position: relative;
        width: 100%;
        height: 100%;
        background: #000;
    }

    #remoteVideo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #localVideo {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 150px;
        height: 200px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #fff;
    }

    .call-controls {
        position: absolute;
        bottom: 40px;
        display: flex;
        gap: 20px;
        z-index: 100;
    }

    .ctrl-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-end { background: #ea0038; }
    .btn-accept { background: #06d755; }
    .btn-mute { background: rgba(255,255,255,0.2); }

    /* Voice Note */
    .voice-meter {
        flex: 1;
        height: 30px;
        background: #f0f2f5;
        border-radius: 15px;
        display: none;
        align-items: center;
        padding: 0 15px;
        color: #ea0038;
        font-weight: 600;
    }

    /* Typing Indicator */
    .typing-status {
        font-size: 12px;
        color: #06d755;
        font-weight: 600;
        display: none;
    }

    /* Media Previews */
    .media-preview {
        max-width: 300px;
        border-radius: 8px;
        margin-bottom: 5px;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .contacts-pane { width: 100%; position: absolute; height: 100%; }
        .conversation-pane { width: 100%; position: absolute; height: 100%; }
        .hidden-mobile { display: none; }
        .contacts-pane.active { display: flex; }
        .conversation-pane.active { display: flex; }
    }
</style>
@endsection

@section('content')
<div class="chat-wrapper">
    <!-- Left: Contact List -->
    <div class="contacts-pane" id="contactsPane">
        <div class="pane-header">
            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=00a884&color=fff" style="width:40px;height:40px;border-radius:50%;">
            <div style="display:flex; gap: 20px; color: #54656f; font-size: 20px;">
                <i class="fa-solid fa-users-viewfinder"></i>
                <i class="fa-solid fa-circle-notch"></i>
                <i class="fa-solid fa-message"></i>
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </div>
        </div>

        <div class="search-box">
            <div class="search-inner">
                <i class="fa-solid fa-magnifying-glass" style="color: #54656f;"></i>
                <input type="text" id="userSearch" placeholder="Raadi ama bilow sheeko cusub">
            </div>
        </div>

        <div id="usersList" style="flex:1; overflow-y:auto; background:#fff;">
            <!-- Rendered by JS -->
        </div>
    </div>

    <!-- Right: Conversation -->
    <div class="conversation-pane" id="conversationPane">
        <!-- Welcoming Empty State -->
        <div id="noChatSelected" style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center;">
            <img src="https://static.whatsapp.net/rsrc.php/v3/y6/r/wa669ae5zba.png" style="width:400px; opacity:0.8;">
            <h1 style="color:#41525d; font-weight:300; margin-top:20px;">WhatsApp for Somali Police</h1>
            <p style="color:#667781; font-size:14px; max-width:450px;">U dir ama ka hel fariimo sugan adiga oo aan talefankaaga online ka dhigin. <br>Isticmaal WhatsApp ilaa 4 aaladood oo isku xidhan hal mar.</p>
        </div>

        <div id="activeChat" style="display:none; flex:1; flex-direction:column;">
            <div class="pane-header" style="background:#f0f2f5;">
                <div style="display:flex; align-items:center; gap:15px;">
                    <i class="fa-solid fa-arrow-left" onclick="backToContacts()" style="display:none;" id="backBtn"></i>
                    <div id="activeUserAvatar"></div>
                    <div>
                        <div id="activeUserName" style="font-weight:600; color:#111b21;">John Doe</div>
                        <div id="activeUserStatus" style="font-size:12px; color:#667781;">online</div>
                        <div class="typing-status" id="typingStatus">is typing...</div>
                    </div>
                </div>
                <div style="display:flex; gap:25px; color:#54656f; font-size:18px;">
                    <i class="fa-solid fa-video" onclick="initiateCall('video')" style="cursor:pointer;"></i>
                    <i class="fa-solid fa-phone" onclick="initiateCall('audio')" style="cursor:pointer;"></i>
                    <i class="fa-solid fa-magnifying-glass" style="cursor:pointer;"></i>
                    <i class="fa-solid fa-ellipsis-vertical" style="cursor:pointer;"></i>
                </div>
            </div>

            <div class="messages-viewport" id="messageArea">
                <!-- Messages -->
            </div>

            <div class="input-dock">
                <i class="fa-regular fa-face-smile" id="emojiBtn" style="font-size:24px; color:#54656f; cursor:pointer;"></i>
                <label for="fileUpload" style="margin:0; cursor:pointer;">
                    <i class="fa-solid fa-plus" style="font-size:22px; color:#54656f;"></i>
                    <input type="file" id="fileUpload" hidden onchange="handleFileUpload(event)">
                </label>
                
                <div class="input-container" id="inputContainer">
                    <input type="text" id="messageInput" placeholder="Qor fariin" onkeyup="handleTyping()">
                </div>

                <div class="voice-meter" id="voiceMeter">
                    <i class="fa-solid fa-circle-stop" onclick="stopRecording()" style="cursor:pointer;"></i>
                    <span id="recordTimer">00:00</span>
                    <div style="flex:1; margin-left:10px; border-bottom:2px solid; opacity:0.3;"></div>
                </div>

                <i class="fa-solid fa-microphone" id="micBtn" onclick="startRecording()" style="font-size:22px; color:#54656f; cursor:pointer;"></i>
                <i class="fa-solid fa-paper-plane" id="sendBtn" onclick="sendMessage()" style="font-size:22px; color:#00a884; cursor:pointer; display:none;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Call UI -->
<div class="call-window" id="callWindow">
    <div class="video-container" id="videoContainer" style="display:none;">
        <video id="remoteVideo" autoplay playsinline></video>
        <video id="localVideo" autoplay playsinline muted></video>
    </div>

    <div id="audioCallUI" style="text-align:center;">
        <img id="callUserImg" src="" style="width:150px; height:150px; border-radius:50%; margin-bottom:30px;">
        <h2 id="callUserName">Sarkaal</h2>
        <p id="callStatusText">Wicitaan ayaa socda...</p>
    </div>

    <div class="call-controls">
        <div class="ctrl-btn btn-mute"><i class="fa-solid fa-microphone-slash"></i></div>
        <div class="ctrl-btn btn-accept" id="acceptBtn" style="display:none;" onclick="answerCall()"><i class="fa-solid fa-phone"></i></div>
        <div class="ctrl-btn btn-end" onclick="hangUp()"><i class="fa-solid fa-phone-slash"></i></div>
    </div>
</div>

<!-- Audio Assets -->
<audio id="ringtone" loop src="https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3"></audio>

<!-- Scripts -->
<script src="https://unpkg.com/peerjs@1.5.2/dist/peerjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@4.6.4/dist/index.min.js"></script>

<script>
    const MY_ID = {{ auth()->id() }};
    let currentReceiverId = null;
    let peer = null;
    let activeCall = null;
    let localStream = null;
    let isRecording = false;
    let mediaRecorder = null;
    let audioChunks = [];
    let activeCallData = null;

    // --- INITIALIZATION ---
    document.addEventListener('DOMContentLoaded', () => {
        initPeer();
        fetchUsers();
        setInterval(fetchUsers, 10000);
        setInterval(smartSync, 3000);
        initEmoji();
    });

    function initPeer() {
        peer = new Peer('SPD-' + MY_ID);
        peer.on('call', async (call) => {
            activeCall = call;
            // Handle incoming call notification via DB is faster, but Peer signal is secondary
        });
    }

    // --- UI LOGIC ---
    function fetchUsers() {
        fetch("{{ route('chat.users') }}")
            .then(res => res.json())
            .then(users => {
                let html = '';
                users.forEach(u => {
                    const avatar = u.profile_image ? `<img src="/storage/${u.profile_image}" style="width:49px;height:49px;border-radius:50%;">` : 
                                  `<div style="width:49px;height:49px;border-radius:50%;background:#dfe5e7;display:flex;align-items:center;justify-content:center;font-weight:700;">${u.name.charAt(0)}</div>`;
                    
                    html += `
                    <div onclick="selectUser(${u.id}, '${u.name}', '${u.profile_image}')" style="display:flex; padding:12px 16px; gap:12px; cursor:pointer; align-items:center; border-bottom:1px solid #f2f2f2;">
                        ${avatar}
                        <div style="flex:1;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:2px;">
                                <span style="font-weight:500; color:#111b21;">${u.name}</span>
                                <span style="font-size:12px; color:#667781;">${u.last_seen || ''}</span>
                            </div>
                            <div style="font-size:13px; color:#667781; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:flex; justify-content:space-between;">
                                <span>${u.is_online ? '<span style="color:#06d755">online</span>' : 'Riix si aad u qortid'}</span>
                                ${u.unread_count > 0 ? `<span style="background:#06d755; color:#fff; border-radius:50%; padding:2px 7px; font-size:11px; font-weight:700;">${u.unread_count}</span>` : ''}
                            </div>
                        </div>
                    </div>`;
                });
                document.getElementById('usersList').innerHTML = html;
            });
    }

    function selectUser(id, name, img) {
        currentReceiverId = id;
        document.getElementById('noChatSelected').style.display = 'none';
        document.getElementById('activeChat').style.display = 'flex';
        document.getElementById('activeUserName').innerText = name;
        
        const avatarHtml = img ? `<img src="/storage/${img}" style="width:40px;height:40px;border-radius:50%;">` : 
                          `<div style="width:40px;height:40px;border-radius:50%;background:#00a884;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;">${name.charAt(0)}</div>`;
        document.getElementById('activeUserAvatar').innerHTML = avatarHtml;

        if(window.innerWidth <= 768) {
            document.getElementById('contactsPane').style.display = 'none';
            document.getElementById('backBtn').style.display = 'block';
        }
        
        loadMessages();
    }

    function backToContacts() {
        document.getElementById('contactsPane').style.display = 'flex';
        document.getElementById('activeChat').style.display = 'none';
    }

    // --- MESSAGING ---
    function loadMessages() {
        if(!currentReceiverId) return;
        fetch(`{{ route('chat.messages') }}?receiver_id=${currentReceiverId}`)
            .then(res => res.json())
            .then(messages => {
                let html = '';
                messages.forEach(m => {
                    const isSent = m.sender_id == MY_ID;
                    const time = new Date(m.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
                    
                    let content = m.message;
                    if(m.type === 'image') content = `<img src="/storage/${m.file_path}" class="media-preview" onclick="window.open(this.src)">`;
                    if(m.type === 'video') content = `<video src="/storage/${m.file_path}" class="media-preview" controls></video>`;
                    if(m.type === 'audio') content = `<audio src="/storage/${m.file_path}" controls style="height:35px; width:220px;"></audio>`;

                    const statusIcon = m.read_at ? '<i class="fa-solid fa-check-double status-icon"></i>' : (m.delivered_at ? '<i class="fa-solid fa-check-double" style="color:#667781"></i>' : '<i class="fa-solid fa-check" style="color:#667781"></i>');

                    html += `
                    <div class="msg-bubble ${isSent ? 'msg-sent' : 'msg-received'}">
                        ${content}
                        <div class="msg-info">
                            <span>${time}</span>
                            ${isSent ? statusIcon : ''}
                        </div>
                    </div>`;
                });
                const area = document.getElementById('messageArea');
                area.innerHTML = html;
                area.scrollTop = area.scrollHeight;
            });
    }

    function sendMessage(file = null) {
        const input = document.getElementById('messageInput');
        const msg = input.value.trim();
        if(!msg && !file) return;

        let formData = new FormData();
        formData.append('receiver_id', currentReceiverId);
        if(msg) formData.append('message', msg);
        if(file) formData.append('file', file);

        fetch("{{ route('chat.send') }}", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: formData
        }).then(() => {
            input.value = '';
            document.getElementById('micBtn').style.display = 'block';
            document.getElementById('sendBtn').style.display = 'none';
            loadMessages();
        });
    }

    // --- MEDIA HANDLING ---
    function handleFileUpload(e) {
        const file = e.target.files[0];
        if(file) sendMessage(file);
    }

    // --- VOICE NOTES ---
    async function startRecording() {
        if(!navigator.mediaDevices) return alert("Browser kaagu ma taageero cod duubista.");
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];
        
        mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
        mediaRecorder.onstop = () => {
            const blob = new Blob(audioChunks, { type: 'audio/ogg; codecs=opus' });
            const file = new File([blob], "voice_note.ogg", { type: 'audio/ogg' });
            sendMessage(file);
            stream.getTracks().forEach(t => t.stop());
        };

        mediaRecorder.start();
        isRecording = true;
        document.getElementById('micBtn').style.color = '#ea0038';
        document.getElementById('inputContainer').style.display = 'none';
        document.getElementById('voiceMeter').style.display = 'flex';
        
        let sec = 0;
        window.recordInt = setInterval(() => {
            sec++;
            let m = Math.floor(sec/60).toString().padStart(2, '0');
            let s = (sec%60).toString().padStart(2, '0');
            document.getElementById('recordTimer').innerText = `${m}:${s}`;
        }, 1000);
    }

    function stopRecording() {
        mediaRecorder.stop();
        clearInterval(window.recordInt);
        document.getElementById('micBtn').style.color = '#54656f';
        document.getElementById('inputContainer').style.display = 'flex';
        document.getElementById('voiceMeter').style.display = 'none';
        document.getElementById('recordTimer').innerText = "00:00";
    }

    // --- VOIP CALLS (WebRTC) ---
    async function initiateCall(type) {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true, video: type === 'video' });
        localStream = stream;
        
        document.getElementById('callWindow').style.display = 'flex';
        document.getElementById('callUserName').innerText = document.getElementById('activeUserName').innerText;
        document.getElementById('callUserImg').src = document.getElementById('activeUserAvatar').querySelector('img')?.src || '';
        document.getElementById('ringtone').play();

        if(type === 'video') {
            document.getElementById('videoContainer').style.display = 'block';
            document.getElementById('localVideo').srcObject = stream;
        }

        // Signaling via DB
        fetch("{{ route('chat.call.initiate') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ 
                receiver_id: currentReceiverId, 
                call_type: type,
                signal: 'SPD-' + MY_ID 
            })
        }).then(res => res.json()).then(call => {
            activeCallData = call;
        });
    }

    async function answerCall() {
        const stream = await navigator.mediaDevices.getUserMedia({ 
            audio: true, 
            video: activeCallData.call_type === 'video' 
        });
        localStream = stream;
        document.getElementById('ringtone').pause();
        document.getElementById('acceptBtn').style.display = 'none';

        if(activeCallData.call_type === 'video') {
            document.getElementById('videoContainer').style.display = 'block';
            document.getElementById('localVideo').srcObject = stream;
        }

        // Respond via PeerJS
        const call = peer.call(activeCallData.caller_signal, stream);
        setupCallHandlers(call);
        
        // Update DB
        fetch("{{ route('chat.call.respond') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ call_id: activeCallData.id, status: 'accepted', signal: 'SPD-' + MY_ID })
        });
    }

    function setupCallHandlers(call) {
        call.on('stream', remoteStream => {
            document.getElementById('remoteVideo').srcObject = remoteStream;
            document.getElementById('callStatusText').innerText = "Wicitaanku waa socdaa...";
        });
        call.on('close', hangUp);
    }

    function hangUp() {
        if(activeCallData) {
            fetch("{{ route('chat.call.end') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ call_id: activeCallData.id })
            });
        }
        if(localStream) localStream.getTracks().forEach(t => t.stop());
        document.getElementById('callWindow').style.display = 'none';
        document.getElementById('ringtone').pause();
        activeCallData = null;
    }

    // --- REAL-TIME SYNC ---
    function smartSync() {
        // Check for incoming calls
        fetch("{{ route('chat.call.check') }}")
            .then(res => res.json())
            .then(call => {
                if(call && call.status === 'ringing' && !activeCallData) {
                    activeCallData = call;
                    document.getElementById('callWindow').style.display = 'flex';
                    document.getElementById('callUserName').innerText = call.caller.name;
                    document.getElementById('callStatusText').innerText = "Wicitaanka xafiiska...";
                    document.getElementById('acceptBtn').style.display = 'flex';
                    document.getElementById('ringtone').play();
                } else if(call && call.status === 'ended' && activeCallData) {
                    hangUp();
                } else if(call && call.status === 'accepted' && activeCallData && activeCallData.caller_id === MY_ID) {
                    // Start PeerJS connection if I am the caller and they just accepted
                    if(!activeCall) {
                        peer.on('call', call => {
                            call.answer(localStream);
                            setupCallHandlers(call);
                        });
                    }
                }
            });

        if(currentReceiverId) loadMessages();
    }

    // --- HELPERS ---
    function handleTyping() {
        const input = document.getElementById('messageInput');
        const hasText = input.value.trim().length > 0;
        document.getElementById('micBtn').style.display = hasText ? 'none' : 'block';
        document.getElementById('sendBtn').style.display = hasText ? 'block' : 'none';
        // Simulation: Send typing event to DB here if needed
    }

    function initEmoji() {
        const picker = new EmojiButton({ position: 'top-start' });
        picker.on('emoji', selection => {
            document.getElementById('messageInput').value += selection.emoji;
            handleTyping();
        });
        document.getElementById('emojiBtn').addEventListener('click', () => picker.togglePicker(document.getElementById('emojiBtn')));
    }
</script>
@endsection
