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

<div class="glass-card">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 2px solid var(--border-soft);">
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Askariga</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Saldhigga</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Taliyaha Diiwaangeliyay</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Darajada</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Nooca Shaqada</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Taariikhda</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Xaaladda</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Tallaabooyinka</th>
            </tr>
        </thead>
        <tbody>
            @forelse($officers as $officer)
            @php
                $isHighLevel = in_array(auth()->user()->role->slug, ['admin', 'taliye-gobol', 'taliye-qaran']);
                $commanderRecord = \App\Models\StationCommander::where('user_id', auth()->id())->first();
                $canManage = $isHighLevel || ($commanderRecord && $officer->station_id == $commanderRecord->station_id);
            @endphp
            <tr style="border-bottom: 1px solid var(--border-soft);">
                <td style="padding: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        @if($officer->user && $officer->user->profile_image)
                            <img src="{{ asset('storage/' . $officer->user->profile_image) }}" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($officer->user->name ?? 'N/A') }}&size=35&background=random" style="border-radius: 50%;">
                        @endif
                        <span style="font-weight: 700;">{{ $officer->user->name ?? 'Lama yaqaan' }}</span>
                    </div>
                </td>
                <td style="padding: 1rem;">{{ $officer->station->station_name ?? 'Ma jiro' }}</td>
                <td style="padding: 1rem; color: var(--text-sub);">{{ $officer->commander->user->name ?? 'Ma jiro' }}</td>
                <td style="padding: 1rem;">
                    <span style="display: flex; align-items: center; gap: 0.3rem;">
                        <i class="fa-solid fa-star" style="font-size: 0.7rem; color: #f1c40f;"></i> {{ $officer->rank }}
                    </span>
                </td>
                <td style="padding: 1rem; text-transform: capitalize;">{{ $officer->duty_type }}</td>
                <td style="padding: 1rem; font-size: 0.85rem;">{{ \Carbon\Carbon::parse($officer->assigned_date)->format('d M Y') }}</td>
                <td style="padding: 1rem;">
                    <span style="
                        padding: 0.3rem 0.6rem; 
                        border-radius: 20px; 
                        font-size: 0.7rem; 
                        font-weight: 700;
                        background: {{ $officer->status == 'active' ? '#d4edda' : '#f8d7da' }};
                        color: {{ $officer->status == 'active' ? '#155724' : '#721c24' }};
                    ">
                        {{ strtoupper($officer->status) }}
                    </span>
                </td>
                <td style="padding: 1rem;">
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('station-officers.show', $officer->id) }}" style="color: #3498db;" title="Eeg"><i class="fa-solid fa-eye"></i></a>
                        @if($canManage)
                            <a href="{{ route('station-officers.edit', $officer->id) }}" style="color: #f39c12;" title="Wax ka bedel"><i class="fa-solid fa-edit"></i></a>
                            <form action="{{ route('station-officers.destroy', $officer->id) }}" method="POST" onsubmit="return confirm('Ma xaqiijinaysaa inaad tirtirto askarigan?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; padding: 0;" title="Tirtir">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding: 2rem; text-align: center; color: var(--text-sub);">
                    <i class="fa-solid fa-user-shield" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                    <p>Wali ma jiraan askar diiwaangashan.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding: 1rem;">
        {{ $officers->links() }}
    </div>
</div>
@endsection
