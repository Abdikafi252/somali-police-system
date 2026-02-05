@extends('layouts.app')

@section('title', 'Faahfaahinta Askariga')

@section('content')
<div class="header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">FAAHFAAHINTA ASKARIGA</h1>
        <p style="color: var(--text-sub);">Macluumaadka buuxa ee askariga ka tirsan saldhigga.</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('station-officers.edit', $officer->id) }}" class="btn-primary" style="text-decoration: none; padding: 0.8rem 1.5rem; background: #f39c12;">
            <i class="fa-solid fa-edit"></i> Wax ka bedel
        </a>
        <a href="{{ route('station-officers.index') }}" style="padding: 0.8rem 1.5rem; background: #6c757d; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Dib u noqo
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem;">
    <!-- Profile Card -->
    <div class="glass-card" style="text-align: center; padding: 2rem;">
        <div style="margin-bottom: 1.5rem;">
            @if($officer->user && $officer->user->profile_image)
                <img src="{{ asset('storage/' . $officer->user->profile_image) }}" style="width: 150px; height: 150px; border-radius: 50%; border: 5px solid var(--sidebar-bg); object-fit: cover;">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($officer->user->name ?? 'N/A') }}&size=150&background=random" style="border-radius: 50%; border: 5px solid var(--sidebar-bg);">
            @endif
        </div>
        <h2 style="font-weight: 800; font-family: 'Outfit'; margin-bottom: 0.5rem;">{{ $officer->user->name ?? 'N/A' }}</h2>
        <p style="color: var(--text-sub); margin-bottom: 1.5rem;">{{ $officer->user->email ?? 'N/A' }}</p>
        
        <div style="background: {{ $officer->status == 'active' ? '#d4edda' : '#f8d7da' }}; color: {{ $officer->status == 'active' ? '#155724' : '#721c24' }}; padding: 0.5rem; border-radius: 20px; font-weight: 700; display: inline-block; padding: 0.5rem 1.5rem;">
            {{ strtoupper($officer->status) }}
        </div>
    </div>

    <!-- Details Card -->
    <div class="glass-card" style="padding: 2rem;">
        <h3 style="border-bottom: 2px solid var(--border-soft); padding-bottom: 1rem; margin-bottom: 1.5rem; font-weight: 700;">Macluumaadka Shaqada</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <label style="color: var(--text-sub); font-size: 0.9rem; display: block; margin-bottom: 0.3rem;">Saldhigga</label>
                <p style="font-weight: 700; font-size: 1.1rem;">{{ $officer->station->station_name ?? 'N/A' }}</p>
            </div>
            <div>
                <label style="color: var(--text-sub); font-size: 0.9rem; display: block; margin-bottom: 0.3rem;">Darajada</label>
                <p style="font-weight: 700; font-size: 1.1rem;">{{ $officer->rank }}</p>
            </div>
            <div>
                <label style="color: var(--text-sub); font-size: 0.9rem; display: block; margin-bottom: 0.3rem;">Nooca Shaqada</label>
                <p style="font-weight: 700; font-size: 1.1rem; text-transform: capitalize;">{{ $officer->duty_type }}</p>
            </div>
            <div>
                <label style="color: var(--text-sub); font-size: 0.9rem; display: block; margin-bottom: 0.3rem;">Taariikhda la xilsaaray</label>
                <p style="font-weight: 700; font-size: 1.1rem;">{{ \Carbon\Carbon::parse($officer->assigned_date)->format('d M Y') }}</p>
            </div>
            <div style="grid-column: span 2;">
                <label style="color: var(--text-sub); font-size: 0.9rem; display: block; margin-bottom: 0.3rem;">Taliyaha Diiwaangeliyay</label>
                <p style="font-weight: 700; font-size: 1.1rem;">{{ $officer->commander->user->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
