@extends('layouts.app')

@section('css')
<link href="{{ asset('css/dashboard-glass.css') }}" rel="stylesheet">
<style>
    /* Specific overrides for the main dashboard */
    .glass-card-dark {
        background: rgba(16, 23, 41, 0.7) !important; /* Slightly darker for contrast */
    }
    .chart-container {
        position: relative; 
        min-height: 250px;
    }
    
    /* Custom Progress Bar for "Used per month" style */
    .storage-progress {
        background: rgba(255,255,255,0.1);
        height: 6px;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 1rem;
    }
    .storage-bar {
        height: 100%;
        background: linear-gradient(90deg, #F43F5E, #3B82F6, #10B981); /* Multicolor gradient */
        width: 65%; /* Dynamic */
    }
    
    /* File list item style */
    .file-item {
        display: flex;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .file-icon-box {
        width: 40px; 
        height: 40px; 
        border-radius: 8px;
        display: flex; 
        align-items: center; 
        justify-content: center;
        margin-right: 1rem;
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
    }

    /* Spaces Card Grid */
    .spaces-grid {
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 1rem;
    }
    
    /* Team Avatars */
    .avatar-group {
        display: flex;
    }
    .avatar-group img {
        width: 30px; 
        height: 30px; 
        border-radius: 50%;
        border: 2px solid #0f172a;
        margin-left: -10px;
    }
    .avatar-group img:first-child {
        margin-left: 0;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container" style="padding: 2rem;">
    <!-- Main Grid -->
    <div class="dashboard-grid" style="display: grid; grid-template-columns: 1.8fr 1.2fr; gap: 1.5rem;">
        
        <!-- Left Column -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Hero Card: Total Cases (Replaces "Used per month") -->
            <div class="glass-card-dark" style="position: relative; overflow: hidden; padding: 2rem; min-height: 250px; display: flex; flex-direction: column; justify-content: space-between;">
                <!-- Decorative background blob -->
                <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(59, 130, 246, 0.2); filter: blur(60px); border-radius: 50%;"></div>
                
                <div>
                    <div style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 0.5rem;">Total Cases Recorded</div>
                    <div style="font-size: 4rem; font-weight: 700; color: white; line-height: 1;">
                        {{ $stats['total_crimes'] + $case_stats['total'] }}
                        <span style="font-size: 1.2rem; color: #94a3b8; font-weight: 400;">Records</span>
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; color: #cbd5e1; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        <span>System Usage</span>
                        <span>{{ $stats['active_cases'] }} Active Cases</span>
                    </div>
                    <div class="storage-progress">
                        <div class="storage-bar" style="width: {{ $stats['active_cases'] > 0 ? '75%' : '10%' }};"></div>
                    </div>
                </div>
            </div>

            <!-- Uploading Files (Active Investigations List) -->
            <div class="glass-card-dark">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="color: white; margin: 0; font-size: 1.1rem;">Latest Activity</h3>
                    <button style="background: rgba(255,255,255,0.05); border: none; color: #94a3b8; width: 30px; height: 30px; border-radius: 50%; cursor: pointer;"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                </div>

                <div style="display: flex; flex-direction: column;">
                    @foreach($recent_crimes as $crime)
                    <div class="file-item">
                        <div class="file-icon-box">
                            <i class="fa-solid fa-file-contract"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="color: white; font-size: 0.9rem; font-weight: 500;">{{ $crime->crime_type }}</div>
                            <div style="color: #94a3b8; font-size: 0.8rem;">REF: #{{ $crime->id }} • {{ $crime->created_at->diffForHumans() }}</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="color: #10B981; font-size: 0.8rem;">Open</div>
                            <div style="width: 80px; height: 4px; background: rgba(255,255,255,0.1); border-radius: 2px; margin-top: 5px;">
                                <div style="width: 60%; height: 100%; background: #10B981; border-radius: 2px;"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right Column -->
        </div>

        <!-- Right Column -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Quick Actions / Reports -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <a href="{{ route('reports.export', ['type' => 'pdf']) }}" class="glass-card-dark" style="text-decoration: none; padding: 1rem; display: flex; align-items: center; gap: 0.8rem; border: 1px solid rgba(16, 185, 129, 0.2);">
                    <div style="width: 35px; height: 35px; background: rgba(16, 185, 129, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #10B981;">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div style="color: white; font-size: 0.9rem; font-weight: 500;">Export Report</div>
                </a>
                <a href="{{ route('reports.index') }}" class="glass-card-dark" style="text-decoration: none; padding: 1rem; display: flex; align-items: center; gap: 0.8rem; border: 1px solid rgba(59, 130, 246, 0.2);">
                    <div style="width: 35px; height: 35px; background: rgba(59, 130, 246, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #3B82F6;">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div style="color: white; font-size: 0.9rem; font-weight: 500;">Analytics</div>
                </a>
            </div>

            <!-- Case Trends Chart -->
            <div class="glass-card-dark">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div style="color: white; font-weight: 600;">Case Trends</div>
                    <div style="display: flex; gap: 10px;">
                        <span style="font-size: 0.75rem; color: #10B981; background: rgba(16, 185, 129, 0.1); padding: 2px 8px; border-radius: 12px;">+{{ $today_cases }} Today</span>
                        <span style="font-size: 0.75rem; color: #3B82F6; background: rgba(59, 130, 246, 0.1); padding: 2px 8px; border-radius: 12px;">+{{ $week_cases }} This Week</span>
                    </div>
                </div>
                <div id="trendChart"></div>
            </div>

            <!-- New Charts Grid: Gender & Crime Types -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <!-- Gender Chart -->
                <div class="glass-card-dark" style="padding: 1rem;">
                    <h4 style="color: white; font-size: 0.9rem; margin-bottom: 1rem;">Suspects by Gender</h4>
                    <div id="genderChart" style="min-height: 150px;"></div>
                </div>
                <!-- Crime Types Chart -->
                <div class="glass-card-dark" style="padding: 1rem;">
                    <h4 style="color: white; font-size: 0.9rem; margin-bottom: 1rem;">Crime Distribution</h4>
                    <div id="crimeTypeChart" style="min-height: 150px;"></div>
                </div>
            </div>

            <!-- Regional Stats (Top Stations) -->
            <div class="glass-card-dark">
                <h3 style="color: white; margin: 0 0 1rem 0; font-size: 1rem;">Highest Crime Areas (By Station)</h3>
                @foreach($crimes_by_station as $stat)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        <div style="width: 35px; height: 35px; background: rgba(239, 68, 68, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #EF4444; font-weight: 700; font-size: 0.8rem;">
                            {{ substr($stat->station_name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <div style="color: white; font-size: 0.9rem;">{{ Str::limit($stat->station_name ?? 'Unknown', 15) }}</div>
                            <div style="color: #94a3b8; font-size: 0.75rem;">{{ $stat->location ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div style="color: #EF4444; font-weight: 700;">{{ $stat->total }} Cases</div>
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
            name: 'Cases',
            data: @json(array_values($trend_counts))
        }],
        chart: {
            type: 'area',
            height: 180,
            toolbar: { show: false },
            background: 'transparent'
        },
        colors: ['#3b82f6'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        xaxis: {
            categories: @json($months),
            labels: { style: { colors: '#94a3b8' } },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            show: false
        },
        grid: {
            show: false,
            padding: { top: 0, right: 0, bottom: 0, left: 10 }
        },
        theme: { mode: 'dark' }
    };

    var chart = new ApexCharts(document.querySelector("#trendChart"), options);
    chart.render();

    // Gender Donut Chart
    var genderOptions = {
        series: @json($suspect_gender->pluck('count')),
        chart: {
            type: 'donut',
            height: 180,
            background: 'transparent'
        },
        labels: @json($suspect_gender->pluck('gender')),
        colors: ['#3b82f6', '#ec4899', '#10b981'],
        dataLabels: { enabled: false },
        legend: { show: false },
        stroke: { show: false },
        theme: { mode: 'dark' }
    };
    new ApexCharts(document.querySelector("#genderChart"), genderOptions).render();

    // Crime Types Pie Chart
    var crimeTypeOptions = {
        series: @json($crime_types->pluck('count')),
        chart: {
            type: 'pie',
            height: 180,
            background: 'transparent'
        },
        labels: @json($crime_types->pluck('crime_type')),
        colors: ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6'],
        dataLabels: { enabled: false },
        legend: { show: false },
        stroke: { show: false },
        theme: { mode: 'dark' }
    };
    new ApexCharts(document.querySelector("#crimeTypeChart"), crimeTypeOptions).render();
</script>
@endsection
