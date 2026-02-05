@extends('layouts.app')

@section('title', 'Diiwaanka Baaritaanada')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit'; uppercase">DIIWAANKA BAARITAANADA CID</h1>
    <p style="color: var(--text-sub);">Diiwaanka baaritaanada ay gacanta ku hayaan baarayaasha CID-da.</p>
</div>

<div class="glass-card" style="padding: 1.5rem;">
    @if(session('success'))
        <div style="background: rgba(46, 204, 113, 0.1); color: #27ae60; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600; border-left: 4px solid #2ecc71;">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: rgba(231, 76, 60, 0.1); color: #c0392b; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600; border-left: 4px solid #e74c3c;">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border-soft);">
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">CASE NUMBER</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">NOOCA DAMBIGA</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">XAALADDA</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">TAARIIKHDA</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem; text-align: right;">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($investigations as $inv)
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.3s ease;" onmouseover="this.style.background='rgba(39, 174, 96, 0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.2rem 1rem; font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">
                        {{ $inv->policeCase->case_number ?? 'N/A' }}
                    </td>
                    <td style="padding: 1.2rem 1rem; font-weight: 700; color: var(--sidebar-bg);">
                        {{ $inv->policeCase->crime->crime_type ?? 'N/A' }}
                    </td>
                    <td style="padding: 1.2rem 1rem;">
                        @php
                            $statusColors = [
                                'Xeer-Ilaalinta' => ['bg' => '#f3e5f5', 'color' => '#7b1fa2'],
                                'Baaris' => ['bg' => '#e8f5e9', 'color' => '#1b5e20'],
                            ];
                            $colors = $statusColors[$inv->status] ?? ['bg' => '#f1f2f6', 'color' => '#2d3436'];
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
                            {{ $inv->status }}
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1rem; color: var(--text-sub); font-size: 0.9rem;">
                        {{ $inv->created_at->format('d M Y') }}
                    </td>
                    <td style="padding: 1.2rem 1rem; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                            <a href="{{ route('investigations.show', $inv->id) }}" class="btn-primary" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 6px; width: auto; background: var(--sidebar-bg); border: none;" title="View">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if(auth()->user()->role->slug == 'admin' || auth()->user()->role->slug == 'cid')
                            <a href="{{ route('investigations.edit', $inv->id) }}" class="btn-primary" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 6px; width: auto; background: #f1c40f; border: none; color: black;" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('investigations.destroy', $inv->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ma hubtaa inaad tirtirto baaritaankan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-primary" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 6px; width: auto; background: #e74c3c; border: none;" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 3rem; text-align: center; color: var(--text-sub);">
                        <i class="fa-solid fa-magnifying-glass-chart fa-3x" style="opacity: 0.2; margin-bottom: 1rem;"></i>
                        <p style="font-weight: 600;">Wali ma jiraan baaritaano la diwaangeliyay.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
