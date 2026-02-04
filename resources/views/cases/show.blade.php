@extends('layouts.app')

@section('title', 'Faahfaahinta Kiiska: ' . $case->case_number)

@section('content')
<div class="print-container">
    <!-- Screen-Only Header -->
    <div class="header no-print" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit'; display: flex; align-items: center; gap: 0.5rem;">
                {{ $case->case_number }}
                <span style="font-size: 0.9rem; background: var(--accent-lime); color: var(--sidebar-bg); padding: 4px 12px; border-radius: 20px; letter-spacing: 0;">{{ $case->status }}</span>
            </h1>
            <p style="color: var(--text-sub);">Faahfaahinta baaritaanka iyo heerarka dacwad qaadista.</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <button onclick="window.print()" class="btn" style="background: var(--sidebar-bg); color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fa-solid fa-print"></i> Print Report
            </button>
            <a href="{{ route('cases.index') }}" class="btn" style="background: white; border: 1px solid var(--border-soft); color: var(--text-sub); text-decoration: none; padding: 0.8rem 1.5rem; border-radius: 8px; font-weight: 600;">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Official Print Header (Only visible on print) -->
    <div class="print-header only-print">
        <div style="text-align: center; margin-bottom: 2rem; border-bottom: 2px solid #000; padding-bottom: 1rem;">
             <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e4/Emblem_of_Somalia.svg/1024px-Emblem_of_Somalia.svg.png" style="width: 80px; margin-bottom: 1rem;">
             <h2 style="margin: 0; font-size: 1.2rem; text-transform: uppercase; font-weight: 800;">Jamhuuriyadda Federaalka Soomaaliya</h2>
             <h3 style="margin: 5px 0; font-size: 1rem; text-transform: uppercase;">Ciidanka Booliska Soomaaliyeed</h3>
             <h4 style="margin: 5px 0; font-size: 0.9rem; color: #d35400; text-transform: uppercase;">Waaxda Baarista Dambiyada (CID)</h4>
             <h5 style="margin-top: 1rem; font-size: 1rem; text-decoration: underline;">WARBIXINTA BAARISTA RASMIGA AH</h5>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="details-grid">
        
        <!-- XOGTA GUUD (SUMMARY) -->
        <div class="edu-card full-width section" style="margin-bottom: 2rem;">
            <h3 class="section-title">XOGTA GUUD EE KIISKA (CASE SUMMARY)</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 1rem;">
                <div class="field-group">
                    <span class="label">LAMBARKA KIISKA (CASE NUMBER)</span>
                    <span class="value lg">{{ $case->case_number }}</span>
                </div>
                <div class="field-group">
                    <span class="label">NOOCA DAMBIGA</span>
                    <span class="value">{{ $case->crime->crime_type }}</span>
                </div>
            </div>
             <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div class="field-group">
                    <span class="label">GOOBTA</span>
                    <span class="value">{{ $case->crime->location }}</span>
                </div>
                <div class="field-group">
                    <span class="label">TAARIIKHDA & WAQTIGA</span>
                    <span class="value">{{ \Carbon\Carbon::parse($case->crime->crime_date)->format('d/m/Y - h:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- FAAHFAAHINTA DHACDADA -->
        <div class="edu-card full-width section" style="margin-bottom: 2rem;">
             <h3 class="section-title">NATIIJADA BAARITAANKA (FINDINGS)</h3>
             <div class="text-content">
                <p>
                    {{ $case->crime->description }}
                </p>
             </div>
        </div>

        <!-- EEDEYSANE & DHIBANE -->
        <div class="split-row">
            
            <!-- Suspects -->
            <div class="edu-card section">
                <h3 class="section-title">EEDEYSANE (SUSPECT)</h3>
                @forelse($case->crime->suspects as $suspect)
                <div class="person-box">
                    <div class="person-header">
                        <span class="person-name">{{ $suspect->name }}</span>
                        <span class="person-role">Eedeysane</span>
                    </div>
                    <div class="person-details">
                         <div><strong>Naaneys:</strong> {{ $suspect->nickname ?? '-' }}</div>
                         <div><strong>Da'da:</strong> {{ $suspect->age }} sano</div>
                         <div><strong>Hooyada:</strong> {{ $suspect->mother_name ?? '-' }}</div>
                         <div><strong>Deggan:</strong> {{ $suspect->residence ?? '-' }}</div>
                         <div><strong>Xaaladda:</strong> {{ $suspect->arrest_status }}</div>
                    </div>
                </div>
                @empty
                 <p class="empty-text">Macluumaad lagama hayo.</p>
                @endforelse
            </div>

            <!-- Victims -->
            <div class="edu-card section">
                 <h3 class="section-title">DHIBANE (VICTIM)</h3>
                 @forelse($case->crime->victims as $victim)
                 <div class="person-box victim-box">
                     <div class="person-header">
                        <span class="person-name">{{ $victim->name }}</span>
                        <span class="person-role">Dhibane</span>
                    </div>
                     <div class="person-details">
                         <div><strong>Da'da:</strong> {{ $victim->age ?? '-' }}</div>
                         <div><strong>Jinsiga:</strong> {{ $victim->gender ?? '-' }}</div>
                         <div style="margin-top: 5px; font-style: italic;">"{{ $victim->injury_type ?? 'No Detail' }}"</div>
                    </div>
                 </div>
                 @empty
                   <p class="empty-text">Macluumaad lagama hayo.</p>
                 @endforelse
            </div>

        </div>

        <!-- Signatures (Print Only) -->
        <div class="signatures only-print" style="margin-top: 4rem; display: flex; justify-content: space-between; padding-top: 2rem; border-top: 1px solid #000;">
            <div style="text-align: center; width: 40%;">
                <p style="margin-bottom: 2rem;">__________________________</p>
                <strong>Sarkaalka Baarista (CID Officer)</strong><br>
                <span>{{ $case->assignedOfficer->name ?? '________________' }}</span>
            </div>
            <div style="text-align: center; width: 40%;">
                 <p style="margin-bottom: 2rem;">__________________________</p>
                <strong>Taliyaha CID-da (Approval)</strong><br>
                <span>Magaca & Saxiixa</span>
            </div>
        </div>

    </div>
</div>

<style>
    /* Default Screen Styles */
    .section-title {
        font-size: 0.9rem;
        font-weight: 800;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 1rem;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 0.5rem;
    }
    .field-group { margin-bottom: 0.5rem; }
    .label { display: block; font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; }
    .value { font-size: 1rem; font-weight: 600; color: #1e293b; }
    .value.lg { font-size: 1.2rem; font-weight: 800; }
    .text-content p { line-height: 1.6; color: #334155; }
    
    .person-box {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid #e2e8f0;
    }
    .victim-box { background: #fef2f2; border-color: #fecaca; }
    
    .person-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
    .person-name { font-weight: 700; color: #0f172a; }
    .person-role { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; background: #e2e8f0; padding: 2px 6px; border-radius: 4px; color: #64748b; }
    .victim-box .person-role { background: #fee2e2; color: #991b1b; }
    .person-details { font-size: 0.85rem; color: #475569; display: grid; icon-gap: 5px; }

    .split-row { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
    .only-print { display: none; }

    /* Print Styles */
    @media print {
        @page { margin: 2cm; size: A4; }
        body { background: white !important; font-family: 'Times New Roman', serif; color: #000; }
        .no-print, .edu-sidebar, .top-bar { display: none !important; }
        .only-print { display: block !important; }
        .print-container { padding: 0; max-width: 100%; }
        .edu-card { border: none !important; padding: 0 !important; margin-bottom: 1.5rem !important; box-shadow: none !important; }
        .section-title { color: #000 !important; border-bottom: 1px solid #000 !important; font-weight: 900 !important; font-size: 10pt; }
        .label { color: #555 !important; font-size: 8pt; }
        .value { color: #000 !important; font-size: 11pt; }
        .person-box { border: 1px solid #000 !important; background: white !important; }
        .person-role { border: 1px solid #000; background: white !important; color: #000 !important; }
        .split-row { gap: 1cm; }
        a { text-decoration: none; color: #000; }
        
        /* Typography adjustments for print */
        h1, h2, h3, h4, h5 { font-family: 'Times New Roman', serif; color: #000 !important; }
        p, span, div { font-family: 'Times New Roman', serif; }
    }
</style>
@endsection
