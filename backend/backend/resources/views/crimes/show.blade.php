@extends('layouts.app')

@section('title', 'Faahfaahinta Dambiga: ' . $crime->case_number)

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">{{ $crime->case_number }}</h1>
        <p style="color: var(--text-sub);">Xogta iyo faahfaahinta dambiga la diiwaangeliyay.</p>
    </div>
    <a href="{{ route('crimes.index') }}" class="btn" style="background: white; border: 1px solid var(--border-soft); color: var(--text-sub); text-decoration: none; padding: 0.8rem 1.5rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem;">
        <i class="fa-solid fa-arrow-left"></i> Ku laabo liiska
    </a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Crime Details -->
        <div class="glass-card" style="padding: 2.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem;">
                <h3 style="color: var(--sidebar-bg); font-family: 'Outfit'; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.8rem;">
                    <i class="fa-solid fa-file-invoice" style="color: var(--accent-color);"></i> FAAHFAAHINTA DAMBIGA
                </h3>
                <span style="background: rgba(52, 152, 219, 0.1); color: var(--accent-color); padding: 0.5rem 1rem; border-radius: 8px; font-weight: 800; font-size: 0.85rem; letter-spacing: 0.5px;">
                    {{ $crime->crime_type }}
                </span>
            </div>
            
            <div style="background: #fbfbfc; padding: 2rem; border-radius: 16px; border: 1px solid #f1f2f6; margin-bottom: 2rem;">
                <p style="line-height: 1.8; color: #2d3436; font-size: 1.1rem; margin: 0;">
                    {{ $crime->description }}
                </p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 45px; height: 45px; border-radius: 12px; background: #e3f2fd; display: flex; align-items: center; justify-content: center; color: #0d47a1;">
                        <i class="fa-solid fa-location-dot fa-lg"></i>
                    </div>
                    <div>
                        <p style="font-size: 0.75rem; margin: 0; font-weight: 700; color: var(--text-sub); text-transform: uppercase; letter-spacing: 1px;">Goobta uu ka dhacay</p>
                        <p style="font-size: 1rem; margin: 0.2rem 0 0; color: var(--sidebar-bg); font-weight: 800;">{{ $crime->location }}</p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 45px; height: 45px; border-radius: 12px; background: #e8f5e9; display: flex; align-items: center; justify-content: center; color: #1b5e20;">
                        <i class="fa-solid fa-calendar-days fa-lg"></i>
                    </div>
                    <div>
                        <p style="font-size: 0.75rem; margin: 0; font-weight: 700; color: var(--text-sub); text-transform: uppercase; letter-spacing: 1px;">Taariikhda & Waqtiga</p>
                        <p style="font-size: 1rem; margin: 0.2rem 0 0; color: var(--sidebar-bg); font-weight: 800;">{{ \Carbon\Carbon::parse($crime->crime_date)->format('d M Y | h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($crime->status == 'Diiwaangelin')
        <!-- Next Action Prompt -->
        <div class="glass-card" style="padding: 2.5rem; border-left: 6px solid #e74c3c; background: linear-gradient(to right, rgba(231, 76, 60, 0.02), transparent);">
            <div style="display: flex; gap: 1.5rem; align-items: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(231, 76, 60, 0.1); display: flex; align-items: center; justify-content: center; color: #e74c3c;">
                    <i class="fa-solid fa-circle-exclamation fa-2xl"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="color: var(--sidebar-bg); margin: 0 0 0.5rem; font-family: 'Outfit'; font-weight: 700;">TALLAABADA XIGTA</h3>
                    <p style="color: #636e72; margin-bottom: 1.5rem; line-height: 1.6;">Kiiskan wali cidna looma xilsaarin. Taliyaha Saldhigga ayaa looga fadhiyaa inuu furo kiiska oo u magacaabo sarkaal CID ah oo baara.</p>
                    
                    @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-saldhig']))
                        <a href="{{ route('cases.create', ['crime_id' => $crime->id]) }}" class="btn-primary" style="text-decoration: none; width: auto; padding: 1rem 2rem; display: inline-flex; align-items: center; gap: 0.8rem; border-radius: 10px; font-weight: 800; border: none; box-shadow: 0 10px 20px rgba(45, 74, 83, 0.15);">
                            <i class="fa-solid fa-briefcase"></i> FUR KIISKA & XILSAAR CID
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar Cards -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Status Card -->
        <div class="glass-card" style="text-align: center; padding: 2rem;">
            <p style="color: var(--text-sub); font-size: 0.75rem; text-transform: uppercase; margin-bottom: 1rem; font-weight: 700; letter-spacing: 1px;">Status-ka Hadda</p>
            @php
                $statusColors = [
                    'Diiwaangelin' => ['bg' => '#e3f2fd', 'color' => '#0d47a1'],
                    'Baaris' => ['bg' => '#fff3e0', 'color' => '#e65100'],
                    'Xiran' => ['bg' => '#e8f5e9', 'color' => '#1b5e20'],
                ];
                $colors = $statusColors[$crime->status] ?? ['bg' => '#f1f2f6', 'color' => '#2d3436'];
            @endphp
            <div style="padding: 1.2rem; border-radius: 14px; font-weight: 900; font-size: 1.4rem; background: {{ $colors['bg'] }}; color: {{ $colors['color'] }}; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                {{ $crime->status }}
            </div>
        </div>

        <!-- Reporter Card -->
        <div class="glass-card" style="padding: 1.5rem;">
            <p style="color: var(--text-sub); font-size: 0.75rem; text-transform: uppercase; margin-bottom: 1.5rem; font-weight: 700; letter-spacing: 1px;">Diiwaangeliyay</p>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <img src="{{ $crime->reporter->profile_image ? asset('storage/' . $crime->reporter->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($crime->reporter->name) . '&background=1abc9c&color=fff' }}" 
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($crime->reporter->name) }}&background=1abc9c&color=fff'"
                     style="width: 55px; height: 55px; border-radius: 12px; object-fit: cover; border: 2px solid var(--border-soft);">
                <div>
                    <div style="font-weight: 800; color: var(--sidebar-bg); font-size: 1.05rem;">{{ $crime->reporter->name }}</div>
                    <div style="font-size: 0.75rem; color: var(--accent-teal); font-weight: 700; background: rgba(26, 188, 156, 0.05); padding: 0.2rem 0.5rem; border-radius: 4px; display: inline-block; margin-top: 0.3rem;">{{ strtoupper($crime->reporter->role->name) }}</div>
                </div>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="glass-card" style="padding: 1.5rem; background: var(--sidebar-bg); color: white; border: none;">
            <h4 style="margin-bottom: 1rem; font-family: 'Outfit';">Ogeysiis</h4>
            <ul style="padding: 0; list-style: none; font-size: 0.85rem; display: flex; flex-direction: column; gap: 0.8rem; opacity: 0.9;">
                <li style="display: flex; gap: 0.6rem;"><i class="fa-solid fa-circle-check" style="margin-top: 3px;"></i> Dambigan waxaa la diiwaangeliyay {{ $crime->created_at->diffForHumans() }}.</li>
                <li style="display: flex; gap: 0.6rem;"><i class="fa-solid fa-circle-check" style="margin-top: 3px;"></i> Xogta lama bedeli karo markii kiiska la furo.</li>
            </ul>
        </div>
    </div>
</div>
@endsection

