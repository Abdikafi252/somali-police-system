@extends('layouts.app')

@section('title', 'Dambiilayaasha')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">DIIWAANKA DAMBIILAYAASHA</h1>
        <p style="color: var(--text-sub);">Xubnaha lagu tuhunsan yahay ama loo haysto falal dambiyeedyo.</p>
    </div>
    @if(auth()->user()->role->slug == 'admin' || auth()->user()->role->slug == 'taliye-saldhig')
    <a href="{{ route('suspects.create') }}" class="btn-primary" style="text-decoration: none; width: auto; padding: 0.8rem 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <i class="fa-solid fa-user-plus"></i> Diiwangiil Dambiile
    </a>
    @endif
</div>

<div class="glass-card">
    @if(session('success'))
    <div style="background: rgba(26, 188, 156, 0.1); color: var(--accent-teal); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background: rgba(231, 76, 60, 0.1); color: #c0392b; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600;">
        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
    </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 2px solid var(--border-soft);">
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Magaca Geediga</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">National ID</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Status</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Kiiska</th>
                <th style="padding: 1rem; color: var(--text-sub); font-weight: 600;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suspects as $suspect)
            <tr style="border-bottom: 1px solid var(--border-soft);">
                <td style="padding: 1rem; font-weight: 700; color: var(--sidebar-bg);">
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        <img src="{{ $suspect->photo ? asset('storage/' . $suspect->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($suspect->name) . '&background=6366f1&color=fff' }}"
                            alt="Suspect"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($suspect->name) }}&background=6366f1&color=fff'"
                            style="width: 40px; height: 40px; border-radius: 12px; object-fit: cover; border: 2px solid #f1f5f9;">
                        {{ $suspect->name }}
                    </div>
                </td>
                <td style="padding: 1rem;">{{ $suspect->national_id ?? 'N/A' }}</td>
                <td style="padding: 1rem;">
                    <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; 
                        background: {{ $suspect->arrest_status == 'arrested' ? 'rgba(255, 118, 117, 0.1)' : 'rgba(26, 188, 156, 0.1)' }}; 
                        color: {{ $suspect->arrest_status == 'arrested' ? 'var(--accent-red)' : 'var(--accent-teal)' }};">
                        {{ strtoupper($suspect->arrest_status) }}
                    </span>
                </td>
                <td style="padding: 1rem;">
                    @if($suspect->crime)
                    <span style="color: var(--accent-blue); font-weight: 600;">{{ $suspect->crime->case_number }}</span>
                    @else
                    <span style="color: #ccc;">Ma jiro</span>
                    @endif
                </td>
                <td style="padding: 1rem;">
                    <div style="display: flex; gap: 0.8rem;">
                        <a href="{{ route('suspects.show', $suspect) }}" style="color: var(--accent-blue); text-decoration: none; font-weight: 600;" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        @if(auth()->user()->role->slug == 'admin' || auth()->user()->role->slug == 'taliye-saldhig')
                        <a href="{{ route('suspects.edit', $suspect) }}" style="color: #f1c40f; text-decoration: none; font-weight: 600;" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('suspects.destroy', $suspect) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ma hubtaa inaad tirtirto dambiilahan?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #e74c3c; cursor: pointer; padding: 0;" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 1.5rem;">
        {{ $suspects->links('vendor.pagination.glass') }}
    </div>
</div>
@endsection