@extends('layouts.app')

@section('title', 'Dashboard-ka guud')

@section('content')
<div class="dashboard-container animate-up">
    

    <!-- Stats Grid -->
    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        
        <!-- Total Cases -->
        <div class="glass-card" style="padding: 1.5rem;">
            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <div class="stat-label" style="color: var(--text-sub); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem;">Wadarta Kiisaska</div>
            <div class="stat-value" style="font-size: 1.8rem; font-weight: 800; color: var(--sidebar-bg);">{{ $case_stats['total'] }}</div>
        </div>

        <!-- Active Investigations -->
        <div class="glass-card" style="padding: 1.5rem;">
            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <i class="fa-solid fa-magnifying-glass-chart"></i>
            </div>
            <div class="stat-label" style="color: var(--text-sub); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem;">Baarista Socota</div>
            <div class="stat-value" style="font-size: 1.8rem; font-weight: 800; color: var(--sidebar-bg);">{{ $case_stats['investigating'] }}</div>
        </div>

        <!-- Prosecution & Court -->
        <div class="glass-card" style="padding: 1.5rem;">
            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                <i class="fa-solid fa-gavel"></i>
            </div>
            <div class="stat-label" style="color: var(--text-sub); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem;">Maxkamad/Xeer-ilaalin</div>
            <div class="stat-value" style="font-size: 1.8rem; font-weight: 800; color: var(--sidebar-bg);">{{ $case_stats['prosecution'] + $case_stats['court'] }}</div>
        </div>

        <!-- Resolved -->
        <div class="glass-card" style="padding: 1.5rem;">
            <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fa-solid fa-check-double"></i>
            </div>
            <div class="stat-label" style="color: var(--text-sub); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem;">La Xaliyay</div>
            <div class="stat-value" style="font-size: 1.8rem; font-weight: 800; color: var(--sidebar-bg);">{{ $case_stats['closed'] }}</div>
        </div>
    </div>

    <!-- Charts & Analytics Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2.5rem;">
        
        <!-- Monthly Trends -->
        <div class="glass-card" style="padding: 1.5rem; min-height: 400px; grid-column: span 2;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: var(--sidebar-bg); font-size: 1.2rem;">
                    <i class="fa-solid fa-chart-line" style="color: #6366f1; margin-right: 8px;"></i>
                    Dhaqdhaqaaqa Bilaha (Monthly Trends)
                </h3>
            </div>
            <div style="position: relative; height: 350px; width: 100%;">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>

        <!-- Crime Types Chart -->
        <div class="glass-card" style="padding: 1.5rem; min-height: 400px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: var(--sidebar-bg); font-size: 1.2rem;">
                    <i class="fa-solid fa-chart-pie" style="color: #6366f1; margin-right: 8px;"></i>
                    Noocyada Dambiyada
                </h3>
            </div>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="crimeTypesChart"></canvas>
            </div>
        </div>

        <!-- Station Performance -->
        <div class="glass-card" style="padding: 1.5rem;">
            <h3 style="font-family: 'Outfit'; font-weight: 700; color: var(--sidebar-bg); font-size: 1.2rem; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-building-shield" style="color: #6366f1; margin-right: 8px;"></i>
                 Saldhigyada ugu sareeya
            </h3>
            <div style="position: relative; height: 300px;">
                <canvas id="stationStatsChart"></canvas>
            </div>
        </div>
    </div>
    <!-- Upcoming Hearings & Evidence -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2.5rem;">
        
        <!-- Upcoming Hearings -->
        <div class="glass-card" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: var(--sidebar-bg); font-size: 1.2rem;">
                    <i class="fa-solid fa-gavel" style="color: #6366f1; margin-right: 8px;"></i>
                    Ballamaha Maxkamadda
                </h3>
                <a href="{{ route('court-cases.index') }}" style="text-decoration: none; font-size: 0.75rem; color: #6366f1; font-weight: 700;">Eeg Dhammaan</a>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                @forelse($upcoming_hearings as $hearing)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: rgba(255,255,255,0.6); border-radius: 12px; border: 1px solid rgba(0,0,0,0.05);">
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <div style="text-align: center; min-width: 50px;">
                            <span style="display: block; font-size: 0.7rem; font-weight: 800; color: #ef4444; text-transform: uppercase;">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('M') }}</span>
                            <span style="display: block; font-size: 1.4rem; font-weight: 900; color: var(--sidebar-bg);">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('d') }}</span>
                        </div>
                        <div style="border-left: 2px solid rgba(0,0,0,0.05); padding-left: 1rem;">
                            <h5 style="font-size: 0.9rem; font-weight: 700; color: var(--sidebar-bg); margin-bottom: 0px;">{{ $hearing->case_number ?? 'Kiis #' . $hearing->id }}</h5>
                            <p style="font-size: 0.75rem; color: var(--text-sub); margin: 0;">Garsoore: {{ $hearing->judge->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 2rem; color: var(--text-sub); font-size: 0.85rem;">Ma jiraan ballamo dhow.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Evidence Uploads -->
        <div class="glass-card" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: var(--sidebar-bg); font-size: 1.2rem;">
                    <i class="fa-solid fa-fingerprint" style="color: #6366f1; margin-right: 8px;"></i>
                    Caddaymaha Cusub
                </h3>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(70px, 1fr)); gap: 0.8rem;">
                @forelse($recent_evidence as $evidence)
                <div style="position: relative; aspect-ratio: 1; border-radius: 12px; overflow: hidden; border: 2px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    @if(Str::startsWith($evidence->file_type, 'image'))
                        <img src="{{ asset('storage/' . $evidence->file_path) }}" 
                             onerror="this.src='https://ui-avatars.com/api/?name=Ev&background=f1f5f9&color=6366f1&bold=true'"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f8fafc; color: #6366f1; font-size: 1.2rem;">
                            <i class="fa-solid fa-file"></i>
                        </div>
                    @endif
                </div>
                @empty
                <div style="grid-column: span 3; text-align: center; padding: 2rem; color: var(--text-sub); font-size: 0.85rem;">Ma jiraan caddeymo.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity & Top Officers -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2.5rem;">
        
        <!-- Recent Activities -->
        <div class="glass-card" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: var(--sidebar-bg); font-size: 1.2rem;">
                    <i class="fa-solid fa-list-ul" style="color: #6366f1; margin-right: 8px;"></i>
                    Dhaqdhaqaaqa Nidaamka
                </h3>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($activities as $activity)
                <div style="display: flex; gap: 0.8rem; align-items: center; padding: 0.8rem; background: rgba(255,255,255,0.4); border-radius: 12px; border: 1px solid rgba(0,0,0,0.03);">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #6366f1; color: white; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; flex-shrink: 0;">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--sidebar-bg);">{{ $activity->user->name ?? 'System' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-sub);">{{ Str::limit($activity->description ?? $activity->details, 35) }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Officers -->
        <div class="glass-card" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: var(--sidebar-bg); font-size: 1.2rem;">
                    <i class="fa-solid fa-user-shield" style="color: #6366f1; margin-right: 8px;"></i>
                    Askarta ugu Firfircoon
                </h3>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                @foreach($top_officers as $officer)
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.5rem; border-bottom: 1px solid rgba(0,0,0,0.03);">
                    <img src="{{ $officer->profile_image ? asset('storage/' . $officer->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($officer->name) . '&background=6366f1&color=fff' }}" 
                         style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                    <div style="flex-grow: 1;">
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--sidebar-bg);">{{ $officer->name }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-sub);">Kiisaska: {{ $officer->cases_count }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
        </div>
    </div>

    <!-- Station Performance & Recent Cases -->
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; margin-bottom: 2.5rem;">
        
        <!-- Station Performance -->
        <div class="glass-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem;">
                    <i class="fa-solid fa-trophy" style="color: #f1c40f; margin-right: 8px;"></i>
                    Saldhigyada ugu fiican
                </h3>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($station_performance as $station)
                @php
                    $rankColor = match($loop->iteration) {
                        1 => 'linear-gradient(135deg, #FFD700 0%, #FDB931 100%)', // Gold
                        2 => 'linear-gradient(135deg, #E0E0E0 0%, #BDBDBD 100%)', // Silver
                        3 => 'linear-gradient(135deg, #CD7F32 0%, #A0522D 100%)', // Bronze
                        default => 'rgba(241, 245, 249, 0.5)' // Default
                    };
                    $textColor = $loop->iteration <= 3 ? 'white' : '#64748b';
                    $borderColor = $loop->iteration <= 3 ? 'transparent' : '#e2e8f0';
                @endphp
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: {{ $loop->iteration <= 3 ? 'white' : '#f8fafc' }}; border-radius: 16px; border: 1px solid {{ $borderColor }}; box-shadow: {{ $loop->iteration <= 3 ? '0 10px 20px rgba(0,0,0,0.05)' : 'none' }}; transition: transform 0.2s;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 45px; height: 45px; border-radius: 12px; background: {{ $rankColor }}; display: flex; align-items: center; justify-content: center; font-weight: 800; color: {{ $textColor }}; font-size: 1.2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            @if($loop->iteration <= 3)
                                <i class="fa-solid fa-crown"></i>
                            @else
                                {{ $loop->iteration }}
                            @endif
                        </div>
                        <div>
                            <div style="font-weight: 800; color: #1e293b; font-size: 1rem;">{{ $station['name'] }}</div>
                            <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                                <i class="fa-solid fa-user-tie" style="color: #3498db;"></i> {{ $station['commander'] }}
                            </div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span style="display: block; font-weight: 900; color: #2ecc71; font-size: 1.2rem;">{{ $station['solved'] }}</span>
                        <span style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Xaliyay</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Cases Table (Moved inside this grid) -->
    <div class="glass-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem;">
                    <i class="fa-solid fa-clock-rotate-left" style="color: #3498db; margin-right: 8px;"></i>
                    Kiisaskii u dambeeyay
                </h3>
                <a href="{{ route('cases.index') }}" style="color: #3498db; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                    Eeg dhammaan <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            
            <table class="modern-table">
                <tbody>
                    @foreach($recent_crimes as $crime)
                    <tr>
                        <td style="width: 50px;">
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(52, 152, 219, 0.1); display: flex; align-items: center; justify-content: center; color: #3498db;">
                                <i class="fa-solid fa-file-shield"></i>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #1e293b;">{{ $crime->crime_type }}</div>
                            <div style="font-size: 0.8rem; color: #64748b;">{{ $crime->case_number }}</div>
                        </td>
                        <td>
                            <div style="font-size: 0.9rem; color: #1e293b;"><i class="fa-solid fa-location-dot" style="color: #95a5a6;"></i> {{ $crime->location }}</div>
                        </td>
                        <td style="text-align: right;">
                            <span class="badge-soft {{ $crime->status == 'Diiwaangelin' ? 'badge-soft-danger' : 'badge-soft-success' }}">
                                {{ ucfirst($crime->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- My Tasks Highlight (if available) -->
        @if(!empty($my_stats))
        <div class="glass-card" style="background: linear-gradient(145deg, #ffffff, #f8fafc);">
            <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-list-check" style="color: #27ae60; margin-right: 8px;"></i>
                Shaqadayda Maanta
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div style="background: white; padding: 1.5rem; border-radius: 16px; border: 1px solid #f1f5f9; text-align: center;">
                    <div style="font-size: 3rem; font-weight: 800; color: #3498db; margin-bottom: 0.5rem;">{{ $my_stats['active'] }}</div>
                    <div style="font-size: 0.9rem; color: #64748b; font-weight: 600;">Kiisaska Furan</div>
                </div>
                
                <div style="background: white; padding: 1.5rem; border-radius: 16px; border: 1px solid #f1f5f9; text-align: center;">
                    <div style="font-size: 3rem; font-weight: 800; color: #2ecc71; margin-bottom: 0.5rem;">{{ $my_stats['completed'] }}</div>
                    <div style="font-size: 0.9rem; color: #64748b; font-weight: 600;">La Dhameeyay</div>
                </div>

                <div style="grid-column: span 2; background: #2d4a53; padding: 1.5rem; border-radius: 16px; color: white; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: 800;">{{ $my_stats['assigned'] }}</div>
                        <div style="opacity: 0.8; font-size: 0.9rem;">Wadarta Guud ee lugu soo diray</div>
                    </div>
                    <div>
                        <a href="{{ route('cases.index', ['assigned' => 'me']) }}" style="background: rgba(255,255,255,0.2); color: white; padding: 0.8rem 1.5rem; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: background 0.3s;">
                            Gale Kiisaska
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

    <!-- Wanted Suspects & Facilities -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2.5rem;">
        
        <!-- Wanted Suspects -->
        <div class="glass-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem;">
                    <i class="fa-solid fa-handcuffs" style="color: #e74c3c; margin-right: 8px;"></i>
                    Dambiilayaasha La Raadinayo
                </h3>
                <span class="badge badge-soft-danger px-3">Wanted</span>
            </div>
            
            <div style="display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 1rem;">
                @forelse($wanted_suspects as $suspect)
                <div style="min-width: 150px; background: white; border-radius: 12px; border: 1px solid #f1f5f9; padding: 1rem; text-align: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div style="width: 80px; height: 80px; margin: 0 auto 1rem; border-radius: 50%; overflow: hidden; border: 3px solid #e74c3c; position: relative; background: #f8fafc;">
                        <img src="{{ $suspect->photo ? asset('storage/' . $suspect->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($suspect->name) . '&background=e74c3c&color=fff' }}" 
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($suspect->name) }}&background=e74c3c&color=fff'"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <h5 style="font-size: 0.9rem; font-weight: 700; margin-bottom: 0.2rem; color: #1e293b;">{{ Str::limit($suspect->name, 15) }}</h5>
                    <p style="font-size: 0.75rem; color: #e74c3c; font-weight: 600;">{{ $suspect->crime->crime_type ?? 'N/A' }}</p>
                </div>
                @empty
                <div class="text-center w-100 py-3 text-muted">
                    Ma jiraan dambiilayaal la raadinayo hadda.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Facility Status -->
        <div class="glass-card">
            <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-hospital" style="color: #9b59b6; margin-right: 8px;"></i>
                Xaaladda Xarumaha
            </h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($facility_stats as $stat)
                <div style="display: flex; justify-content: space-between; padding: 1rem; background: #f8fafc; border-radius: 10px;">
                    <span style="font-weight: 600; color: #2d3436;">{{ $stat->type }}</span>
                    <span class="badge bg-primary rounded-pill">{{ $stat->count }}</span>
                </div>
                @endforeach
                 @if($facility_stats->isEmpty())
                    <p class="text-muted small">No facility data available.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Active Deployments -->
    <div class="glass-card mb-4" style="overflow: hidden;">
        <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem; margin-bottom: 1.5rem;">
            <i class="fa-solid fa-user-clock" style="color: #2ecc71; margin-right: 8px;"></i>
            Shaqaalaha Hadda Shaqada Ku Jira (Active Deployments)
        </h3>
        <table class="modern-table">
            <thead>
                <tr>
                    <th style="padding: 1rem; text-align: left; color: #64748b;">Askari</th>
                    <th style="padding: 1rem; text-align: left; color: #64748b;">Goobta</th>
                    <th style="padding: 1rem; text-align: left; color: #64748b;">Shift-ka</th>
                    <th style="padding: 1rem; text-align: right; color: #64748b;">Xaaladda</th>
                </tr>
            </thead>
            <tbody>
                @forelse($active_deployments as $deployment)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                             <img src="https://ui-avatars.com/api/?name={{ urlencode($deployment->user->name) }}&size=30&background=random" style="border-radius: 50%;">
                            <span style="font-weight: 600;">{{ $deployment->user->name }}</span>
                        </div>
                    </td>
                    <td>
                        {{ $deployment->station->station_name ?? ($deployment->facility->name ?? 'N/A') }}
                    </td>
                    <td>
                        <span class="badge bg-info text-dark">{{ $deployment->shift }}</span>
                    </td>
                    <td style="text-align: right;">
                        <span class="badge bg-success">On Duty</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-3 text-muted">Ma jiraan ciidan hadda shaqada ku jira oo la diiwaangeliyay.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Trend Chart (Line)
    const ctxTrend = document.getElementById('monthlyTrendChart').getContext('2d');
    const monthlyTrendChart = new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Kiisaska la diiwaangeliyay',
                data: {!! json_encode($trend_counts) !!},
                borderColor: '#3498db',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#3498db',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        borderDash: [5, 5]
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Crime Types Chart
    const ctxCrime = document.getElementById('crimeTypesChart').getContext('2d');
    const crimeTypesChart = new Chart(ctxCrime, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($crime_types->pluck('crime_type')) !!},
            datasets: [{
                data: {!! json_encode($crime_types->pluck('count')) !!},
                backgroundColor: [
                    '#3498db',
                    '#e74c3c',
                    '#f1c40f',
                    '#2ecc71',
                    '#9b59b6',
                    '#34495e'
                ],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            family: 'Inter',
                            size: 12
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });

    // Station Stats Chart (Horizontal Bar)
    const ctxStation = document.getElementById('stationStatsChart').getContext('2d');
    const stationStatsChart = new Chart(ctxStation, {
        type: 'bar',
        data: {
            labels: {!! json_encode($stations_with_counts->pluck('station_name')) !!},
            datasets: [{
                label: 'Ciidanka Jooga',
                data: {!! json_encode($stations_with_counts->pluck('active_station_officers_count')) !!},
                backgroundColor: '#2d4a53',
                borderRadius: 6,
                barThickness: 20
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: 'Inter'
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
