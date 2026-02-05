@extends('layouts.app')

@section('title', 'Furidda Kiiska')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">FURI KIIS CUSUB</h1>
    <p style="color: var(--text-sub);">Dambiga Selected: <span style="font-weight: 700; color: var(--accent-blue);">#{{ $crime->case_number }}</span> ({{ $crime->crime_type }})</p>
</div>

<div class="glass-card" style="max-width: 600px;">
    <form action="{{ route('cases.store') }}" method="POST">
        @csrf
        <input type="hidden" name="crime_id" value="{{ $crime->id }}">
        
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="assigned_to" style="font-size: 0.85rem; color: var(--sidebar-bg); font-weight: 600;">U Xilsaar Sarkaal CID ah</label>
            <select name="assigned_to" id="assigned_to" class="form-control" required>
                <option value="">Dooro Sarkaal...</option>
                @foreach($officers as $officer)
                    <option value="{{ $officer->id }}">{{ $officer->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="status" style="font-size: 0.85rem; color: var(--sidebar-bg); font-weight: 600;">Status-ka Hore</label>
            <select name="status" id="status" class="form-control" required>
                <option value="assigned">Assigned (La xilsaaray)</option>
                <option value="investigation">Investigation (Baaritaan socda)</option>
            </select>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn-primary" style="width: auto; padding: 0.8rem 2.5rem;">FUR KIISKA</button>
            <a href="{{ route('crimes.show', $crime) }}" class="btn" style="background: #f1f2f6; color: var(--text-sub); text-decoration: none; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 600;">Jooji</a>
        </div>
    </form>
</div>
@endsection

