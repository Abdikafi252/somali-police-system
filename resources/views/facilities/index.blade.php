@extends('layouts.app')

@section('title', 'Diiwaanka Xarumaha')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">DIIWAANKA XARUMAHA GAARKA AH (NATIONAL REGISTRY)</h1>
        <p style="color: var(--text-sub);">Diiwaanka rasmiga ah ee Maxkamadaha, Xabsiyada, iyo dhamaan unugyada gaarka ah.</p>
    </div>
    @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-qaran']))
    <a href="{{ route('facilities.create') }}" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; width: auto; padding: 0.8rem 1.5rem; border-radius: 12px; background: var(--sidebar-bg); border: none; font-weight: 700;">
        <i class="fa-solid fa-plus-circle"></i> Kordhi Xarun Cusub
    </a>
    @endif
</div>

<!-- Controls -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div style="position: relative; width: 350px;">
        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
        <input type="text" id="facilitySearch" placeholder="Raadi Maxkamad, Xabsi ama Xarun..." style="
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.8rem;
            border-radius: 15px;
            border: 1px solid var(--border-soft);
            background: white;
            font-size: 0.9rem;
            outline: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        ">
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <div style="background: #fff; padding: 0.5rem 1rem; border-radius: 10px; border: 1px solid var(--border-soft); font-size: 0.75rem; font-weight: 700; color: #27ae60;">
             <i class="fa-solid fa-circle-check"></i> ACTIVE REGISTRY
        </div>
    </div>
</div>

<div class="glass-card" style="padding: 0; overflow: hidden; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.05);">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;" id="facTable">
            <thead>
                <tr style="background: #f8f9fc; border-bottom: 2px solid #edeff5;">
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">XARUNTA / NOOCA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">GOOBTA (LOCATION)</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">TALIYAHA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">AMNIGA</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase; text-align: right;">MAAREYNTA</th>
                </tr>
            </thead>
            <tbody id="facBody">
                @forelse($facilities as $fac)
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.25s ease;" onmouseover="this.style.background='rgba(52, 152, 219, 0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1.2rem;">
                            <div style="width: 48px; height: 48px; border-radius: 14px; background: {{ $fac->type == 'Prision' ? 'linear-gradient(135deg, #c0392b, #e74c3c)' : ($fac->type == 'Court' ? 'linear-gradient(135deg, #8e44ad, #9b59b6)' : 'linear-gradient(135deg, #2980b9, #3498db)') }}; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                                <i class="fa-solid {{ $fac->type == 'Prision' ? 'fa-building-lock' : ($fac->type == 'Court' ? 'fa-scale-balanced' : ($fac->type == 'Airport' ? 'fa-plane-arrival' : 'fa-building-shield')) }}"></i>
                            </div>
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-weight: 800; color: var(--sidebar-bg); font-size: 1.05rem;">{{ $fac->name }}</span>
                                <span style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Record ID: #FAC-{{ str_pad($fac->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--sidebar-bg); font-weight: 700; font-size: 0.9rem;">
                            <i class="fa-solid fa-map-location-dot" style="color: #3498db;"></i>
                            {{ $fac->location }}
                        </div>
                        <span style="font-size: 0.75rem; color: #94a3b8; display: block; margin-top: 4px;">{{ $fac->type }} Unit</span>
                    </td>
                    <td style="padding: 1.5rem;">
                        @if($fac->commander)
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($fac->commander->name) }}&background=f1f2f6&color=2d3436" style="width: 35px; height: 35px; border-radius: 10px; border: 1px solid #ddd;">
                                <div style="display: flex; flex-direction: column;">
                                    <span style="font-weight: 700; color: var(--sidebar-bg); font-size: 0.85rem;">{{ $fac->commander->name }}</span>
                                    <span style="font-size: 0.65rem; color: #f39c12; font-weight: 800; text-transform: uppercase;">{{ $fac->commander->rank ?? 'TALIYE' }}</span>
                                </div>
                            </div>
                        @else
                            <span style="color: #cbd5e0; font-size: 0.8rem; font-style: italic;">Lama magacaabin</span>
                        @endif
                    </td>
                    <td style="padding: 1.5rem;">
                        <span style="
                            padding: 0.4rem 0.85rem; 
                            border-radius: 20px; 
                            font-size: 0.65rem; 
                            font-weight: 900;
                            background: {{ $fac->security_level == 'High' ? '#fee2e2' : ($fac->security_level == 'Medium' ? '#ffedd5' : '#dcfce7') }};
                            color: {{ $fac->security_level == 'High' ? '#dc2626' : ($fac->security_level == 'Medium' ? '#ea580c' : '#16a34a') }};
                            display: inline-flex;
                            align-items: center;
                            gap: 0.3rem;
                            text-transform: uppercase;
                        ">
                            <i class="fa-solid {{ $fac->security_level == 'High' ? 'fa-bolt' : 'fa-check' }}"></i> {{ $fac->security_level }}
                        </span>
                    </td>
                    <td style="padding: 1.5rem; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                            <a href="{{ route('facilities.show', $fac->id) }}" style="width: 36px; height: 36px; border-radius: 10px; background: #f0f7ff; color: #3498db; display: flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #d1e9ff;" title="Eeg Xogta">
                                <i class="fa-solid fa-folder-open"></i>
                            </a>
                            @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-qaran']))
                            <form action="{{ route('facilities.destroy', $fac->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ma hubtaa?');">
                                @csrf @method('DELETE')
                                <button type="submit" style="width: 36px; height: 36px; border-radius: 10px; background: #fff5f5; color: #e74c3c; display: flex; align-items: center; justify-content: center; border: 1px solid #ffe3e3; cursor: pointer;" title="Tirtir">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 6rem; text-align: center;">
                        <i class="fa-solid fa-building-circle-xmark fa-4x" style="opacity: 0.1; margin-bottom: 2rem;"></i>
                        <p style="font-weight: 800; font-size: 1.2rem; color: var(--sidebar-bg); margin: 0;">Ma jiraan xarumo kale oo diiwaangashan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@section('js')
<script>
    document.getElementById('facilitySearch').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#facBody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    });
</script>
@endsection
@endsection
