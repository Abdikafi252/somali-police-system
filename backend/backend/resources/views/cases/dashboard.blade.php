@extends('layouts.app')

@section('title', 'Dashboard')

@section('css')
<link rel="stylesheet" href="{{ asset('css/dashboard-glass.css') }}">
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="dashboard-grid">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="dashboard-title">
                <p style="color: var(--text-secondary); margin-bottom: 0.5rem;">Subax Wanaagsan,</p>
                <h1>{{ auth()->user()->name }}</h1>
            </div>
            
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <div class="search-box-glass">
                    <i class="fa-solid fa-magnifying-glass" style="color: var(--text-secondary);"></i>
                    <input type="text" placeholder="Raadi fayl, kiis, ama warbixin...">
                    <span style="font-size: 0.7rem; color: var(--text-secondary); border: 1px solid var(--glass-border); padding: 2px 6px; border-radius: 4px;">ALT+F</span>
                </div>
                
                <a href="{{ route('profile.show') }}" class="glass-card-dark" style="padding: 0.8rem; display: flex; align-items: center; justify-content: center; width: 50px; height: 50px; border-radius: 14px; color: var(--text-primary);">
                    <i class="fa-solid fa-gear"></i>
                </a>
            </div>
        </div>

        <!-- Hero Stats Card (Left Large) -->
        <div class="hero-stat-card glass-card-dark">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; z-index: 2; position: relative;">
                <div>
                    <h3 style="color: var(--text-secondary); font-size: 0.9rem; font-weight: 500;">GUUD AHAAN KIISASKA</h3>
                    <div class="stat-value-large">{{ $stats['total'] }}</div>
                </div>
                
                <div style="background: rgba(30, 41, 59, 0.5); padding: 0.5rem 1rem; border-radius: 10px; font-size: 0.8rem; color: var(--neon-cyan); border: 1px solid var(--glass-border);">
                    Updated: Now
                </div>
            </div>

            <!-- Area Chart Container -->
            <div id="mainAreaChart" style="position: absolute; bottom: 0; left: 0; right: 0; height: 150px; z-index: 1;"></div>
            
            <div style="position: relative; z-index: 2; margin-top: 2rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: var(--text-secondary); font-size: 0.9rem;">Heerka Xalinta (Clearance Rate)</span>
                    <span style="color: white; font-weight: 700;">
                        @php
                            $rate = $stats['total'] > 0 ? round(($stats['closed'] / $stats['total']) * 100) : 0;
                        @endphp
                        {{ $rate }}%
                    </span>
                </div>
                <div class="progress-bar-custom">
                    <div class="progress-fill" style="width: {{ $rate }}%"></div>
                </div>
            </div>
        </div>

        <!-- Quick Access / Spaces (Right Top) -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="glass-card-dark">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="margin: 0; font-size: 1.1rem; color: white;">Qaybaha (Spaces)</h3>
                    <button style="background: var(--neon-blue); border: none; width: 30px; height: 30px; border-radius: 8px; color: white; cursor: pointer;">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>

                <div class="spaces-grid">
                    <a href="{{ route('cases.index', ['status' => 'Baaris']) }}" class="space-card">
                        <div class="icon-box" style="color: var(--neon-purple);">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <div>
                            <div style="font-size: 1.2rem; font-weight: 700; color: white;">{{ $stats['investigating'] }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">CID Investigations</div>
                        </div>
                    </a>

                    <a href="{{ route('cases.index', ['status' => 'Xeer-Ilaalinta']) }}" class="space-card">
                        <div class="icon-box" style="color: var(--neon-cyan);">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <div>
                            <div style="font-size: 1.2rem; font-weight: 700; color: white;">{{ $stats['prosecution'] }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">Xeer Ilaalinta</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity (Bottom Left) -->
        <div class="glass-card-dark" style="grid-column: 1 / 2;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; font-size: 1.2rem; color: white;">Diiwaangelintii Ugu Dambeeyay</h3>
                <i class="fa-solid fa-ellipsis" style="color: var(--text-secondary); cursor: pointer;"></i>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                @foreach($recentCases as $case)
                <div class="case-list-item">
                    <div class="file-icon">
                        <i class="fa-regular fa-folder-open fa-lg"></i>
                    </div>
                    <div class="case-meta">
                        <div class="case-title">{{ $case->case_number }}</div>
                        <div class="case-sub">{{ $case->crime->crime_type }} • {{ $case->created_at->diffForHumans() }}</div>
                    </div>
                    
                    <!-- Progress / Status Pill -->
                    <div style="text-align: right;">
                        <span class="status-badge" style="
                            background: {{ $case->status == 'Xiran' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(59, 130, 246, 0.1)' }};
                            color: {{ $case->status == 'Xiran' ? '#10b981' : '#3b82f6' }};
                        ">
                            {{ $case->status }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Storage Access / Team (Bottom Right) -->
        <div class="glass-card-dark">
            <h3 style="margin: 0 0 1.5rem 0; font-size: 1.2rem; color: white;">Status Overview</h3>
            
            <!-- Radial Bar Chart -->
            <div id="statusChart" style="min-height: 250px; display: flex; align-items: center; justify-content: center;"></div>
            
            <div style="margin-top: 1rem; display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.8rem; background: rgba(255,255,255,0.03); border-radius: 12px;">
                    <div style="display: flex; align-items: center; gap: 0.8rem;">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=random" style="width: 35px; height: 35px; border-radius: 50%;"> 
                        <div style="color: white; font-size: 0.9rem;">Closed Cases</div>
                    </div>
                    <button style="padding: 0.4rem 1rem; border-radius: 20px; border: none; background: rgba(59, 130, 246, 0.2); color: #60a5fa; font-size: 0.8rem; cursor: pointer;">View</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // Main Area Chart (Wave)
    var optionsArea = {
        series: [{
            name: 'Cases',
            data: [10, 25, 15, 30, 20, 45, 35, 55, 40, 60] // Dummy data for waveform visual
        }],
        chart: {
            height: 180,
            type: 'area',
            toolbar: { show: false },
            zoom: { enabled: false },
            sparkline: { enabled: true }
        },
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.5,
                opacityTo: 0.0,
                stops: [0, 90, 100]
            }
        },
        colors: ['#06b6d4'],
        tooltip: {
            theme: 'dark',
            x: { show: false }
        }
    };

    var chartArea = new ApexCharts(document.querySelector("#mainAreaChart"), optionsArea);
    chartArea.render();

    // Radial Bar Chart (Status)
    var optionsRadial = {
        series: [{{ $rate }}],
        chart: {
            height: 280,
            type: 'radialBar',
        },
        plotOptions: {
            radialBar: {
                startAngle: -135,
                endAngle: 135,
                hollow: {
                    margin: 15,
                    size: '60%',
                    background: 'transparent',
                    image: undefined,
                },
                track: {
                    background: 'rgba(255,255,255,0.1)',
                    strokeWidth: '100%',
                    margin: 15, // margin is in pixels
                },
                dataLabels: {
                    show: true,
                    name: {
                        offsetY: -10,
                        show: true,
                        color: '#94a3b8',
                        fontSize: '14px'
                    },
                    value: {
                        offsetY: 5,
                        color: '#fff',
                        fontSize: '24px',
                        show: true,
                        formatter: function (val) {
                            return val + "%";
                        }
                    }
                }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                type: 'horizontal',
                shadeIntensity: 0.5,
                gradientToColors: ['#8b5cf6'],
                inverseColors: true,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            }
        },
        stroke: {
            lineCap: 'round'
        },
        labels: ['Success Rate'],
        colors: ['#3b82f6'],
    };

    var chartRadial = new ApexCharts(document.querySelector("#statusChart"), optionsRadial);
    chartRadial.render();
</script>
@endsection
