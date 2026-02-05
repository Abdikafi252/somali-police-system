@extends('layouts.app')

@section('title', 'Faahfaahinta Xarunta')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">FAAHFAAHINTA XARUNTA</h1>
    <p style="color: var(--text-sub);">Xogta dhammeystiran ee xarunta <strong>{{ $facility->name }}</strong></p>
</div>

<div class="glass-card" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <div style="display: flex; gap: 2rem; margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--border-soft);">
        <div style="width: 100px; height: 100px; border-radius: 20px; background: linear-gradient(135deg, #27ae60, #16a085); color: white; display: flex; align-items: center; justify-content: center; font-size: 3rem; box-shadow: 0 10px 20px rgba(39, 174, 96, 0.2);">
            <i class="fa-solid {{ $facility->type == 'Prision' ? 'fa-building-lock' : ($facility->type == 'Airport' ? 'fa-plane-up' : 'fa-building-user') }}"></i>
        </div>
        <div>
            <h2 style="margin: 0 0 0.5rem 0; color: var(--sidebar-bg); font-weight: 800;">{{ $facility->name }}</h2>
            <span style="padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700; background: #e8f5e9; color: #2e7d32; text-transform: uppercase;">
                {{ $facility->type }}
            </span>
            <div style="margin-top: 1rem; display: flex; gap: 1rem; font-weight: 600; color: var(--text-sub);">
                <span><i class="fa-solid fa-location-dot" style="color: #e74c3c;"></i> {{ $facility->location }}</span>
                <span><i class="fa-solid fa-shield-halved" style="color: #f1c40f;"></i> {{ $facility->security_level }} Security</span>
            </div>
        </div>
    </div>

    <div style="background: rgba(255,255,255,0.5); padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem;">
        <h4 style="margin-top: 0; color: var(--sidebar-bg); border-bottom: 2px solid var(--border-soft); padding-bottom: 0.5rem; margin-bottom: 1rem;">Taliyaha Xarunta</h4>
        @if($facility->commander)
            <div style="display: flex; align-items: center; gap: 1rem;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($facility->commander->name) }}&background=0d47a1&color=fff" style="width: 50px; height: 50px; border-radius: 12px;">
                <div>
                    <div style="font-weight: 700; color: var(--sidebar-bg);">{{ $facility->commander->name }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-sub);">{{ $facility->commander->rank }}</div>
                </div>
            </div>
        @else
            <div style="color: var(--text-sub); font-style: italic;">Lama magacaabin taliye.</div>
        @endif
    </div>

    <!-- Official Registry Data -->
    <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; border: 1px solid #e2e8f0;">
        <h4 style="margin-top: 0; color: var(--sidebar-bg); border-bottom: 2px solid #ddd; padding-bottom: 0.5rem; margin-bottom: 1rem; font-size: 0.9rem; text-transform: uppercase;">
             <i class="fa-solid fa-file-contract" style="color: #667eea;"></i> XOGTA DIIWAANGELINTA RASMIGA AH
        </h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.85rem;">
            <div>
                <span style="color: #94a3b8; font-weight: 600;">Digital Record ID:</span>
                <div style="font-weight: 800; color: var(--sidebar-bg); font-family: monospace;">#FAC-REG-{{ str_pad($facility->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div>
                <span style="color: #94a3b8; font-weight: 600;">Taariikhda Diiwaanka:</span>
                <div style="font-weight: 800; color: var(--sidebar-bg);">{{ $facility->created_at->format('M d, Y H:i:s') }}</div>
            </div>
            <div>
                <span style="color: #94a3b8; font-weight: 600;">Nooca Xafiiska:</span>
                <div style="font-weight: 800; color: #2980b9;">{{ strtoupper($facility->type) }}</div>
            </div>
            <div>
                <span style="color: #94a3b8; font-weight: 600;">Status:</span>
                <div style="font-weight: 800; color: #27ae60;">VALIDATED / SHAQAYNAYA</div>
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('facilities.index') }}" class="btn-primary" style="background: #95a5a6; border: none; padding: 0.8rem 1.5rem;">
            <i class="fa-solid fa-arrow-left"></i> Dib u noqo
        </a>
        @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-qaran']))
        <a href="{{ route('facilities.edit', $facility->id) }}" class="btn-primary" style="background: #f1c40f; color: black; border: none; padding: 0.8rem 1.5rem;">
            <i class="fa-solid fa-pen-to-square"></i> Wax ka bedel
        </a>
        <form action="{{ route('facilities.destroy', $facility->id) }}" method="POST" onsubmit="return confirm('Ma hubtaa inaad tirtirto xaruntan?');" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-primary" style="background: #e74c3c; border: none; padding: 0.8rem 1.5rem;">
                <i class="fa-solid fa-trash"></i> Tirtir
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
