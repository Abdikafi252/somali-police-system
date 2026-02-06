@extends('layouts.app')

@section('content')
<div class="dashboard-grid">
    <!-- Main Column (Left) -->
    <div class="main-column">

        <!-- Stats Row 1 - Primary Metrics -->
        <div class="stats-row">
            <div class="edu-card stat-card">
                <div style="display: flex; justify-content: space-between;">
                    <div class="stat-icon bg-lime-light">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    @if($today_cases > 0)
                    <div style="font-size: 0.75rem; font-weight: 700; background: rgba(198, 240, 72, 0.3); color: #4d7c0f; padding: 4px 8px; border-radius: 6px; height: fit-content;">+{{ $today_cases }} Maanta</div>
                    @endif
                </div>
                <div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #111827;">{{ $case_stats['total'] }}</div>
                    <div style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Wadarta Kiisaska</div>
                </div>
            </div>

            <div class="edu-card stat-card">
                <div class="stat-icon bg-purple-light">
                    <i class="fa-solid fa-user-secret"></i>
                </div>
                <div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #111827;">{{ $stats['total_suspects'] }}</div>
                    <div style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Eedeysanayaasha</div>
                </div>
            </div>

            <div class="edu-card stat-card">
                <div class="stat-icon bg-orange-light">
                    <i class="fa-solid fa-gavel"></i>
                </div>
                <div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #111827;">{{ $stats['court_proceedings'] }}</div>
                    <div style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Dhageysiga Maxkamada</div>
                </div>
            </div>
        </div>

        <!-- Stats Row 2 - Secondary Metrics -->
        <div class="stats-row" style="grid-template-columns: repeat(4, 1fr);">
            <div class="edu-card stat-card" style="min-height: 110px;">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; width: 40px; height: 40px; font-size: 1rem;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: #111827;">{{ $stats['pending_investigations'] }}</div>
                    <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">Baaritaan Socda</div>
                </div>
            </div>

            <div class="edu-card stat-card" style="min-height: 110px;">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981; width: 40px; height: 40px; font-size: 1rem;">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: #111827;">{{ $case_stats['closed'] }}</div>
                    <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">Kiisas Xiran</div>
                </div>
            </div>

            <div class="edu-card stat-card" style="min-height: 110px;">
                <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; width: 40px; height: 40px; font-size: 1rem;">
                    <i class="fa-solid fa-user-slash"></i>
                </div>
                <div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: #111827;">{{ $wanted_suspects->count() }}</div>
                    <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">La Raadinayo</div>
                </div>
            </div>

            <div class="edu-card stat-card" style="min-height: 110px;">
                <div class="stat-icon" style="background: rgba(168, 85, 247, 0.1); color: #a855f7; width: 40px; height: 40px; font-size: 1rem;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: #111827;">{{ $stats['total_officers'] }}</div>
                    <div style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">Saraakiisha</div>
                </div>
            </div>
        </div>

        <!-- Activity Chart -->
        <div class="edu-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; font-size: 1.1rem; color: #111827;">📊 Falanqeynta Dambiyada</h3>
                <div style="display: flex; gap: 1rem;">
                    <span style="font-size: 0.8rem; color: var(--text-muted); background: #f3f4f6; padding: 4px 10px; border-radius: 20px;">6 Bilood ee Ugu Dambeeyay</span>
                </div>
            </div>
            <div id="trendChart"></div>
        </div>

        <!-- Case Status Breakdown -->
        <div class="edu-card">
            <h3 style="margin: 0 0 1.5rem 0; font-size: 1.1rem; color: #111827;">📋 Kala Qaybinta Kiisaska</h3>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div style="padding: 15px; background: #f0fdf4; border-radius: 12px; border-left: 4px solid #10b981;">
                    <div style="font-size: 0.85rem; color: #059669; font-weight: 600;">Baarista CID</div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: #111827; margin-top: 5px;">{{ $case_stats['investigating'] }}</div>
                </div>
                <div style="padding: 15px; background: #fef3c7; border-radius: 12px; border-left: 4px solid #f59e0b;">
                    <div style="font-size: 0.85rem; color: #d97706; font-weight: 600;">Xeer-Ilaalinta</div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: #111827; margin-top: 5px;">{{ $case_stats['prosecution'] }}</div>
                </div>
                <div style="padding: 15px; background: #dbeafe; border-radius: 12px; border-left: 4px solid #3b82f6;">
                    <div style="font-size: 0.85rem; color: #2563eb; font-weight: 600;">Maxkamadda</div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: #111827; margin-top: 5px;">{{ $case_stats['court'] }}</div>
                </div>
                <div style="padding: 15px; background: #f3f4f6; border-radius: 12px; border-left: 4px solid #6b7280;">
                    <div style="font-size: 0.85rem; color: #4b5563; font-weight: 600;">Xiran/Dhamaaday</div>
                    <div style="font-size: 1.8rem; font-weight: 800; color: #111827; margin-top: 5px;">{{ $case_stats['closed'] }}</div>
                </div>
            </div>
        </div>

        <!-- Recent Cases -->
        <div class="edu-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; font-size: 1.1rem; color: #111827;">🗂️ Kiisaskii Ugu Dambeeyay</h3>
                <a href="{{ route('cases.index') }}" style="font-size: 0.85rem; color: #3b82f6; text-decoration: none; font-weight: 600;">Eeg Dhamaan →</a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($recent_crimes->take(5) as $crime)
                <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 45px; height: 45px; border-radius: 12px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 0.95rem; color: #1f2937;">{{ $crime->crime_type }}</div>
                            <div style="color: var(--text-muted); font-size: 0.8rem; margin-top: 2px;">{{ $crime->location }} • {{ $crime->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <span style="background: #ecfccb; color: #4d7c0f; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">{{ $crime->status ?? 'Cusub' }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Wanted Suspects -->
        <div class="edu-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; font-size: 1.1rem; color: #dc2626;">⚠️ La Raadinayo (Wanted)</h3>
                <a href="{{ route('crimes.index') }}" style="font-size: 0.85rem; color: #3b82f6; text-decoration: none; font-weight: 600;">Eeg Dhamaan →</a>
            </div>
            <div style="display: flex; gap: 15px; overflow-x: auto; padding-bottom: 10px;">
                @forelse($wanted_suspects->take(8) as $suspect)
                <div style="min-width: 120px; text-align: center;">
                    <img src="{{ $suspect->photo_url ?? 'https://ui-avatars.com/api/?name='.$suspect->first_name.'&background=fee2e2&color=dc2626' }}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #dc2626;">
                    <div style="font-weight: 700; font-size: 0.85rem; margin-top: 5px; color: #1f2937;">{{ $suspect->first_name }}</div>
                    <div style="font-size: 0.75rem; color: #dc2626;">{{ $suspect->crime->crime_type ?? 'Dambi' }}</div>
                </div>
                @empty
                <div style="color: var(--text-muted); font-size: 0.9rem; width: 100%; text-align: center; padding: 20px;">Ma jiraan eedeysanayaal la raadinayo hadda.</div>
                @endforelse
            </div>
        </div>

        <!-- Top Officers Performance -->
        <div class="edu-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; font-size: 1.1rem; color: #111827;">🏆 Saraakiisha Ugu Shaqada Badan</h3>
            </div>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($top_officers as $index => $officer)
                <div style="display: flex; align-items: center; gap: 12px; padding: 10px; background: #f9fafb; border-radius: 10px;">
                    <div style="width: 30px; height: 30px; border-radius: 50%; background: {{ $index === 0 ? '#fbbf24' : ($index === 1 ? '#d1d5db' : '#cd7f32') }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">{{ $index + 1 }}</div>
                    <img src="https://ui-avatars.com/api/?name={{ $officer->name }}&background=C6F048&color=1C1E26" style="width: 40px; height: 40px; border-radius: 50%;">
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 0.9rem; color: #1f2937;">{{ $officer->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $officer->rank ?? 'Sarkaal' }}</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 700; font-size: 1.1rem; color: #111827;">{{ $officer->cases_count }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted);">Kiisas</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Right Column -->
    <div class="right-column">

        <!-- Quick Stats Summary -->
        <div class="edu-card">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; color: #111827;">⚡ Xog Degdeg ah</h3>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="font-size: 0.85rem; color: #6b7280;">Kiisaska Maanta</span>
                    <span style="font-weight: 700; color: #111827;">{{ $today_cases }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="font-size: 0.85rem; color: #6b7280;">Toddobaadkan</span>
                    <span style="font-weight: 700; color: #111827;">{{ $week_cases }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f3f4f6;">
                    <span style="font-size: 0.85rem; color: #6b7280;">Xalinta %</span>
                    <span style="font-weight: 700; color: #10b981;">{{ number_format($stats['solved_percent'], 1) }}%</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 8px 0;">
                    <span style="font-size: 0.85rem; color: #6b7280;">Deployment-yada</span>
                    <span style="font-weight: 700; color: #111827;">{{ $stats['active_deployments'] }}</span>
                </div>
            </div>
        </div>

        <!-- Gender Demographics -->
        <div class="edu-card">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; color: #111827;">👥 Kala Qeybinta Jinsiga</h3>
            <div id="genderChart"></div>
        </div>

        <!-- Top Stations -->
        <div class="edu-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="margin: 0; font-size: 1rem; color: #111827;">🏢 Saldhigyada Ugu Shaqada Badan</h3>
            </div>

            @foreach($crimes_by_station as $stat)
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.2rem;">
                <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--accent-lime);"></div>
                <div style="flex: 1;">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #374151;">{{ Str::limit($stat->station_name ?? 'Saldhig', 18) }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">{{ $stat->total }} Kiis</div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Recent Activities -->
        <div class="edu-card">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; color: #111827;">📝 Dhaqdhaqaaqyada Nidaamka</h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                @forelse($activities->take(6) as $activity)
                <div style="display: flex; gap: 10px; align-items: flex-start;">
                    <div style="min-width: 8px; height: 8px; margin-top: 6px; border-radius: 50%; background: #3b82f6;"></div>
                    <div>
                        <div style="font-size: 0.85rem; color: #374151; line-height: 1.4;">
                            <span style="font-weight: 600;">{{ $activity->user->name ?? 'System' }}:</span> {{ $activity->action ?? 'Action' }}
                        </div>
                        <div style="font-size: 0.7rem; color: #9ca3af;">{{ $activity->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div style="font-size: 0.8rem; color: var(--text-muted);">Wax dhaqdhaqaaq ah ma jiro.</div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Court Hearings -->
        <div class="edu-card">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; color: #111827;">⚖️ Dhageysiga Maxkamada</h3>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @forelse($upcoming_hearings->take(4) as $hearing)
                <div style="padding: 10px; background: #fef3c7; border-radius: 8px; border-left: 3px solid #f59e0b;">
                    <div style="font-weight: 600; font-size: 0.85rem; color: #92400e;">{{ $hearing->case_title ?? 'Kiis #'.$hearing->id }}</div>
                    <div style="font-size: 0.75rem; color: #78350f; margin-top: 3px;">📅 {{ $hearing->hearing_date->format('M d, Y') }}</div>
                </div>
                @empty
                <div style="font-size: 0.8rem; color: var(--text-muted); text-align: center; padding: 10px;">Ma jiraan dhageysi dhow.</div>
                @endforelse
            </div>
        </div>

        <!-- Active Deployments -->
        <div class="edu-card">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; color: #111827;">🚔 Deployment-yada Firfircoon</h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @forelse($active_deployments->take(4) as $deployment)
                <div style="display: flex; align-items: center; gap: 10px; padding: 8px; background: #f0fdf4; border-radius: 8px;">
                    <div style="width: 6px; height: 6px; border-radius: 50%; background: #10b981;"></div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 0.85rem; color: #1f2937;">{{ $deployment->user->name ?? 'Unknown Officer' }}</div>
                        <div style="font-size: 0.75rem; color: #6b7280;">{{ $deployment->station->station_name ?? $deployment->facility->name ?? 'Location' }}</div>
                    </div>
                </div>
                @empty
                <div style="font-size: 0.8rem; color: var(--text-muted); text-align: center; padding: 10px;">Ma jiraan deployment-yo firfircoon.</div>
                @endforelse
            </div>
        </div>

        <!-- Facility Stats -->
        <div class="edu-card">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; color: #111827;">🏛️ Xarumaha</h3>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                @foreach($facility_stats as $facility)
                <div style="display: flex; justify-content: space-between; padding: 8px; background: #f9fafb; border-radius: 6px;">
                    <span style="font-size: 0.85rem; color: #374151; font-weight: 500;">{{ ucfirst($facility->type) }}</span>
                    <span style="font-weight: 700; color: #111827;">{{ $facility->count }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Trends Chart
    var options = {
        series: [{
            name: 'Kiisaska',
            data: @json(array_values($trend_counts))
        }],
        chart: {
            type: 'area',
            height: 250,
            toolbar: {
                show: false
            },
            fontFamily: 'Outfit, sans-serif'
        },
        colors: ['#1C1E26'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            categories: @json($months),
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            labels: {
                style: {
                    colors: '#9ca3af'
                }
            }
        },
        yaxis: {
            show: true,
            labels: {
                style: {
                    colors: '#9ca3af'
                }
            }
        },
        grid: {
            borderColor: 'rgba(0,0,0,0.05)',
            strokeDashArray: 4
        },
        tooltip: {
            theme: 'dark'
        }
    };
    new ApexCharts(document.querySelector("#trendChart"), options).render();

    // Gender Donut Chart
    var genderOptions = {
        series: @json($suspect_gender - > pluck('count')),
        chart: {
            type: 'donut',
            height: 200,
            fontFamily: 'Outfit, sans-serif'
        },
        labels: @json($suspect_gender - > pluck('gender')),
        colors: ['#1C1E26', '#C6F048', '#d1d5db'],
        dataLabels: {
            enabled: false
        },
        legend: {
            position: 'bottom',
            markers: {
                radius: 12
            }
        },
        stroke: {
            show: false
        },
        tooltip: {
            theme: 'dark'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '75%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Wadarta',
                            color: '#374151',
                            fontSize: '12px'
                        }
                    }
                }
            }
        }
    };
    new ApexCharts(document.querySelector("#genderChart"), genderOptions).render();
</script>
@endsection