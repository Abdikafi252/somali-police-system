@extends('layouts.app')

@section('title', 'Askarta Saldhigyada')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">ASKARTA SALDHIGYADA</h1>
        <p style="color: var(--text-sub);">Diiwaanka askarta loo xilsaaray saldhigyada gaarka ah.</p>
    </div>
    @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran', 'taliye-saldhig']))
    <a href="{{ route('station-officers.create') }}" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; width: auto; padding: 0.8rem 1.5rem;">
        <i class="fa-solid fa-plus"></i> Ku dar Askari
    </a>
    @endif
</div>

@if(session('success'))
<div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #28a745;">
    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
</div>
@endif

<!-- Stats Row -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="glass-card" style="padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden;">
        <div style="position: absolute; right: -10px; top: -10px; font-size: 5rem; opacity: 0.05; color: #27ae60; transform: rotate(15deg);">
            <i class="fa-solid fa-person-military-rifle"></i>
        </div>
        <div>
            <div style="color: var(--text-sub); font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Wadarta Askarta</div>
            <div style="font-size: 2rem; font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">{{ $officers->total() }}</div>
        </div>
        <div style="width: 50px; height: 50px; background: rgba(39, 174, 96, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #27ae60;">
            <i class="fa-solid fa-users-line fa-lg"></i>
        </div>
    </div>
</div>

<div class="glass-card animate-up" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-soft); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.5);">
        <h5 style="margin: 0; color: var(--sidebar-bg); font-weight: 800; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px;">
            <i class="fa-solid fa-list-ul" style="margin-right: 0.5rem; color: #8e44ad;"></i> Liiska Askarta
        </h5>

        <form action="{{ route('station-officers.index') }}" method="GET" style="position: relative; width: 300px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Raadi Askari..." style="width: 100%; padding: 0.6rem 1rem 0.6rem 2.8rem; border-radius: 30px; border: 1px solid var(--border-soft); font-size: 0.85rem; outline: none; background: #fff;">
        </form>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: rgba(248, 249, 252, 0.6);">
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">ASKARIGA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">SALDHIGGA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">DARAJADA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">SHAQADA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">XAALADDA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; text-align: right;">MAAREYNTA</th>
                </tr>
            </thead>
            <tbody>
                @forelse($officers as $officer)
                @php
                $isHighLevel = in_array(auth()->user()->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran']);
                $commanderRecord = \App\Models\StationCommander::where('user_id', auth()->id())->first();
                $canManage = $isHighLevel || ($commanderRecord && $officer->station_id == $commanderRecord->station_id);
                @endphp
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.2s;" onmouseover="this.style.background='rgba(39, 174, 96, 0.03)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <img src="{{ ($officer->user && $officer->user->profile_image) ? asset('storage/' . $officer->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($officer->user->name ?? 'N/A') . '&background=27ae60&color=fff' }}"
                                style="width: 45px; height: 45px; border-radius: 12px; object-fit: cover; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            <div>
                                <div style="font-weight: 800; color: var(--sidebar-bg);">{{ $officer->user->name ?? 'Lama yaqaan' }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">{{ \Carbon\Carbon::parse($officer->assigned_date)->format('d M, Y') }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <span style="font-weight: 600; color: #2c3e50;"><i class="fa-solid fa-building-shield" style="color: #95a5a6; margin-right: 5px;"></i> {{ $officer->station->station_name ?? 'N/A' }}</span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <span style="background: #fff8e1; color: #f39c12; padding: 4px 10px; border-radius: 15px; font-size: 0.75rem; font-weight: 800; border: 1px solid #ffe8b3;">
                            <i class="fa-solid fa-star" style="font-size: 0.6rem;"></i> {{ $officer->rank }}
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem; text-transform: capitalize; color: var(--text-main); font-weight: 600;">
                        {{ $officer->duty_type }}
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <span style="
                            padding: 0.35rem 0.8rem; 
                            border-radius: 20px; 
                            font-size: 0.7rem; 
                            font-weight: 800;
                            background: {{ $officer->status == 'active' ? '#dcfce7' : '#fee2e2' }};
                            color: {{ $officer->status == 'active' ? '#166534' : '#991b1b' }};
                            display: inline-flex; align-items: center; gap: 0.3rem;">
                            <i class="fa-solid fa-circle" style="font-size: 0.4rem;"></i> {{ strtoupper($officer->status) }}
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                            <a href="{{ route('station-officers.show', $officer->id) }}" class="icon-btn-sm" style="background: #e0f2fe; color: #0284c7;" title="Eeg"><i class="fa-solid fa-eye"></i></a>
                            @if($canManage)
                            <a href="{{ route('station-officers.edit', $officer->id) }}" class="icon-btn-sm" style="background: #fef3c7; color: #d97706;" title="Wax ka bedel"><i class="fa-solid fa-pen"></i></a>
                            <form action="{{ route('station-officers.destroy', $officer->id) }}" method="POST" onsubmit="return confirm('Ma xaqiijinaysaa inaad tirtirto askarigan?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-btn-sm" style="background: #fee2e2; color: #dc2626; border: none; cursor: pointer;" title="Tirtir"><i class="fa-solid fa-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 4rem; text-align: center; color: var(--text-sub);">
                        <i class="fa-solid fa-users-slash" style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;"></i>
                        <p style="font-weight: 600;">Wali ma jiraan askar diiwaangashan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 1.5rem; background: #fafbfc; border-top: 1px solid var(--border-soft);">
        {{ $officers->links() }}
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