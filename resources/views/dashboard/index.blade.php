@extends('layouts.app')

@section('title', 'Dashboard-ka guud')

@section('content')
<div class="dashboard-container animate-up">
    
    <!-- Welcome Header -->
    <div class="welcome-section" style="display: flex; justify-content: space-between; align-items: center; position: relative;">
        <div class="welcome-content">
            <h1 style="font-family: 'Outfit', sans-serif; font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; color: #ffffff;">
                Soo dhowow, <span style="color: #6edff6;">{{ Auth::user()->name }}</span> 👋
            </h1>
            <p style="font-size: 1.1rem; opacity: 0.9; margin-bottom: 1.5rem; font-weight: 300;">
                Halkaan waxaad kala socon kartaa dhammaan dhaqdhaqaaqyada nidaamka booliska.
                <br>
                <span style="font-size: 0.9rem; background: rgba(255,255,255,0.1); padding: 4px 10px; border-radius: 20px; font-weight: 600; margin-top: 10px; display: inline-block;">
                    <i class="fa-regular fa-calendar" style="margin-right: 5px;"></i> {{ \Carbon\Carbon::now()->locale('so')->isoFormat('dddd, D MMMM YYYY') }}
                </span>
            </p>
        </div>
        <div style="text-align: right; z-index: 2;">
            <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 1rem 2rem; border-radius: 20px; border: 1px solid rgba(255,255,255,0.2);">
                <span style="display: block; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.8;">Darajadaada</span>
                <span style="display: block; font-size: 1.5rem; font-weight: 800;">{{ Auth::user()->rank ?? 'Sargaal' }}</span>
            </div>
        </div>
        <i class="fa-solid fa-shield-halved welcome-bg-icon"></i>
    </div>

    <!-- Quick Actions Row -->
    <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem; margin-bottom: 1rem; padding-left: 0.5rem;" class="animate-up">
        <i class="fa-solid fa-bolt" style="color: #f1c40f; margin-right: 8px;"></i> Ficilada Degdega ah
    </h3>
    <div class="quick-actions-row animate-up" style="display: flex; gap: 1rem; margin-bottom: 2.5rem; overflow-x: auto; padding-bottom: 1rem; padding-left: 0.5rem; scroll-snap-type: x mandatory;">
        <a href="{{ route('crimes.create') }}" class="glass-card" style="min-width: 180px; padding: 1.5rem; text-decoration: none; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; border-radius: 20px; background: white; border: 1px solid #f1f5f9; transition: all 0.3s; scroll-snap-align: start;">
            <div style="width: 50px; height: 50px; border-radius: 14px; background: linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(231, 76, 60, 0.2) 100%); color: #e74c3c; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; box-shadow: 0 4px 10px rgba(231, 76, 60, 0.15);">
                <i class="fa-solid fa-file-circle-plus"></i>
            </div>
            <span style="color: #1e293b; font-weight: 700; font-size: 0.95rem;">Diiwaangeli Dambi</span>
        </a>

        <a href="{{ route('suspects.create') }}" class="glass-card" style="min-width: 180px; padding: 1.5rem; text-decoration: none; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; border-radius: 20px; background: white; border: 1px solid #f1f5f9; transition: all 0.3s; scroll-snap-align: start;">
            <div style="width: 50px; height: 50px; border-radius: 14px; background: linear-gradient(135deg, rgba(52, 152, 219, 0.1) 0%, rgba(52, 152, 219, 0.2) 100%); color: #3498db; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; box-shadow: 0 4px 10px rgba(52, 152, 219, 0.15);">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <span style="color: #1e293b; font-weight: 700; font-size: 0.95rem;">Diiwaangeli Dambiile</span>
        </a>

        <a href="{{ route('cases.create') }}" class="glass-card" style="min-width: 180px; padding: 1.5rem; text-decoration: none; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; border-radius: 20px; background: white; border: 1px solid #f1f5f9; transition: all 0.3s; scroll-snap-align: start;">
            <div style="width: 50px; height: 50px; border-radius: 14px; background: linear-gradient(135deg, rgba(46, 204, 113, 0.1) 0%, rgba(46, 204, 113, 0.2) 100%); color: #2ecc71; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; box-shadow: 0 4px 10px rgba(46, 204, 113, 0.15);">
                <i class="fa-solid fa-briefcase"></i>
            </div>
            <span style="color: #1e293b; font-weight: 700; font-size: 0.95rem;">Fur Kiis Cusub</span>
        </a>

        <a href="{{ route('reports.index') }}" class="glass-card" style="min-width: 180px; padding: 1.5rem; text-decoration: none; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; border-radius: 20px; background: white; border: 1px solid #f1f5f9; transition: all 0.3s; scroll-snap-align: start;">
            <div style="width: 50px; height: 50px; border-radius: 14px; background: linear-gradient(135deg, rgba(155, 89, 182, 0.1) 0%, rgba(155, 89, 182, 0.2) 100%); color: #9b59b6; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; box-shadow: 0 4px 10px rgba(155, 89, 182, 0.15);">
                <i class="fa-solid fa-file-invoices"></i>
            </div>
            <span style="color: #1e293b; font-weight: 700; font-size: 0.95rem;">Warbixin Guud</span>
        </a>
    </div>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        
        <!-- Total Cases -->
        <div class="glass-card stat-card" style="border-bottom: 4px solid #3498db;">
            <div class="stat-icon" style="background: rgba(52, 152, 219, 0.1); color: #3498db;">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $case_stats['total'] }}</h3>
                <p>Wadarta Kiisaska</p>
            </div>
        </div>

        <!-- Active Investigations -->
        <div class="glass-card stat-card" style="border-bottom: 4px solid #e67e22;">
            <div class="stat-icon" style="background: rgba(230, 126, 34, 0.1); color: #e67e22;">
                <i class="fa-solid fa-magnifying-glass-chart"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $case_stats['investigating'] }}</h3>
                <p>Baarista Socota</p>
            </div>
        </div>

        <!-- Prosecution & Court -->
        <div class="glass-card stat-card" style="border-bottom: 4px solid #9b59b6;">
            <div class="stat-icon" style="background: rgba(155, 89, 182, 0.1); color: #9b59b6;">
                <i class="fa-solid fa-gavel"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $case_stats['prosecution'] + $case_stats['court'] }}</h3>
                <p>Maxkamad/Xeer-ilaalin</p>
            </div>
        </div>

        <!-- Resolved -->
        <div class="glass-card stat-card" style="border-bottom: 4px solid #2ecc71;">
            <div class="stat-icon" style="background: rgba(46, 204, 113, 0.1); color: #2ecc71;">
                <i class="fa-solid fa-check-double"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $case_stats['closed'] }}</h3>
                <p>La Xaliyay</p>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Section -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2.5rem;">
        
        <!-- Monthly Trends (New Line Chart) -->
        <div class="glass-card" style="min-height: 400px; grid-column: span 2;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem;">
                    <i class="fa-solid fa-chart-line" style="color: #3498db; margin-right: 8px;"></i>
                    Dhaqdhaqaaqa Bilaha (Monthly Trends)
                </h3>
            </div>
            <div style="position: relative; height: 350px; width: 100%;">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>

        <!-- Crime Types Chart -->
        <div class="glass-card" style="min-height: 400px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem;">
                    <i class="fa-solid fa-chart-pie" style="color: #3498db; margin-right: 8px;"></i>
                    Noocyada Dambiyada
                </h3>
            </div>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="crimeTypesChart"></canvas>
            </div>
        </div>

        <!-- Station Performance -->
        <div class="glass-card">
            <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem; margin-bottom: 1.5rem;">
                <i class="fa-solid fa-building-shield" style="color: #2d4a53; margin-right: 8px;"></i>
                 Saldhigyada ugu sareeya
            </h3>
            <div style="position: relative; height: 300px;">
                <canvas id="stationStatsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Upcoming Hearings & Evidence -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2.5rem;">
        
        <!-- Upcoming Hearings -->
        <div class="glass-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem;">
                    <i class="fa-solid fa-gavel" style="color: #2d3436; margin-right: 8px;"></i>
                    Ballamaha Maxkamadda (Upcoming Hearings)
                </h3>
                <a href="{{ route('court-cases.index') }}" class="text-xs font-bold text-blue-500 hover:text-blue-700" style="text-decoration: none;">Eeg Dhammaan</a>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                @forelse($upcoming_hearings as $hearing)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #fff; border-radius: 12px; border: 1px solid #f1f5f9; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <div style="text-align: center; min-width: 50px; line-height: 1.1;">
                            <span style="display: block; font-size: 0.8rem; font-weight: 700; color: #e74c3c; text-transform: uppercase;">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('M') }}</span>
                            <span style="display: block; font-size: 1.5rem; font-weight: 800; color: #2d3436;">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('d') }}</span>
                        </div>
                        <div style="border-left: 2px solid #f1f5f9; padding-left: 1rem;">
                            <h5 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 0.1rem;">{{ $hearing->case_number ?? 'Kiis #' . $hearing->id }}</h5>
                            <p style="font-size: 0.8rem; color: #64748b; margin: 0;">
                                Judge: {{ $hearing->judge->name ?? 'N/A' }} 
                            </p>
                            @if($hearing->prosecution && $hearing->prosecution->suspect)
                                <p style="font-size: 0.75rem; color: #e74c3c; font-weight: 600; margin-top: 2px;">
                                    Suspect: {{ $hearing->prosecution->suspect->name }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="badge badge-soft-warning">{{ \Carbon\Carbon::parse($hearing->hearing_date)->format('h:i A') }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">Ma jiraan ballamo maxkamadeed oo soo socda.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Evidence Uploads -->
        <div class="glass-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem;">
                    <i class="fa-solid fa-fingerprint" style="color: #8e44ad; margin-right: 8px;"></i>
                    Caddaymaha Cusub (Recent Evidence)
                </h3>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 1rem;">
                @forelse($recent_evidence as $evidence)
                <div style="position: relative; aspect-ratio: 1; border-radius: 12px; overflow: hidden; border: 2px solid #f1f5f9; cursor: pointer;">
                    @if(Str::startsWith($evidence->file_type, 'image'))
                        <img src="{{ asset('storage/' . $evidence->file_path) }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s hover:scale-110;">
                    @else
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f8fafc; color: #64748b; font-size: 1.5rem;">
                            @if(Str::contains($evidence->file_type, 'video'))
                                <i class="fa-solid fa-file-video"></i>
                            @elseif(Str::contains($evidence->file_type, 'pdf'))
                                <i class="fa-solid fa-file-pdf"></i>
                            @else
                                <i class="fa-solid fa-file-lines"></i>
                            @endif
                        </div>
                    @endif
                    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.6); color: white; padding: 4px; font-size: 0.65rem; text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $evidence->crime->case_number ?? '#' . $evidence->crime_id }}
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-4 text-muted">Ma jiro caddayn dhawaan lasoo galiyay.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity & Top Officers -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2.5rem;">
        
        <!-- Recent Activities (Audit Logs) -->
        <div class="glass-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem;">
                    <i class="fa-solid fa-list-ul" style="color: #e67e22; margin-right: 8px;"></i>
                    Dhaqdhaqaaqa Nidaamka
                </h3>
                <span style="font-size: 0.75rem; color: #2ecc71; background: rgba(46, 204, 113, 0.1); padding: 4px 10px; border-radius: 20px; font-weight: 700;">LIVE</span>
            </div>
            
            <div style="position: relative; padding-left: 10px;">
                <!-- Timeline Line -->
                <div style="position: absolute; left: 27px; top: 10px; bottom: 10px; width: 2px; background: #f1f5f9; z-index: 0;"></div>

                <div style="display: flex; flex-direction: column; gap: 1.2rem; position: relative; z-index: 1;">
                    @foreach($activities as $activity)
                    @php
                        $icon = match($activity->action) {
                            'create' => 'fa-plus',
                            'update' => 'fa-pen',
                            'delete' => 'fa-trash',
                            'view' => 'fa-eye',
                            'login' => 'fa-right-to-bracket',
                            default => 'fa-circle-dot'
                        };
                        $iconColor = match($activity->action) {
                            'create' => '#2ecc71', // Green
                            'update' => '#3498db', // Blue
                            'delete' => '#e74c3c', // Red
                            'view' => '#9b59b6', // Purple
                            'login' => '#f1c40f', // Yellow
                            default => '#95a5a6'
                        };
                        $bgColor = match($activity->action) {
                            'create' => 'rgba(46, 204, 113, 0.1)',
                            'update' => 'rgba(52, 152, 219, 0.1)',
                            'delete' => 'rgba(231, 76, 60, 0.1)',
                            'view' => 'rgba(155, 89, 182, 0.1)',
                            'login' => 'rgba(241, 196, 15, 0.1)',
                            default => '#f8fafc'
                        };
                    @endphp
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: white; border: 2px solid {{ $iconColor }}; display: flex; align-items: center; justify-content: center; color: {{ $iconColor }}; font-size: 0.9rem; flex-shrink: 0; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                            <i class="fa-solid {{ $icon }}"></i>
                        </div>
                        <div style="flex-grow: 1; background: white; padding: 0.8rem; border-radius: 12px; border: 1px solid #f1f5f9; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.3rem;">
                                <span style="font-weight: 700; color: #1e293b; font-size: 0.9rem;">{{ $activity->user->name ?? 'System' }}</span>
                                <span style="font-size: 0.7rem; color: #94a3b8;">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                            <p style="margin: 0; font-size: 0.85rem; color: #64748b; line-height: 1.4;">
                                {{ ucfirst($activity->action) }}: <span style="color: #475569;">{{ Str::limit($activity->description ?? $activity->details, 40) }}</span>
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Top Officers -->
        <div class="glass-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-family: 'Outfit'; font-weight: 700; color: #1e293b; font-size: 1.2rem;">
                    <i class="fa-solid fa-user-shield" style="color: #27ae60; margin-right: 8px;"></i>
                    Askarta ugu Shaqada badan
                </h3>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($top_officers as $officer)
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.8rem; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                         <div style="font-weight: 900; color: #cbd5e1; font-size: 1.2rem;">#{{ $loop->iteration }}</div>
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: white; overflow: hidden; border: 1px solid #e2e8f0;">
                             @if($officer->profile_image)
                                <img src="{{ asset('storage/' . $officer->profile_image) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($officer->name) }}&background=27ae60&color=fff" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                            @endif
                        </div>
                        <div>
                            <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem;">{{ $officer->name }}</div>
                            <div style="font-size: 0.75rem; color: #64748b; font-weight: 600;">{{ $officer->rank }}</div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <span style="display: block; font-weight: 800; color: #2d3436; font-size: 1.1rem;">{{ $officer->cases_count }}</span>
                        <span style="font-size: 0.7rem; color: #64748b;">Kiis</span>
                    </div>
                </div>
                @endforeach
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
                    <div style="width: 80px; height: 80px; margin: 0 auto 1rem; border-radius: 50%; overflow: hidden; border: 3px solid #e74c3c;">
                        @if($suspect->photo)
                            <img src="{{ asset('storage/' . $suspect->photo) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($suspect->name) }}&background=e74c3c&color=fff" style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
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
