@extends('layouts.app')

@section('title', 'Warbixinta CID: ' . $investigation->policeCase->case_number)

@section('css')
<style>
    @media print {
        .sidebar, .top-bar, .no-print {
            display: none !important;
        }
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .glass-card {
            border: none !important;
            box-shadow: none !important;
            background: white !important;
            padding: 0 !important;
        }
        body {
            background: white !important;
        }
    }

    .report-container {
        max-width: 800px;
        margin: 2rem auto;
        background: white;
        padding: 3rem;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border-radius: 8px;
    }

    .report-header {
        text-align: center;
        margin-bottom: 3rem;
        border-bottom: 2px solid var(--sidebar-bg);
        padding-bottom: 1.5rem;
    }

    .report-section {
        margin-bottom: 2.5rem;
    }

    .section-title {
        font-family: 'Outfit';
        font-weight: 800;
        color: var(--sidebar-bg);
        border-bottom: 1px solid #eee;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        text-transform: uppercase;
        font-size: 1rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-item label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-sub);
        text-transform: uppercase;
    }

    .info-item span {
        font-weight: 700;
        color: var(--sidebar-bg);
    }

    .statement-box {
        background: #f9f9f9;
        padding: 1.5rem;
        border-left: 4px solid #9b59b6;
        margin-bottom: 1rem;
        font-style: italic;
    }

    .signature-section {
        margin-top: 5rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
    }

    .sig-line {
        border-top: 1px solid #000;
        padding-top: 0.5rem;
        text-align: center;
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<div class="no-print" style="max-width: 800px; margin: 1rem auto; display: flex; justify-content: space-between;">
    <a href="{{ route('cases.show', $investigation->policeCase) }}" class="btn" style="background: white; border: 1px solid var(--border-soft); text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; color: var(--text-sub);">
        <i class="fa-solid fa-arrow-left"></i> Ku laabo Kiiska
    </a>
    <button onclick="window.print()" class="btn-primary" style="padding: 0.5rem 2rem; border-radius: 5px; border: none; cursor: pointer;">
        <i class="fa-solid fa-print"></i> Daabac Warbixinta
    </button>
</div>

<div class="report-container">
    <div class="report-header">
        <div style="text-align: center; margin-bottom: 1rem;">
            @php
                $logoPath = public_path('images/logo.jpg');
                $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
            @endphp
            @if($logoData)
                <img src="data:image/jpeg;base64,{{ $logoData }}" style="width: 100px; height: auto;">
            @endif
        </div>
        <h2 style="margin: 0; font-family: 'Outfit'; color: var(--sidebar-bg);">JAMHUURIYADDA FEDERAALKA SOOMAALIYA</h2>
        <h3 style="margin: 5px 0; color: var(--sidebar-bg);">CIIDANKA BOOLISKA SOOMAALIYEED</h3>
        <h4 style="margin: 5px 0; color: #e67e22;">WAXAXDA BAARISTA DAMBIYADA (CID)</h4>
        <div style="margin-top: 1rem; font-weight: 700;">WARBIXINTA BAARISTA RASMIGA AH</div>
    </div>

    <div class="report-section">
        <div class="section-title">Xogta Guud ee Kiiska (Case Summary)</div>
        <div class="info-grid">
            <div class="info-item">
                <label>Case Number</label>
                <span>{{ $investigation->policeCase->case_number }}</span>
            </div>
            <div class="info-item">
                <label>Nooca Dambiga</label>
                <span>{{ $investigation->policeCase->crime->crime_type }}</span>
            </div>
            <div class="info-item">
                <label>Goobta</label>
                <span>{{ $investigation->policeCase->crime->location }}</span>
            </div>
            <div class="info-item">
                <label>Taariikhda Baarista</label>
                <span>{{ $investigation->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    <div class="report-section">
        <div class="section-title">Natiijada Baaritaanka (Findings)</div>
        <div style="line-height: 1.8; color: #333;">
            {!! $investigation->findings !!}
        </div>
    </div>

    @if($investigation->evidence_list)
    <div class="report-section">
        <div class="section-title">Liiska Caddeymada (Evidence List)</div>
        <p style="color: #444;">{{ $investigation->evidence_list }}</p>
    </div>
    @endif

    @if($investigation->statements->count() > 0)
    <div class="report-section">
        <div class="section-title">Hadallada Eedeysanayaasha/Markhaatiyada</div>
        @foreach($investigation->statements as $statement)
        <div class="statement-box">
            <div style="font-weight: 700; margin-bottom: 0.5rem; color: #9b59b6;">
                {{ $statement->person_name }} ({{ $statement->person_type }}) - {{ \Carbon\Carbon::parse($statement->statement_date)->format('d/m/Y') }}
            </div>
            <p style="margin: 0;">"{{ $statement->statement }}"</p>
        </div>
        @endforeach
    </div>
    @endif

    <div class="signature-section">
        <div class="sig-column" style="text-align: center;">
            <div class="sig-line">Sarkaalka Baarista (CID Officer)</div>
            <div style="margin-top: 5px;">{{ $investigation->policeCase->assignedOfficer->name ?? '________________' }}</div>
            <div style="font-size: 0.8rem; color: #666;">Ciidanka Booliska Soomaaliyeed</div>
        </div>
        <div class="sig-column" style="text-align: center;">
            <div class="sig-line">Taliyaha CID-da (Approval)</div>
            <div style="margin-top: 10px; font-family: 'Courier New', monospace; font-weight: bold; color: #e67e22; border: 2px solid #e67e22; padding: 10px; display: inline-block; transform: rotate(-2deg); opacity: 0.8;">
                WAAXDA BAARISTA<br>
                (CID)<br>
                ANSIXINTA
            </div>
            <div style="font-size: 0.8rem; color: #666; margin-top: 5px;">Ciidanka Booliska Soomaaliyeed</div>
        </div>
    </div>

    <div style="margin-top: 4rem; text-align: center; font-size: 0.7rem; color: #777; border-top: 1px solid #eee; padding-top: 1rem;">
        Warbixintan waxaa si otomaatig ah looga soo saaray Nidaamka Isku-dhafka ah ee Booliska Soomaaliyeed (SNP System).
    </div>
</div>
@endsection
