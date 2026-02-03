@extends('layouts.app')

@section('title', 'Diiwaanka Xeer-Ilaalinta')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit'; uppercase">MAAMULKA XEER-ILAALINTA</h1>
    <p style="color: var(--text-sub);">Xafiiska dacwad oogista iyo diyaarinta gal-dacweedka maxkamadda.</p>
</div>

@if(count($pending_cases) > 0)
<!-- Pending Prosecution Section -->
<div style="margin-bottom: 3rem;">
    <h3 style="font-family: 'Outfit'; font-weight: 700; color: #8e44ad; font-size: 1.1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.8rem;">
        <i class="fa-solid fa-hourglass-half"></i> KIISASKA SUGAYA OOGISTA (PENDING)
        <span style="background: #8e44ad; color: white; padding: 2px 10px; border-radius: 20px; font-size: 0.75rem;">{{ count($pending_cases) }}</span>
    </h3>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
        @foreach($pending_cases as $case)
        <div class="glass-card" style="padding: 1.5rem; border-top: 4px solid #8e44ad; position: relative; overflow: hidden;">
            <div style="position: absolute; right: -10px; top: -10px; font-size: 4rem; opacity: 0.03; color: #8e44ad;">
                <i class="fa-solid fa-gavel"></i>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                <div>
                    <h5 style="margin: 0; font-family: 'Outfit'; font-weight: 800; color: var(--sidebar-bg);">{{ $case->case_number }}</h5>
                    <span style="font-size: 0.75rem; color: var(--text-sub);">Baarista CID: {{ $case->investigation->created_at->format('d/m/Y') }}</span>
                </div>
                <span style="font-size: 0.65rem; background: #f3e5f5; color: #8e44ad; padding: 4px 8px; border-radius: 6px; font-weight: 800;">DIYAAR AH</span>
            </div>
            <p style="font-size: 0.9rem; font-weight: 700; color: var(--sidebar-bg); margin-bottom: 0.5rem;">{{ $case->crime->crime_type }}</p>
            <p style="font-size: 0.8rem; color: var(--text-sub); margin-bottom: 1.5rem; line-height: 1.4;">{{ Str::limit(strip_tags($case->investigation->findings), 80) }}</p>
            
            <a href="{{ route('prosecutions.create', ['case_id' => $case->id]) }}" class="btn-primary" style="text-decoration: none; display: block; text-align: center; background: #8e44ad; border: none; font-size: 0.85rem; padding: 0.8rem;">
                <i class="fa-solid fa-file-signature"></i> BILOW OOGIS DACWAD
            </a>
        </div>
        @endforeach
    </div>
</div>

<hr style="border: none; border-top: 1px solid var(--border-soft); margin-bottom: 3rem;">
@endif

<h3 style="font-family: 'Outfit'; font-weight: 700; color: var(--sidebar-bg); font-size: 1.1rem; margin-bottom: 1.5rem;">
    <i class="fa-solid fa-folder-tree"></i> DACWADAHA HORE LOO GUDBIYAY
</h3>

<div class="glass-card" style="padding: 1.5rem;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-soft);">
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">CASE NUMBER</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">XEER-ILAALIYAHA</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">MAXKAMADDA</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">XAALADDA</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem; text-align: right;">TALLAABO</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prosecutions as $pros)
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.3s ease;" onmouseover="this.style.background='rgba(142, 68, 173, 0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.2rem 1rem; font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">
                        {{ $pros->policeCase->case_number ?? 'N/A' }}
                    </td>
                    <td style="padding: 1.2rem 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.8rem;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pros->prosecutor->name) }}&background=8e44ad&color=fff&size=35" style="border-radius: 8px;">
                            <span style="font-weight: 700; color: var(--sidebar-bg);">{{ $pros->prosecutor->name }}</span>
                        </div>
                    <td style="padding: 1.2rem 1rem;">
                        <div style="font-weight: 700; color: var(--sidebar-bg);">{{ $pros->court->name ?? 'N/A' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-sub);">{{ $pros->court->location ?? '' }}</div>
                    </td>
                    <td style="padding: 1.2rem 1rem;">
                        @php
                            $statusColors = [
                                'submitted' => ['bg' => '#f3e5f5', 'color' => '#7b1fa2', 'label' => 'Gudbis'],
                                'completed' => ['bg' => '#e8f5e9', 'color' => '#1b5e20', 'label' => 'Dhamaaday'],
                            ];
                            $colors = $statusColors[$pros->status] ?? ['bg' => '#f1f2f6', 'color' => '#2d3436', 'label' => $pros->status];
                        @endphp
                        <span style="
                            padding: 0.4rem 0.8rem; 
                            border-radius: 8px; 
                            font-size: 0.75rem; 
                            font-weight: 800; 
                            background: {{ $colors['bg'] }}; 
                            color: {{ $colors['color'] }};
                            text-transform: uppercase;
                        ">
                            {{ $colors['label'] }}
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1rem; text-align: right;">
                        @if($pros->status == 'submitted' && auth()->user()->role->slug == 'judge')
                            <a href="{{ route('court-cases.create', ['prosecution_id' => $pros->id]) }}" class="btn-primary" style="padding: 0.5rem 1rem; font-size: 0.8rem; border-radius: 6px; width: auto; background: #8e44ad; border: none; font-weight: 800;">
                                <i class="fa-solid fa-gavel"></i> XUKUN KA SOO SAAR
                            </a>
                        @else
                            <span style="color: var(--text-sub); font-size: 0.8rem; font-style: italic;">Ma jiro tallaabo furan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 3rem; text-align: center; color: var(--text-sub);">
                        <i class="fa-solid fa-scale-balanced fa-3x" style="opacity: 0.2; margin-bottom: 1rem;"></i>
                        <p style="font-weight: 600;">Wali ma jiraan dacwado la diwaangeliyay.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
