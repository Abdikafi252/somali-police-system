@extends('layouts.app')

@section('title', 'Diiwangiil Dambiile')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">DIIWANGELI DAMBIILE CUSUB</h1>
    <p style="color: var(--text-sub);">Fadlan gali xogta dambiilaha iyo kiiska loo haysto.</p>
</div>

<div class="glass-card" style="max-width: 800px;">
    <form action="{{ route('suspects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="photo" style="font-size: 0.85rem; color: var(--sidebar-bg); font-weight: 600;">Sawirka (Photo)</label>
            <input type="file" name="photo" id="photo" class="form-control" style="padding: 0.4rem; border: 1px dashed var(--border-soft);">
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="name" style="font-size: 0.85rem; color: var(--sidebar-bg); font-weight: 600;">Magaca oo Buuxa</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Tusaale: Maxamed Cali Abdi" required>
        </div>

        <div class="stat-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 1.5rem; gap: 1.5rem;">
            <div class="form-group">
                <label for="national_id" style="font-size: 0.85rem; color: var(--sidebar-bg); font-weight: 600;">National ID / Baasaboor</label>
                <input type="text" name="national_id" id="national_id" class="form-control" placeholder="Tusaale: SO12345678">
            </div>
            <div class="form-group">
                <label for="crime_id" style="font-size: 0.85rem; color: var(--sidebar-bg); font-weight: 600;">Kiiska loo haysto</label>
                <select name="crime_id" id="crime_id" class="form-control" required>
                    <option value="">Dooro Kiiska...</option>
                    @foreach(\App\Models\Crime::latest()->get() as $crime)
                        <option value="{{ $crime->id }}">{{ $crime->case_number }} - {{ $crime->crime_type }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="stat-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 1.5rem; gap: 1.5rem;">
            <div class="form-group">
                <label for="age" style="font-size: 0.85rem; color: var(--sidebar-bg); font-weight: 600;">Da'da (Age)</label>
                <input type="number" name="age" id="age" class="form-control" placeholder="Tusaale: 25">
            </div>
            <div class="form-group">
                <label for="gender" style="font-size: 0.85rem; color: var(--sidebar-bg); font-weight: 600;">Lab/Dhedig</label>
                <select name="gender" id="gender" class="form-control">
                    <option value="">Dooro...</option>
                    <option value="Lab">Lab (Male)</option>
                    <option value="Dhedig">Dhedig (Female)</option>
                </select>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="arrest_status" style="font-size: 0.85rem; color: var(--sidebar-bg); font-weight: 600;">Arrest Status</label>
            <select name="arrest_status" id="arrest_status" class="form-control" required>
                <option value="arrested">Xiran (Arrested)</option>
                <option value="released">Siideyn (Released)</option>
                <option value="wanted">La raadinayo (Wanted)</option>
                <option value="convicted">La Xukumay (Convicted)</option>
            </select>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn-primary" style="width: auto; padding: 0.8rem 2.5rem;">DIIWANGELI</button>
            <a href="{{ route('suspects.index') }}" class="btn" style="background: #f1f2f6; color: var(--text-sub); text-decoration: none; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 600;">Jooji</a>
        </div>
    </form>
</div>
@endsection

