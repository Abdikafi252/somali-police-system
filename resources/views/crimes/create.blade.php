@extends('layouts.app')

@section('title', 'Diiwangiil Dambi')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">DIIWANGELI DAMBI CUSUB</h1>
    <p style="color: var(--text-sub);">Fadlan gali xogta dambiga si sax ah.</p>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <!-- Form Column -->
    <div class="glass-card">
    <form action="{{ route('crimes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- 1. XOGTA EEDEYSANAHA (SUSPECT DETAILS) -->
        <h3 style="font-size: 1.1rem; color: var(--sidebar-bg); margin-bottom: 1rem; font-weight: 700; border-bottom: 2px solid var(--accent-color); padding-bottom: 0.5rem;">
            1. XOGTA EEDEYSANAHA (SUSPECT DETAILS)
        </h3>
        <div style="background: rgba(0,0,0,0.02); padding: 1.5rem; border-radius: 12px; border: 1px dashed var(--border-color); margin-bottom: 2rem;">
            <!-- Row 1: Names & Age -->
            <div class="grid-3" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label for="suspect_name" class="form-label">Magaca Buuxa</label>
                    <input type="text" name="suspect_name" id="suspect_name" class="form-control" placeholder="Magaca oo saddexan">
                </div>
                <div class="form-group">
                    <label for="suspect_nickname" class="form-label">Naaneysta</label>
                    <input type="text" name="suspect_nickname" id="suspect_nickname" class="form-control" placeholder="Naaneysta">
                </div>
                <div class="form-group">
                    <label for="suspect_age" class="form-label">Da'da</label>
                    <input type="number" name="suspect_age" id="suspect_age" class="form-control" placeholder="Sano">
                </div>
            </div>

            <!-- Row 2: Mother, Gender, Address -->
            <div class="grid-3" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label for="suspect_mother_name" class="form-label">Hooyada</label>
                    <input type="text" name="suspect_mother_name" id="suspect_mother_name" class="form-control" placeholder="Magaca Hooyada">
                </div>
                <div class="form-group">
                    <label for="suspect_gender" class="form-label">Jinsiga</label>
                    <select name="suspect_gender" id="suspect_gender" class="form-select">
                        <option value="">Dooro...</option>
                        <option value="Male">Lab (Male)</option>
                        <option value="Female">Dhedig (Female)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="suspect_address" class="form-label">Deggan (Address)</label>
                    <input type="text" name="suspect_address" id="suspect_address" class="form-control" placeholder="Degmada/Xaafadda">
                </div>
            </div>

            <!-- Row 3: Crime Type, Location, ID -->
            <div class="grid-3" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label for="crime_type" class="form-label">Nuuca Dambiga</label>
                    <input type="text" name="crime_type" id="crime_type" class="form-control" placeholder="Gali nooca dambiga..." required>
                </div>
                <div class="form-group">
                    <label for="location" class="form-label">Goobta</label>
                    <input type="text" name="location" id="location" class="form-control" placeholder="Goobta uu ka dhacay" required>
                </div>
                <div class="form-group">
                    <label for="national_id" class="form-label">Numberka Aqoonsiga (Nation ID)</label>
                    <input type="text" name="national_id" id="national_id" class="form-control" placeholder="Haddii la hayo">
                </div>
            </div>

            <!-- Row 4: Status, Time, Photo -->
            <div class="grid-3" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="arrest_status" class="form-label">Xaaladda</label>
                    <select name="arrest_status" id="arrest_status" class="form-select">
                        <option value="Baxsad">Baxsad</option>
                        <option value="Xiran">Xiran</option>
                        <option value="La Raadinayo">La Raadinayo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="crime_date" class="form-label">Waqtiga</label>
                    <input type="datetime-local" name="crime_date" id="crime_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="suspect_photo" class="form-label">Sawirka Dambiilaha</label>
                    <input type="file" name="suspect_photo" id="suspect_photo" class="form-control" accept="image/*" style="padding: 0.3rem;">
                </div>
            </div>
        </div>

        <!-- 2. XOGTA DHIBANAHA (VICTIM DETAILS) -->
        <h3 style="font-size: 1.1rem; color: var(--sidebar-bg); margin-bottom: 1rem; font-weight: 700; border-bottom: 2px solid var(--accent-color); padding-bottom: 0.5rem;">
            2. XOGTA DHIBANAHA (VICTIM DETAILS)
        </h3>
        <div style="background: rgba(0,0,0,0.02); padding: 1.5rem; border-radius: 12px; border: 1px dashed var(--border-color); margin-bottom: 2rem;">
            <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group" style="grid-column: span 2;">
                    <label for="victim_name" class="form-label">Magaca Dhibanaha</label>
                    <input type="text" name="victim_name" id="victim_name" class="form-control" placeholder="Magaca oo saddexan">
                </div>
                <div class="form-group">
                    <label for="victim_age" class="form-label">Da'da</label>
                    <input type="number" name="victim_age" id="victim_age" class="form-control" placeholder="Sano">
                </div>
                <div class="form-group">
                    <label for="victim_gender" class="form-label">Jinsiga</label>
                    <select name="victim_gender" id="victim_gender" class="form-select">
                        <option value="">Dooro...</option>
                        <option value="Male">Lab (Male)</option>
                        <option value="Female">Dhedig (Female)</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label for="victim_injury" class="form-label">Dhibka gaaray (Injury/Harm)</label>
                    <textarea name="victim_injury" id="victim_injury" class="form-control" rows="2" placeholder="Faahfaahin kooban..."></textarea>
                </div>
            </div>
        </div>

        <!-- 3. FAAHFAAHINTA DHACDADA -->
        <h3 style="font-size: 1.1rem; color: var(--sidebar-bg); margin-bottom: 1rem; font-weight: 700; border-bottom: 2px solid var(--accent-color); padding-bottom: 0.5rem;">
            FAAHFAAHINTA DHACDADA (INCIDENT DESCRIPTION)
        </h3>
        <div style="padding: 1.5rem; border-radius: 12px; background: #fff; margin-bottom: 1.5rem;">
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="description" class="form-label">Faahfaahinta Dhacdada</label>
                <textarea name="description" id="description" class="form-control" rows="5" placeholder="Sharaxaad waafi ah..." required></textarea>
            </div>
            
            <div class="form-group">
                <label for="evidence" class="form-label">Cadaymaha (Sawiro/Dukumeenti)</label>
                <input type="file" name="evidence[]" id="evidence" class="form-control" multiple accept="image/*,application/pdf" style="padding: 0.6rem;">
                <small style="color: var(--text-sub); font-size: 0.75rem;">Waxaad soo gelin kartaa sawiro badan ama PDF files (Max: 5MB)</small>
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('crimes.index') }}" class="btn-secondary" style="text-decoration: none;">Jooji</a>
            <button type="submit" class="btn-primary" style="padding: 0.8rem 2rem; font-weight: 800;">DIIWANGELI WARBIXINTA</button>
        </div>
    </form>
</div>

    <!-- Right Sidebar: Recent Crimes -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="glass-card" style="padding: 1.5rem; background: var(--sidebar-bg); color: white; border: none;">
            <h4 style="margin-bottom: 1rem; font-family: 'Outfit'; font-size: 1.1rem;"><i class="fa-solid fa-list"></i> Dambiyada Hadda Jira</h4>
            <p style="font-size: 0.85rem; opacity: 0.9; line-height: 1.6;">
                Halkan waxaad ka arki kartaa dambiyadii ugu dambeeyay ee la diiwangeliyay. Tani waxay kaa caawinaysaa inaad iska ilaaliso ku celcelinta.
            </p>
        </div>

        <div class="glass-card" style="padding: 0; overflow: hidden;">
            <div style="padding: 1rem; border-bottom: 1px solid var(--border-soft); background: #f8f9fc;">
                <h5 style="margin: 0; font-weight: 700; color: var(--sidebar-bg); font-size: 0.9rem;">5-tii Dambi ee Ugu Dambeeyay</h5>
            </div>
            <div style="display: flex; flex-direction: column;">
                @if(isset($recentCrimes) && $recentCrimes->count() > 0)
                    @foreach($recentCrimes as $rc)
                    <div style="padding: 1rem; border-bottom: 1px solid var(--border-soft); display: flex; gap: 1rem; align-items: center;">
                        <div style="width: 40px; height: 40px; background: #e3f2fd; color: #3498db; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        <div style="flex: 1; overflow: hidden;">
                            <h6 style="margin: 0; font-weight: 700; color: var(--sidebar-bg); font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $rc->crime_type }}
                            </h6>
                            <span style="font-size: 0.75rem; color: var(--text-sub);">{{ $rc->case_number }}</span>
                        </div>
                        <span style="font-size: 0.7rem; font-weight: 700; padding: 2px 6px; border-radius: 4px; background: {{ $rc->status == 'Diiwaangelin' ? '#fff3e0' : '#e8f5e9' }}; color: {{ $rc->status == 'Diiwaangelin' ? '#e67e22' : '#27ae60' }};">
                            {{ $rc->status }}
                        </span>
                    </div>
                    @endforeach
                    <a href="{{ route('crimes.index') }}" style="padding: 1rem; text-align: center; display: block; text-decoration: none; font-size: 0.85rem; font-weight: 700; color: #3498db;">
                        Eeg Dhamaan Liiska <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @else
                    <div style="padding: 2rem; text-align: center; color: var(--text-sub);">
                        <i class="fa-solid fa-folder-open" style="opacity: 0.3; font-size: 2rem; margin-bottom: 0.5rem;"></i>
                        <p style="font-size: 0.85rem;">Ma jiraan dambiyo hore</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

