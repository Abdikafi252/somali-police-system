@extends('layouts.app')

@section('title', 'Magacaabista Taliyaha')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">MAGACAABISTA TALIYAHA SALDHIGGA</h1>
    <p style="color: var(--text-sub);">U magacaab isticmaale inuu noqdo taliyaha saldhig gaar ah.</p>
</div>

@if($errors->any())
<div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #dc3545;">
    <ul style="margin: 0; padding-left: 1.5rem;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="glass-card" style="max-width: 800px;">
    <form action="{{ route('station-commanders.store') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- User Selection -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Isticmaalaha (Taliyaha)</label>
                <select name="user_id" id="user_select" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: white;" required>
                    <option value="">-- Dooro Isticmaale --</option>
                    @foreach($available_users as $user)
                        <option value="{{ $user->id }}" data-station-id="{{ $user->station_id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                <p style="font-size: 0.75rem; color: var(--text-sub); margin-top: 0.3rem;">Kaliya isticmaalayaasha leh doorka 'Taliyaha Saldhigga' ayaa halkan ka muuqda.</p>
            </div>

            <!-- Station Selection -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Saldhigga</label>
                <select name="station_id" id="station_select" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: white;" required>
                    <option value="">-- Dooro Saldhig --</option>
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}">{{ $station->station_name }}</option>
                    @endforeach
                </select>
            </div>



            <!-- Appointed Date -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Taariikhda Magacaabista</label>
                <input type="date" name="appointed_date" value="{{ date('Y-m-d') }}" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft);" required>
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn-primary" style="padding: 0.8rem 2rem;">
                <i class="fa-solid fa-save"></i> Kaydi & Magacaab
            </button>
            <a href="{{ route('station-commanders.index') }}" style="padding: 0.8rem 2rem; background: #6c757d; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                Kansal garee
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('user_select').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var stationId = selectedOption.getAttribute('data-station-id');
        var stationSelect = document.getElementById('station_select');
        
        if (stationId) {
            stationSelect.value = stationId;
        } else {
            stationSelect.value = "";
        }
    });
</script>
@endsection
