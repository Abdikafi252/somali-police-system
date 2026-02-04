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
        .info-grid { width: 100%; margin: 15px 0; border-collapse: collapse; }
        .info-grid td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
        .info-label { font-weight: bold; color: #475569; width: 35%; }
        .info-value { color: #1e293b; }
        .evidence-grid { width: 100%; border-collapse: separate; border-spacing: 15px; }
        .evidence-item { text-align: center; border: 1px solid #e2e8f0; padding: 10px; border-radius: 8px; vertical-align: top; width: 30%; }
        .evidence-item img { max-width: 100%; height: auto; border-radius: 4px; }
        .footer { margin-top: 50px; text-align: center; font-size: 11px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 15px; }
        .tag { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .tag-red { background: #fee2e2; color: #dc2626; }
        .tag-green { background: #dcfce7; color: #16a34a; }
        .tag-blue { background: #dbeafe; color: #1e40af; }
    </style>
</head>
<body>
    <div style="height: 5px; background: #1e3a8a; position: absolute; top: 0; left: 0; right: 0;"></div>
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
        <table class="info-grid">
            <tr>
                <td class="info-label">Lambarka Kiiska:</td>
                <td class="info-value"><strong>{{ $crime->case_number }}</strong></td>
            </tr>
            <tr>
                <td class="info-label">Nooca Dambiga:</td>
                <td class="info-value">{{ $crime->crime_type }}</td>
            </tr>
            <tr>
                <td class="info-label">Taariikhda:</td>
                <td class="info-value">{{ \Carbon\Carbon::parse($crime->crime_date)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td class="info-label">Goobta:</td>
                <td class="info-value">{{ $crime->location }}</td>
            </tr>
            <tr>
                <td class="info-label">Xaalada:</td>
                <td class="info-value">
                    <span class="tag {{ $crime->status == 'Baaris' ? 'tag-red' : ($crime->status == 'Xiran' ? 'tag-green' : 'tag-blue') }}">
                        {{ $crime->status }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="info-label">La Soo Sheegay:</td>
                <td class="info-value">{{ $crime->reporter->name ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">FAAHFAAHINTA DAMBIGA</div>
        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; color: #334155; text-align: justify;">
            {{ $crime->description }}
        </div>
    </div>

    @if($crime->victims->count() > 0)
    <div class="section">
        <div class="section-title">DHIBANAYAASHA ({{ $crime->victims->count() }})</div>
        <table width="100%" style="border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="background: #f1f5f9; text-align: left;">
                    <th style="padding: 10px; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 12px;">MAGACA</th>
                    <th style="padding: 10px; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 12px;">DA'DA</th>
                    <th style="padding: 10px; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 12px;">JINSIGA</th>
                    <th style="padding: 10px; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 12px;">NOOCA DHAAWACA</th>
                </tr>
            </thead>
            <tbody>
                @foreach($crime->victims as $victim)
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">{{ $victim->name }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">{{ $victim->age }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">{{ $victim->gender }}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #e2e8f0;">{{ $victim->injury_type }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($crime->suspects->count() > 0)
    <div class="section">
        <div class="section-title">DAMBIILAYAASHA ({{ $crime->suspects->count() }})</div>
        @foreach($crime->suspects as $suspect)
        <div style="margin: 15px 0; padding: 15px; background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; display: table; width: 100%;">
            <div style="display: table-row;">
                @if($suspect->photo)
                    @php
                        $photoPath = storage_path('app/public/' . $suspect->photo);
                        $photoData = file_exists($photoPath) ? base64_encode(file_get_contents($photoPath)) : '';
                        $photoExt = pathinfo($suspect->photo, PATHINFO_EXTENSION);
                    @endphp
                    @if($photoData)
                    <div style="display: table-cell; width: 100px; vertical-align: top; padding-right: 15px;">
                        <img src="data:image/{{ $photoExt }};base64,{{ $photoData }}" alt="Suspect Photo" style="width: 80px; height: 80px; border-radius: 8px; object-fit: cover; border: 2px solid #dc2626;">
                    </div>
                    @endif
                @endif
                <div style="display: table-cell; vertical-align: top;">
                    <table class="info-grid" style="margin: 0;">
                        <tr>
                            <td class="info-label" style="width: 30%;">Magaca:</td>
                            <td class="info-value">{{ $suspect->name }} ({{ $suspect->nickname ?? '-' }})</td>
                        </tr>
                        <tr>
                            <td class="info-label">Hooyada:</td>
                            <td class="info-value">{{ $suspect->mother_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Da'da & Jinsiga:</td>
                            <td class="info-value">{{ $suspect->age }} sano, {{ $suspect->gender }}</td>
                        </tr>
                        <tr>
                            <td class="info-label">Xaalada:</td>
                            <td class="info-value">
                                <span class="tag {{ $suspect->arrest_status == 'arrested' ? 'tag-red' : 'tag-green' }}">
                                    {{ ucfirst($suspect->arrest_status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-label">Cinwaanka:</td>
                            <td class="info-value">{{ $suspect->address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($crime->evidence->count() > 0)
    <div class="section">
        <div class="section-title">CADAYMAHA ({{ $crime->evidence->count() }})</div>
        <table class="evidence-grid">
            <tr>
            @foreach($crime->evidence as $index => $evidence)
                @if($index % 3 == 0 && $index != 0)
                    </tr><tr>
                @endif
                <td class="evidence-item">
                    @if($evidence->file_type == 'image')
                        @php
                            $evidencePath = storage_path('app/public/' . $evidence->file_path);
                            $evidenceData = file_exists($evidencePath) ? base64_encode(file_get_contents($evidencePath)) : '';
                            $evidenceExt = pathinfo($evidence->file_path, PATHINFO_EXTENSION);
                        @endphp
                        @if($evidenceData)
                            <img src="data:image/{{ $evidenceExt }};base64,{{ $evidenceData }}" alt="Evidence">
                        @else
                           <div style="padding: 20px; color: #94a3b8;">Sawir lagama helin server-ka</div>
                        @endif
                    @else
                        <div style="padding: 20px; background: #f1f5f9; border-radius: 4px;">
                            <div style="font-size: 24px; color: #64748b;">📄</div>
                            <div style="font-size: 11px; color: #475569; margin-top: 5px;">Document</div>
                        </div>
                    @endif
                    <div style="font-size: 10px; color: #64748b; margin-top: 5px;">{{ $evidence->notes ?? 'Cadayn #' . ($index + 1) }}</div>
                </td>
            @endforeach
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        <p><strong>Booliska Soomaaliya</strong> | Muqdisho, Soomaaliya</p>
        <p>Warbixintan waxaa la soo saaray: {{ now()->format('d/m/Y H:i') }} | Waxaa soo saaray: {{ auth()->user()->name }}</p>
    </div>
</body>
</html>
