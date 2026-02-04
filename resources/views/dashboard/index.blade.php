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
                    <div style="font-size: 0.8rem; font-weight: 600; background: rgba(198, 240, 72, 0.2); color: #65a30d; padding: 2px 8px; border-radius: 6px; height: fit-content;">+{{ $today_cases }} Today</div>
                </div>
                <div>
                    <div style="font-size: 2rem; font-weight: 700;">{{ $case_stats['total'] }}</div>
                    <div style="color: var(--text-muted); font-size: 0.9rem;">Total Cases</div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="edu-card stat-card">
                <div class="stat-icon bg-purple-light">
                    <i class="fa-solid fa-user-secret"></i>
                </div>
                <div>
                    <div style="font-size: 2rem; font-weight: 700;">{{ $stats['total_suspects'] }}</div>
                    <div style="color: var(--text-muted); font-size: 0.9rem;">Suspects Recorded</div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="edu-card stat-card">
                <div class="stat-icon bg-orange-light">
                    <i class="fa-solid fa-gavel"></i>
                </div>
                <div>
                    <div style="font-size: 2rem; font-weight: 700;">{{ $stats['court_proceedings'] }}</div>
                    <div style="color: var(--text-muted); font-size: 0.9rem;">Active Hearings</div>
                </div>
            </div>
        </div>

        <!-- Activity Chart -->
        <div class="edu-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; font-size: 1.1rem;">Crime Analytics</h3>
                <div style="display: flex; gap: 1rem;">
                    <span style="font-size: 0.8rem; color: var(--text-muted);">This Week</span>
                </div>
            </div>
            <div id="trendChart"></div>
        </div>

        <!-- Recent Crimes List -->
        <div class="edu-card">
            <h3 style="margin: 0 0 1.5rem 0; font-size: 1.1rem;">Recent Cases</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($recent_crimes as $crime)
                <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-folder"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $crime->crime_type }}</div>
                            <div style="color: var(--text-muted); font-size: 0.8rem;">Ref: #{{ $crime->id }} • {{ $crime->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <span style="background: #ecfccb; color: #4d7c0f; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">Active</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="right-column">
        <!-- New Course / Promo Card Style -->
        <div class="edu-card" style="background: #1C1E26; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <h3 style="margin: 0 0 0.5rem 0;">Go Premium</h3>
                    <p style="font-size: 0.8rem; color: #94a3b8; margin: 0 0 1rem 0;">Access advanced forensics and AI reporting tools.</p>
                    <button style="background: var(--accent-lime); color: #1C1E26; border: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; cursor: pointer;">Upgrade</button>
                </div>
                <img src="https://cdn-icons-png.flaticon.com/512/2942/2942813.png" style="width: 60px; opacity: 0.8;">
            </div>
        </div>

        <!-- Gender Chart -->
        <div class="edu-card">
            <h3 style="margin: 0 0 1rem 0; font-size: 1rem;">Suspect Demographics</h3>
            <div id="genderChart"></div>
        </div>

        <!-- Stations List -->
        <div class="edu-card">
             <h3 style="margin: 0 0 1rem 0; font-size: 1rem;">Top Stations</h3>
             @foreach($crimes_by_station as $stat)
             <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                 <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--accent-lime);"></div>
                 <div style="flex: 1;">
                     <div style="font-size: 0.85rem; font-weight: 600;">{{ Str::limit($stat->station_name, 15) }}</div>
                     <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $stat->total }} Cases</div>
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
    // Trends Chart (Area)
    var options = {
        series: [{ name: 'Cases', data: @json(array_values($trend_counts)) }],
        chart: { type: 'bar', height: 250, toolbar: { show: false }, fontFamily: 'Outfit, sans-serif' },
        colors: ['#1C1E26'], // Dark bars
        plotOptions: { bar: { borderRadius: 4, columnWidth: '30%' } },
        dataLabels: { enabled: false },
        xaxis: { categories: @json($months), axisBorder: { show: false }, axisTicks: { show: false } },
        grid: { borderColor: 'rgba(0,0,0,0.05)', strokeDashArray: 4 },
    };
    new ApexCharts(document.querySelector("#trendChart"), options).render();

    // Gender Donut Chart
    var genderOptions = {
        series: @json($suspect_gender->pluck('count')),
        chart: { type: 'donut', height: 200, fontFamily: 'Outfit, sans-serif' },
        labels: @json($suspect_gender->pluck('gender')),
        colors: ['#1C1E26', '#C6F048', '#94a3b8'], // Theme colors
        dataLabels: { enabled: false },
        legend: { position: 'bottom' },
        stroke: { show: false }
    };
    new ApexCharts(document.querySelector("#genderChart"), genderOptions).render();

    // Crime Types Pie Chart (Hidden if not needed, or add back if needed)
</script>
@endsection
