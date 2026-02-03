@extends('layouts.app')

@section('title', 'Diiwangiil Dambi')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">DIIWANGELI DAMBI CUSUB</h1>
    <p style="color: var(--text-sub);">Fadlan gali xogta dambiga si sax ah.</p>
</div>

<div class="glass-card" style="max-width: 800px;">
    <form action="{{ route('crimes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid-2">
            <div class="form-group">
                <label for="crime_type" class="form-label">Nooca Dambiga</label>
                <select name="crime_type" id="crime_type" class="form-select" required>
                    <option value="">Dooro nooca...</option>
                    <option value="Dil (Qof dil)">Dil (Qof dil)</option>
                    <option value="Isku day dil">Isku day dil</option>
                    <option value="Dhaawac / Jirdil">Dhaawac / Jirdil</option>
                    <option value="Kufsi">Kufsi</option>
                    <option value="Afduub">Afduub</option>
                    <option value="Dhac / Tuugnimo">Dhac / Tuugnimo</option>
                    <option value="Boob hubeysan">Boob hubeysan</option>
                    <option value="Xatooyo">Xatooyo</option>
                    <option value="Khiyaano / Musuq-maasuq">Khiyaano / Musuq-maasuq</option>
                    <option value="Tahriib">Tahriib</option>
                    <option value="Ka ganacsiga daroogada">Ka ganacsiga daroogada</option>
                    <option value="Ka ganacsiga hubka">Ka ganacsiga hubka</option>
                    <option value="Argagixiso">Argagixiso</option>
                    <option value="Qarax">Qarax</option>
                    <option value="Gubid hanti">Gubid hanti</option>
                    <option value="Burburin hanti">Burburin hanti</option>
                    <option value="Been abuur">Been abuur</option>
                    <option value="Lunsasho hanti dowladeed">Lunsasho hanti dowladeed</option>
                    <option value="Tahriib dad">Tahriib dad</option>
                    <option value="Rabshad dadweyne">Rabshad dadweyne</option>
                    <option value="Ku xadgudub awood">Ku xadgudub awood</option>
                    <option value="Dembi Internet (Cyber Crime)">Dembi Internet (Cyber Crime)</option>
                    <option value="Khiyaano diimeed / dhaqan">Khiyaano diimeed / dhaqan</option>
                    <option value="Dembi anshax xumo">Dembi anshax xumo</option>
                    <option value="Khal-khal amni">Khal-khal amni</option>
                    <option value="Kicin Bulsho">Kicin Bulsho</option>
                    <option value="Dembi kale">Dembi kale</option>
                </select>
            </div>
            <div class="form-group">
                <label for="crime_date" class="form-label">Taariikhda & Waqtiga</label>
                <input type="datetime-local" name="crime_date" id="crime_date" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label for="location" class="form-label">Goobta uu ka dhacay (Location)</label>
            <input type="text" name="location" id="location" class="form-control" placeholder="Tusaale: Degmada Hodan, Muqdisho" required>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Faahfaahinta Dambiga</label>
            <textarea name="description" id="description" class="form-control" rows="5" placeholder="Sharaxaad dambe oo kooban..." required></textarea>
        </div>

        <div class="form-group">
            <label for="evidence" class="form-label">Cadaymaha (Sawiro/Dukumeenti)</label>
            <input type="file" name="evidence[]" id="evidence" class="form-control" multiple accept="image/*,application/pdf" style="padding: 0.6rem;">
            <small style="color: var(--text-sub); font-size: 0.75rem;">Waxaad soo gelin kartaa sawiro badan ama PDF files (Max: 5MB)</small>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('crimes.index') }}" class="btn-secondary" style="text-decoration: none;">Jooji</a>
            <button type="submit" class="btn-primary">DIIWANGELI DAMBIGA</button>
        </div>
    </form>
</div>
@endsection

