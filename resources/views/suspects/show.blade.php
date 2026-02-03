@extends('layouts.app')

@section('title', 'Faahfaahinta Dambiilaha')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">FAAHFAAHINTA DAMBIILE: {{ $suspect->name }}</h1>
        <p style="color: var(--text-sub);">Xogta shaqsiyadeed iyo kiisaska loo haysto.</p>
    </div>
    <a href="{{ route('suspects.index') }}" class="btn" style="background: #f1f2f6; color: var(--text-sub); text-decoration: none; padding: 0.8rem 1.5rem; border-radius: 8px; font-weight: 600;">
        <i class="fa-solid fa-arrow-left"></i> Ku laabo liiska
    </a>
</div>

<div class="dashboard-row" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; display: grid;">
    <div class="glass-card" style="text-align: center;">
        @if($suspect->photo)
            <img src="{{ asset('storage/' . $suspect->photo) }}" style="width: 120px; height: 120px; border-radius: 20px; margin-bottom: 1rem; object-fit: cover;">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($suspect->name) }}&size=120&background=eaf1f7&color=2d4a53" style="border-radius: 20px; margin-bottom: 1rem;">
        @endif
        <h2 style="font-weight: 800; color: var(--sidebar-bg);">{{ $suspect->name }}</h2>
        <p style="color: var(--text-sub);">National ID: {{ $suspect->national_id ?? 'N/A' }}</p>
        
        <div style="margin-top: 1.5rem; padding: 1rem; border-radius: 12px; font-weight: 800; 
            background: {{ $suspect->arrest_status == 'arrested' ? 'rgba(255, 118, 117, 0.1)' : 'rgba(26, 188, 156, 0.1)' }}; 
            color: {{ $suspect->arrest_status == 'arrested' ? 'var(--accent-red)' : 'var(--accent-teal)' }};">
            {{ strtoupper($suspect->arrest_status) }}
        </div>
    </div>

    <div class="glass-card">
        <h3 class="section-title">MACLUUMAAD DHEERAAD AH</h3>
        <div style="display: grid; gap: 1rem; margin-top: 1rem;">
            <div style="display: flex; justify-content: space-between; padding-bottom: 0.8rem; border-bottom: 1px solid var(--border-soft);">
                <span style="color: var(--text-sub);">Da'da (Age)</span>
                <span style="font-weight: 700;">{{ $suspect->age ?? 'N/A' }} Sano</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding-bottom: 0.8rem; border-bottom: 1px solid var(--border-soft);">
                <span style="color: var(--text-sub);">Gender</span>
                <span style="font-weight: 700;">{{ $suspect->gender ?? 'N/A' }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding-bottom: 0.8rem; border-bottom: 1px solid var(--border-soft);">
                <span style="color: var(--text-sub);">Kiiska Loo Haysto</span>
                @if($suspect->crime)
                    <a href="{{ route('crimes.show', $suspect->crime) }}" style="font-weight: 700; color: var(--accent-blue);">{{ $suspect->crime->case_number }}</a>
                @else
                    <span style="font-weight: 700; color: #ccc;">Ma jiro</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

