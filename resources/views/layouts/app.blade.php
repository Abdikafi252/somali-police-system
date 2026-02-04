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
    <i class="fa-solid fa-bars mobile-toggle" style="display: none;" onclick="toggleSidebar()"></i>

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

                @if(auth()->user()->role->slug == 'admin' || auth()->user()->role->slug == 'cid' || auth()->user()->role->slug == 'askari')
                <div class="nav-section-title">Operations</div>
                <a href="{{ route('cases.index') }}" class="nav-link {{ request()->is('cases*') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder-open"></i> <span>Cases</span>
                </a>
                <a href="{{ route('crimes.index') }}" class="nav-link {{ request()->is('crimes*') ? 'active' : '' }}">
                    <i class="fa-solid fa-handcuffs"></i> <span>Crimes</span>
                </a>
                <a href="{{ route('suspects.index') }}" class="nav-link {{ request()->is('suspects*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-secret"></i> <span>Suspects</span>
                </a>
                @endif

                @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-saldhig', 'taliye-gobol']))
                <div class="nav-section-title">Administration</div>
                <a href="{{ route('stations.index') }}" class="nav-link {{ request()->is('stations*') ? 'active' : '' }}">
                    <i class="fa-solid fa-building-shield"></i> <span>Stations</span>
                </a>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-group"></i> <span>Officers</span>
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

            <!-- Download App Promo -->
            <div class="sidebar-promo">
                <div style="font-size: 0.85rem; font-weight: 600; margin-bottom: 0.5rem; color: white;">Download Mobile App</div>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.8rem;">Get access to cases on the go.</div>
                <button style="background: var(--accent-lime); color: var(--sidebar-bg); border: none; border-radius: 8px; padding: 6px 12px; font-size: 0.75rem; font-weight: 700; cursor: pointer; width: 100%;">Get App</button>
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
                    <div class="search-bar">
                        <i class="fa-solid fa-magnifying-glass" style="color: var(--text-muted);"></i>
                        <input type="text" placeholder="Search cases, suspects, files...">
                    </div>

                    <!-- Actions -->
                    <button class="icon-btn">
                        <i class="fa-regular fa-bell"></i>
                        <div class="badge">3</div>
                    </button>
                    
                    <button class="icon-btn">
                        <i class="fa-regular fa-calendar"></i>
                    </button>
                    
                    <div class="user-profile">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=C6F048&color=1C1E26" style="width: 45px; height: 45px; border-radius: 50%; border: 2px solid white; box-shadow: var(--shadow-soft);">
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
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>

        @auth
            const userTheme = "{{ auth()->user()->settings->theme ?? 'light' }}";
            document.body.setAttribute('data-theme', userTheme);
        @endauth

        function toggleNotifications() {
            var list = document.getElementById('notification-list');
            if (list.style.display === 'none' || list.style.display === '') {
                list.style.display = 'block';
            } else {
                list.style.display = 'none';
            }
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.fa-bell') && !event.target.closest('.notification-dropdown')) {
                var dropdowns = document.getElementsByClassName("notification-dropdown");
                var list = document.getElementById('notification-list');
                if (list && list.style.display === 'block') {
                    list.style.display = 'none';
                }
            }
        }
    </script>
    
    <script>
        // Notification Sound Logic
        (function() {
            @auth
            const soundEnabled = {{ auth()->user()->settings?->notification_sound ? 'true' : 'false' }};
            const soundFile = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3'); // Simple notification bell
            
            if (soundEnabled) {
                setInterval(() => {
                    fetch("{{ route('notifications.check') }}")
                        .then(response => response.json())
                        .then(data => {
                            if (data.count > 0) {
                                soundFile.play().catch(e => console.log('Audio play failed', e));
                            }
                        });
                }, 30000); // Check every 30 seconds
            }
            @endauth
        })();

        // Sidebar Toggle (Mobile & Desktop)
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            const mainContent = document.querySelector('.main-content');
            
            if (window.innerWidth <= 1024) {
                // Mobile behavior
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            } else {
                // Desktop behavior
                sidebar.classList.toggle('collapsed');
                if (mainContent) {
                    mainContent.classList.toggle('expanded');
                }
            }
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target) && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            }
        });
    </script>
    @yield('js')
</body>
</html>

