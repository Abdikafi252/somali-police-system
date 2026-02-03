@extends('layouts.app')

@section('title', 'Diiwaanka Maxkamadda')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit'; uppercase">DIIWAANKA MAXKAMADDA</h1>
    <p style="color: var(--text-sub);">Diiwaanka rasmiga ah ee xukunada iyo go'aanada Maxkamadda.</p>
</div>

<div class="glass-card" style="padding: 1.5rem;">
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
