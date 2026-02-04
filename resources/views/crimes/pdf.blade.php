@extends('layouts.app')

@section('title', 'Warbixinta Dambiga: ' . $crime->case_number)

@section('css')
<style>
    @media print {
        .sidebar, .top-bar, .no-print, .edu-sidebar, .edu-topbar, .mobile-toggle {
            display: none !important;
        }
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: auto !important;
            overflow: visible !important;
        }
        .glass-card {
            border: none !important;
            box-shadow: none !important;
            background: white !important;
            padding: 0 !important;
        }
        body {
            background: white !important;
            overflow: visible !important;
        }
        .app-wrapper {
            display: block !important;
            height: auto !important;
            overflow: visible !important;
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
        border-bottom: 2px solid #1C1E26;
        padding-bottom: 1.5rem;
    }

    .report-section {
        margin-bottom: 2.5rem;
    }

    .section-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        color: #1C1E26;
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
        color: #8F95A2;
        text-transform: uppercase;
    }

    .info-item span {
        font-weight: 700;
        color: #1C1E26;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }

    .table th {
        text-align: left;
        background: #f9f9f9;
        padding: 0.75rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #666;
        border-bottom: 2px solid #eee;
    }

    .table td {
        padding: 0.75rem;
        border-bottom: 1px solid #eee;
        font-size: 0.9rem;
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

    .pill {
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    .pill-red { background: #fee2e2; color: #dc2626; }
    .pill-green { background: #dcfce7; color: #16a34a; }
    .pill-blue { background: #dbeafe; color: #1e40af; }
</style>
@endsection

@section('content')
<div class="no-print" style="max-width: 800px; margin: 1rem auto; display: flex; justify-content: space-between;">
    <a href="{{ route('crimes.show', $crime->id) }}" class="btn" style="background: white; border: 1px solid rgba(0,0,0,0.1); text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; color: #666;">
        <i class="fa-solid fa-arrow-left"></i> Ku laabo Dambiga
    </a>
    <button onclick="window.print()" class="btn-primary" style="background: #1C1E26; color: white; padding: 0.5rem 2rem; border-radius: 5px; border: none; cursor: pointer; font-weight: 600;">
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
        <h2 style="margin: 0; font-family: 'Outfit'; color: #1C1E26;">JAMHUURIYADDA FEDERAALKA SOOMAALIYA</h2>
        <h3 style="margin: 5px 0; color: #1C1E26;">CIIDANKA BOOLISKA SOOMAALIYEED</h3>
        <h4 style="margin: 5px 0; color: #dc2626;">WARBIXINTA DAMBIGA (CRIME REPORT)</h4>
        <div style="margin-top: 1rem; font-weight: 700; color: #666;">TAARIIKH: {{ now()->format('d/m/Y') }}</div>
    </div>

    <div class="report-section">
        <div class="section-title">Xogta Guud ee Kiiska (Case Summary)</div>
        <div class="info-grid">
            <div class="info-item">
                <label>Case Number</label>
                <span>{{ $crime->case_number }}</span>
            </div>
            <div class="info-item">
                <label>Nooca Dambiga (Crime Type)</label>
                <span>{{ $crime->crime_type }}</span>
            </div>
            <div class="info-item">
                <label>Goobta (Location)</label>
                <span>{{ $crime->location }}</span>
            </div>
            <div class="info-item">
                <label>Taariikhda Dambiga (Date & Time)</label>
                <span>{{ \Carbon\Carbon::parse($crime->crime_date)->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-item">
                <label>Xaalada (Status)</label>
                <span class="pill {{ $crime->status == 'Baaris' ? 'pill-red' : ($crime->status == 'Xiran' ? 'pill-green' : 'pill-blue') }}">
                    {{ $crime->status }}
                </span>
            </div>
            <div class="info-item">
                <label>Sarkaalka Diiwaangeliyay</label>
                <span>{{ $crime->reporter->name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <div class="report-section">
        <div class="section-title">Faahfaahinta Dambiga (Description)</div>
        <div style="line-height: 1.8; color: #333; text-align: justify; font-size: 0.95rem;">
            {{ $crime->description }}
        </div>
    </div>

    @if($crime->victims->count() > 0)
    <div class="report-section">
        <div class="section-title">Dhibanayaasha (Victims)</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Magaca</th>
                    <th>Da'da</th>
                    <th>Jinsiga</th>
                    <th>Nooca Dhaawaca</th>
                </tr>
            </thead>
            <tbody>
                @foreach($crime->victims as $victim)
                <tr>
                    <td>{{ $victim->name }}</td>
                    <td>{{ $victim->age }}</td>
                    <td>{{ $victim->gender }}</td>
                    <td>{{ $victim->injury_type }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($crime->suspects->count() > 0)
    <div class="report-section">
        <div class="section-title">Eedeysanayaasha (Suspects)</div>
        @foreach($crime->suspects as $suspect)
        <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px dashed #eee;">
            @if($suspect->photo)
                @php
                    $photoPath = storage_path('app/public/' . $suspect->photo);
                    $photoUrl = file_exists($photoPath) 
                        ? 'data:image/' . pathinfo($suspect->photo, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($photoPath))
                        : null;
                @endphp
                @if($photoUrl)
                <img src="{{ $photoUrl }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                @else
                <div style="width: 80px; height: 80px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #ccc;">
                    <i class="fa-solid fa-user"></i>
                </div>
                @endif
            @else
                <div style="width: 80px; height: 80px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #ccc;">
                    <i class="fa-solid fa-user"></i>
                </div>
            @endif
            
            <div style="flex: 1;">
                <div style="font-weight: 700; font-size: 1.1rem; color: #1C1E26; margin-bottom: 0.5rem;">
                    {{ $suspect->name }} 
                    @if($suspect->nickname)
                        <span style="font-size: 0.9rem; font-weight: 400; color: #666;">("{{ $suspect->nickname }}")</span>
                    @endif
                </div>
                <div class="info-grid" style="grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 0;">
                    <div class="info-item"><label>Da'da</label> <span>{{ $suspect->age ?? '-' }}</span></div>
                    <div class="info-item"><label>Jinsiga</label> <span>{{ $suspect->gender ?? '-' }}</span></div>
                    <div class="info-item"><label>Hooyada</label> <span>{{ $suspect->mother_name ?? '-' }}</span></div>
                    <div class="info-item"><label>Xaalada</label> <span>{{ ucfirst($suspect->arrest_status) }}</span></div>
                    <div class="info-item" style="grid-column: span 2;"><label>Cinwaanka</label> <span>{{ $suspect->address ?? '-' }}</span></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($crime->evidence->count() > 0)
    <div class="report-section">
        <div class="section-title">Cadeymaha (Evidence)</div>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
            @foreach($crime->evidence as $ev)
            <div style="border: 1px solid #eee; border-radius: 8px; padding: 1rem; text-align: center;">
                @if($ev->file_type === 'image')
                    @php
                        $evPath = storage_path('app/public/' . $ev->file_path);
                        $evUrl = file_exists($evPath) 
                            ? 'data:image/' . pathinfo($ev->file_path, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($evPath))
                            : null;
                    @endphp
                    @if($evUrl)
                        <img src="{{ $evUrl }}" style="max-width: 100%; height: 100px; object-fit: cover; border-radius: 4px; margin-bottom: 0.5rem;">
                    @else
                        <div style="height: 100px; display: flex; align-items: center; justify-content: center; background: #f9f9f9; margin-bottom: 0.5rem;"><i class="fa-solid fa-image"></i> No Image</div>
                    @endif
                @else
                    <div style="height: 100px; display: flex; align-items: center; justify-content: center; background: #f9f9f9; margin-bottom: 0.5rem; font-size: 2rem; color: #666;">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                @endif
                <div style="font-size: 0.8rem; color: #666;">{{ $ev->notes ?? 'Cadeyn' }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="signature-section">
        <div class="sig-column" style="text-align: center;">
            <div class="sig-line">Sarkaalka Diwangeliyay</div>
            <div style="margin-top: 5px;">{{ $crime->reporter->name ?? '________________' }}</div>
            <div style="font-size: 0.8rem; color: #666;">Ciidanka Booliska Soomaaliyeed</div>
        </div>
        <div class="sig-column" style="text-align: center;">
            <div class="sig-line">Taliyaha Saldhiga (Approval)</div>
            <div style="margin-top: 10px; font-family: 'Courier New', monospace; font-weight: bold; color: #1e3a8a; border: 2px solid #1e3a8a; padding: 10px; display: inline-block; transform: rotate(-2deg); opacity: 0.8;">
                SALDHIGA BOOLISKA<br>
                {{ strtoupper($crime->location) }}<br>
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
