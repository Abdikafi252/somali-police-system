@extends('layouts.app')

@section('title', 'Faahfaahinta Baaritaanka')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">FAAHFAAHINTA BAARITAANKA</h1>
    <p style="color: var(--text-sub);">Lambarka Kiiska: <strong>{{ $investigation->policeCase->case_number ?? 'N/A' }}</strong></p>
</div>

<div class="glass-card" style="padding: 2rem;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <div>
            <h3 style="color: var(--sidebar-bg); font-weight: 700; margin-bottom: 1rem;">Xogta Kiiska</h3>
            <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                <div>
                    <span style="color: var(--text-sub); font-size: 0.85rem;">Lambarka Kiiska:</span>
                    <div style="font-weight: 600; color: var(--sidebar-bg);">{{ $investigation->policeCase->case_number ?? 'N/A' }}</div>
                </div>
                <div>
                    <span style="color: var(--text-sub); font-size: 0.85rem;">Nooca Dambiga:</span>
                    <div style="font-weight: 600; color: var(--sidebar-bg);">{{ $investigation->policeCase->crime->crime_type ?? 'N/A' }}</div>
                </div>
                <div>
                    <span style="color: var(--text-sub); font-size: 0.85rem;">Xaalada:</span>
                    <div>
                        @php
                            $statusColors = [
                                'pending' => ['bg' => '#fff3e0', 'color' => '#e65100'],
                                'in_progress' => ['bg' => '#e3f2fd', 'color' => '#0d47a1'],
                                'completed' => ['bg' => '#e8f5e9', 'color' => '#1b5e20'],
                            ];
                            $colors = $statusColors[$investigation->status] ?? ['bg' => '#f1f2f6', 'color' => '#2d3436'];
                        @endphp
                        <span style="background: {{ $colors['bg'] }}; color: {{ $colors['color'] }}; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 700;">
                            {{ ucfirst($investigation->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 style="color: var(--sidebar-bg); font-weight: 700; margin-bottom: 1rem;">Natiijada</h3>
            <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; border-left: 4px solid #3498db;">
                <div style="font-weight: 600; color: var(--sidebar-bg);">{{ $investigation->outcome ?? 'Wali lama helin' }}</div>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 2rem;">
        <h3 style="color: var(--sidebar-bg); font-weight: 700; margin-bottom: 1rem;">Natiijada Baaritaanka</h3>
        <div style="background: rgba(255,255,255,0.5); padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border-soft);">
            {!! $investigation->findings !!}
        </div>
    </div>

    @if($investigation->evidence_list)
    <div style="margin-bottom: 2rem;">
        <h3 style="color: var(--sidebar-bg); font-weight: 700; margin-bottom: 1rem;">Liiska Cadaymaha</h3>
        <div style="background: rgba(255,255,255,0.5); padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border-soft);">
            {{ $investigation->evidence_list }}
        </div>
    </div>
    @endif

    @if($investigation->files && count($investigation->files) > 0)
    <div style="margin-bottom: 2rem;">
        <h3 style="color: var(--sidebar-bg); font-weight: 700; margin-bottom: 1rem;">Sawirrada/Dukumeentiyada</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
            @foreach($investigation->files as $file)
            <div style="border: 1px solid var(--border-soft); border-radius: 8px; overflow: hidden;">
                <img src="{{ asset('storage/' . $file) }}" alt="Evidence" style="width: 100%; height: 150px; object-fit: cover;">
                <div style="padding: 0.5rem; background: #f8f9fa; text-align: center;">
                    <a href="{{ asset('storage/' . $file) }}" target="_blank" style="color: #3498db; font-size: 0.8rem; text-decoration: none;">
                        <i class="fa-solid fa-download"></i> Soo Deji
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($investigation->statements && $investigation->statements->count() > 0)
    <div style="margin-bottom: 2rem;">
        <h3 style="color: var(--sidebar-bg); font-weight: 700; margin-bottom: 1rem;">Bayaannada Markhaatiyada</h3>
        @foreach($investigation->statements as $statement)
        <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #10b981;">
            <div style="font-weight: 600; color: var(--sidebar-bg); margin-bottom: 0.5rem;">{{ $statement->witness_name }}</div>
            <div style="color: var(--text-sub); font-size: 0.9rem;">{{ $statement->statement }}</div>
            <div style="font-size: 0.75rem; color: var(--text-sub); margin-top: 0.5rem;">
                {{ $statement->created_at->format('d/m/Y H:i') }}
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div style="margin-top: 2rem; display: flex; gap: 1rem;">
        <a href="{{ route('investigations.index') }}" class="btn" style="background: #f1f2f6; color: var(--text-sub); text-decoration: none; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Dib u noqo
        </a>
        <a href="{{ route('investigations.edit', $investigation->id) }}" class="btn-primary" style="padding: 0.8rem 2rem; text-decoration: none;">
            <i class="fa-solid fa-pen-to-square"></i> Wax ka bedel
        </a>
    </div>
</div>
@endsection
