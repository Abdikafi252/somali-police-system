@extends('layouts.app')

@section('title', 'Maamulka Shaqaallaha')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">MAAMULKA SHAQAALLAHA</h1>
        <p style="color: var(--text-sub);">Diiwaanka dhammaan saraakiisha iyo shaqaalaha ka tirsan Xoogga Booliska.</p>
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

<div class="glass-card" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-soft); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.5);">
        <h5 style="margin: 0; color: var(--sidebar-bg); font-weight: 800; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Saraakiisha Diiwaangashan</h5>
        <div style="font-size: 0.8rem; color: var(--text-sub);">
            Wajarta: <strong>{{ $users->count() }}</strong> Sarkaal
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8f9fc;">
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Sarkaalka / Darajada</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Doorka</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Xarunta / Saldhigga</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Xaalada</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; text-align: right;">Maareynta</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.2s;" onmouseover="this.style.background='#fcfcfd'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="position: relative;">
                                <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=3498db&color=fff' }}" 
                                     alt="Profile"
                                     class="user-profile-img"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=3498db&color=fff'"
                                     style="width: 48px; height: 48px; border-radius: 12px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                                <div style="position: absolute; bottom: -5px; right: -5px; width: 14px; height: 14px; border-radius: 50%; background: {{ $user->status == 'active' ? '#2ecc71' : '#95a5a6' }}; border: 2px solid #fff;"></div>
                            </div>
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-weight: 800; color: var(--sidebar-bg); font-size: 0.95rem;">{{ $user->name }}</span>
                                <span style="font-size: 0.75rem; color: #f39c12; font-weight: 700; display: flex; align-items: center; gap: 0.3rem;">
                                    <i class="fa-solid fa-medal"></i> {{ $user->rank ?? 'ASKARI' }}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <span style="
                            padding: 0.4rem 0.8rem; 
                            border-radius: 10px; 
                            font-size: 0.7rem; 
                            font-weight: 800;
                            text-transform: uppercase;
                            background: {{ $user->role->slug == 'admin' ? '#e3f2fd' : ($user->role->slug == 'cid' ? '#fff3e0' : ($user->role->slug == 'prosecutor' ? '#f3e5f5' : '#f0f2f5')) }};
                            color: {{ $user->role->slug == 'admin' ? '#1976d2' : ($user->role->slug == 'cid' ? '#f57c00' : ($user->role->slug == 'prosecutor' ? '#7b1fa2' : '#475569')) }};
                            border: 1px solid {{ $user->role->slug == 'admin' ? '#bbdefb' : ($user->role->slug == 'cid' ? '#ffe0b2' : ($user->role->slug == 'prosecutor' ? '#e1bee7' : '#e2e8f0')) }};
                        ">
                            <i class="fa-solid {{ $user->role->slug == 'admin' ? 'fa-user-tie' : ($user->role->slug == 'cid' ? 'fa-user-secret' : ($user->role->slug == 'prosecutor' ? 'fa-scale-balanced' : 'fa-user-shield')) }}" style="margin-right: 0.3rem;"></i>
                            {{ $user->role->name ?? 'Ma jiro' }}
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; flex-direction: column;">
                            <span style="font-weight: 700; color: var(--sidebar-bg); font-size: 0.85rem;">{{ $user->station->station_name ?? 'Lama geeyn' }}</span>
                            <span style="font-size: 0.7rem; color: var(--text-sub);">{{ $user->region_id ?? 'Gobolka lama yaqaan' }}</span>
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <span style="
                            padding: 0.4rem 0.8rem; 
                            border-radius: 10px; 
                            font-size: 0.7rem; 
                            font-weight: 800;
                            background: {{ $user->status == 'active' ? 'rgba(46, 204, 113, 0.1)' : 'rgba(149, 165, 166, 0.1)' }};
                            color: {{ $user->status == 'active' ? '#27ae60' : '#7f8c8d' }};
                        ">
                            {{ $user->status == 'active' ? 'FIRFIRCOON' : 'MACLAL' }}
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                            <a href="{{ route('users.show', $user->id) }}" style="width: 32px; height: 32px; border-radius: 8px; background: #f0f7ff; color: #007bff; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #cce5ff;" title="View Profile">
                                <i class="fa-solid fa-user"></i>
                            </a>
                            @if(auth()->user()->role->slug == 'admin')
                            <a href="{{ route('users.edit', $user->id) }}" style="width: 32px; height: 32px; border-radius: 8px; background: #f0fff4; color: #28a745; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #d1e7dd;" title="Edit Data">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ma hubtaa inaad tirtirto sarkaalkan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="width: 32px; height: 32px; border-radius: 8px; background: #fff5f5; color: #dc3545; display: flex; align-items: center; justify-content: center; border: 1px solid #f8d7da; cursor: pointer;" title="Delete">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 4rem; text-align: center; color: var(--text-sub);">
                        <div style="opacity: 0.2; margin-bottom: 1rem;">
                            <i class="fa-solid fa-users-slash fa-4x"></i>
                        </div>
                        <p style="font-weight: 700; font-size: 1.1rem;">Ma jiraan saraakiil diiwaangashan wali.</p>
                        <p style="font-size: 0.85rem;">Isticmaal badhanka kor ku yaal si aad mid cusub ugu darto.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
