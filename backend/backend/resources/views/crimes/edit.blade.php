@extends('layouts.app')

@section('title', 'Wax ka bedel Dambiga')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">WAX KA BEDEL DAMBIGA</h1>
    <p style="color: var(--text-sub);">Dambiga Lambarkiisu yahay: <strong>{{ $crime->case_number }}</strong></p>
</div>

<div class="glass-card" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <form action="{{ route('crimes.update', $crime->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Nooca Dambiga</label>
            <select name="crime_type" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                <option value="">Dooro nooca...</option>
                @foreach([
                    'Dil (Qof dil)', 'Isku day dil', 'Dhaawac / Jirdil', 'Kufsi', 'Afduub', 
                    'Dhac / Tuugnimo', 'Boob hubeysan', 'Xatooyo', 'Khiyaano / Musuq-maasuq',
                    'Tahriib', 'Ka ganacsiga daroogada', 'Ka ganacsiga hubka', 'Argagixiso',
                    'Qarax', 'Gubid hanti', 'Burburin hanti', 'Been abuur', 'Lunsasho hanti dowladeed',
                    'Tahriib dad', 'Rabshad dadweyne', 'Ku xadgudub awood', 'Dembi Internet (Cyber Crime)',
                    'Khiyaano diimeed / dhaqan', 'Dembi anshax xumo', 'Khal-khal amni', 'Kicin Bulsho', 'Dembi kale'
                ] as $type)
                    <option value="{{ $type }}" {{ old('crime_type', $crime->crime_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Faahfaahin</label>
            <textarea name="description" rows="4" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">{{ old('description', $crime->description) }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Goobta uu ka dhacay</label>
                <input type="text" name="location" value="{{ old('location', $crime->location) }}" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
            </div>
            <div class="form-group">
                <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Taariikhda & Waqtiga</label>
                <input type="datetime-local" name="crime_date" value="{{ old('crime_date', \Carbon\Carbon::parse($crime->crime_date)->format('Y-m-d\TH:i')) }}" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
            </div>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-primary" style="padding: 0.8rem 2rem; border-radius: 10px; border: none; background: var(--sidebar-bg); color: white; font-weight: 700; cursor: pointer;">
                Keydi Isbedelka
            </button>
            <a href="{{ route('crimes.index') }}" style="padding: 0.8rem 2rem; border-radius: 10px; border: 1px solid var(--border-soft); background: white; color: var(--text-sub); text-decoration: none; font-weight: 600;">
                Ka Noqo
            </a>
        </div>
    </form>
</div>
@endsection
