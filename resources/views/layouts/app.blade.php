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
        <div class="sidebar-profile">
            <a href="{{ route('profile.show') }}" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 10px; width: 100%;">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile" class="profile-img" style="object-fit: cover;">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1abc9c&color=fff" alt="Profile" class="profile-img">
                @endif
                <div class="profile-info">
                    <h4>{{ auth()->user()->name }}</h4>
                    <p>{{ auth()->user()->email }}</p>
                </div>
            </a>
        </div>
        
        <nav class="nav-menu">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>

            @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran']))
            <a href="{{ route('stations.index') }}" class="nav-link {{ request()->is('stations*') ? 'active' : '' }}">
                <i class="fa-solid fa-house-chimney-crack"></i> Saldhigyada
            </a>
            <a href="{{ route('station-commanders.index') }}" class="nav-link {{ request()->is('station-commanders*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-tie"></i> Taliyayaasha Saldhiga
            </a>
            @endif

            @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran', 'taliye-saldhig']))
            <a href="{{ route('station-officers.index') }}" class="nav-link {{ request()->is('station-officers*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-shield"></i> Askarta Saldhigyada
            </a>
            @endif
            
            @if(auth()->user()->role->slug == 'admin' || auth()->user()->role->slug == 'askari' || auth()->user()->role->slug == 'taliye-saldhig')
            <a href="{{ route('crimes.index') }}" class="nav-link {{ request()->is('crimes*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice"></i> Dambiyada
            </a>
            @endif

            @if(!in_array(auth()->user()->role->slug, ['prosecutor', 'judge']))
            <a href="{{ route('suspects.index') }}" class="nav-link {{ request()->is('suspects*') ? 'active' : '' }}">
                <i class="fa-solid fa-users-viewfinder"></i> Dambiilayaasha
            </a>
            @endif
            

            <a href="{{ route('cases.index') }}" class="nav-link {{ request()->is('cases') || (request()->is('cases/*') && !request()->has('assigned')) ? 'active' : '' }}">
                <i class="fa-solid fa-briefcase"></i> Kiisaska
            </a>

            @if(auth()->user()->role->slug == 'cid' || auth()->user()->role->slug == 'askari')
            <a href="{{ route('cases.index', ['assigned' => 'me']) }}" class="nav-link {{ request()->has('assigned') ? 'active' : '' }}">
                <i class="fa-solid fa-list-check"></i> Kiisaska la ii xil-saaray
            </a>
            @endif

            @if(auth()->user()->role->slug == 'admin' || auth()->user()->role->slug == 'cid' || auth()->user()->role->slug == 'taliye-saldhig')
            <a href="{{ route('investigations.index') }}" class="nav-link {{ request()->is('investigations*') ? 'active' : '' }}">
                <i class="fa-solid fa-magnifying-glass-chart"></i> Baaritaanada
            </a>
            @endif

            @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-saldhig', 'taliye-gobol']))
            <a href="{{ route('deployments.index') }}" class="nav-link {{ request()->is('deployments*') ? 'active' : '' }}">
                <i class="fa-solid fa-shield-halved"></i> Shaqaalaha
            </a>
            @endif

            @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-saldhig', 'taliye-gobol']))
            <a href="{{ route('facilities.index') }}" class="nav-link {{ request()->is('facilities*') ? 'active' : '' }}">
                <i class="fa-solid fa-building-shield"></i> Xarumaha
            </a>
            @endif

            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Warbixinada
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
            <a href="#" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>
    @endauth

    <main class="{{ Auth::check() ? 'main-content' : '' }}">
        @auth
        <header class="top-bar">
            <div class="top-bar-left">
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="greeting">
                    <h2>Soo dhawoow, {{ auth()->user()->name }}! 👋</h2>
                    <p>{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
            
            <div class="search-bar">
                <form action="{{ route('search') }}" method="GET" style="width: 100%; display: flex; align-items: center;">
                    <i class="fa-solid fa-magnifying-glass" style="color: #636e72; margin-right: 10px;"></i>
                    <input type="text" name="q" placeholder="Raadi kiisaska, dambiilayaasha..." value="{{ request('q') }}" style="border: none; outline: none; width: 100%; background: transparent;">
                </form>
            </div>
            <div class="utility-icons" style="display: flex; gap: 15px; align-items: center;">
                
                <!-- Notification Dropdown -->
                <div class="notification-dropdown" style="position: relative;">
                    <i class="fa-regular fa-bell" style="cursor: pointer; color: #636e72; font-size: 1.1rem;" onclick="toggleNotifications()"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span style="position: absolute; top: -5px; right: -5px; background: #e74c3c; color: white; border-radius: 50%; width: 16px; height: 16px; font-size: 0.6rem; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif

                    <div id="notification-list" style="display: none; position: absolute; top: 30px; right: 0; background: white; width: 300px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 1px solid var(--border-soft); z-index: 1000; overflow: hidden;">
                        <div style="padding: 10px 15px; border-bottom: 1px solid var(--border-soft); font-weight: 700; color: var(--sidebar-bg); display: flex; justify-content: space-between; align-items: center;">
                            <span>Notifications</span>
                            <div style="display: flex; gap: 10px;">
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" id="mark-all-read-form" style="display: none;">@csrf</form>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('mark-all-read-form').submit();" style="font-size: 0.7rem; color: #3498db; text-decoration: none;">Mark all read</a>
                                @endif
                                <a href="{{ route('notifications.index') }}" style="font-size: 0.7rem; color: var(--primary); text-decoration: none;">View All</a>
                            </div>
                        </div>
                        <div style="max-height: 400px; overflow-y: auto;">
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                <div style="padding: 12px 15px; border-bottom: 1px solid #f1f2f6; background: #fff; transition: 0.2s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='#fff'">
                                    
                                    @if(isset($notification->data['type']) && $notification->data['type'] == 'suspect_alert')
                                        <!-- Suspect Notification -->
                                        <div style="display: flex; gap: 10px; align-items: start;">
                                            @if(!empty($notification->data['photo']))
                                                <img src="{{ asset('storage/' . $notification->data['photo']) }}" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                                            @else
                                                <div style="width: 40px; height: 40px; border-radius: 8px; background: #e74c3c; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                    {{ substr($notification->data['message'], 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div style="font-weight: 700; font-size: 0.85rem; color: #e74c3c; margin-bottom: 2px;">
                                                    <i class="fa-solid fa-handcuffs"></i> Dambiile Cusub
                                                </div>
                                                <div style="font-size: 0.8rem; color: var(--sidebar-bg); font-weight: 600;">{{ $notification->data['name'] }} ({{ $notification->data['age'] }} sano)</div>
                                                <div style="font-size: 0.75rem; color: var(--text-sub);">
                                                    <strong>{{ $notification->data['crime_type'] }}</strong> - {{ $notification->data['case_number'] }}
                                                </div>
                                                <div style="font-size: 0.7rem; color: var(--text-sub); margin-top: 2px;">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Standard Crime Notification -->
                                        <div style="font-weight: 700; font-size: 0.85rem; color: #3498db; margin-bottom: 2px;">
                                            <i class="fa-solid fa-file-invoice"></i> {{ $notification->data['message'] ?? 'New Notification' }}
                                        </div>
                                        @if(isset($notification->data['description']))
                                            <div style="font-size: 0.75rem; color: #636e72; margin-bottom: 3px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                {{ $notification->data['description'] }}
                                            </div>
                                        @endif
                                        <div style="font-size: 0.7rem; color: #b2bec3;">
                                            {{ $notification->created_at->diffForHumans() }}
                                            @if(isset($notification->data['reporter_name']))
                                                <span class="mx-1">•</span> <i class="fa-solid fa-user-shield"></i> {{ $notification->data['reporter_name'] }}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div style="padding: 20px; text-align: center; color: var(--text-sub); font-size: 0.85rem;">
                                    No new notifications
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>


            </div>
        </header>
        @endauth

        @yield('content')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Initialize Theme
        (function() {
            @auth
                const userTheme = "{{ auth()->user()->settings->theme ?? 'light' }}";
                document.body.setAttribute('data-theme', userTheme);
            @endauth
        })();

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

