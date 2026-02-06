@extends('layouts.app')

@section('title', 'Wax ka bedel Askariga')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">WAX KA BEDEL ASKARIGA</h1>
    <p style="color: var(--text-sub);">Cusboonaysii xogta askariga iyo xilka uu hayo.</p>
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
    <form action="{{ route('station-officers.update', $officer->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- Officer Name (Disabled) -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Askariga</label>
                <input type="text" value="{{ $officer->user->name ?? 'N/A' }}" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: #f8f9fa;" disabled>
            </div>



            <!-- Duty Type -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nooca Shaqada</label>
                <select name="duty_type" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: white;" required>
                    <option value="patrol" {{ $officer->duty_type == 'patrol' ? 'selected' : '' }}>Patrol (Roondo)</option>
                    <option value="guard" {{ $officer->duty_type == 'guard' ? 'selected' : '' }}>Guard (Ilaalo)</option>
                    <option value="investigation" {{ $officer->duty_type == 'investigation' ? 'selected' : '' }}>Investigation (Baaris)</option>
                    <option value="traffic" {{ $officer->duty_type == 'traffic' ? 'selected' : '' }}>Traffic (Nabadgelyada Wadooyinka)</option>
                    <option value="admin" {{ $officer->duty_type == 'admin' ? 'selected' : '' }}>Admin (Maamulka)</option>
                </select>
            </div>

            <!-- Assigned Date -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Taariikhda la xilsaaray</label>
                <input type="date" name="assigned_date" value="{{ old('assigned_date', $officer->assigned_date) }}" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft);" required>
            </div>

            <!-- Status -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Xaaladda (Status)</label>
                <select name="status" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: white;" required>
                    <option value="active" {{ $officer->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $officer->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        @if($isHighLevel)
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--border-soft);">
            <!-- Station Selection (High Level) -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Saldhigga (Maamulka Sare)</label>
                <select name="station_id" id="station_id" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: white;" required>
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}" {{ $officer->station_id == $station->id ? 'selected' : '' }}>{{ $station->station_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Commander Selection (High Level) -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Taliyaha (Maamulka Sare)</label>
                <select name="commander_id" id="commander_id" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: white;" required>
                    @foreach($commanders as $c)
                        <option value="{{ $c->id }}" data-station-id="{{ $c->station_id }}" {{ $officer->commander_id == $c->id ? 'selected' : '' }}>{{ $c->user->name }} ({{ $c->station->station_name }})</option>
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
        @endif

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn-primary" style="padding: 0.8rem 2rem;">
                <i class="fa-solid fa-save"></i> Kaydi Isbedelada
            </button>
            <a href="{{ route('station-officers.index') }}" style="padding: 0.8rem 2rem; background: #6c757d; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                Kansal garee
            </a>
        </div>
    </form>
</div>
@endsection
