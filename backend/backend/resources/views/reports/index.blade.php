@extends('layouts.app')

@section('title', 'Warbixinada Qaranka')

@section('css')
<style>
    .reports-container {
        padding: 2rem;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .report-title h1 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--sidebar-bg);
        font-family: 'Outfit', sans-serif;
        margin-bottom: 0.5rem;
    }

    .report-title p {
        color: var(--text-sub);
        font-size: 1rem;
    }

    .report-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .date-filter {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        background: white;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .date-filter input {
        border: 1px solid #e0e0e0;
        padding: 0.5rem;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .date-filter button {
        background: var(--sidebar-bg);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .date-filter button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 188, 156, 0.3);
    }

    .export-dropdown {
        position: relative;
    }

    .export-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .export-menu {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 0.5rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        padding: 0.5rem;
        min-width: 150px;
        display: none;
        z-index: 100;
    }

    .export-menu.active {
        display: block;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .export-menu a {
        display: block;
        padding: 0.7rem 1rem;
        color: #2d3436;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s ease;
        font-weight: 600;
    }

    .export-menu a:hover {
        background: #f8f9fa;
        color: var(--sidebar-bg);
        transform: translateX(5px);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--sidebar-bg));
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 1rem;
    }

    .stat-card.crimes .stat-icon {
        background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
        color: white;
    }

    .stat-card.cases .stat-icon {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: white;
    }

    .stat-card.suspects .stat-icon {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: white;
    }

    .stat-card.active .stat-icon {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
        color: white;
    }

    .stat-label {
        color: var(--text-sub);
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--sidebar-bg);
        font-family: 'Outfit', sans-serif;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }

    .chart-card h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--sidebar-bg);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .chart-card h3 i {
        color: var(--primary-color);
    }

    .table-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.7) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .table-card h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--sidebar-bg);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead tr {
        border-bottom: 2px solid #e0e0e0;
    }

    .data-table th {
        padding: 1rem;
        text-align: left;
        color: var(--text-sub);
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        font-weight: 600;
    }

    .data-table tbody tr {
        transition: all 0.2s ease;
    }

    .data-table tbody tr:hover {
        background: rgba(26, 188, 156, 0.05);
    }

    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
    }

    .status-badge.covered {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.unguarded {
        background: #f8d7da;
        color: #721c24;
    }

    @media (max-width: 768px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }

        .report-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .report-actions {
            flex-direction: column;
            width: 100%;
        }

        .date-filter {
            width: 100%;
            flex-wrap: wrap;
        }
    }
</style>
@endsection

@section('content')
<div class="reports-container">
    <!-- Header -->
    <div class="report-header">
        <div class="report-title">
            <h1><i class="fa-solid fa-chart-line"></i> WARBIXINADA QARANKA</h1>
            <p>Xogta guud iyo analytics-ka Booliska Qaranka Soomaaliya</p>
        </div>
        <div class="report-actions">
            <!-- Date Filter -->
            <form action="{{ route('reports.index') }}" method="GET" class="date-filter">
                <input type="date" name="start_date" value="{{ $startDate }}" required>
                <span>-</span>
                <input type="date" name="end_date" value="{{ $endDate }}" required>
                <button type="submit">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
            </form>

            <!-- Export Dropdown -->
            <div class="export-dropdown">
                <button class="export-btn" onclick="toggleExportMenu()">
                    <i class="fa-solid fa-download"></i> Export
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="export-menu" id="exportMenu">
                    <a href="{{ route('reports.export', ['format' => 'csv']) }}">
                        <i class="fa-solid fa-file-csv"></i> Export CSV
                    </a>
                    <a href="{{ route('reports.export', ['format' => 'pdf']) }}">
                        <i class="fa-solid fa-file-pdf"></i> Export PDF
                    </a>
                    <a href="{{ route('reports.export', ['format' => 'excel']) }}">
                        <i class="fa-solid fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card crimes">
            <div class="stat-icon">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
            <div class="stat-label">Wadarta Dambiyada</div>
            <div class="stat-value">{{ number_format($totalCrimes) }}</div>
        </div>

        <div class="stat-card cases">
            <div class="stat-icon">
                <i class="fa-solid fa-briefcase"></i>
            </div>
            <div class="stat-label">Wadarta Kiisaska</div>
            <div class="stat-value">{{ number_format($totalCases) }}</div>
        </div>

        <div class="stat-card suspects">
            <div class="stat-icon">
                <i class="fa-solid fa-users-viewfinder"></i>
            </div>
            <div class="stat-label">Wadarta Dambiilayaasha</div>
            <div class="stat-value">{{ number_format($totalSuspects) }}</div>
        </div>

        <div class="stat-card active">
            <div class="stat-icon">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div class="stat-label">Kiisas Furan</div>
            <div class="stat-value">{{ number_format($activeCases) }}</div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Crime Trends Chart -->
        <div class="chart-card">
            <h3><i class="fa-solid fa-chart-line"></i> Isbeddelka Dambiyada</h3>
            <canvas id="crimeTrendsChart"></canvas>
        </div>

        <!-- Case Status Chart -->
        <div class="chart-card">
            <h3><i class="fa-solid fa-chart-pie"></i> Xaaladda Kiisaska</h3>
            <canvas id="caseStatusChart"></canvas>
        </div>

        <!-- Crime Types Chart -->
        <div class="chart-card">
            <h3><i class="fa-solid fa-chart-bar"></i> Noocyada Dambiyada</h3>
            <canvas id="crimeTypesChart"></canvas>
        </div>

        <!-- Officer Workload Chart -->
        <div class="chart-card">
            <h3><i class="fa-solid fa-user-shield"></i> Shaqada Saraakiisha</h3>
            <canvas id="officerWorkloadChart"></canvas>
        </div>
    </div>

    <!-- Facility Coverage Table -->
    <div class="table-card">
        <h3><i class="fa-solid fa-building-shield"></i> Sugidda Amniga Xarumaha</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Xarunta</th>
                    <th>Nooca</th>
                    <th>Ciidanka Jooga</th>
                    <th>Heerka Sugida</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facility_coverage as $facility)
                <tr>
                    <td style="font-weight: 700;">{{ $facility->name }}</td>
                    <td>{{ $facility->type }}</td>
                    <td>{{ $facility->deployments_count }} Askari</td>
                    <td>
                        <div style="background: #e0e0e0; height: 8px; border-radius: 10px; overflow: hidden;">
                            <div style="background: {{ $facility->deployments_count > 5 ? '#43e97b' : ($facility->deployments_count > 0 ? '#f39c12' : '#e74c3c') }}; 
                                        height: 100%; 
                                        width: {{ min($facility->deployments_count * 10, 100) }}%;
                                        transition: width 0.3s ease;"></div>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge {{ $facility->deployments_count > 0 ? 'covered' : 'unguarded' }}">
                            {{ $facility->deployments_count > 0 ? 'COVERED' : 'UNGUARDED' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Case Resolution Rate -->
    <div class="chart-card" style="text-align: center;">
        <h3><i class="fa-solid fa-check-circle"></i> Heerka Xallinta Kiisaska</h3>
        <div style="font-size: 4rem; font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit', sans-serif; margin: 2rem 0;">
            {{ $resolutionRate }}%
        </div>
        <p style="color: var(--text-sub); font-size: 1.1rem;">
            {{ $resolutionRate >= 70 ? '✅ Wanaagsan' : ($resolutionRate >= 50 ? '⚠️ Dhexdhexaad' : '❌ Hooseeya') }}
        </p>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Toggle Export Menu
    function toggleExportMenu() {
        document.getElementById('exportMenu').classList.toggle('active');
    }

    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.querySelector('.export-dropdown');
        if (!dropdown.contains(event.target)) {
            document.getElementById('exportMenu').classList.remove('active');
        }
    });

    // Chart.js Configuration
    const chartColors = {
        primary: '#1abc9c',
        danger: '#e74c3c',
        warning: '#f39c12',
        info: '#3498db',
        success: '#2ecc71',
        purple: '#9b59b6',
        gradient1: ['#667eea', '#764ba2'],
        gradient2: ['#f093fb', '#f5576c'],
        gradient3: ['#4facfe', '#00f2fe'],
        gradient4: ['#43e97b', '#38f9d7']
    };

    // Crime Trends Chart
    const crimeTrendsCtx = document.getElementById('crimeTrendsChart').getContext('2d');
    new Chart(crimeTrendsCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($crimeTrends->pluck('month')) !!},
            datasets: [{
                label: 'Dambiyada',
                data: {!! json_encode($crimeTrends->pluck('total')) !!},
                borderColor: chartColors.primary,
                backgroundColor: 'rgba(26, 188, 156, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: chartColors.primary
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
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

    // Case Status Chart
    const caseStatusCtx = document.getElementById('caseStatusChart').getContext('2d');
    new Chart(caseStatusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($caseStatus->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($caseStatus->pluck('total')) !!},
                backgroundColor: [
                    chartColors.success,
                    chartColors.warning,
                    chartColors.danger,
                    chartColors.info
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Crime Types Chart
    const crimeTypesCtx = document.getElementById('crimeTypesChart').getContext('2d');
    new Chart(crimeTypesCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($crime_types->pluck('type')) !!},
            datasets: [{
                label: 'Tirada',
                data: {!! json_encode($crime_types->pluck('total')) !!},
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
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

    // Officer Workload Chart
    const officerWorkloadCtx = document.getElementById('officerWorkloadChart').getContext('2d');
    new Chart(officerWorkloadCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($officer_workload->map(function($w) { return $w->assignedOfficer->name ?? 'N/A'; })) !!},
            datasets: [{
                label: 'Kiisas',
                data: {!! json_encode($officer_workload->pluck('total')) !!},
                backgroundColor: 'rgba(67, 233, 123, 0.8)',
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endsection
