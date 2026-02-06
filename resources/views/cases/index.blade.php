@extends('layouts.app')

@section('title', 'Diiwaanka Kiisaska')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">
            {{ request()->has('assigned') ? 'KIISASKA LA II XIL-SAARAY' : 'DIIWAANKA KIISASKA' }}
        </h1>
        <p style="color: var(--text-sub);">{{ request()->has('assigned') ? 'Liiska kiisaska adiga laguu xilsaaray inaad baarto.' : 'Diiwaanka guud ee kiisaska boliska dalka.' }}</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        @if(request()->has('assigned'))
        <a href="{{ route('cases.index') }}" class="btn" style="text-decoration: none; border: 1px solid var(--border-soft); padding: 0.5rem 1rem; border-radius: 6px; color: var(--sidebar-bg); font-weight: 700;">
            <i class="fa-solid fa-list-ul"></i> Muuji dhammaan
        </a>
        @endif
    </div>
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
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">DAMBIGA</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">SARKAALKA BAARISTA</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem;">XAALADDA (STATUS)</th>
                    <th style="padding: 1.2rem 1rem; color: var(--text-sub); font-weight: 600; font-size: 0.85rem; text-align: right;">TALLAABO</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cases as $case)
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.3s ease;" onmouseover="this.style.background='rgba(52, 152, 219, 0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.2rem 1rem; font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit'; letter-spacing: 0.5px;">
                        {{ $case->case_number }}
                        @if($case->assigned_to == auth()->id())
                        <div style="font-size: 0.6rem; background: #3498db; color: white; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-left: 5px; vertical-align: middle; letter-spacing: 0;">MIDKAAGA</div>
                        @endif
                    </td>
                    <td style="padding: 1.2rem 1rem;">
                        <div style="font-weight: 700; color: var(--sidebar-bg);">{{ $case->crime->crime_type }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-sub); display: flex; align-items: center; gap: 0.3rem;">
                            <i class="fa-solid fa-hashtag" style="font-size: 0.6rem;"></i> {{ $case->crime->case_number }}
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1rem;">
                        @if($case->assignedOfficer)
                        <div style="display: flex; align-items: center; gap: 0.6rem;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($case->assignedOfficer->name) }}&size=30&background=F0F4F8&color=3498db&font-size=0.4" style="border-radius: 8px; border: 1px solid var(--border-soft);">
                            <span style="font-size: 0.9rem; font-weight: 600;">{{ $case->assignedOfficer->name }}</span>
                        </div>
                        @else
                        <span style="color: #e74c3c; font-size: 0.8rem; font-weight: 700; background: rgba(231, 76, 60, 0.05); padding: 0.3rem 0.6rem; border-radius: 6px;">
                            <i class="fa-solid fa-user-slash"></i> Lama magacaabin
                        </span>
                        @endif
                    </td>
                    <td style="padding: 1.2rem 1rem;">
                        @php
                        $statusColors = [
                        'assigned' => ['bg' => '#e3f2fd', 'color' => '#0d47a1', 'label' => 'Loo-xilsaaray', 'progress' => 25],
                        'Baaris' => ['bg' => '#fff3e0', 'color' => '#e65100', 'label' => 'Baaritaan', 'progress' => 25],
                        'Baarista-CID' => ['bg' => '#fff3e0', 'color' => '#e65100', 'label' => 'Baaritaan CID', 'progress' => 40],
                        'Xeer-Ilaalinta' => ['bg' => '#f3e5f5', 'color' => '#4a148c', 'label' => 'Xeer-ilaalinta', 'progress' => 60],
                        'Maxkamadda' => ['bg' => '#efebe9', 'color' => '#3e2723', 'label' => 'Maxkamadda', 'progress' => 80],
                        'Xiran' => ['bg' => '#e8f5e9', 'color' => '#1b5e20', 'label' => 'Xiran', 'progress' => 100],
                        'Xukunsan' => ['bg' => '#e8f5e9', 'color' => '#1b5e20', 'label' => 'Xukunsan', 'progress' => 100],
                        'Dhamaaday' => ['bg' => '#e8f5e9', 'color' => '#1b5e20', 'label' => 'Dhamaaday', 'progress' => 100],
                        ];
                        $statusData = $statusColors[$case->status] ?? ['bg' => '#f1f2f6', 'color' => '#2d3436', 'label' => $case->status, 'progress' => 0];
                        @endphp

                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <span style="
                                padding: 0.4rem 0.8rem; 
                                border-radius: 8px; 
                                font-size: 0.75rem; 
                                font-weight: 800; 
                                background: {{ $statusData['bg'] }}; 
                                color: {{ $statusData['color'] }};
                                display: inline-flex;
                                align-items: center;
                                gap: 0.4rem;
                                width: fit-content;
                            ">
                                <i class="fa-solid fa-circle" style="font-size: 0.4rem;"></i>
                                {{ $statusData['label'] }}
                            </span>

                            <!-- Progress Bar -->
                            <div style="width: 100%; background: #e0e0e0; border-radius: 10px; height: 6px; overflow: hidden;">
                                <div style="
                                    width: {{ $statusData['progress'] }}%; 
                                    height: 100%; 
                                    background: linear-gradient(90deg, {{ $statusData['color'] }}, {{ $statusData['color'] }}dd);
                                    transition: width 0.3s ease;
                                    border-radius: 10px;
                                "></div>
                            </div>
                            <small style="font-size: 0.7rem; color: var(--text-sub); font-weight: 600;">
                                {{ $statusData['progress'] }}% Dhammaystiran
                            </small>
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1rem; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                            <a href="{{ route('cases.show', $case) }}" class="btn-primary" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 6px; width: auto; background: var(--sidebar-bg); border: none;" title="View">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if(auth()->user()->role->slug == 'admin' || $case->assigned_to == auth()->id())
                            <a href="{{ route('cases.edit', $case) }}" class="btn-primary" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 6px; width: auto; background: #f1c40f; border: none; color: black;" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            @endif
                            @if(auth()->user()->role->slug == 'admin')
                            <form action="{{ route('cases.destroy', $case) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ma hubtaa inaad tirtirto kiiskan?');">
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
                        <i class="fa-solid fa-box-open fa-3x" style="opacity: 0.2; margin-bottom: 1rem;"></i>
                        <p style="font-weight: 600;">Wali ma jiraan kiisas diwaangashan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem;">
        {{ $cases->links() }}
    </div>
</div>
@endsection