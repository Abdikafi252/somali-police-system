@extends('layouts.app')

@section('title', 'Wax ka bedel Dambiilaha')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">WAX KA BEDEL DAMBIILAHA</h1>
    <p style="color: var(--text-sub);">Magaca: <strong>{{ $suspect->name }}</strong></p>
</div>

<div class="glass-card" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <form action="{{ route('suspects.update', $suspect->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Magaca Buuxa</label>
            <input type="text" name="name" value="{{ old('name', $suspect->name) }}" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Da'da</label>
                <input type="number" name="age" value="{{ old('age', $suspect->age) }}" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
            </div>
            <div class="form-group">
                <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Jinsiga</label>
                <select name="gender" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                    <option value="Male" {{ old('gender', $suspect->gender) == 'Male' ? 'selected' : '' }}>Lab</option>
                    <option value="Female" {{ old('gender', $suspect->gender) == 'Female' ? 'selected' : '' }}>Dhedig</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">National ID</label>
            <input type="text" name="national_id" value="{{ old('national_id', $suspect->national_id) }}" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Kiiska / Dambiga</label>
            <select name="crime_id" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                @foreach($crimes as $crime)
                    <option value="{{ $crime->id }}" {{ old('crime_id', $suspect->crime_id) == $crime->id ? 'selected' : '' }}>
                        {{ $crime->case_number }} - {{ $crime->crime_type }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Xaaladda Xarigga (Arrest Status)</label>
            <select name="arrest_status" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                <option value="wanted" {{ old('arrest_status', $suspect->arrest_status) == 'wanted' ? 'selected' : '' }}>Wanted (La doon-doonayo)</option>
                <option value="arrested" {{ old('arrest_status', $suspect->arrest_status) == 'arrested' ? 'selected' : '' }}>Arrested (Xiran)</option>
                <option value="released" {{ old('arrest_status', $suspect->arrest_status) == 'released' ? 'selected' : '' }}>Released (La sii daayay)</option>
                <option value="convicted" {{ old('arrest_status', $suspect->arrest_status) == 'convicted' ? 'selected' : '' }}>Convicted (La Xukumay)</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Sawirka (Haddii la bedelayo)</label>
            <input type="file" name="photo" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-primary" style="padding: 0.8rem 2rem; border-radius: 10px; border: none; background: var(--sidebar-bg); color: white; font-weight: 700; cursor: pointer;">
                Keydi Isbedelka
            </button>
            <a href="{{ route('suspects.index') }}" style="padding: 0.8rem 2rem; border-radius: 10px; border: 1px solid var(--border-soft); background: white; color: var(--text-sub); text-decoration: none; font-weight: 600;">
                Ka Noqo
            </a>
        </div>
    </form>
</div>
@endsection
