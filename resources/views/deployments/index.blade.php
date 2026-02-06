@extends('layouts.app')

@section('title', 'Diiwaanka Shaqada')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">DIIWAANKA SHAQO-QEYBINTA</h1>
        <p style="color: var(--text-sub);">Maareynta iyo kormeerka askarta loo xilsaaray waajibaadka qaran.</p>
    </div>
    <a href="{{ route('deployments.create') }}" class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; width: auto; padding: 0.8rem 1.5rem; border-radius: 12px; background: var(--sidebar-bg); border: none; font-weight: 700;">
        <i class="fa-solid fa-person-military-pointing"></i> Xilsaar Askari Cusub
    </a>
</div>

@if(session('success'))
<div style="background: #e8f5e9; color: #2e7d32; padding: 1.2rem; border-radius: 15px; margin-bottom: 1.5rem; border-left: 5px solid #4caf50; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
    <i class="fa-solid fa-circle-check fa-lg"></i>
    <span style="font-weight: 600;">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div style="background: #fce8e6; color: #c0392b; padding: 1.2rem; border-radius: 15px; margin-bottom: 1.5rem; border-left: 5px solid #e74c3c; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
    <i class="fa-solid fa-circle-exclamation fa-lg"></i>
    <span style="font-weight: 600;">{{ session('error') }}</span>
</div>
@endif

<div class="glass-card" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-soft); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.5);">
        <h5 style="margin: 0; color: var(--sidebar-bg); font-weight: 800; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Dhaqdhaqaaqa Ciidanka</h5>
        <div style="font-size: 0.8rem; color: var(--text-sub);">
            Total: <strong>{{ $deployments->total() }}</strong> Geeyn
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8f9fc;">
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Askariga / Darajada</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Waajibaadka</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Goobta Shaqada</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Shift-iga</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Xaalada</th>
                    <th style="padding: 1.2rem 1.5rem; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deployments as $dep)
                <tr style="border-bottom: 1px solid var(--border-soft); transition: background 0.2s;" onmouseover="this.style.background='#fcfcfd'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            @if($dep->user->profile_image)
                            <img src="{{ asset('storage/' . $dep->user->profile_image) }}" style="width: 40px; height: 40px; border-radius: 10px; object-fit: cover;">
                            @else
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #f0f2f5; color: var(--sidebar-bg); display: flex; align-items: center; justify-content: center; font-weight: 800;">
                                {{ substr($dep->user->name, 0, 1) }}
                            </div>
                            @endif
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-weight: 800; color: var(--sidebar-bg); font-size: 0.9rem;">{{ $dep->user->name }}</span>
                                <span style="font-size: 0.7rem; color: #f39c12; font-weight: 700;">{{ $dep->user->rank ?? 'ASKARI' }}</span>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; color: var(--sidebar-bg); font-size: 0.85rem;">
                            <i class="fa-solid fa-briefcase" style="color: var(--text-sub); font-size: 0.8rem;"></i>
                            {{ $dep->duty_type }}
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; flex-direction: column;">
                            @if($dep->station)
                            <span style="font-weight: 700; color: var(--sidebar-bg); font-size: 0.85rem; display: flex; align-items: center; gap: 0.4rem;">
                                <i class="fa-solid fa-shield-halved" style="color: #3498db; font-size: 0.8rem;"></i> {{ $dep->station->station_name }}
                            </span>
                            @elseif($dep->facility)
                            <span style="font-weight: 700; color: var(--sidebar-bg); font-size: 0.85rem; display: flex; align-items: center; gap: 0.4rem;">
                                <i class="fa-solid fa-building-circle-check" style="color: #27ae60; font-size: 0.8rem;"></i> {{ $dep->facility->name }}
                            </span>
                            @else
                            <span style="color: #ccc; font-size: 0.85rem;">Lama yaqaan</span>
                            @endif
                        </div>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <span style="
                            padding: 0.4rem 0.8rem; 
                            border-radius: 8px; 
                            font-size: 0.7rem; 
                            font-weight: 800;
                            background: {{ $dep->shift == 'Maalin' ? '#fff9db' : '#e7f5ff' }};
                            color: {{ $dep->shift == 'Maalin' ? '#f08c00' : '#1971c2' }};
                        ">
                            <i class="fa-solid {{ $dep->shift == 'Maalin' ? 'fa-sun' : 'fa-moon' }}" style="margin-right: 0.3rem;"></i>
                            {{ strtoupper($dep->shift) }}
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <span style="
                            padding: 0.4rem 0.8rem; 
                            border-radius: 8px; 
                            font-size: 0.7rem; 
                            font-weight: 800;
                            background: {{ $dep->status == 'on_duty' ? '#e8f5e9' : '#f1f2f6' }};
                            color: {{ $dep->status == 'on_duty' ? '#2e7d32' : '#7f8c8d' }};
                        ">
                        </span>
                    </td>
                    <td style="padding: 1.2rem 1.5rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('deployments.show', $dep->id) }}" class="btn-primary" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 8px; width: auto; background: #3498db; border: none;" title="View">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-saldhig']))
                            <a href="{{ route('deployments.edit', $dep->id) }}" class="btn-primary" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 8px; width: auto; background: #f1c40f; border: none; color: black;" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('deployments.destroy', $dep->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ma hubtaa inaad tirtirto shaqo-qeybintan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-primary" style="padding: 0.5rem 0.8rem; font-size: 0.8rem; border-radius: 8px; width: auto; background: #e74c3c; border: none;" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 4rem; text-align: center; color: var(--text-sub);">
                        <div style="opacity: 0.2; margin-bottom: 1rem;">
                            <i class="fa-solid fa-folder-open fa-4x"></i>
                        </div>
                        <p style="font-weight: 700; font-size: 1.1rem;">Ma jiro wax diiwaan shaqo-qeybin ah wali.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 1.5rem; border-top: 1px solid var(--border-soft); background: #fbfbfc;">
        {{ $deployments->links() }}
    </div>
</div>
@endsection