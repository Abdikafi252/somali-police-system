<!DOCTYPE html>
<html lang="so">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warbixinta Qaranka - Ciidanka Booliska Soomaaliyeed</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1C1E26;
            background: #fff;
            margin: 0;
            padding: 20px;
        }
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1C1E26;
            padding-bottom: 20px;
        }
        .report-header h2 {
            margin: 5px 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .report-header h3 {
            margin: 5px 0;
            font-size: 13px;
            color: #dc2626;
        }
        .report-header p {
            margin: 5px 0;
            font-size: 11px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1C1E26;
            border-bottom: 2px solid #1C1E26;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .stats-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .stats-grid td {
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            font-size: 12px;
        }
        .stats-grid .label {
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .stats-grid .value {
            font-size: 20px;
            font-weight: bold;
            color: #1C1E26;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th {
            background: #1C1E26;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 7px 10px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        tr:nth-child(even) td {
            background: #f9f9f9;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .date-range {
            background: #f5f5f5;
            padding: 8px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 11px;
            color: #555;
        }
        .resolution-bar {
            background: #e0e0e0;
            height: 12px;
            border-radius: 6px;
            width: 200px;
            display: inline-block;
        }
        .resolution-fill {
            background: #16a34a;
            height: 12px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h2>JAMHUURIYADDA FEDERAALKA SOOMAALIYA</h2>
        <h2>CIIDANKA BOOLISKA SOOMAALIYEED</h2>
        <h3>WARBIXINTA GUUD (COMPREHENSIVE REPORT)</h3>
        <p>Taariikhda la soo saaray: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="date-range">
        <strong>Muddada Warbixinta:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} &rarr; {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
    </div>

    {{-- Overview Statistics --}}
    <div class="section">
        <div class="section-title">Tirakoobka Guud (Overview Statistics)</div>
        <table class="stats-grid">
            <tr>
                <td>
                    <div class="label">Dambiyada Guud</div>
                    <div class="value">{{ $totalCrimes }}</div>
                    <div>Total Crimes</div>
                </td>
                <td>
                    <div class="label">Kiisaska Guud</div>
                    <div class="value">{{ $totalCases }}</div>
                    <div>Total Cases</div>
                </td>
                <td>
                    <div class="label">Eedeysanayaasha</div>
                    <div class="value">{{ $totalSuspects }}</div>
                    <div>Total Suspects</div>
                </td>
                <td>
                    <div class="label">Kiisas Furan</div>
                    <div class="value">{{ $activeCases }}</div>
                    <div>Active Cases</div>
                </td>
                <td>
                    <div class="label">Heerka Xalka</div>
                    <div class="value">{{ $resolutionRate }}%</div>
                    <div>Resolution Rate</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Crime Types --}}
    @if($crime_types->count() > 0)
    <div class="section">
        <div class="section-title">Noocyada Dambiyada (Crime Types)</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nooca Dambiga (Crime Type)</th>
                    <th>Tirada (Count)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($crime_types as $i => $crime)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $crime->type }}</td>
                    <td>{{ $crime->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Case Status --}}
    @if($caseStatus->count() > 0)
    <div class="section">
        <div class="section-title">Xaaladda Kiisaska (Case Status)</div>
        <table>
            <thead>
                <tr>
                    <th>Xaaladda (Status)</th>
                    <th>Tirada (Count)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($caseStatus as $status)
                <tr>
                    <td>{{ ucfirst($status->status) }}</td>
                    <td>{{ $status->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Officer Workload --}}
    @if($officer_workload->count() > 0)
    <div class="section">
        <div class="section-title">Culayska Saraakiisha (Officer Workload)</div>
        <table>
            <thead>
                <tr>
                    <th>Magaca Sarkaalka (Officer)</th>
                    <th>Tirada Kiisaska (Cases)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($officer_workload as $work)
                <tr>
                    <td>{{ $work->assignedOfficer->name ?? 'N/A' }}</td>
                    <td>{{ $work->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Facility Coverage --}}
    @if($facility_coverage->count() > 0)
    <div class="section">
        <div class="section-title">Dahaadka Xarumaha (Facility Coverage)</div>
        <table>
            <thead>
                <tr>
                    <th>Magaca Xarunta (Facility)</th>
                    <th>Nooca (Type)</th>
                    <th>Saraakiisha Sugan (Officers)</th>
                    <th>Xaaladda (Status)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facility_coverage as $facility)
                <tr>
                    <td>{{ $facility->name }}</td>
                    <td>{{ $facility->type }}</td>
                    <td>{{ $facility->deployments_count }}</td>
                    <td>{{ $facility->deployments_count > 0 ? 'DAHAAD / COVERED' : 'BANAAN / UNGUARDED' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Suspect Demographics --}}
    @if($suspectGender->count() > 0)
    <div class="section">
        <div class="section-title">Jinsiga Eedeysanayaasha (Suspect Demographics)</div>
        <table>
            <thead>
                <tr>
                    <th>Jinsiga (Gender)</th>
                    <th>Tirada (Count)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suspectGender as $sg)
                <tr>
                    <td>{{ ucfirst($sg->gender ?? 'N/A') }}</td>
                    <td>{{ $sg->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        Warbixintan waxaa si otomaatig ah looga soo saaray Nidaamka Isku-dhafka ah ee Booliska Soomaaliyeed (SNP System).
        &bull; Taariikhda: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
