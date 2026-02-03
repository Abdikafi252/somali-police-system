@extends('layouts.app')

@section('title', 'Diiwaangeli Askari')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">DIIWAANGELI ASKARI CUSUB</h1>
    <p style="color: var(--text-sub);">Ku dar askari cusub cutubkaaga/saldhiggaaga.</p>
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
    <form action="{{ route('station-officers.store') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- Officer Selection -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Dooro Askariga</label>
                <select name="officer_id" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: white;" required>
                    <option value="">-- Dooro User --</option>
                    @foreach($available_users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>



            <!-- Duty Type -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nooca Shaqada</label>
                <select name="duty_type" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: white;" required>
                    <option value="patrol">Patrol (Roondo)</option>
                    <option value="guard">Guard (Ilaalo)</option>
                    <option value="investigation">Investigation (Baaris)</option>
                    <option value="traffic">Traffic (Nabadgelyada Wadooyinka)</option>
                    <option value="admin">Admin (Maamulka)</option>
                </select>
            </div>

            <!-- Assigned Date -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Taariikhda la xilsaaray</label>
                <input type="date" name="assigned_date" value="{{ date('Y-m-d') }}" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft);" required>
            </div>
        </div>

        @if($isHighLevel)
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--border-soft);">
            <!-- Station Selection (High Level) -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Saldhigga (Maamulka Sare)</label>
                <select name="station_id" id="station_id" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: white;" required>
                    <option value="">-- Dooro Saldhig --</option>
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}">{{ $station->station_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Commander Selection (High Level) -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Taliyaha (Maamulka Sare)</label>
                <select name="commander_id" id="commander_id" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: white;" required>
                    <option value="">-- Dooro Taliye --</option>
                    @foreach($commanders as $c)
                        <option value="{{ $c->id }}" data-station-id="{{ $c->station_id }}">{{ $c->user->name }} ({{ $c->station->station_name }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const stationSelect = document.getElementById('station_id');
                const commanderSelect = document.getElementById('commander_id');

                if (stationSelect && commanderSelect) {
                    stationSelect.addEventListener('change', function() {
                        const selectedStationId = this.value;
                        
                        // Loop through commander options and find the one that matches the station
                        for (let i = 0; i < commanderSelect.options.length; i++) {
                            const option = commanderSelect.options[i];
                            if (option.getAttribute('data-station-id') === selectedStationId) {
                                commanderSelect.value = option.value;
                                break;
                            }
                        }
                    });
                }
            });
        </script>
        @else
        <div style="background: #e9ecef; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
            <p style="margin: 0; font-size: 0.9rem; color: #495057;">
                <i class="fa-solid fa-info-circle"></i> Waxaad askarigan u diiwaangelinaysaa saldhigga: <strong>{{ $commander->station->station_name ?? 'N/A' }}</strong>
            </p>
        </div>
        @endif

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn-primary" style="padding: 0.8rem 2rem;">
                <i class="fa-solid fa-save"></i> Diiwaangeli
            </button>
            <a href="{{ route('station-officers.index') }}" style="padding: 0.8rem 2rem; background: #6c757d; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                Kansal garee
            </a>
        </div>
    </form>
</div>
@endsection
