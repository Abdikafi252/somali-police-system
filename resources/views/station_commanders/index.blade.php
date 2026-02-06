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

<div class="glass-card">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 2px solid var(--border-soft);">
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Taliyaha</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Saldhigga</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Darajada</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Taariikhda la magacaabay</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Xaaladda</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Tallaabooyinka</th>
            </tr>
        </thead>
        <tbody>
            @forelse($commanders as $commander)
            <tr style="border-bottom: 1px solid var(--border-soft);">
                <td style="padding: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        <img src="{{ $commander->user->profile_image ? asset('storage/' . $commander->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($commander->user->name) . '&background=random&color=fff' }}"
                            alt="Commander"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($commander->user->name) }}&background=6366f1&color=fff'"
                            style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 1px solid #eee;">
                        <span style="font-weight: 700;">{{ $commander->user->name }}</span>
                    </div>
                </td>
                <td style="padding: 1rem;">{{ $commander->station->station_name }}</td>
                <td style="padding: 1rem;">{{ $commander->rank }}</td>
                <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($commander->appointed_date)->format('d M Y') }}</td>
                <td style="padding: 1rem;">
                    <span style="
                        padding: 0.3rem 0.6rem; 
                        border-radius: 20px; 
                        font-size: 0.7rem; 
                        font-weight: 700;
                        background: {{ $commander->status == 'active' ? '#d4edda' : '#f8d7da' }};
                        color: {{ $commander->status == 'active' ? '#155724' : '#721c24' }};
                    ">
                        {{ strtoupper($commander->status) }}
                    </span>
                </td>
                <td style="padding: 1rem;">
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('station-commanders.show', $commander->id) }}" style="color: #3498db;" title="Eeg"><i class="fa-solid fa-eye"></i></a>
                        <a href="{{ route('station-commanders.edit', $commander->id) }}" style="color: #f39c12;" title="Wax ka bedel"><i class="fa-solid fa-edit"></i></a>
                        <form action="{{ route('station-commanders.destroy', $commander->id) }}" method="POST" onsubmit="return confirm('Ma xaqiijinaysaa inaad tirtirto taliyahan?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; padding: 0;" title="Tirtir">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-sub);">
                    <i class="fa-solid fa-user-tie" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                    <p>Wali ma jiraan taliyayaal diiwaangashan.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding: 1rem;">
        {{ $commanders->links() }}
    </div>
</div>
@endsection