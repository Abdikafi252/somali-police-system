@extends('layouts.app')

@section('title', 'Wax ka bedel Kiiska')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">WAX KA BEDEL KIISKA</h1>
    <p style="color: var(--text-sub);">Kiiska Lambarkiisu yahay: <strong>{{ $case->case_number }}</strong></p>
</div>

<div class="glass-card" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <form action="{{ route('cases.update', $case->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Sarkaalka Baarista (cid)</label>
            <select name="assigned_to" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                <option value="">Dooro Sarkaal</option>
                @foreach($officers as $officer)
                    <option value="{{ $officer->id }}" {{ $case->assigned_to == $officer->id ? 'selected' : '' }}>
                        {{ $officer->name }} ({{ $officer->rank }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Xaaladda Kiiska</label>
            <select name="status" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                <option value="Loo-xilsaaray" {{ $case->status == 'Loo-xilsaaray' ? 'selected' : '' }}>Loo-xilsaaray</option>
                <option value="Baaris" {{ $case->status == 'Baaris' ? 'selected' : '' }}>Baaris</option>
                <option value="Xeer-Ilaalinta" {{ $case->status == 'Xeer-Ilaalinta' ? 'selected' : '' }}>Xeer-Ilaalinta</option>
                <option value="Maxkamadda" {{ $case->status == 'Maxkamadda' ? 'selected' : '' }}>Maxkamadda</option>
                <option value="Xiran" {{ $case->status == 'Xiran' ? 'selected' : '' }}>Xiran</option>
            </select>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-primary" style="padding: 0.8rem 2rem; border-radius: 10px; border: none; background: var(--sidebar-bg); color: white; font-weight: 700; cursor: pointer;">
                Keydi Isbedelka
            </button>
            <a href="{{ route('cases.index') }}" style="padding: 0.8rem 2rem; border-radius: 10px; border: 1px solid var(--border-soft); background: white; color: var(--text-sub); text-decoration: none; font-weight: 600;">
                Ka Noqo
            </a>
        </div>
    </form>
</div>
@endsection
