@extends('layouts.app')

@section('title', 'Diiwaanka Saldhigyada')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">DIIWAANKA SALDHIGYADA (STATION REGISTRY)</h1>
        <p style="color: var(--text-sub);">Diiwaanka rasmiga ah ee dhamaan Saldhigyada Booliska Soomaaliyeed.</p>
    </div>
    @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-qaran']))
    <a href="{{ route('stations.create') }}" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; width: auto; padding: 0.8rem 1.5rem; border-radius: 12px; background: var(--sidebar-bg); border: none; font-weight: 700;">
        <i class="fa-solid fa-plus-circle"></i> Kordhi Saldhig Cusub
    </a>
    @endif
</div>

@if(session('success'))
<div style="background: #e8f5e9; color: #2e7d32; padding: 1.2rem; border-radius: 15px; margin-bottom: 1.5rem; border-left: 5px solid #4caf50; display: flex; align-items: center; gap: 1rem;">
    <i class="fa-solid fa-circle-check fa-lg"></i> 
    <span style="font-weight: 600;">{{ session('success') }}</span>
</div>
@endif

<!-- Registry Controls -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div style="position: relative; width: 350px;">
        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
        <input type="text" id="stationSearch" placeholder="Raadi Magaca Saldhigga ama Goobta..." style="
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.8rem;
            border-radius: 15px;
            border: 1px solid var(--border-soft);
            background: white;
            font-size: 0.9rem;
            outline: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        ">
    </div>
    <div style="background: #fff; padding: 0.5rem 1.25rem; border-radius: 12px; border: 1px solid var(--border-soft); font-size: 0.85rem; font-weight: 700; color: var(--sidebar-bg);">
        Wajarta: <span style="color: #667eea;">{{ $stations->count() }}</span> Saldhigyo Diiwaangashan
    </div>
</div>

<div class="glass-card" style="padding: 0; overflow: hidden; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.05);">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;" id="registryTable">
            <thead>
                <tr style="background: #f8f9fc; border-bottom: 2px solid #edeff5;">
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">SALDHIGGA / NOOCA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">GOOBTA (LOCATION)</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">DIIWAANGELIN (COA)</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">SARAAKIISHA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; text-align: right;">MAAREYNTA</th>
                </tr>
            </thead>
            <tbody id="registryBody">
                @forelse($stations as $station)
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.25s ease;" onmouseover="this.style.background='rgba(102, 126, 234, 0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1.2rem;">
                            <div style="width: 50px; height: 50px; border-radius: 14px; background: linear-gradient(135deg, #1e293b, #0f172a); color: #c6f048; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-weight: 800; color: var(--sidebar-bg); font-size: 1.1rem; letter-spacing: -0.5px;">{{ $station->station_name }}</span>
                                <span style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">ID: #STN-{{ str_pad($station->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.6rem; color: var(--sidebar-bg); font-weight: 700; font-size: 0.95rem;">
                            <i class="fa-solid fa-location-dot" style="color: #e74c3c;"></i>
                            {{ $station->location }}
                        </div>
                        <span style="font-size: 0.75rem; color: #94a3b8; display: block; margin-top: 4px; padding-left: 1.2rem;">Saldhigga Degmada</span>
                    </td>
                    <td style="padding: 1.5rem;">
                        <span style="font-size: 0.85rem; color: var(--sidebar-bg); font-weight: 600; display: block;">{{ $station->created_at->format('M d, Y') }}</span>
                        <span style="font-size: 0.7rem; color: #27ae60; font-weight: 800; text-transform: uppercase;">Diiwaangashan</span>
                    </td>
                    <td style="padding: 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 0.3rem;">
                            <span style="font-weight: 800; color: #1e293b; font-size: 0.95rem;">{{ $station->officer_count ?? 0 }} Askari</span>
                            @if($station->commander)
                                <span style="font-size: 0.75rem; color: #f39c12; font-weight: 700; display: flex; align-items: center; gap: 0.3rem;">
                                    <i class="fa-solid fa-user-tie"></i> {{ $station->commander->name }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td style="padding: 1.5rem; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                            <a href="{{ route('stations.show', $station->id) }}" style="width: 38px; height: 38px; border-radius: 12px; background: #f0f7ff; color: #3498db; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #d1e9ff; transition: 0.3s;" title="Xogta Faahfaahsan">
                                <i class="fa-solid fa-id-card-clip"></i>
                            </a>
                            @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-qaran']))
                            <a href="{{ route('stations.edit', $station->id) }}" style="width: 38px; height: 38px; border-radius: 12px; background: #fdfae3; color: #f1c40f; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #f9f1c7; transition: 0.3s;" title="Wax ka bedel">
                                <i class="fa-solid fa-pen-nib"></i>
                            </a>
                            <form action="{{ route('stations.destroy', $station->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ma hubtaa inaad tirtirto saldhiggan?');">
                                @csrf @method('DELETE')
                                <button type="submit" style="width: 38px; height: 38px; border-radius: 12px; background: #fff5f5; color: #e74c3c; display: flex; align-items: center; justify-content: center; border: 1px solid #ffe3e3; cursor: pointer; transition: 0.3s;" title="Tirtir">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 6rem; text-align: center; color: var(--text-sub);">
                        <i class="fa-solid fa-building-circle-exclamation fa-4x" style="opacity: 0.1; margin-bottom: 2rem;"></i>
                        <p style="font-weight: 800; font-size: 1.3rem; color: var(--sidebar-bg); margin: 0;">Ma jiraan saldhigyo diiwaangashan wali.</p>
                        <p style="font-size: 0.95rem; margin-top: 0.5rem;">Riix barta (Kordhi Saldhig) si aad dalka ugu darto saldhig cusub.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@section('js')
<script>
    document.getElementById('stationSearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#registryBody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    });
</script>
@endsection
@endsection
