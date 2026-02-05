@extends('layouts.app')

@section('title', 'Wax ka bedel Shaqada')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">WAX KA BEDEL SHAQADA</h1>
    <p style="color: var(--text-sub);">Askariga: <strong>{{ $deployment->user->name }}</strong></p>
</div>

<div class="glass-card" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <form action="{{ route('deployments.update', $deployment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Nooca Shaqada</label>
            <select name="duty_type" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                <option value="Patrol" {{ old('duty_type', $deployment->duty_type) == 'Patrol' ? 'selected' : '' }}>Patrol (Roondo)</option>
                <option value="Guard" {{ old('duty_type', $deployment->duty_type) == 'Guard' ? 'selected' : '' }}>Guard (Ilaalo)</option>
                <option value="Traffic" {{ old('duty_type', $deployment->duty_type) == 'Traffic' ? 'selected' : '' }}>Traffic (Taraafiko)</option>
                <option value="Investigation" {{ old('duty_type', $deployment->duty_type) == 'Investigation' ? 'selected' : '' }}>Investigation (Baaris)</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Goobta (Saldhig ama Xarun)</label>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <select name="station_id" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                    <option value="">Dooro Saldhig</option>
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}" {{ old('station_id', $deployment->station_id) == $station->id ? 'selected' : '' }}>{{ $station->station_name }}</option>
                    @endforeach
                </select>
                <select name="facility_id" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                    <option value="">Dooro Xarun</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}" {{ old('facility_id', $deployment->facility_id) == $facility->id ? 'selected' : '' }}>{{ $facility->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Shift-iga</label>
            <select name="shift" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                <option value="Maalin" {{ old('shift', $deployment->shift) == 'Maalin' ? 'selected' : '' }}>Maalin</option>
                <option value="Habeen" {{ old('shift', $deployment->shift) == 'Habeen' ? 'selected' : '' }}>Habeen</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Xaaladda (Status)</label>
            <select name="status" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                <option value="on_duty" {{ old('status', $deployment->status) == 'on_duty' ? 'selected' : '' }}>On Duty (Shaqo ayuu jiraa)</option>
                <option value="completed" {{ old('status', $deployment->status) == 'completed' ? 'selected' : '' }}>Completed (Dhamaaday)</option>
            </select>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-primary" style="padding: 0.8rem 2rem; border-radius: 10px; border: none; background: var(--sidebar-bg); color: white; font-weight: 700; cursor: pointer;">
                Keydi Isbedelka
            </button>
            <a href="{{ route('deployments.index') }}" style="padding: 0.8rem 2rem; border-radius: 10px; border: 1px solid var(--border-soft); background: white; color: var(--text-sub); text-decoration: none; font-weight: 600;">
                Ka Noqo
            </a>
        </div>
    </form>
</div>
@endsection
