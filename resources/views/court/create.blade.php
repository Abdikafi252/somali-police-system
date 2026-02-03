@extends('layouts.app')

@section('title', 'Soo saar Xukun Raxmi ah')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit'; uppercase">SOO SAAR XUKUN (COURT VERDICT)</h1>
    <p style="color: var(--text-sub);">Dhamaystirkii dacwadda kiiska: <strong style="color: var(--sidebar-bg);">{{ $prosecution->policeCase->case_number }}</strong></p>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- CID Investigation & Prosecution Charges Comparison -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- CID Report -->
            <div class="glass-card" style="padding: 1.5rem; border-left: 5px solid #2ecc71; background: #fff;">
                <h4 style="margin-bottom: 1rem; color: #27ae60; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; text-transform: uppercase;">
                    <i class="fa-solid fa-file-shield"></i> WARBIXINTA BAARISTA CID
                </h4>
                <div style="font-size: 0.85rem; color: #34495e; line-height: 1.6; max-height: 250px; overflow-y: auto; padding-right: 0.5rem;">
                    <strong style="display: block; margin-bottom: 0.5rem; color: #27ae60;">NATIIJADA (FINDINGS):</strong>
                    {!! $prosecution->policeCase->investigation->findings !!}
                    
                    @if($prosecution->policeCase->investigation->statements->count() > 0)
                    <hr style="margin: 1rem 0; border: none; border-top: 1px dashed #eee;">
                    <strong style="display: block; margin-bottom: 0.5rem; color: #9b59b6;">HADALLADA (STATEMENTS):</strong>
                    @foreach($prosecution->policeCase->investigation->statements as $statement)
                        <div style="margin-bottom: 0.5rem; padding-left: 0.5rem; border-left: 2px solid #ddd;">
                            <span style="font-weight: 700; font-size: 0.75rem;">{{ $statement->person_name }}:</span> 
                            <span style="font-style: italic;">"{{ Str::limit($statement->statement, 60) }}"</span>
                        </div>
                    @endforeach
                    @endif
                </div>
            </div>

            <!-- Prosecution Charges -->
            <div class="glass-card" style="padding: 1.5rem; border-left: 5px solid #8e44ad; background: #fff;">
                <h4 style="margin-bottom: 1rem; color: #8e44ad; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; text-transform: uppercase;">
                    <i class="fa-solid fa-scale-balanced"></i> EEDEYMAHA XEER-ILAALINTA
                </h4>
                <div style="font-size: 0.85rem; color: #34495e; line-height: 1.6; max-height: 250px; overflow-y: auto; padding-right: 0.5rem;">
                    <strong style="display: block; margin-bottom: 0.5rem; color: #8e44ad;">EEDEYMAHA (CHARGES):</strong>
                    {{ $prosecution->charges }}
                </div>
            </div>
        </div>

        <!-- Court Verdict Form -->
        <div class="glass-card" style="padding: 2.5rem;">
            <form action="{{ route('court-cases.store') }}" method="POST">
                @csrf
                <input type="hidden" name="prosecution_id" value="{{ $prosecution->id }}">

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="verdict" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase;">
                        <i class="fa-solid fa-gavel" style="color: var(--sidebar-bg); margin-right: 0.5rem;"></i> Xukunka Maxkamadda (Final Verdict)
                    </label>
                    <textarea name="verdict" id="verdict" class="form-control" rows="10" required 
                        style="border: 2px solid var(--border-soft); border-radius: 12px; padding: 1.2rem; font-size: 1rem; line-height: 1.6; transition: all 0.3s ease;" 
                        placeholder="Gali xukunka rasmiga ah ee ay maxkamaddu soo saartay..."></textarea>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn-primary" style="padding: 1.2rem 3rem; font-weight: 800; border-radius: 10px; border: none; background: var(--sidebar-bg); box-shadow: 0 10px 20px rgba(45, 74, 83, 0.2);">
                        <i class="fa-solid fa-check-double"></i> XIR KIISKA & SOO SAAR XUKUN
                    </button>
                    <a href="{{ route('cases.show', $prosecution->policeCase) }}" class="btn" style="padding: 1.2rem 2rem; background: #f1f2f6; color: var(--text-sub); border-radius: 10px; font-weight: 700; text-decoration: none;">
                        Jooji
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="glass-card" style="padding: 1.5rem; background: #f8fafc; border: 1px dashed var(--border-soft);">
            <h4 style="color: var(--sidebar-bg); margin-bottom: 1rem; font-family: 'Outfit';">Xogta Dacwad-shaqalaha</h4>
            <div style="font-size: 0.9rem; color: var(--text-sub); display: flex; flex-direction: column; gap: 0.8rem;">
                <p style="margin: 0;"><strong style="color: var(--sidebar-bg);">Xeer-ilaaliye:</strong> {{ $prosecution->prosecutor->name ?? 'Lama heli karo' }}</p>
                <p style="margin: 0;"><strong style="color: var(--sidebar-bg);">Gudbisay:</strong> {{ $prosecution->submission_date }}</p>
            </div>
        </div>

        <div class="glass-card" style="padding: 1.5rem; background: var(--sidebar-bg); color: white; border: none;">
            <h4 style="margin-bottom: 1rem; font-family: 'Outfit';">Awoodda Garsoorka</h4>
            <p style="font-size: 0.85rem; line-height: 1.6; opacity: 0.9;">
                Go'aankani waa kan ugu dambeeya ee kiiskan. Markaad gudbiso, kiisku wuxuu noqonayaa mid <strong>Xiran (Closed)</strong>, xukunkuna wuxuu noqonayaa mid rasmiga ah oo diiwaangashan.
            </p>
        </div>
    </div>
</div>
@endsection
