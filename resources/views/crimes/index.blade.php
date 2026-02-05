@extends('layouts.app')

@section('title', 'Diiwaanka Dambiyada')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">DIIWAANKA DAMBIYADA</h1>
        <p style="color: var(--text-sub);">Diiwaangelinta iyo kormeerka falal-dambiyeedyada dalka.</p>
    </div>
    <a href="{{ route('crimes.create') }}" class="btn-primary" style="text-decoration: none; width: auto; padding: 0.8rem 1.5rem; display: flex; align-items: center; gap: 0.5rem; border: none; font-weight: 700;">
        <i class="fa-solid fa-file-circle-plus"></i> Diiwangiil Dambi Cusub
    </a>
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
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">GOOBTA</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">XAALADDA</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem; text-align: right;">TALLAABO</th>
                </tr>
            </thead>
            <tbody>
                @forelse($crimes as $crime)
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.3s ease;" onmouseover="this.style.background='rgba(52, 152, 219, 0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.2rem 1rem; font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">
                        {{ $crime->case_number }}
                    </td>
                    <td style="padding: 1.2rem 1rem;">
                        <span style="font-weight: 700; color: var(--sidebar-bg);">{{ $crime->crime_type }}</span>
                    </td>
                    <td style="padding: 1.2rem 1rem; color: var(--text-sub); font-size: 0.9rem;">
                        <i class="fa-solid fa-location-dot" style="font-size: 0.8rem; margin-right: 0.3rem;"></i> {{ $crime->location }}
                    </td>
                    <td style="padding: 1.2rem 1rem;">
                        @php
                            $statusColors = [
                                'Diiwaangelin' => ['bg' => '#e3f2fd', 'color' => '#0d47a1'],
                                'Baaris' => ['bg' => '#fff3e0', 'color' => '#e65100'],
                                'Xiran' => ['bg' => '#e8f5e9', 'color' => '#1b5e20'],
                            ];
                            $colors = $statusColors[$crime->status] ?? ['bg' => '#f1f2f6', 'color' => '#2d3436'];
                        @endphp
                        <span style="
                            padding: 0.4rem 0.8rem; 
                            border-radius: 8px; 
                            font-size: 0.75rem; 
                            font-weight: 800; 
                            background: {{ $colors['bg'] }}; 
                            color: {{ $colors['color'] }};
                            display: inline-flex;
                            align-items: center;
                            gap: 0.4rem;
                        ">
                            {{ $crime->status }}
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1rem; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                            <!-- View -->
                            <a href="{{ route('crimes.show', $crime->id) }}" class="btn-icon" title="View" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 8px; width: auto; background: #3498db; border: none; color: white;">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            
                            <!-- Edit -->
                            @if(auth()->user()->role->slug == 'admin' || auth()->user()->id == $crime->reported_by)
                            <a href="{{ route('crimes.edit', $crime->id) }}" class="btn-icon" title="Edit" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 8px; width: auto; background: #f1c40f; border: none; color: black;">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            @endif

                            <!-- Print -->
                            <a href="{{ route('crimes.pdf', $crime->id) }}" class="btn-icon" title="Print PDF" target="_blank" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 8px; width: auto; background: #10b981; border: none; color: white;">
                                <i class="fa-solid fa-print"></i>
                            </a>

                            <!-- Delete -->
                            @if(auth()->user()->role->slug == 'admin')
                            <form action="{{ route('crimes.destroy', $crime->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Ma hubtaa inaad tirtirto dambigan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 8px; width: auto; background: #e74c3c; border: none; color: white;" title="Delete">
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
                        <i class="fa-solid fa-folder-open fa-3x" style="opacity: 0.2; margin-bottom: 1rem;"></i>
                        <p style="font-weight: 600;">Wali ma jiraan dambiyo la diwaangeliyay.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 2rem;">
        {{ $crimes->links() }}
    </div>
</div>
@endsection

