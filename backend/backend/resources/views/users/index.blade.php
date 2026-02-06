<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Police Chat System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            height: 100vh;
            background: #e5ddd5;
        }

        /* MAIN WRAPPER */
        .chat-app {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* SIDEBAR */
        .sidebar {
            width: 300px;
            background: #202c33;
            color: #fff;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 15px;
            background: #111b21;
            font-weight: bold;
        }

        .search {
            padding: 10px;
        }

        .search input {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: none;
            outline: none;
        }

        .users {
            flex: 1;
            overflow-y: auto;
        }

        .user {
            padding: 12px;
            border-bottom: 1px solid #2a3942;
            cursor: pointer;
        }

        .user:hover {
            background: #2a3942;
        }

        /* CHAT AREA */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #efeae2;
        }

        .chat-header {
            height: 60px;
            background: #202c33;
            color: white;
            display: flex;
            align-items: center;
            padding: 0 15px;
        }

        .chat-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
        }

        .message {
            max-width: 70%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            font-size: 14px;
        }

        .sent {
            background: #d9fdd3;
            margin-left: auto;
        }

        .received {
            background: #ffffff;
        }

        /* INPUT AREA */
        .chat-input {
            height: 60px;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            padding: 10px;
            gap: 10px;
        }

        .chat-input input {
            flex: 1;
            height: 40px;
            border-radius: 20px;
            border: 1px solid #ccc;
            padding: 0 15px;
            outline: none;
        }

        .chat-input button {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            border: none;
            background: #25d366;
            color: white;
            cursor: pointer;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="chat-app">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-header">My Chats</div>

            <div class="search">
                <input type="text" placeholder="Raadi sarkaalka...">
            </div>

            <div class="users">
                <div class="user">Admin User</div>
                <div class="user">Askari Cali</div>
                <div class="user">Sadam</div>
                <div class="user">Moha</div>
                <div class="user">Ahmed</div>
            </div>
        </div>

        <!-- CHAT -->
        <div class="chat-area">
            <div class="chat-header">
                Abdikafi Abdikadir Ali 🟢 Online
            </div>

            <div class="chat-messages">
                <div class="message received">asc</div>
                <div class="message sent">wcs</div>
                <div class="message received">side tahay?</div>
                <div class="message sent">wan fiicanahay</div>
            </div>

            <div class="chat-input">
                <button>📎</button>
                <input type="text" placeholder="Qor fariin...">
                <button>➤</button>
            </div>
        </div>

    </div>

</body>

</html>