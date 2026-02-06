@extends('layouts.app')

@section('title', 'Wax ka bedel Baaritaanka')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">WAX KA BEDEL BAARITAANKA</h1>
    <p style="color: var(--text-sub);">Kiiska: <strong>{{ $investigation->policeCase->case_number ?? 'N/A' }}</strong></p>
</div>

<div class="glass-card" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <form action="{{ route('investigations.update', $investigation->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Natiijada Baaritaanka (Findings)</label>
            <textarea name="findings" rows="6" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">{{ old('findings', $investigation->findings) }}</textarea>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Gabagabada (Outcome)</label>
            <textarea name="outcome" rows="4" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">{{ old('outcome', $investigation->outcome) }}</textarea>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Xaaladda (Status)</label>
            <select name="status" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                <option value="Baaris" {{ old('status', $investigation->status) == 'Baaris' ? 'selected' : '' }}>Baaris</option>
                <option value="completed" {{ old('status', $investigation->status) == 'completed' ? 'selected' : '' }}>Completed (Dhamaaday)</option>
                <option value="Xeer-Ilaalinta" {{ old('status', $investigation->status) == 'Xeer-Ilaalinta' ? 'selected' : '' }}>Xeer-Ilaalinta</option>
            </select>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-primary" style="padding: 0.8rem 2rem; border-radius: 10px; border: none; background: var(--sidebar-bg); color: white; font-weight: 700; cursor: pointer;">
                Keydi Isbedelka
            </button>
            <a href="{{ route('investigations.index') }}" style="padding: 0.8rem 2rem; border-radius: 10px; border: 1px solid var(--border-soft); background: white; color: var(--text-sub); text-decoration: none; font-weight: 600;">
                Ka Noqo
            </a>
        </div>
    </form>
</div>
@endsection
