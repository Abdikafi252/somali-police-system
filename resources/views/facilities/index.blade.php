@extends('layouts.app')

@section('title', 'Xarumaha')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">XARUMAHA GAARKA AH</h1>
        <p style="color: var(--text-sub);">Diiwaanka xarumaha, baraha hubinta, iyo unugyada gaarka ah.</p>
    </div>
    @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-qaran']))
    <a href="{{ route('facilities.create') }}" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; width: auto; padding: 0.8rem 1.5rem; border-radius: 12px; background: var(--sidebar-bg); border: none; font-weight: 700;">
        <i class="fa-solid fa-plus-circle"></i> Diiwaangeli Xarun
    </a>
    @endif
</div>

@if(session('success'))
<div style="background: #e8f5e9; color: #2e7d32; padding: 1.2rem; border-radius: 15px; margin-bottom: 1.5rem; border-left: 5px solid #4caf50; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
    <i class="fa-solid fa-circle-check fa-lg"></i> 
    <span style="font-weight: 600;">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div style="background: #fce8e6; color: #c0392b; padding: 1.2rem; border-radius: 15px; margin-bottom: 1.5rem; border-left: 5px solid #e74c3c; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
    <i class="fa-solid fa-circle-exclamation fa-lg"></i> 
    <span style="font-weight: 600;">{{ session('error') }}</span>
</div>
@endif

<div class="glass-card" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-soft); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.5);">
        <h5 style="margin: 0; color: var(--sidebar-bg); font-weight: 800; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Xarumaha Booliska</h5>
        <div style="font-size: 0.8rem; color: var(--text-sub);">
            Wajarta: <strong>{{ $facilities->count() }}</strong> Xarun
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8f9fc;">
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Magaca Xarunta</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Nooca / Qaybta</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Goobta</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Taliyaha Xarunta</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; text-align: right;">Heerka Amniga</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($facilities as $fac)
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.2s;" onmouseover="this.style.background='#fcfcfd'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 45px; height: 45px; border-radius: 12px; background: linear-gradient(135deg, #27ae60, #16a085); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                <i class="fa-solid {{ $fac->type == 'Prision' ? 'fa-building-lock' : ($fac->type == 'Airport' ? 'fa-plane-up' : 'fa-building-user') }}"></i>
                            </div>
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-weight: 800; color: var(--sidebar-bg); font-size: 0.95rem;">{{ $fac->name }}</span>
                                <span style="font-size: 0.65rem; color: var(--text-sub); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">{{ $fac->type }}</span>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <span style="
                            padding: 0.4rem 0.8rem; 
                            border-radius: 8px; 
                            font-size: 0.7rem; 
                            font-weight: 800;
                            background: #f0f2f5;
                            color: #475569;
                            text-transform: uppercase;
                        ">
                            {{ $fac->type }}
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--sidebar-bg); font-weight: 700; font-size: 0.85rem;">
                            <i class="fa-solid fa-location-arrow" style="color: #3498db; font-size: 0.8rem;"></i>
                            {{ $fac->location }}
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.8rem;">
                            @if($fac->commander)
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($fac->commander->name) }}&background=random" style="width: 32px; height: 32px; border-radius: 8px;">
                                <div style="display: flex; flex-direction: column;">
                                    <span style="font-weight: 700; color: var(--sidebar-bg); font-size: 0.85rem;">{{ $fac->commander->name }}</span>
                                    <span style="font-size: 0.65rem; color: #f39c12; font-weight: 700;">{{ $fac->commander->rank ?? 'TALIYE' }}</span>
                                </div>
                            @else
                                <span style="color: #ccc; font-size: 0.85rem; font-style: italic;">Lama magacaabin</span>
                            @endif
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem; text-align: right;">
                        <span style="
                            padding: 0.4rem 0.8rem; 
                            border-radius: 8px; 
                            font-size: 0.7rem; 
                            font-weight: 800;
                            background: {{ $fac->security_level == 'High' ? '#ffebee' : ($fac->security_level == 'Medium' ? '#fff3e0' : '#e8f5e9') }};
                            color: {{ $fac->security_level == 'High' ? '#c62828' : ($fac->security_level == 'Medium' ? '#ef6c00' : '#2e7d32') }};
                            border: 1px solid {{ $fac->security_level == 'High' ? '#ffcdd2' : ($fac->security_level == 'Medium' ? '#ffe0b2' : '#c8e6c9') }};
                        ">
                            <i class="fa-solid fa-shield-halved"></i> {{ strtoupper($fac->security_level) }}
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                            <a href="{{ route('facilities.show', $fac->id) }}" class="btn-primary" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 8px; width: auto; background: #3498db; border: none;" title="View">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-qaran']))
                            <a href="{{ route('facilities.edit', $fac->id) }}" class="btn-primary" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 8px; width: auto; background: #f1c40f; border: none; color: black;" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('facilities.destroy', $fac->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ma hubtaa inaad tirtirto xaruntan?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-primary" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 8px; width: auto; background: #e74c3c; border: none;" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 5rem; text-align: center; color: var(--text-sub);">
                        <div style="opacity: 0.1; margin-bottom: 1.5rem;">
                            <i class="fa-solid fa-building-circle-exclamation fa-5x"></i>
                        </div>
                        <p style="font-weight: 800; font-size: 1.2rem; color: var(--sidebar-bg);">Ma jiraan xarumo kale oo diiwaangashan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
