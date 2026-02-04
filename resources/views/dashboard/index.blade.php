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
    <!-- Top Header -->
    <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 700; color: white; margin: 0;">Dashboard</h1>
        </div>
        
        <div style="display: flex; align-items: center; gap: 1.5rem;">
            <!-- Search -->
            <div class="search-box-glass">
                <i class="fa-solid fa-magnifying-glass" style="color: #94a3b8;"></i>
                <input type="text" placeholder="Search case, suspect, officer..." style="margin-left: 0.5rem;">
            </div>
            
            <!-- Profile -->
            <div style="display: flex; align-items: center; gap: 0.8rem;">
                <div style="text-align: right;">
                    <div style="color: white; font-weight: 600; font-size: 0.9rem;">{{ auth()->user()->name }}</div>
                    <div style="color: #94a3b8; font-size: 0.8rem;">{{ auth()->user()->email }}</div>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=3b82f6&color=fff" style="width: 45px; height: 45px; border-radius: 12px; border: 2px solid rgba(255,255,255,0.1);">
            </div>
        </div>
    </div>

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
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <!-- Chart Widget (Replaces "Statistics") -->
            <div class="glass-card-dark">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div style="color: white; font-weight: 600;">Case Trends</div>
                    <select style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 2px 8px; border-radius: 6px; font-size: 0.8rem;">
                        <option>Last 6 Months</option>
                    </select>
                </div>
                <div id="trendChart"></div>
            </div>

            <!-- Spaces (Status Cards) -->
            <div class="spaces-grid">
                <!-- Card 1 -->
                <div class="glass-card-dark" style="padding: 1.2rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <div style="width: 35px; height: 35px; background: rgba(59, 130, 246, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #60a5fa;">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <i class="fa-solid fa-ellipsis" style="color: #64748b;"></i>
                    </div>
                    <div style="color: white; font-size: 1.5rem; font-weight: 700;">{{ $stats['total_suspects'] }}</div>
                    <div style="color: #94a3b8; font-size: 0.8rem;">Suspects</div>
                    <div style="margin-top: 0.5rem; height: 4px; background: rgba(255,255,255,0.1); border-radius: 2px;">
                        <div style="width: 45%; height: 100%; background: #60a5fa; border-radius: 2px;"></div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="glass-card-dark" style="padding: 1.2rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <div style="width: 35px; height: 35px; background: rgba(244, 63, 94, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fb7185;">
                            <i class="fa-solid fa-gavel"></i>
                        </div>
                        <i class="fa-solid fa-ellipsis" style="color: #64748b;"></i>
                    </div>
                    <div style="color: white; font-size: 1.5rem; font-weight: 700;">{{ $stats['court_proceedings'] }}</div>
                    <div style="color: #94a3b8; font-size: 0.8rem;">Hearings</div>
                    <div style="margin-top: 0.5rem; height: 4px; background: rgba(255,255,255,0.1); border-radius: 2px;">
                        <div style="width: 80%; height: 100%; background: #fb7185; border-radius: 2px;"></div>
                    </div>
                </div>
            </div>

            <!-- Storage Access (Team / Officers) -->
            <div class="glass-card-dark">
                <h3 style="color: white; margin: 0 0 1rem 0; font-size: 1rem;">Active Departments</h3>
                
                @foreach($stations_with_counts as $station)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.8rem 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        <div style="width: 35px; height: 35px; background: rgba(16, 185, 129, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #10B981; font-weight: 700; font-size: 0.8rem;">
                            {{ substr($station->station_name, 0, 1) }}
                        </div>
                        <div>
                            <div style="color: white; font-size: 0.9rem;">{{ Str::limit($station->station_name, 15) }}</div>
                            <div style="color: #94a3b8; font-size: 0.75rem;">{{ $station->active_station_officers_count }} Officers</div>
                        </div>
                    </div>
                    <button style="padding: 4px 12px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1); background: transparent; color: #94a3b8; font-size: 0.75rem;">View</button>
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
</script>
@endsection
