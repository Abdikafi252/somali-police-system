@extends('layouts.app')

@section('title', 'Dashboard-ka Kiisaska')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">DASHBOARD-KA KIISASKA</h1>
        <p style="color: var(--text-sub);">Guud ahaan xaaladda iyo horumarka kiisaska boliska.</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('crimes.create') }}" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; background: #e67e22; border: none;">
            <i class="fa-solid fa-file-circle-plus"></i> Diwaangeli Dambi
        </a>
        <a href="{{ route('cases.index') }}" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-list-check"></i> Liiska Kiisaska
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="glass-card" style="padding: 1.5rem; border-left: 5px solid #3498db;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <p style="color: var(--text-sub); font-size: 0.9rem; font-weight: 600;">GUUD AHAAN</p>
                <h2 style="font-size: 2rem; margin: 0.5rem 0;">{{ $stats['total'] }}</h2>
            </div>
            <div style="background: rgba(52, 152, 219, 0.1); padding: 0.8rem; border-radius: 12px; color: #3498db;">
                <i class="fa-solid fa-folder-open fa-2x"></i>
            </div>
        </div>
    </div>

    <div class="glass-card" style="padding: 1.5rem; border-left: 5px solid #f1c40f;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <p style="color: var(--text-sub); font-size: 0.9rem; font-weight: 600;">BAARISTA CID</p>
                <h2 style="font-size: 2rem; margin: 0.5rem 0;">{{ $stats['investigating'] }}</h2>
            </div>
            <div style="background: rgba(241, 196, 15, 0.1); padding: 0.8rem; border-radius: 12px; color: #f1c40f;">
                <i class="fa-solid fa-magnifying-glass fa-2x"></i>
            </div>
        </div>
    </div>

    <div class="glass-card" style="padding: 1.5rem; border-left: 5px solid #e67e22;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <p style="color: var(--text-sub); font-size: 0.9rem; font-weight: 600;">XEER ILAALINTA</p>
                <h2 style="font-size: 2rem; margin: 0.5rem 0;">{{ $stats['prosecution'] }}</h2>
            </div>
            <div style="background: rgba(230, 126, 34, 0.1); padding: 0.8rem; border-radius: 12px; color: #e67e22;">
                <i class="fa-solid fa-scale-balanced fa-2x"></i>
            </div>
        </div>
    </div>

    <div class="glass-card" style="padding: 1.5rem; border-left: 5px solid #9b59b6;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <p style="color: var(--text-sub); font-size: 0.9rem; font-weight: 600;">MAXKAMADDA</p>
                <h2 style="font-size: 2rem; margin: 0.5rem 0;">{{ $stats['court'] }}</h2>
            </div>
            <div style="background: rgba(155, 89, 182, 0.1); padding: 0.8rem; border-radius: 12px; color: #9b59b6;">
                <i class="fa-solid fa-gavel fa-2x"></i>
            </div>
        </div>
    </div>

    <div class="glass-card" style="padding: 1.5rem; border-left: 5px solid #2ecc71;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <p style="color: var(--text-sub); font-size: 0.9rem; font-weight: 600;">XIRAN</p>
                <h2 style="font-size: 2rem; margin: 0.5rem 0;">{{ $stats['closed'] }}</h2>
            </div>
            <div style="background: rgba(46, 204, 113, 0.1); padding: 0.8rem; border-radius: 12px; color: #2ecc71;">
                <i class="fa-solid fa-check-double fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Recent Cases Table -->
    <div class="glass-card" style="padding: 1.5rem;">
        <h3 style="margin-bottom: 1.5rem; color: var(--sidebar-bg); display: flex; align-items: center; gap: 0.6rem;">
            <i class="fa-solid fa-clock-rotate-left"></i> Kiisaskii ugu dambeeyay
        </h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-soft); text-align: left;">
                    <th style="padding: 1rem; color: var(--text-sub);">Case Number</th>
                    <th style="padding: 1rem; color: var(--text-sub);">Dambiga</th>
                    <th style="padding: 1rem; color: var(--text-sub);">Heerka</th>
                    <th style="padding: 1rem; color: var(--text-sub);">Sarkaalka</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentCases as $case)
                <tr style="border-bottom: 1px solid var(--border-soft);">
                    <td style="padding: 1rem; font-weight: 700;">{{ $case->case_number }}</td>
                    <td style="padding: 1rem;">{{ $case->crime->crime_type }}</td>
                    <td style="padding: 1rem;">
                        <span style="
                            padding: 0.3rem 0.6rem; 
                            border-radius: 20px; 
                            font-size: 0.75rem; 
                            font-weight: 700;
                            background: {{ $case->status == 'Xiran' ? '#d4edda' : '#e3f2fd' }};
                            color: {{ $case->status == 'Xiran' ? '#155724' : '#0d47a1' }};
                        ">
                            {{ $case->status }}
                        </span>
                    </td>
                    <td style="padding: 1rem; color: var(--text-sub);">{{ $case->assignedOfficer->name ?? 'Lama magacaabin' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Activity Feed / Quick Info -->
    <div class="glass-card" style="padding: 1.5rem;">
        <h3 style="margin-bottom: 1.5rem; color: var(--sidebar-bg); display: flex; align-items: center; gap: 0.6rem;">
            <i class="fa-solid fa-circle-info"></i> Macluumaad Degdeg ah
        </h3>
        <div style="display: flex; flex-direction: column; gap: 1.2rem;">
            <div style="padding: 1rem; background: rgba(52, 152, 219, 0.05); border-radius: 12px; border: 1px dashed var(--border-soft);">
                <p style="font-weight: 700; margin-bottom: 0.3rem;">Warbixinta Maanta</p>
                <p style="font-size: 0.85rem; color: var(--text-sub);">Waxaa la diwaangeliyay {{ $stats['total'] }} kiis cusub mudo dhow ah.</p>
            </div>
            
            <div style="padding: 1rem; background: rgba(46, 204, 113, 0.05); border-radius: 12px; border: 1px dashed var(--border-soft);">
                <p style="font-weight: 700; margin-bottom: 0.3rem;">Guusha Xalinta</p>
                @php
                    $rate = $stats['total'] > 0 ? round(($stats['closed'] / $stats['total']) * 100) : 0;
                @endphp
                <p style="font-size: 0.85rem; color: var(--text-sub);">Heerka xirista kiisaska waa <strong>{{ $rate }}%</strong>.</p>
            </div>
        </div>
    </div>
</div>
@endsection
