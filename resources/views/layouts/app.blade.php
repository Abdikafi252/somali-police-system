<!DOCTYPE html>
<html lang="so">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Somali Police System') }} - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>
<body>
    @auth
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <div class="sidebar" id="sidebar">
        <!-- Inside Sidebar Close Button (Mobile Only) -->
        <button class="sidebar-close-btn" onclick="toggleSidebar()">
            <i class="fa-solid fa-xmark"></i>
        </button>
        
        <div class="sidebar-header" style="padding: 2rem 1.5rem; text-align: center;">
            <div style="position: relative; display: inline-block; margin-bottom: 1rem;">
                <div class="profile-glow"></div>
                <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366f1&color=fff' }}" 
                     alt="Profile" class="profile-img" 
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff'"
                     style="width: 80px; height: 80px; border-radius: 24px; border: 3px solid rgba(255,255,255,0.8); object-fit: cover; box-shadow: 0 10px 25px rgba(0,0,0,0.1); position: relative; z-index: 2;">
            </div>
            <h4 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: white;">{{ auth()->user()->name }}</h4>
            <p style="margin: 4px 0 0; font-size: 0.8rem; opacity: 0.7; color: white; font-weight: 500;">{{ auth()->user()->role->name }}</p>
        </div>

        <nav class="nav-menu" style="padding: 0 1rem 2rem;">
            <div class="nav-section-label">MAGNA (MAIN)</div>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                <i class="fa-solid fa-house-chimney"></i> <span>Dashboard</span>
            </a>

            <div class="nav-section-label">AMNIGA & DAMBIYADA</div>
            @if(auth()->user()->role->slug == 'admin' || auth()->user()->role->slug == 'askari' || auth()->user()->role->slug == 'taliye-saldhig')
            <a href="{{ route('crimes.index') }}" class="nav-link {{ request()->is('crimes*') ? 'active' : '' }}">
                <i class="fa-solid fa-handcuffs"></i> <span>Dambiyada (Crimes)</span>
            </a>
            @endif

            @if(!in_array(auth()->user()->role->slug, ['prosecutor', 'judge']))
            <a href="{{ route('suspects.index') }}" class="nav-link {{ request()->is('suspects*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-ninja"></i> <span>Dambiilayaasha</span>
            </a>
            @endif
            
            <a href="{{ route('cases.index') }}" class="nav-link {{ request()->is('cases') || (request()->is('cases/*') && !request()->has('assigned')) ? 'active' : '' }}">
                <i class="fa-solid fa-folder-tree"></i> <span>Kiisaska (Cases)</span>
            </a>

            @if(auth()->user()->role->slug == 'admin' || auth()->user()->role->slug == 'cid' || auth()->user()->role->slug == 'taliye-saldhig')
            <a href="{{ route('investigations.index') }}" class="nav-link {{ request()->is('investigations*') ? 'active' : '' }}">
                <i class="fa-solid fa-magnifying-glass-location"></i> <span>Baaritaanada</span>
            </a>
            @endif

            <div class="nav-section-label">MAAMULKA (ADMIN)</div>
            @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran']))
            <a href="{{ route('stations.index') }}" class="nav-link {{ request()->is('stations*') ? 'active' : '' }}">
                <i class="fa-solid fa-building-shield"></i> <span>Saldhigyada</span>
            </a>
            <a href="{{ route('station-commanders.index') }}" class="nav-link {{ request()->is('station-commanders*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-tie"></i> <span>Taliyayaasha</span>
            </a>
            @endif

            @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran', 'taliye-saldhig']))
            <a href="{{ route('station-officers.index') }}" class="nav-link {{ request()->is('station-officers*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-shield"></i> <span>Askarta</span>
            </a>
            @endif

            @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-saldhig', 'taliye-gobol']))
            <a href="{{ route('deployments.index') }}" class="nav-link {{ request()->is('deployments*') ? 'active' : '' }}">
                <i class="fa-solid fa-users-gear"></i> <span>Shaqaalaha</span>
            </a>
            <a href="{{ route('facilities.index') }}" class="nav-link {{ request()->is('facilities*') ? 'active' : '' }}">
                <i class="fa-solid fa-house-medical-flag"></i> <span>Xarumaha</span>
            </a>
            @endif

            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i> <span>Warbixinada</span>
            </a>

            @if(auth()->user()->role->slug == 'admin')
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Isticmaalayaasha
            </a>
            
            <a href="{{ route('audit.index') }}" class="nav-link {{ request()->is('audit*') ? 'active' : '' }}">
                <i class="fa-solid fa-clock-rotate-left"></i> Audit Logs
            </a>
            @endif

            <a href="{{ route('chat.index') }}" class="nav-link {{ request()->is('chat*') ? 'active' : '' }}">
                <i class="fa-solid fa-comments"></i> Wada-hadalka
            </a>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                @csrf
            </form>
            <button class="logout-btn-premium" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-power-off"></i> <span>SAX GAX (LOGOUT)</span>
            </button>
        </div>
    </div>
    @endauth

    <main class="{{ Auth::check() ? 'main-content' : '' }}">
        @auth
        <header class="top-bar is-minimized" id="topBar">
            <div class="top-bar-left">
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="greeting">
                    <h2>{{ auth()->user()->name }}</h2>
                    <p>{{ auth()->user()->role->name }}</p>
                </div>
            </div>
            
            <div class="search-bar">
                <form action="{{ route('search') }}" method="GET" style="width: 100%; display: flex; align-items: center;">
                    <i class="fa-solid fa-magnifying-glass" style="color: #636e72; margin-right: 10px;"></i>
                    <input type="text" name="q" placeholder="Raadi..." value="{{ request('q') }}" style="border: none; outline: none; width: 100%; background: transparent;">
                </form>
            </div>

            <div class="top-bar-trigger" onclick="toggleTopBar()">
                <i class="fa-solid fa-chevron-down"></i>
            </div>
        </header>
        @endauth

        @yield('content')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function toggleTopBar() {
            const topBar = document.getElementById('topBar');
            topBar.classList.toggle('is-minimized');
        }

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

        // Mobile Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
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

