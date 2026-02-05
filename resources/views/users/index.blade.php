@extends('layouts.app')

@section('title', 'Maamulka Shaqaallaha')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">DIIWAANKA SHAQAALLAHA (PERSONNEL REGISTRY)</h1>
        <p style="color: var(--text-sub);">Diiwaanka dhammaan saraakiisha iyo shaqaalaha ka tirsan Xoogga Booliska Soomaaliyeed.</p>
    </div>
    @if(auth()->user()->role->slug == 'admin')
    <a href="{{ route('users.create') }}" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; width: auto; padding: 0.8rem 1.5rem; border-radius: 12px; background: var(--sidebar-bg); border: none; font-weight: 700;">
        <i class="fa-solid fa-user-plus"></i> Diwaangeli Askari
    </a>
    @endif
</div>

@if(session('success'))
<div style="background: #e8f5e9; color: #2e7d32; padding: 1.2rem; border-radius: 15px; margin-bottom: 1.5rem; border-left: 5px solid #4caf50; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
    <i class="fa-solid fa-circle-check fa-lg"></i> 
    <span style="font-weight: 600;">{{ session('success') }}</span>
</div>
@endif

<!-- Interactive Stats Charts Row -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Role Distribution Chart -->
    <div class="glass-card" style="padding: 1.5rem; position: relative;">
        <h5 style="margin: 0 0 1.5rem 0; color: var(--sidebar-bg); font-weight: 800; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
            <i class="fa-solid fa-chart-pie" style="color: #3498db; margin-right: 0.5rem;"></i> Qeybinta Doorka (Role Distribution)
        </h5>
        <div id="roleChart" style="min-height: 250px;"></div>
    </div>
    
    <!-- Rank Distribution Chart -->
    <div class="glass-card" style="padding: 1.5rem; position: relative;">
        <h5 style="margin: 0 0 1.5rem 0; color: var(--sidebar-bg); font-weight: 800; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
            <i class="fa-solid fa-chart-bar" style="color: #2ecc71; margin-right: 0.5rem;"></i> Qeybinta Darajada (Rank Distribution)
        </h5>
        <div id="rankChart" style="min-height: 250px;"></div>
    </div>
</div>

<div class="glass-card" style="padding: 0; overflow: hidden; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.05);">
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-soft); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); flex-wrap: wrap; gap: 1rem;">
        <h5 style="margin: 0; color: var(--sidebar-bg); font-weight: 800; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
            <i class="fa-solid fa-list-ul" style="margin-right: 0.5rem; color: #667eea;"></i> Saraakiisha Diiwaangashan
        </h5>
        
        <!-- Server-Side Search Tool -->
        <form action="{{ route('users.index') }}" method="GET" style="position: relative; width: 350px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.85rem;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Raadi Magac, Darajo ama ID..." style="
                width: 100%;
                padding: 0.7rem 1rem 0.7rem 2.8rem;
                border-radius: 30px;
                border: 1px solid var(--border-soft);
                font-size: 0.85rem;
                outline: none;
                transition: all 0.3s;
                background: #f8f9fa;
            ">
            @if(request('search'))
                <a href="{{ route('users.index') }}" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #ef4444; font-size: 0.8rem; text-decoration: none;">
                    <i class="fa-solid fa-circle-xmark"></i>
                </a>
            @endif
        </form>

        <div style="font-size: 0.8rem; color: var(--text-sub); background: #f0f4ff; padding: 6px 15px; border-radius: 20px; border: 1px solid #d1dbff; font-weight: 700;">
            WADARTA: <strong style="color: var(--sidebar-bg);">{{ $users->total() }}</strong> SARKAAL
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;" id="staffTable">
            <thead>
                <tr style="background: rgba(248, 249, 252, 0.8);">
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; border-bottom: 2px solid #edeff5;">ID / MAGACA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; border-bottom: 2px solid #edeff5;">DARAJO / DOOR</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; border-bottom: 2px solid #edeff5;">XARUNTA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; border-bottom: 2px solid #edeff5;">XAALADA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; text-align: right; border-bottom: 2px solid #edeff5;">MAAREYNTA</th>
                </tr>
            </thead>
            <tbody id="staffBody">
                @forelse($users as $user)
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.2s;" onmouseover="this.style.background='#fcfcfd'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="position: relative;">
                                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=1c1e26&color=fff' }}" 
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1c1e26&color=fff'"
                                     style="width: 48px; height: 48px; border-radius: 12px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                                <div style="position: absolute; bottom: -4px; right: -4px; width: 12px; height: 12px; border-radius: 50%; background: {{ $user->status == 'active' ? '#2ecc71' : '#94a3af' }}; border: 2px solid #fff;"></div>
                            </div>
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-weight: 800; color: var(--sidebar-bg); font-size: 0.95rem;">{{ $user->name }}</span>
                                <span style="font-size: 0.7rem; color: #94a3b8; font-weight: 800;">ID: #SNP-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <span style="font-size: 0.75rem; color: #f39c12; font-weight: 800; text-transform: uppercase;">
                                <i class="fa-solid fa-medal"></i> {{ $user->rank ?? 'ASKARI' }}
                            </span>
                            <span style="font-size: 0.7rem; color: #64748b; font-weight: 700;">{{ $user->role->name }}</span>
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-weight: 700; color: var(--sidebar-bg); font-size: 0.85rem;">{{ $user->station->station_name ?? 'MA GEEYN' }}</span>
                            <span style="font-size: 0.65rem; color: #94a3b8; font-weight: 800; text-transform: uppercase;">{{ $user->region_id ?? 'National' }}</span>
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <span style="
                            padding: 0.35rem 0.8rem; 
                            border-radius: 20px; 
                            font-size: 0.65rem; 
                            font-weight: 900;
                            background: {{ $user->status == 'active' ? '#dcfce7' : '#f1f5f9' }};
                            color: {{ $user->status == 'active' ? '#15803d' : '#64748b' }};
                            text-transform: uppercase;
                            display: inline-flex;
                            align-items: center;
                            gap: 0.3rem;
                        ">
                            <i class="fa-solid fa-circle" style="font-size: 0.4rem;"></i> {{ $user->status == 'active' ? 'FIRFIRCOON' : 'OFFLINE' }}
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                            <a href="{{ route('users.show', $user->id) }}" style="width: 36px; height: 36px; border-radius: 10px; background: #f0f7ff; color: #3498db; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #d1e9ff; transition: 0.3s;" title="View Professional Profile">
                                <i class="fa-solid fa-id-card"></i>
                            </a>
                            @if(auth()->user()->role->slug == 'admin')
                            <a href="{{ route('users.edit', $user->id) }}" style="width: 36px; height: 36px; border-radius: 10px; background: #fffbeb; color: #f59e0b; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #fef3c7; transition: 0.3s;" title="Edit Data">
                                <i class="fa-solid fa-user-pen"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 5rem; text-align: center; color: var(--text-sub);">
                        <i class="fa-solid fa-user-slash fa-4x" style="opacity: 0.1; margin-bottom: 2rem;"></i>
                        <p style="font-weight: 800; font-size: 1.2rem; color: var(--sidebar-bg);">Ma jiro sarkaal la mid ah raadintaada.</p>
                        <a href="{{ route('users.index') }}" style="color: #667eea; font-weight: 700; text-decoration: underline;">Dib u soo saar dhamaan</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Professional Pagination -->
    <div style="padding: 1.5rem; border-top: 1px solid var(--border-soft); background: #f8fafc;">
        {{ $users->links() }}
    </div>
</div>

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Stats for Role Distribution
    var roleOptions = {
        series: @json($roleStats->pluck('count')),
        chart: { type: 'donut', height: 250, fontFamily: 'Outfit, sans-serif' },
        labels: @json($roleStats->pluck('role.name')),
        colors: ['#3498db', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444'],
        legend: { position: 'bottom', fontSize: '11px', fontWeight: 700 },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: { show: true, label: 'WADARTA', fontSize: '12px', fontWeight: 800 }
                    }
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#roleChart"), roleOptions).render();

    // Stats for Rank Distribution
    var rankOptions = {
        series: [{
            name: 'Saraakiisha',
            data: @json($rankStats->pluck('count'))
        }],
        chart: { type: 'bar', height: 250, toolbar: { show: false }, fontFamily: 'Outfit, sans-serif' },
        colors: ['#2ecc71'],
        plotOptions: {
            bar: { borderRadius: 8, horizontal: true, distributed: true }
        },
        xaxis: {
            categories: @json($rankStats->pluck('rank')),
            labels: { style: { colors: '#94a3b8', fontWeight: 700 } }
        },
        yaxis: { labels: { style: { colors: '#94a3b8', fontWeight: 700 } } },
        dataLabels: { enabled: true }
    };
    new ApexCharts(document.querySelector("#rankChart"), rankOptions).render();
</script>
@endsection
@endsection
