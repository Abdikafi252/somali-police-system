@extends('layouts.app')

@section('title', 'Faahfaahinta Taliyaha')

@section('content')
<div class="header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">FAAHFAAHINTA TALIYAHA</h1>
        <p style="color: var(--text-sub);">Macluumaadka buuxa ee taliyaha saldhigga loogu magacaabay.</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('station-commanders.edit', $commander->id) }}" class="btn-primary" style="text-decoration: none; padding: 0.8rem 1.5rem; background: #f39c12;">
            <i class="fa-solid fa-edit"></i> Wax ka bedel
        </a>
        <a href="{{ route('station-commanders.index') }}" style="padding: 0.8rem 1.5rem; background: #6c757d; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Dib u noqo
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem;">
    <!-- Profile Card -->
    <div class="glass-card" style="text-align: center; padding: 2rem;">
        <div style="margin-bottom: 1.5rem;">
            @if($commander->user && $commander->user->profile_image)
                <img src="{{ asset('storage/' . $commander->user->profile_image) }}" style="width: 150px; height: 150px; border-radius: 50%; border: 5px solid var(--sidebar-bg); object-fit: cover;">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($commander->user->name ?? 'N/A') }}&size=150&background=random" style="border-radius: 50%; border: 5px solid var(--sidebar-bg);">
            @endif
        </div>
        <h2 style="font-weight: 800; font-family: 'Outfit'; margin-bottom: 0.5rem;">{{ $commander->user->name ?? 'N/A' }}</h2>
        <p style="color: var(--text-sub); margin-bottom: 1.5rem;">{{ $commander->user->email ?? 'N/A' }}</p>
        
        <div style="background: {{ $commander->status == 'active' ? '#d4edda' : '#f8d7da' }}; color: {{ $commander->status == 'active' ? '#155724' : '#721c24' }}; padding: 0.5rem; border-radius: 20px; font-weight: 700; display: inline-block; padding: 0.5rem 1.5rem;">
            {{ strtoupper($commander->status) }}
        </div>
    </div>

    <!-- Details Card -->
    <div class="glass-card" style="padding: 2rem;">
        <h3 style="border-bottom: 2px solid var(--border-soft); padding-bottom: 1rem; margin-bottom: 1.5rem; font-weight: 700;">Macluumaadka Magacaabista</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <label style="color: var(--text-sub); font-size: 0.9rem; display: block; margin-bottom: 0.3rem;">Saldhigga loo xilsaaray</label>
                <p style="font-weight: 700; font-size: 1.1rem;">{{ $commander->station->station_name ?? 'N/A' }}</p>
            </div>
            <div>
                <label style="color: var(--text-sub); font-size: 0.9rem; display: block; margin-bottom: 0.3rem;">Darajada</label>
                <p style="font-weight: 700; font-size: 1.1rem;">{{ $commander->rank }}</p>
            </div>
            <div>
                <label style="color: var(--text-sub); font-size: 0.9rem; display: block; margin-bottom: 0.3rem;">Taariikhda Magacaabista</label>
                <p style="font-weight: 700; font-size: 1.1rem;">{{ \Carbon\Carbon::parse($commander->appointed_date)->format('d M Y') }}</p>
            </div>
            <div>
                <label style="color: var(--text-sub); font-size: 0.9rem; display: block; margin-bottom: 0.3rem;">Goobta Saldhigga</label>
                <p style="font-weight: 700; font-size: 1.1rem;">{{ $commander->station->location ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
