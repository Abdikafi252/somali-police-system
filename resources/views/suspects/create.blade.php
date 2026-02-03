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
        <div class="row" style="display: flex; gap: 2rem; margin-bottom: 2rem; align-items: center;">
            <div style="flex: 0 0 120px;">
                <label for="photo" style="cursor: pointer; display: block;">
                    <div style="width: 120px; height: 120px; border-radius: 20px; background: #f1f2f6; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 2px dashed var(--border-soft); transition: all 0.3s;" onmouseover="this.style.borderColor='var(--accent-blue)'" onmouseout="this.style.borderColor='var(--border-soft)'">
                        <i class="fa-solid fa-camera" style="font-size: 2rem; color: var(--text-sub);" id="camera-icon"></i>
                        <img id="preview" src="#" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                    </div>
                </label>
                <input type="file" name="photo" id="photo" style="display: none;" onchange="previewImage(this)">
            </div>
            <div style="flex: 1;">
                <label for="name" class="form-label">Magaca oo Buuxa <span style="color: red;">*</span></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Tusaale: Maxamed Cali Abdi" required>
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label for="national_id" class="form-label">National ID / Baasaboor</label>
                <input type="text" name="national_id" id="national_id" class="form-control" placeholder="Tusaale: SO12345678">
            </div>
            <div class="form-group">
                <label for="crime_id" class="form-label">Kiiska loo haysto <span style="color: red;">*</span></label>
                <select name="crime_id" id="crime_id" class="form-select" required>
                    <option value="">Dooro Kiiska...</option>
                    @foreach(\App\Models\Crime::latest()->get() as $crime)
                        <option value="{{ $crime->id }}">{{ $crime->case_number }} - {{ $crime->crime_type }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label for="age" class="form-label">Da'da (Age)</label>
                <input type="number" name="age" id="age" class="form-control" placeholder="Tusaale: 25">
            </div>
            <div class="form-group">
                <label for="gender" class="form-label">Lab/Dhedig</label>
                <select name="gender" id="gender" class="form-select">
                    <option value="">Dooro...</option>
                    <option value="Lab">Lab (Male)</option>
                    <option value="Dhedig">Dhedig (Female)</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="arrest_status" class="form-label">Arrest Status <span style="color: red;">*</span></label>
            <select name="arrest_status" id="arrest_status" class="form-select" required>
                <option value="arrested">Xiran (Arrested)</option>
                <option value="released">Siideyn (Released)</option>
                <option value="wanted">La raadinayo (Wanted)</option>
                <option value="convicted">La Xukumay (Convicted)</option>
            </select>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('suspects.index') }}" class="btn-secondary" style="text-decoration: none;">Jooji</a>
            <button type="submit" class="btn-primary">DIIWANGELI</button>
        </div>
    </form>
</div>
@endsection

