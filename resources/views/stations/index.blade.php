@extends('layouts.app')

@section('title', 'Saldhigyada')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">SALDHIGYADA BOOLISKA</h1>
        <p style="color: var(--text-sub);">Maamulka iyo liiska dhammaan saldhigyada booliska ee dalka.</p>
    </div>
    @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-qaran']))
    <a href="{{ route('stations.create') }}" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; width: auto; padding: 0.8rem 1.5rem; border-radius: 12px; background: var(--sidebar-bg); border: none; font-weight: 700;">
        <i class="fa-solid fa-plus-circle"></i> Diiwaangeli Saldhig
    </a>
    @endif
</div>

@if(session('success'))
<div style="background: #e8f5e9; color: #2e7d32; padding: 1.2rem; border-radius: 15px; margin-bottom: 1.5rem; border-left: 5px solid #4caf50; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
    <i class="fa-solid fa-circle-check fa-lg"></i> 
    <span style="font-weight: 600;">{{ session('success') }}</span>
</div>
@endif

<div class="glass-card" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-soft); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.5);">
        <h5 style="margin: 0; color: var(--sidebar-bg); font-weight: 800; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Saldhigyada Diiwaangashan</h5>
        <div style="font-size: 0.8rem; color: var(--text-sub);">
            Wajarta: <strong>{{ $stations->count() }}</strong> Saldhig
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8f9fc;">
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Magaca Saldhigga</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Goobta / Degmada</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Askarta Joogta</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; text-align: right;">Maareynta</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stations as $station)
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.2s;" onmouseover="this.style.background='#fcfcfd'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 45px; height: 45px; border-radius: 12px; background: linear-gradient(135deg, #2c3e50, #000); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-weight: 800; color: var(--sidebar-bg); font-size: 1rem;">{{ $station->station_name }}</span>
                                <span style="font-size: 0.7rem; color: var(--text-sub); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">POLICE STATION</span>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--sidebar-bg); font-weight: 700; font-size: 0.9rem;">
                            <i class="fa-solid fa-location-dot" style="color: #e74c3c; font-size: 0.8rem;"></i>
                            {{ $station->location }}
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <span style="
                            padding: 0.45rem 1rem; 
                            border-radius: 12px; 
                            font-size: 0.75rem; 
                            font-weight: 800;
                            background: rgba(52, 152, 219, 0.1);
                            color: #2980b9;
                            border: 1px solid rgba(52, 152, 219, 0.2);
                            display: inline-flex;
                            align-items: center;
                            gap: 0.4rem;
                        ">
                            <i class="fa-solid fa-users"></i> {{ $station->officer_count ?? 0 }} ASKARI
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.6rem;">
                            <a href="{{ route('stations.show', $station->id) }}" style="width: 35px; height: 35px; border-radius: 10px; background: #f0f7ff; color: #007bff; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #cce5ff; transition: 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="View Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-qaran']))
                            <a href="{{ route('stations.edit', $station->id) }}" style="width: 35px; height: 35px; border-radius: 10px; background: #f0fff4; color: #28a745; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #d1e7dd; transition: 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="Edit Station">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('stations.destroy', $station->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ma hubtaa inaad tirtirto saldhiggan? Dhammaan xogta la xiriirta waa la waayayaa.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="width: 35px; height: 35px; border-radius: 10px; background: #fff5f5; color: #dc3545; display: flex; align-items: center; justify-content: center; border: 1px solid #f8d7da; cursor: pointer; transition: 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="Delete">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 5rem; text-align: center; color: var(--text-sub);">
                        <div style="opacity: 0.1; margin-bottom: 1.5rem;">
                            <i class="fa-solid fa-building-circle-exclamation fa-5x"></i>
                        </div>
                        <p style="font-weight: 800; font-size: 1.2rem; color: var(--sidebar-bg);">Ma jiraan saldhigyo diiwaangashan.</p>
                        <p style="font-size: 0.9rem;">Riix barta (Diiwaangeli Saldhig) si aad mid u kordhiso.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
