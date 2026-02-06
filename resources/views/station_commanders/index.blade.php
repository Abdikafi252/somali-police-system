@extends('layouts.app')

@section('title', 'Taliyayaasha Saldhigyada')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">TALIYAYAASHA SALDHIGYADA</h1>
        <p style="color: var(--text-sub);">Maamulka iyo magacaabista taliyayaasha saldhigyada dalka.</p>
    </div>
    <a href="{{ route('station-commanders.create') }}" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; width: auto; padding: 0.8rem 1.5rem;">
        <i class="fa-solid fa-plus"></i> Magacaab Taliye
    </a>
</div>

@if(session('success'))
<div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #28a745;">
    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Stats Row -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="glass-card" style="padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden;">
        <div style="position: absolute; right: -10px; top: -10px; font-size: 5rem; opacity: 0.05; color: #3498db; transform: rotate(15deg);">
            <i class="fa-solid fa-user-tie"></i>
        </div>
        <div>
            <div style="color: var(--text-sub); font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Wadarta Taliyaasha</div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">{{ $commanders->total() }}</div>
        </div>
        <div style="width: 50px; height: 50px; background: rgba(52, 152, 219, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #3498db;">
            <i class="fa-solid fa-users fa-lg"></i>
        </div>
    </div>
</div>

<div class="glass-card animate-up" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-soft); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.5);">
        <h5 style="margin: 0; color: var(--sidebar-bg); font-weight: 800; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">
            <i class="fa-solid fa-list-ul" style="margin-right: 0.5rem; color: #f39c12;"></i> Liiska Taliyaasha
        </h5>

        <form action="{{ route('station-commanders.index') }}" method="GET" style="position: relative; width: 300px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Raadi Taliye..." style="width: 100%; padding: 0.6rem 1rem 0.6rem 2.8rem; border-radius: 30px; border: 1px solid var(--border-soft); font-size: 0.85rem; outline: none; background: #fff;">
        </form>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: rgba(248, 249, 252, 0.6);">
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">ID / TALIYAHA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">SALDHIGGA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">DARAJADA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">TAARIIKHDA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">XAALADDA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; text-align: right;">MAAREYNTA</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commanders as $commander)
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.2s;" onmouseover="this.style.background='rgba(52, 152, 219, 0.03)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <img src="{{ $commander->user->profile_image ? asset('storage/' . $commander->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($commander->user->name) . '&background=f39c12&color=fff' }}"
                                style="width: 45px; height: 45px; border-radius: 12px; object-fit: cover; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            <div>
                                <div style="font-weight: 800; color: var(--sidebar-bg);">{{ $commander->user->name }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">ID: #{{ $commander->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <span style="font-weight: 600; color: #2c3e50;"><i class="fa-solid fa-building-shield" style="color: #95a5a6; margin-right: 5px;"></i> {{ $commander->station->station_name }}</span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <span style="background: #fff8e1; color: #f39c12; padding: 4px 10px; border-radius: 15px; font-size: 0.75rem; font-weight: 800; border: 1px solid #ffe8b3;">
                            {{ $commander->rank }}
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem; font-size: 0.85rem; color: var(--text-main); font-weight: 600;">
                        {{ \Carbon\Carbon::parse($commander->appointed_date)->format('d M, Y') }}
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <span style="
                            padding: 0.35rem 0.8rem; 
                            border-radius: 20px; 
                            font-size: 0.7rem; 
                            font-weight: 800;
                            background: {{ $commander->status == 'active' ? '#dcfce7' : '#fee2e2' }};
                            color: {{ $commander->status == 'active' ? '#166534' : '#991b1b' }};
                            display: inline-flex; align-items: center; gap: 0.3rem;">
                            <i class="fa-solid fa-circle" style="font-size: 0.4rem;"></i> {{ strtoupper($commander->status) }}
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                            <a href="{{ route('station-commanders.show', $commander->id) }}" class="icon-btn-sm" style="background: #e0f2fe; color: #0284c7;" title="Eeg"><i class="fa-solid fa-eye"></i></a>
                            <a href="{{ route('station-commanders.edit', $commander->id) }}" class="icon-btn-sm" style="background: #fef3c7; color: #d97706;" title="Wax ka bedel"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('station-commanders.destroy', $commander->id) }}" method="POST" onsubmit="return confirm('Ma xaqiijinaysaa inaad tirtirto taliyahan?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-btn-sm" style="background: #fee2e2; color: #dc2626; border: none; cursor: pointer;" title="Tirtir"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 4rem; text-align: center; color: var(--text-sub);">
                        <i class="fa-solid fa-user-slash" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;"></i>
                        <p style="font-weight: 600;">Wali ma jiraan taliyayaal diiwaangashan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 1.5rem; background: #fafbfc; border-top: 1px solid var(--border-soft);">
        {{ $commanders->links() }}
    </div>
</div>

<style>
    .icon-btn-sm {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: 0.2s;
    }

    .icon-btn-sm:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }
</style>
@endsection