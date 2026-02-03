<!DOCTYPE html>
<html lang="so">
<head>
    <meta charset="UTF-8">
    <title>Warbixinta Kiiska - {{ $crime->case_number }}</title>
    <style>
        @page { margin: 20px; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #2c3e50; line-height: 1.6; }
        .header { text-align: center; border-bottom: 3px solid #1e3a8a; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { width: 80px; height: 80px; margin: 0 auto 15px; }
        .title { font-size: 24px; font-weight: bold; color: #1e3a8a; margin: 10px 0; }
        .subtitle { font-size: 14px; color: #64748b; }
        .section { margin: 25px 0; }
        .section-title { font-size: 16px; font-weight: bold; color: #1e3a8a; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 15px; }
        .info-grid { display: table; width: 100%; margin: 15px 0; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; font-weight: bold; color: #475569; width: 35%; padding: 8px 0; }
        .info-value { display: table-cell; color: #1e293b; padding: 8px 0; }
        .evidence-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin: 15px 0; }
        .evidence-item { text-align: center; border: 1px solid #e2e8f0; padding: 10px; border-radius: 8px; }
        .evidence-item img { max-width: 100%; height: auto; border-radius: 4px; }
        .footer { margin-top: 50px; text-align: center; font-size: 11px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            @php
                $logoPath = public_path('images/police-logo.png');
                $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
            @endphp
            @if($logoData)
                <img src="data:image/png;base64,{{ $logoData }}" alt="Police Logo" style="width: 80px; height: 80px; object-fit: contain;">
            @endif
        </div>
        <div class="title">BOOLISKA SOOMAALIYA</div>
        <div class="subtitle">Somali National Police Force</div>
        <div style="margin-top: 15px; font-size: 18px; font-weight: bold; color: #dc2626;">WARBIXINTA KIISKA</div>
    </div>

    <div class="section">
        <div class="section-title">XOGTA KIISKA</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Lambarka Kiiska:</div>
                <div class="info-value">{{ $crime->case_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nooca Dambiga:</div>
                <div class="info-value">{{ $crime->crime_type }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Taariikhda:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($crime->crime_date)->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Goobta:</div>
                <div class="info-value">{{ $crime->location }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Xaalada:</div>
                <div class="info-value">{{ $crime->status }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">La Soo Sheegay:</div>
                <div class="info-value">{{ $crime->reporter->name ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">FAAHFAAHINTA DAMBIGA</div>
        <p style="text-align: justify; color: #334155;">{{ $crime->description }}</p>
    </div>

    @if($crime->suspects->count() > 0)
    <div class="section">
        <div class="section-title">DAMBIILAYAASHA</div>
        @foreach($crime->suspects as $suspect)
        <div style="margin: 15px 0; padding: 15px; background: #f8fafc; border-left: 4px solid #dc2626; border-radius: 4px; display: flex; gap: 15px;">
            @if($suspect->photo)
                @php
                    $photoPath = storage_path('app/public/' . $suspect->photo);
                    $photoData = file_exists($photoPath) ? base64_encode(file_get_contents($photoPath)) : '';
                    $photoExt = pathinfo($suspect->photo, PATHINFO_EXTENSION);
                @endphp
                @if($photoData)
                <div style="flex-shrink: 0;">
                    <img src="data:image/{{ $photoExt }};base64,{{ $photoData }}" alt="Suspect Photo" style="width: 80px; height: 80px; border-radius: 8px; object-fit: cover; border: 2px solid #dc2626;">
                </div>
                @endif
            @endif
            <div class="info-grid" style="flex: 1;">
                <div class="info-row">
                    <div class="info-label">Magaca:</div>
                    <div class="info-value">{{ $suspect->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Da'da:</div>
                    <div class="info-value">{{ $suspect->age }} sano</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Xaalada Qabashadda:</div>
                    <div class="info-value">{{ $suspect->arrest_status }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($crime->evidence->count() > 0)
    <div class="section">
        <div class="section-title">CADAYMAHA ({{ $crime->evidence->count() }})</div>
        <div class="evidence-grid">
            @foreach($crime->evidence as $evidence)
            <div class="evidence-item">
                @if($evidence->file_type == 'image')
                    @php
                        $evidencePath = storage_path('app/public/' . $evidence->file_path);
                        $evidenceData = file_exists($evidencePath) ? base64_encode(file_get_contents($evidencePath)) : '';
                        $evidenceExt = pathinfo($evidence->file_path, PATHINFO_EXTENSION);
                    @endphp
                    @if($evidenceData)
                        <img src="data:image/{{ $evidenceExt }};base64,{{ $evidenceData }}" alt="Evidence" style="max-width: 100%; height: auto; border-radius: 4px;">
                    @endif
                @else
                    <div style="padding: 30px; background: #f1f5f9; border-radius: 4px;">
                        <div style="font-size: 32px; color: #64748b;">📄</div>
                        <div style="font-size: 11px; color: #475569; margin-top: 8px;">Document</div>
                    </div>
                @endif
                <div style="font-size: 10px; color: #64748b; margin-top: 8px;">#{{ $loop->iteration }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="footer">
        <p><strong>Booliska Soomaaliya</strong> | Muqdisho, Soomaaliya</p>
        <p>Warbixintan waxaa la soo saaray: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
