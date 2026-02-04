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
    @auth
    <!-- Glass Sidebar -->
    <aside class="glass-sidebar">
        <div class="sidebar-logo">
            <span style="color: var(--neon-blue);">Somali</span>Police
        </div>
        
        <div class="sidebar-menu">
            <div style="font-size: 0.75rem; color: var(--text-secondary); margin: 1rem 0 0.5rem 1rem; text-transform: uppercase;">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
                <i class="fa-solid fa-house-chimney"></i> <span>Dashboard</span>
            </a>

            <div style="font-size: 0.75rem; color: var(--text-secondary); margin: 1rem 0 0.5rem 1rem; text-transform: uppercase;">Operations</div>
            
            @if(auth()->user()->role->slug == 'admin' || auth()->user()->role->slug == 'cid' || auth()->user()->role->slug == 'askari')
            <a href="{{ route('cases.index') }}" class="nav-item {{ request()->is('cases*') ? 'active' : '' }}">
                <i class="fa-solid fa-folder-tree"></i> <span>Cases</span>
            </a>
            <a href="{{ route('crimes.index') }}" class="nav-item {{ request()->is('crimes*') ? 'active' : '' }}">
                <i class="fa-solid fa-handcuffs"></i> <span>Crimes</span>
            </a>
            <a href="{{ route('suspects.index') }}" class="nav-item {{ request()->is('suspects*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-ninja"></i> <span>Suspects</span>
            </a>
            @endif

            @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-saldhig', 'taliye-gobol']))
            <div style="font-size: 0.75rem; color: var(--text-secondary); margin: 1rem 0 0.5rem 1rem; text-transform: uppercase;">Management</div>
            <a href="{{ route('deployments.index') }}" class="nav-item {{ request()->is('deployments*') ? 'active' : '' }}">
                <i class="fa-solid fa-users-gear"></i> <span>Deployments</span>
            </a>
            <a href="{{ route('stations.index') }}" class="nav-item {{ request()->is('stations*') ? 'active' : '' }}">
                <i class="fa-solid fa-building-shield"></i> <span>Stations</span>
            </a>
            @endif

            <div style="margin-top: auto;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-item" style="width: 100%; border: none; background: transparent; cursor: pointer; color: #ef4444;">
                        <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Glass Header (Topbar) -->
    <header class="glass-header">
        <!-- Search -->
        <div class="search-box-glass">
            <i class="fa-solid fa-magnifying-glass" style="color: var(--text-secondary);"></i>
            <input type="text" placeholder="Search anything..." style="margin-left: 0.5rem; color: var(--text-primary);">
        </div>

        <!-- Right Side Icons -->
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <!-- Theme Toggle -->
            <button onclick="toggleTheme()" style="background: transparent; border: none; cursor: pointer; color: var(--text-primary); font-size: 1.2rem;">
                <i class="fa-solid fa-moon" id="theme-icon"></i>
            </button>
            
            <div style="width: 1px; height: 24px; background: var(--glass-border);"></div>

            <!-- Profile -->
            <div style="display: flex; align-items: center; gap: 0.8rem;">
                <div style="text-align: right; display: none; @media(min-width: 768px){display: block;}">
                    <div style="color: var(--text-primary); font-weight: 600; font-size: 0.9rem;">{{ auth()->user()->name }}</div>
                    <div style="color: var(--text-secondary); font-size: 0.8rem;">{{ auth()->user()->role->name ?? 'Officer' }}</div>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=3b82f6&color=fff" style="width: 40px; height: 40px; border-radius: 10px; border: 2px solid rgba(255,255,255,0.1);">
            </div>
        </div>
    </header>

    <!-- Main Content Wrapper -->
    <main class="main-content">
        @yield('content')
    </main>
    @else
        <!-- Guest View (Login) -->
        <main>
            @yield('content')
        </main>
    @endauth

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

