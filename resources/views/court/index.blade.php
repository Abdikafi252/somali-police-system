@extends('layouts.app')

@section('title', 'Diiwaanka Maxkamadda')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit'; uppercase">XAFIISKA GARSOORKA (JUDICIAL OFFICE)</h1>
    <p style="color: var(--text-sub);">Maamulka dacwadaha horyaala maxkamadda iyo soo saarista xukunada rasmiga ah.</p>
</div>

@if(count($pending_judgments) > 0)
<!-- Pending Judgment Section -->
<div style="margin-bottom: 3rem;">
    <h3 style="font-family: 'Outfit'; font-weight: 700; color: #d4af37; font-size: 1.1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.8rem;">
        <i class="fa-solid fa-gavel"></i> KIISASKA SUGAYA GO'AANKA (PENDING JUDGMENT)
        <span style="background: #d4af37; color: white; padding: 2px 10px; border-radius: 20px; font-size: 0.75rem;">{{ count($pending_judgments) }}</span>
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 1.5rem;">
        @foreach($pending_judgments as $pros)
        <div class="glass-card" style="padding: 1.8rem; border-top: 4px solid #d4af37; position: relative; overflow: hidden; background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, rgba(255,255,255,1) 100%);">
            <div style="position: absolute; right: -15px; top: -15px; font-size: 5rem; opacity: 0.05; color: #d4af37; transform: rotate(15deg);">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.2rem;">
                <div>
                    <h5 style="margin: 0; font-family: 'Outfit'; font-weight: 800; color: var(--sidebar-bg); font-size: 1.1rem;">{{ $pros->policeCase->case_number }}</h5>
                    <span style="font-size: 0.75rem; color: #d4af37; font-weight: 700;">Gudbis: {{ $pros->created_at->format('d M, Y') }}</span>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.65rem; color: var(--text-sub); font-weight: 600; text-transform: uppercase;">Xeer-Ilaalinta</div>
                    <div style="font-size: 0.8rem; font-weight: 700; color: var(--sidebar-bg);">{{ $pros->prosecutor->name }}</div>
                </div>
            </div>

            <div style="background: rgba(0,0,0,0.02); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem;">
                <p style="font-size: 0.85rem; font-weight: 700; color: var(--sidebar-bg); margin-bottom: 0.4rem;">Tuhunka Dambiga:</p>
                <div style="font-size: 0.9rem; color: #c0392b; font-weight: 800;">{{ $pros->policeCase->crime->crime_type }}</div>
                <hr style="border: none; border-top: 1px solid rgba(0,0,0,0.05); margin: 0.8rem 0;">
                <p style="font-size: 0.85rem; font-weight: 700; color: var(--sidebar-bg); margin-bottom: 0.4rem;">Eedaha (Charges):</p>
                <p style="font-size: 0.85rem; color: var(--text-main); line-height: 1.4;">{{ Str::limit($pros->charges, 120) }}</p>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('court-cases.create', ['prosecution_id' => $pros->id]) }}" class="btn-primary" style="flex: 2; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: #2c3e50; border: none; font-size: 0.85rem; font-weight: 800; padding: 0.9rem;">
                    <i class="fa-solid fa-gavel"></i> SOO SAAR XUKUN
                </a>
                <a href="{{ route('cases.show', $pros->policeCase->id) }}" class="btn-secondary" style="flex: 1; text-decoration: none; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; padding: 0.9rem;">
                    Eeg Kiiska
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
    <h3 style="font-family: 'Outfit'; font-weight: 700; color: var(--sidebar-bg); font-size: 1.1rem; margin: 0;">
        <i class="fa-solid fa-list-check"></i> KAYDKA XUKUNADA (VERDICT HISTORY)
    </h3>
    <div style="flex: 1; height: 1px; background: var(--border-soft);"></div>
</div>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-soft);">
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">CASE NUMBER</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">GARSOORAHA</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">XUKUNKA (SUMMARY)</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">XAALADDA</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courtCases as $cc)
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.3s ease;" onmouseover="this.style.background='rgba(45, 74, 83, 0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.2rem 1rem; font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">
                        {{ $cc->prosecution->policeCase->case_number ?? 'N/A' }}
                    </td>
                    <td style="padding: 1.2rem 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.8rem;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($cc->judge->name) }}&background=2d4a53&color=fff&size=35" style="border-radius: 8px;">
                            <span style="font-weight: 700; color: var(--sidebar-bg);">{{ $cc->judge->name }}</span>
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1rem; color: var(--text-main); font-size: 0.9rem;">
                        {{ \Illuminate\Support\Str::limit($cc->verdict, 60) }}
                    </td>
                    <td style="padding: 1.2rem 1rem;">
                        <span style="
                            padding: 0.4rem 0.8rem; 
                            border-radius: 8px; 
                            font-size: 0.75rem; 
                            font-weight: 800; 
                            background: #e8f5e9; 
                            color: #1b5e20;
                            text-transform: uppercase;
                            display: inline-flex;
                            align-items: center;
                            gap: 0.4rem;
                        ">
                            <i class="fa-solid fa-check-circle"></i> XUKUNSAN
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 3rem; text-align: center; color: var(--text-sub);">
                        <i class="fa-solid fa-gavel fa-3x" style="opacity: 0.2; margin-bottom: 1rem;"></i>
                        <p style="font-weight: 600;">Wali ma jiraan xukuno la diwaangeliyay.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
