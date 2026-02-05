@extends('layouts.app')

@section('title','Messenger')

@section('css')
<style>
    :root {
        --blue: #0084ff;
        --gray: #f0f2f5;
        --bubble: #e4e6eb;
        --text: #050505;
        --sub: #65676b;
    }

    /* MAIN */
    .messenger-wrapper {
        display: flex;
        height: 100vh;
        overflow: hidden;
        background: #fff;
    }

    /* SIDEBAR */
    .ms-sidebar {
        width: 340px;
        border-right: 1px solid #ddd;
        display: flex;
        flex-direction: column;
    }

    .ms-header {
        height: 60px;
        padding: 0 16px;
        display: flex;
        align-items: center;
        font-size: 22px;
        font-weight: bold;
    }

    .ms-search {
        padding: 10px 16px;
    }

    .ms-search input {
        width: 100%;
        padding: 8px 12px;
        border-radius: 20px;
        border: none;
        background: var(--gray);
        outline: none;
    }

    /* USERS */
    .ms-user-list {
        flex: 1;
        overflow-y: auto;
    }

    .ms-user-item {
        display: flex;
        align-items: center;
        padding: 10px 16px;
        cursor: pointer;
        border-radius: 10px;
    }

    .ms-user-item:hover {
        background: #f2f2f2;
    }

    .ms-user-item.active {
        background: #e7f1ff;
    }

    .ms-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 12px;
    }

    .ms-user-name {
        font-weight: 600;
    }

    .ms-user-meta {
        font-size: 12px;
        color: var(--sub);
    }

    /* CHAT */
    .ms-chat {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .ms-chat-header {
        height: 60px;
        padding: 0 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #ddd;
    }

    .ms-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background: #fff;
    }

    .msg-row {
        display: flex;
        margin-bottom: 6px;
    }

    .msg-row.sent {
        justify-content: flex-end;
    }

    .msg-row.received {
        justify-content: flex-start;
    }

    .bubble {
        max-width: 70%;
        padding: 8px 12px;
        border-radius: 18px;
        font-size: 14px;
    }

    .bubble.sent {
        background: var(--blue);
        color: #fff;
        border-bottom-right-radius: 4px;
    }

    .bubble.received {
        background: var(--bubble);
        border-bottom-left-radius: 4px;
    }

    /* FOOTER */
    .ms-footer {
        position: sticky;
        bottom: 0;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border-top: 1px solid #ddd;
    }

    .ms-footer input {
        flex: 1;
        padding: 8px 12px;
        border-radius: 20px;
        border: none;
        background: var(--gray);
        outline: none;
    }

    /* MOBILE */
    @media(max-width:900px) {
        .ms-sidebar {
            width: 100%;
        }

        .ms-chat {
            position: fixed;
            inset: 0;
            background: #fff;
            transform: translateX(100%);
            transition: .3s;
            z-index: 99;
        }

        .ms-chat.active {
            transform: translateX(0);
        }
    }
</style>
@endsection

@section('content')
<div class="messenger-wrapper">

    <!-- SIDEBAR -->
    <div class="ms-sidebar">
        <div class="ms-header">Chats</div>

        <div class="ms-search">
            <input placeholder="Raadi sarkaal...">
        </div>

        <div class="ms-user-list" id="usersList">
            <div class="ms-user-item active" onclick="selectUser(null,'Global Chat')">
                <div class="ms-avatar">G</div>
                <div>
                    <div class="ms-user-name">Global Chat</div>
                    <div class="ms-user-meta">Public</div>
                </div>
            </div>
        </div>
    </div>

    <!-- CHAT -->
    <div class="ms-chat" id="msChat">
        <div class="ms-chat-header">
            <span id="chatName">Dooro Qof</span>
            <span onclick="closeChat()" style="cursor:pointer;">✖</span>
        </div>

        <div class="ms-messages" id="msgs"></div>

        <div class="ms-footer">
            <input id="msgInput" placeholder="Qor fariin..."
                onkeypress="if(event.key==='Enter')sendMsg()">
            <button onclick="sendMsg()">➤</button>
        </div>
    </div>

</div>
@endsection

@section('js')
<script>
    let authId = {
        {
            auth() - > id()
        }
    };
    let currentReceiver = null;

    setInterval(loadMessages, 3000);
    loadUsers();

    function loadUsers() {
        fetch("{{ route('chat.users') }}")
            .then(r => r.json())
            .then(users => {
                let html = `
        <div class="ms-user-item ${currentReceiver===null?'active':''}" 
             onclick="selectUser(null,'Global Chat')">
            <div class="ms-avatar">G</div>
            <div>
                <div class="ms-user-name">Global Chat</div>
                <div class="ms-user-meta">Public</div>
            </div>
        </div>`;
                users.forEach(u => {
                    html += `
            <div class="ms-user-item" onclick="selectUser(${u.id},'${u.name}')">
                <div class="ms-avatar">${u.name[0]}</div>
                <div>
                    <div class="ms-user-name">${u.name}</div>
                    <div class="ms-user-meta">${u.last_seen}</div>
                </div>
            </div>`;
                });
                document.getElementById('usersList').innerHTML = html;
            });
    }

    function selectUser(id, name) {
        currentReceiver = id;
        document.getElementById('chatName').innerText = name;
        document.getElementById('msChat').classList.add('active');
        loadMessages(true);
    }

    function closeChat() {
        document.getElementById('msChat').classList.remove('active');
    }

    function loadMessages(scroll = false) {
        let url = "{{ route('chat.messages') }}";
        if (currentReceiver) url += `?receiver_id=${currentReceiver}`;
        fetch(url).then(r => r.json()).then(msgs => {
            let html = '';
            msgs.forEach(m => {
                let type = m.sender_id === authId ? 'sent' : 'received';
                html += `
            <div class="msg-row ${type}">
                <div class="bubble ${type}">${m.message}</div>
            </div>`;
            });
            let box = document.getElementById('msgs');
            box.innerHTML = html;
            if (scroll) box.scrollTop = box.scrollHeight;
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
                receiver_id: currentReceiver
            })
        }).then(() => {
            input.value = '';
            loadMessages(true);
        });
    }
</script>
@endsection