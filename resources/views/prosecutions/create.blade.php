@extends('layouts.app')

@section('title', 'Oogidda Dacwadda Cusub')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit'; uppercase">OOGIDDA DACWADDA (PROSECUTION)</h1>
    <p style="color: var(--text-sub);">U diyaarinta dacwadda kiiska: <strong style="color: var(--sidebar-bg);">{{ $case->case_number }}</strong></p>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- CID Investigation Full Report -->
        <div class="glass-card" style="padding: 2rem; border-left: 5px solid #27ae60; background: #fff;">
            <h3 style="margin-bottom: 1.5rem; color: #34495e; font-family: 'Outfit'; font-weight: 700; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                <i class="fa-solid fa-file-shield" style="color: #27ae60;"></i> WARBIXINTA BAARISTA CID (FULL REPORT)
            </h3>
            
            <div class="report-section" style="margin-bottom: 2rem;">
                <h5 style="color: #27ae60; font-weight: 800; text-transform: uppercase; font-size: 0.8rem; margin-bottom: 1rem;">
                    <i class="fa-solid fa-clipboard-list"></i> Natiijada Baaritaanka (Findings)
                </h5>
                <div style="background: rgba(39, 174, 96, 0.05); padding: 1.5rem; border-radius: 12px; line-height: 1.8; color: #2c3e50;">
                    {!! $case->investigation->findings !!}
                </div>
            </div>

            @if($case->investigation->evidence_list || ($case->investigation->files && count($case->investigation->files) > 0))
            <div class="report-section" style="margin-bottom: 2rem;">
                <h5 style="color: #e67e22; font-weight: 800; text-transform: uppercase; font-size: 0.8rem; margin-bottom: 1rem;">
                    <i class="fa-solid fa-box-archive"></i> Caddeymada & Lifaaqyada (Evidence)
                </h5>
                <p style="margin-bottom: 1rem; color: #576574;">{{ $case->investigation->evidence_list }}</p>
                
                @if($case->investigation->files && count($case->investigation->files) > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 1rem;">
                    @foreach($case->investigation->files as $file)
                    <div style="border-radius: 8px; overflow: hidden; border: 1px solid var(--border-soft);">
                        <a href="{{ asset('storage/' . $file) }}" target="_blank">
                            <img src="{{ asset('storage/' . $file) }}" style="width: 100%; height: 80px; object-fit: cover;">
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            @if($case->investigation->statements->count() > 0)
            <div class="report-section">
                <h5 style="color: #9b59b6; font-weight: 800; text-transform: uppercase; font-size: 0.8rem; margin-bottom: 1rem;">
                    <i class="fa-solid fa-comments"></i> Hadalladii la Qoray (Statements)
                </h5>
                <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                    @foreach($case->investigation->statements as $statement)
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 10px; border-left: 3px solid #9b59b6;">
                        <div style="font-weight: 800; font-size: 0.85rem; color: #2d3436; margin-bottom: 0.3rem;">
                            {{ $statement->person_name }} ({{ $statement->person_type }})
                        </div>
                        <p style="margin: 0; font-size: 0.85rem; line-height: 1.5; color: #4b6584; font-style: italic;">"{{ $statement->statement }}"</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Prosecution Form -->
        <div class="glass-card" style="padding: 2.5rem;">
            <form action="{{ route('prosecutions.store') }}" method="POST">
                @csrf
                <input type="hidden" name="case_id" value="{{ $case->id }}">

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="court_id" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase;">
                        <i class="fa-solid fa-building-columns" style="color: #3498db; margin-right: 0.5rem;"></i> Maxkamadda (Select Court)
                    </label>
                    <select name="court_id" id="court_id" class="form-control" required style="
                        border: 2px solid #dfe6e9; 
                        border-radius: 12px; 
                        padding: 1rem; 
                        width: 100%; 
                        font-weight: 700; 
                        background: #ffffff; 
                        color: #2d3436;
                        font-size: 0.95rem;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    " onfocus="this.style.borderColor='#3498db'; this.style.boxShadow='0 0 0 4px rgba(52, 152, 219, 0.1)'" onblur="this.style.borderColor='#dfe6e9'; this.style.boxShadow='none'">
                        <option value="" disabled {{ !$selectedCourt ? 'selected' : '' }} style="color: #95a5a6;">Dooro Maxkamadda kiiskan dacwad oogistiisa loo gudbinayo...</option>
                        @foreach($courts as $court)
                            <option value="{{ $court->id }}" {{ $selectedCourt == $court->id ? 'selected' : '' }} style="color: #2d3436; background: #ffffff; padding: 10px;">
                                {{ $court->name }} - {{ $court->location }}
                            </option>
                        @endforeach
                    </select>
                    @if($selectedCourt)
                    <small style="display: block; margin-top: 0.5rem; color: #27ae60; font-weight: 600;">
                        <i class="fa-solid fa-circle-check"></i> Maxkamadda Degmada waxaa loo doortay sababtoo ah kiiskan wuxuu ka dhacay: {{ $case->crime->location }}
                    </small>
                    <small style="display: block; margin-top: 0.3rem; color: #3498db; font-weight: 600; font-style: italic;">
                        <i class="fa-solid fa-info-circle"></i> Waxaad bedeli kartaa maxkamadda haddii aad rabto.
                    </small>
                    @else
                    <small style="display: block; margin-top: 0.5rem; color: #e67e22; font-weight: 600;">
                        <i class="fa-solid fa-exclamation-triangle"></i> Fadlan dooro maxkamadda ku habboon kiiskan.
                    </small>
                    @endif
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="charges" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase;">
                        <i class="fa-solid fa-gavel" style="color: #8e44ad; margin-right: 0.5rem;"></i> Eedeymaha Rasmiga ah (Charges)
                    </label>
                    <textarea name="charges" id="charges" class="form-control" rows="8" required 
                        style="border: 2px solid var(--border-soft); border-radius: 12px; padding: 1.2rem; font-size: 1rem; line-height: 1.6; transition: all 0.3s ease;" 
                        placeholder="Gali eedeymaha rasmiga ah ee lagu soo oogayo dambiilaha..."></textarea>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn-primary" style="padding: 1rem 3rem; font-weight: 800; border-radius: 10px; border: none; background: #8e44ad; box-shadow: 0 10px 20px rgba(142, 68, 173, 0.2);">
                        <i class="fa-solid fa-paper-plane"></i> GUDBI MAXKAMADDA
                    </button>
                    <a href="{{ route('cases.show', $case) }}" class="btn" style="padding: 1rem 2rem; background: #f1f2f6; color: var(--text-sub); border-radius: 10px; font-weight: 700; text-decoration: none;">
                        Jooji
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="glass-card" style="padding: 1.5rem; background: #f8fafc; border: 1px dashed var(--border-soft);">
            <h4 style="color: var(--sidebar-bg); margin-bottom: 1rem; font-family: 'Outfit';">Xogta Kiiska</h4>
            <div style="font-size: 0.9rem; color: var(--text-sub); display: flex; flex-direction: column; gap: 0.8rem;">
                <p style="margin: 0;"><strong style="color: var(--sidebar-bg);">Dambiga:</strong> {{ $case->crime->crime_type }}</p>
                <p style="margin: 0;"><strong style="color: var(--sidebar-bg);">Case #:</strong> {{ $case->case_number }}</p>
            </div>
        </div>

        <div class="glass-card" style="padding: 1.5rem; background: #8e44ad; color: white; border: none;">
            <h4 style="margin-bottom: 1rem; font-family: 'Outfit';">Doorka Xeer Ilaalinta</h4>
            <p style="font-size: 0.85rem; line-height: 1.6; opacity: 0.9;">
                Tallaabadani waxay kiiska u wareejineysaa <strong style="color: #f1c40f;">Maxkamadda</strong>. Hubi in eedeymuhu ay yihiin kuwo waafaqsan sharciga iyo caddeymaha la hayo.
            </p>
        </div>
    </div>
</div>
@endsection
