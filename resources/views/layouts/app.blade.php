<!DOCTYPE html>
<html lang="so">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Somali Police System') }} - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="{{ asset('css/dashboard-glass.css') }}" rel="stylesheet">
    @yield('css')
</head>
<body data-theme="dark">
    <!-- Mobile Overlay -->
    <div class="overlay" onclick="toggleSidebar()"></div>

    <!-- Mobile Toggle Button -->
    <i class="fa-solid fa-bars mobile-toggle" onclick="toggleSidebar()"></i>

    <div class="app-wrapper">
        @auth
        <!-- Eduplex Sidebar (Floating) -->
        <aside class="edu-sidebar" id="sidebar">
            <div class="sidebar-brand">
                <i class="fa-solid fa-shield-cat"></i>
                <span class="logo-text">Police<span style="color: var(--accent-lime);">System</span></span>
            </div>
            
            <div style="flex: 1; overflow-y: auto;">
                <div class="nav-section-title">Main Menu</div>
                
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                    <i class="fa-solid fa-grid-2"></i> <span>Dashboard</span>
                </a>

                @if(in_array(auth()->user()->role->slug, ['admin', 'cid', 'askari', 'taliye-saldhig', 'taliye-gobol', 'taliye-ciidan']))
                <div class="nav-section-title">Operations</div>
                <a href="{{ route('cases.create-unified') }}" class="nav-link {{ request()->is('cases/create-unified*') ? 'active' : '' }}">
                    <i class="fa-solid fa-pen-to-square"></i> <span>New Incident (Warbixin Cusub)</span>
                </a>
                <a href="{{ route('cases.index') }}" class="nav-link {{ request()->is('cases') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder-open"></i> <span>Cases (Kiisaska)</span>
                </a>
                <a href="{{ route('crimes.index') }}" class="nav-link {{ request()->is('crimes*') ? 'active' : '' }}">
                    <i class="fa-solid fa-handcuffs"></i> <span>Crimes (Dambiyada)</span>
                </a>
                <a href="{{ route('suspects.index') }}" class="nav-link {{ request()->is('suspects*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-secret"></i> <span>Suspects (Dambiilaha)</span>
                </a>
                <a href="{{ route('investigations.index') }}" class="nav-link {{ request()->is('investigations*') ? 'active' : '' }}">
                    <i class="fa-solid fa-magnifying-glass-chart"></i> <span>Investigations (Baaritaanka)</span>
                </a>
                <a href="{{ route('chat.index') }}" class="nav-link {{ request()->is('chat*') ? 'active' : '' }}">
                    <i class="fa-solid fa-comments"></i> <span>Chat (Wada-hadalka)</span>
                </a>
                @endif

                @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-saldhig', 'taliye-gobol', 'taliye-ciidan']))
                <div class="nav-section-title">Administration</div>
                <a href="{{ route('stations.index') }}" class="nav-link {{ request()->is('stations*') ? 'active' : '' }}">
                    <i class="fa-solid fa-building-shield"></i> <span>Stations (Saldhigyada)</span>
                </a>
                <a href="{{ route('facilities.index') }}" class="nav-link {{ request()->is('facilities*') ? 'active' : '' }}">
                    <i class="fa-solid fa-building"></i> <span>Facilities (Xarumaha)</span>
                </a>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-group"></i> <span>Officers (Askarta)</span>
                </a>
                <a href="{{ route('station-commanders.index') }}" class="nav-link {{ request()->is('station-commanders*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-tie"></i> <span>Station Commanders</span>
                </a>
                <a href="{{ route('station-officers.index') }}" class="nav-link {{ request()->is('station-officers*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users-viewfinder"></i> <span>Station Officers</span>
                </a>
                @endif
                
                @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-gobol', 'taliye-ciidan']))
                <div class="nav-section-title">Reporting</div>
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-chart-column"></i> <span>Reports (Warbixinada)</span>
                </a>
                <a href="{{ route('audit.index') }}" class="nav-link {{ request()->is('audit*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i> <span>Audit Logs</span>
                </a>
                @endif
                
                <div style="margin-top: 1rem;">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link" style="width: 100%; border: none; background: transparent; cursor: pointer; color: #ef4444;">
                            <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>


        </aside>

        <!-- Main Content (Right Side) -->
        <main class="main-content">
            <!-- Topbar (Integrated) -->
            <header class="edu-topbar">
                <div class="welcome-text">
                    <h1>Welcome back, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
                    <span>Here's what's happening in your jurisdiction today.</span>
                </div>

                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <!-- Search -->
                    <form action="{{ route('cases.index') }}" method="GET" class="search-bar">
                        <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted);"></i>
                        <input type="text" name="search" placeholder="Search cases, suspects..." value="{{ request('search') }}">
                    </form>

                    <!-- Actions -->
                    <button class="icon-btn" onclick="toggleNotifications()">
                        <i class="fa-regular fa-bell"></i>
                        <div class="badge">3</div>
                        <!-- Notification Dropdown -->
                        <div id="notification-list" class="notification-dropdown" style="display: none; position: absolute; right: 0; top: 50px; background: white; width: 300px; padding: 10px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); z-index: 1000;">
                            <div style="font-size: 0.85rem; font-weight: 700; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 5px;">Notifications</div>
                            <div style="font-size: 0.8rem; color: #666;">No new notifications</div>
                        </div>
                    </button>
                    
                    <button class="icon-btn">
                        <i class="fa-regular fa-calendar"></i>
                    </button>
                    
                    <!-- Profile Dropdown -->
                    <div class="user-profile" style="position: relative;" onclick="toggleProfileMenu()">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=C6F048&color=1C1E26" style="width: 45px; height: 45px; border-radius: 50%; border: 2px solid white; box-shadow: var(--shadow-soft); cursor: pointer;">
                        
                        <!-- Dropdown Menu -->
                        <div id="profileDropdown" style="display: none; position: absolute; right: 0; top: 60px; background: white; width: 200px; padding: 0.5rem; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); z-index: 1000; border: 1px solid rgba(0,0,0,0.05);">
                            <div style="padding: 0.8rem; border-bottom: 1px solid rgba(0,0,0,0.05); margin-bottom: 0.5rem;">
                                <div style="font-weight: 700; color: #1f2937;">{{ auth()->user()->name }}</div>
                                <div style="font-size: 0.75rem; color: #9ca3af; text-transform: uppercase;">{{ auth()->user()->role->name }}</div>
                            </div>
                            <a href="{{ route('profile.show') }}" style="display: block; padding: 0.6rem 1rem; color: #4b5563; text-decoration: none; font-size: 0.9rem; border-radius: 8px; transition: 0.2s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='white'">
                                <i class="fa-solid fa-user" style="margin-right: 8px; color: #9ca3af;"></i> Profile
                            </a>
                            <a href="#" style="display: block; padding: 0.6rem 1rem; color: #4b5563; text-decoration: none; font-size: 0.9rem; border-radius: 8px; transition: 0.2s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='white'">
                                <i class="fa-solid fa-gear" style="margin-right: 8px; color: #9ca3af;"></i> Settings
                            </a>
                            <form action="{{ route('logout') }}" method="POST" style="margin-top: 5px; border-top: 1px solid #f3f4f6; padding-top: 5px;">
                                @csrf
                                <button type="submit" style="width: 100%; text-align: left; padding: 0.6rem 1rem; color: #ef4444; background: none; border: none; font-size: 0.9rem; cursor: pointer; border-radius: 8px; transition: 0.2s; display: flex; align-items: center;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='white'">
                                    <i class="fa-solid fa-arrow-right-from-bracket" style="margin-right: 8px;"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            @yield('content')
        </main>
        @else
            <!-- Guest View -->
            <main style="flex: 1;">
                @yield('content')
            </main>
        @endauth
    </div>

    <!-- JavaScript for Mobile Toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function toggleUserMenu() {
            const menu = document.getElementById('profileDropdown');
            if (menu.style.display === 'none') {
                menu.style.display = 'block';
            } else {
                menu.style.display = 'none';
            }
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.closest('.user-profile')) {
                const menu = document.getElementById('profileDropdown');
                if (menu && menu.style.display === 'block') {
                    menu.style.display = 'none';
                }
            }
            if (!event.target.closest('.icon-btn') && !event.target.matches('.fa-bell')) {
                const notif = document.getElementById('notification-list');
                 if (notif && notif.style.display === 'block') {
                    notif.style.display = 'none';
                }
            }
        }

        function toggleNotifications() {
            var list = document.getElementById('notification-list');
            if (list.style.display === 'none' || list.style.display === '') {
                list.style.display = 'block';
            } else {
                list.style.display = 'none';
            }
        }
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <script>
        @auth
            const userTheme = "{{ auth()->user()->settings->theme ?? 'light' }}";
            document.body.setAttribute('data-theme', userTheme);

            // Sound Effect (Simple Ding)
            const notificationSound = new Audio("data:audio/wav;base64,//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4G8QQRDiMcCBcH3Cc+CDv/7xaAAXbm50//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4G8QQRDiMcCBcH3Cc+CDv/7xaAAXbm50//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4G8QQRDiMcCBcH3Cc+CDv/7xaAAXbm50");

            // Check for new notifications every 10 seconds
            setInterval(() => {
                fetch('{{ route("notifications.check") }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.count > 0) {
                            // Play Sound
                            notificationSound.play().catch(e => console.log('Audio play failed (interaction needed first):', e));
                            
                            // Show Toast
                            Toastify({
                                text: "🔔 " + data.count + " Ogeysiis cusub ayaa kuu soo dhacay!",
                                duration: 5000,
                                close: true,
                                gravity: "top", // `top` or `bottom`
                                position: "right", // `left`, `center` or `right`
                                style: {
                                    background: "#C6F048",
                                    color: "#1C1E26",
                                    fontWeight: "bold",
                                    borderRadius: "8px",
                                    boxShadow: "0 4px 15px rgba(0,0,0,0.2)"
                                },
                                onClick: function(){ window.location.href = "{{ route('notifications.index') }}"; } 
                            }).showToast();
                        }
                    });
            }, 10000); // Poll every 10s
        @endauth
    </script>
    @yield('js')
</body>
</html>
