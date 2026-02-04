@extends('layouts.app')

@section('content')
<div class="dashboard-grid">
    <!-- Main Column (Left) -->
    <div class="main-column">
        
        <!-- Stats Row -->
        <div class="stats-row">
            <!-- Card 1 -->
            <div class="edu-card stat-card">
                <div style="display: flex; justify-content: space-between;">
                    <div class="stat-icon bg-lime-light">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    @if($today_cases > 0)
                    <div style="font-size: 0.75rem; font-weight: 700; background: rgba(198, 240, 72, 0.3); color: #4d7c0f; padding: 4px 8px; border-radius: 6px; height: fit-content;">+{{ $today_cases }} Today</div>
                    @endif
                </div>
                <div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #111827;">{{ $case_stats['total'] }}</div>
                    <div style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Total Cases</div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="edu-card stat-card">
                <div class="stat-icon bg-purple-light">
                    <i class="fa-solid fa-user-secret"></i>
                </div>
                <div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #111827;">{{ $stats['total_suspects'] }}</div>
                    <div style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Suspects on File</div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="edu-card stat-card">
                <div class="stat-icon bg-orange-light">
                    <i class="fa-solid fa-gavel"></i>
                </div>
                <div>
                    <div style="font-size: 2.2rem; font-weight: 800; color: #111827;">{{ $stats['court_proceedings'] }}</div>
                    <div style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Active Hearings</div>
                </div>
            </div>
        </div>

        <!-- Activity Chart -->
        <div class="edu-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; font-size: 1.1rem; color: #111827;">Crime Analytics</h3>
                <div style="display: flex; gap: 1rem;">
                    <span style="font-size: 0.8rem; color: var(--text-muted); background: #f3f4f6; padding: 4px 10px; border-radius: 20px;">Monthly View</span>
                </div>
            </div>
            <div id="trendChart"></div>
        </div>

        <!-- Recent Crimes List -->
        <div class="edu-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; font-size: 1.1rem; color: #111827;">Recent Cases</h3>
                <a href="{{ route('cases.index') }}" style="font-size: 0.85rem; color: #3b82f6; text-decoration: none; font-weight: 600;">View All</a>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($recent_crimes as $crime)
                <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 45px; height: 45px; border-radius: 12px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 0.95rem; color: #1f2937;">{{ $crime->crime_type }}</div>
                            <div style="color: var(--text-muted); font-size: 0.8rem; margin-top: 2px;">Ref: #{{ $crime->id }} • {{ $crime->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <span style="background: #ecfccb; color: #4d7c0f; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">Open</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="right-column">


        <!-- Gender Chart -->
        <div class="edu-card">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem; color: #111827;">Demographics</h3>
            <div id="genderChart"></div>
        </div>

        <!-- Stations List -->
        <div class="edu-card">
             <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                 <h3 style="margin: 0; font-size: 1rem; color: #111827;">Top Stations</h3>
                 <i class="fa-solid fa-ellipsis" style="color: var(--text-muted); cursor: pointer;"></i>
             </div>
             
             @foreach($crimes_by_station as $stat)
             <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.2rem;">
                 <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--accent-lime);"></div>
                 <div style="flex: 1;">
                     <div style="font-size: 0.9rem; font-weight: 600; color: #374151;">{{ Str::limit($stat->station_name, 15) }}</div>
                     <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">{{ $stat->total }} Cases Reported</div>
                 </div>
             </div>
             @endforeach
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Trends Chart (Bar/Area)
    var options = {
        series: [{ name: 'Cases', data: @json(array_values($trend_counts)) }],
        chart: { type: 'bar', height: 220, toolbar: { show: false }, fontFamily: 'Outfit, sans-serif' },
        colors: ['#1C1E26'], // Dark bars from sidebar color
        plotOptions: { 
            bar: { 
                borderRadius: 6, 
                columnWidth: '40%',
            } 
        },
        dataLabels: { enabled: false },
        xaxis: { 
            categories: @json($months), 
            axisBorder: { show: false }, 
            axisTicks: { show: false },
            labels: { style: { colors: '#9ca3af' } }
        },
        yaxis: { show: false },
        grid: { borderColor: 'rgba(0,0,0,0.05)', strokeDashArray: 4 },
        tooltip: { theme: 'dark' }
    };
    new ApexCharts(document.querySelector("#trendChart"), options).render();

    // Gender Donut Chart
    var genderOptions = {
        series: @json($suspect_gender->pluck('count')),
        chart: { type: 'donut', height: 200, fontFamily: 'Outfit, sans-serif' },
        labels: @json($suspect_gender->pluck('gender')),
        colors: ['#1C1E26', '#C6F048', '#d1d5db'], // Sidebar Dark, Lime Accent, Gray
        dataLabels: { enabled: false },
        legend: { position: 'bottom', markers: { radius: 12 } },
        stroke: { show: false },
        tooltip: { theme: 'dark' },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
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
