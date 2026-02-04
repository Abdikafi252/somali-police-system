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
                            <span style="font-size: 0.85rem;">Notifications</span>
                            <a href="{{ route('notifications.index') }}" style="font-size: 0.75rem; color: #6366f1; text-decoration: none;">View All</a>
                        </div>
                        <div style="max-height: 350px; overflow-y: auto;">
                            @forelse(auth()->user()->unreadNotifications as $notification)
                                <div style="padding: 12px 15px; border-bottom: 1px solid rgba(0,0,0,0.03); background: #fff; transition: 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                                    <div style="font-weight: 700; font-size: 0.8rem; color: #6366f1; margin-bottom: 2px;">
                                        {{ $notification->data['message'] ?? 'New Alert' }}
                                    </div>
                                    <div style="font-size: 0.7rem; color: #94a3b8;">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @empty
                                <div style="padding: 30px 20px; text-align: center; color: var(--text-sub); font-size: 0.8rem;">
                                    <i class="fa-solid fa-bell-slash" style="display: block; font-size: 1.5rem; margin-bottom: 8px; opacity: 0.3;"></i>
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

