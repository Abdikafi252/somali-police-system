@extends('layouts.app')

@section('title', 'Faahfaahinta Saldhigga')

@section('content')
<div class="header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: start;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">{{ strtoupper($station->station_name) }}</h1>
        <p style="color: var(--text-sub);">Macluumaadka guud iyo liiska shaqaalaha ka tirsan saldhigga.</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-qaran']))
        <a href="{{ route('stations.edit', $station->id) }}" class="btn-primary" style="text-decoration: none; padding: 0.8rem 1.5rem; background: #fff; color: #f39c12; border: 2px solid #f39c12; border-radius: 12px; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-pen-to-square"></i> WAX KA BEDEL
        </a>
        @endif
        <a href="{{ route('stations.index') }}" style="padding: 0.8rem 1.5rem; background: var(--sidebar-bg); color: white; border-radius: 12px; text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-arrow-left"></i> DIB U NOQO
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 350px 1fr; gap: 2rem; align-items: start;">
    <!-- Left Column: Station Info -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Overview Card -->
        <div class="glass-card" style="padding: 2.5rem; text-align: center; border-radius: 25px;">
            <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #2c3e50, #000); color: white; border-radius: 30px; display: flex; align-items: center; justify-content: center; font-size: 3rem; margin: 0 auto 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h2 style="font-weight: 900; color: var(--sidebar-bg); margin-bottom: 0.5rem; font-family: 'Outfit';">{{ $station->station_name }}</h2>
            <p style="color: #e74c3c; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <i class="fa-solid fa-location-dot"></i> {{ $station->location }}
            </p>
            
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-top: 1rem;">
                <div style="background: rgba(52, 152, 219, 0.05); padding: 1.5rem; border-radius: 20px; border: 1px solid rgba(52, 152, 219, 0.1);">
                    <h1 style="margin: 0; font-size: 3rem; color: #2980b9; font-family: 'Outfit'; font-weight: 900;">{{ $station->users->count() }}</h1>
                    <p style="margin: 0; color: #7f8c8d; font-weight: 800; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px;">Askarta Dhismeedka</p>
                </div>
            </div>
        </div>

        <!-- Commander Card -->
        <div class="glass-card" style="padding: 1.5rem; border-radius: 25px;">
            <h5 style="font-weight: 800; color: var(--sidebar-bg); margin-bottom: 1.2rem; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border-soft); padding-bottom: 0.8rem;">
                <i class="fa-solid fa-user-shield" style="margin-right: 0.5rem; color: #f39c12;"></i> Taliyaha Saldhigga
            </h5>
            
            @if($station->commander)
            <div style="display: flex; align-items: center; gap: 1.2rem;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($station->commander->name) }}&size=100&background=random" style="width: 60px; height: 60px; border-radius: 15px; border: 3px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div>
                    <h4 style="margin: 0; font-weight: 800; color: var(--sidebar-bg); font-size: 1rem;">{{ $station->commander->name }}</h4>
                    <p style="margin: 0.2rem 0; color: #f39c12; font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">{{ $station->commander->rank ?? 'TALIYE' }}</p>
                </div>
            </div>
            @else
            <div style="padding: 1rem; background: #fff5f5; border-radius: 15px; border: 1px dashed #feb2b2; text-align: center;">
                <i class="fa-solid fa-user-slash fa-2x" style="color: #f56565; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                <p style="color: #c53030; font-weight: 700; font-size: 0.85rem; margin: 0;">Lama magacaabin taliye</p>
                @if(in_array(auth()->user()->role->slug, ['admin', 'taliye-qaran']))
                <a href="{{ route('stations.edit', $station->id) }}" style="color: #c53030; font-size: 0.75rem; font-weight: 800; text-decoration: underline; margin-top: 0.5rem; display: block;">MAGACAAB HADDA</a>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Staff List -->
    <div class="glass-card" style="padding: 0; overflow: hidden; border-radius: 25px;">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-soft); background: rgba(255,255,255,0.5); display: flex; justify-content: space-between; align-items: center;">
            <h5 style="margin: 0; font-weight: 800; color: var(--sidebar-bg); text-transform: uppercase; font-size: 0.85rem;">
                <i class="fa-solid fa-users-viewfinder" style="margin-right: 0.5rem; color: #3498db;"></i> Liiska Askarta & Shaqaalaha
            </h5>
            <span style="font-weight: 800; font-size: 0.75rem; color: #3498db; background: rgba(52, 152, 219, 0.1); padding: 0.4rem 0.8rem; border-radius: 10px;">
                {{ $station->users->count() }} WADARTA
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fc;">
                        <th style="padding: 1.2rem 1.5rem; text-align: left; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Askari / Ciidan</th>
                        <th style="padding: 1.2rem 1.5rem; text-align: left; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Darajo / Door</th>
                        <th style="padding: 1.2rem 1.5rem; text-align: left; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Xaaladda</th>
                        <th style="padding: 1.2rem 1.5rem; text-align: right; color: var(--sidebar-bg); font-weight: 800; font-size: 0.75rem; text-transform: uppercase;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($station->users as $staff)
                    <tr style="border-bottom: 1px solid var(--border-soft); transition: 0.2s;" onmouseover="this.style.background='#fcfcfd'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1.2rem 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($staff->name) }}&background=random" style="width: 40px; height: 40px; border-radius: 12px;">
                                <div style="display: flex; flex-direction: column;">
                                    <span style="font-weight: 800; color: var(--sidebar-bg); font-size: 0.95rem;">{{ $staff->name }}</span>
                                    <span style="font-size: 0.7rem; color: var(--text-sub);">{{ $staff->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 1.2rem 1.5rem;">
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-weight: 800; color: #2d3436; font-size: 0.85rem;">{{ $staff->rank ?? 'ASKARI' }}</span>
                                <span style="font-size: 0.65rem; color: #3498db; font-weight: 700; text-transform: uppercase;">{{ $staff->role->name ?? 'Door' }}</span>
                            </div>
                        </td>
                        <td style="padding: 1.2rem 1.5rem;">
                            <span style="
                                padding: 0.4rem 0.8rem; 
                                border-radius: 8px; 
                                font-size: 0.7rem; 
                                font-weight: 800;
                                background: {{ $staff->status == 'active' ? '#e8f5e9' : '#ffebee' }};
                                color: {{ $staff->status == 'active' ? '#2e7d32' : '#c62828' }};
                                display: inline-flex;
                                align-items: center;
                                gap: 0.3rem;
                                text-transform: uppercase;
                            ">
                                <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i> {{ $staff->status == 'active' ? 'Shaqaynaya' : 'Ma Joogo' }}
                            </span>
                        </td>
                        <td style="padding: 1.2rem 1.5rem; text-align: right;">
                            <a href="{{ route('users.show', $staff->id) }}" style="width: 35px; height: 35px; border-radius: 10px; background: #f0f7ff; color: #007bff; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; border: 1px solid #cce5ff; transition: 0.2s;" title="View Profile">
                                <i class="fa-solid fa-user-tag"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 4rem; text-align: center; color: var(--text-sub);">
                            <i class="fa-solid fa-user-group fa-3x" style="opacity: 0.1; margin-bottom: 1rem;"></i>
                            <p style="font-weight: 800; font-size: 1rem;">Ma jiro askari ku qoran saldhiggan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
