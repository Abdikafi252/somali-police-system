@extends('layouts.app')

@section('title', 'Diiwaanka Waxqabadka')

@section('css')
<style>
    .audit-container {
        padding: 2rem;
    }

    .audit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .audit-title h1 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--sidebar-bg);
        font-family: 'Outfit', sans-serif;
        margin-bottom: 0.5rem;
    }

    .audit-title p {
        color: var(--text-sub);
        font-size: 1rem;
    }

    .export-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        text-decoration: none;
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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
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
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        color: white;
    }

    .stat-label {
        color: var(--text-sub);
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--sidebar-bg);
        font-family: 'Outfit', sans-serif;
    }

    .filters-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-group label {
        font-weight: 600;
        color: var(--sidebar-bg);
        font-size: 0.9rem;
    }

    .filter-group select,
    .filter-group input {
        padding: 0.7rem;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.1);
    }

    .search-box {
        position: relative;
        grid-column: span 2;
    }

    .search-box input {
        width: 100%;
        padding: 0.7rem 0.7rem 0.7rem 2.5rem;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .search-box i {
        position: absolute;
        left: 0.8rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-sub);
    }

    .filter-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .btn-filter {
        background: var(--sidebar-bg);
        color: white;
        padding: 0.7rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26, 188, 156, 0.3);
    }

    .btn-reset {
        background: #6c757d;
        color: white;
        padding: 0.7rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        background: #5a6268;
    }

    .logs-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .logs-table {
        width: 100%;
        border-collapse: collapse;
    }

    .logs-table thead tr {
        border-bottom: 2px solid #e0e0e0;
    }

    .logs-table th {
        padding: 1rem;
        text-align: left;
        color: var(--text-sub);
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .logs-table td {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .logs-table tbody tr {
        transition: all 0.2s ease;
    }

    .logs-table tbody tr:hover {
        background: rgba(26, 188, 156, 0.05);
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }

    .user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
    }

    .user-name {
        font-weight: 700;
        color: var(--sidebar-bg);
    }

    .action-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
        text-transform: uppercase;
    }

    .action-badge.created {
        background: #d4edda;
        color: #155724;
    }

    .action-badge.updated {
        background: #d1ecf1;
        color: #0c5460;
    }

    .action-badge.deleted {
        background: #f8d7da;
        color: #721c24;
    }

    .action-badge.viewed {
        background: #e2e3e5;
        color: #383d41;
    }

    .resource-tag {
        background: #f8f9fa;
        padding: 0.3rem 0.8rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #495057;
    }

    .time-text {
        color: var(--text-sub);
        font-size: 0.85rem;
    }

    .pagination {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .filters-grid {
            grid-template-columns: 1fr;
        }

        .search-box {
            grid-column: span 1;
        }

        .audit-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .logs-table {
            font-size: 0.85rem;
        }

        .logs-table th,
        .logs-table td {
            padding: 0.7rem 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="audit-container">
    <!-- Header -->
    <div class="audit-header">
        <div class="audit-title">
            <h1><i class="fa-solid fa-clock-rotate-left"></i> DIIWAANKA WAXQABADKA</h1>
            <p>Dhammaan waxqabadka ka dhaca nidaamka Booliska Qaranka</p>
        </div>
        <a href="{{ route('audit.export') }}" class="export-btn">
            <i class="fa-solid fa-download"></i> Soo Dejiso CSV
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-list"></i>
            </div>
            <div class="stat-label">Wadarta Diiwaanka</div>
            <div class="stat-value">{{ number_format($totalLogs) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
            <div class="stat-label">Maanta</div>
            <div class="stat-value">{{ number_format($todayLogs) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-plus-circle"></i>
            </div>
            <div class="stat-label">La Abuuray</div>
            <div class="stat-value">{{ number_format($actionStats['created'] ?? 0) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-edit"></i>
            </div>
            <div class="stat-label">La Cusboonaysiiyay</div>
            <div class="stat-value">{{ number_format($actionStats['updated'] ?? 0) }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-trash"></i>
            </div>
            <div class="stat-label">La Tirtiray</div>
            <div class="stat-value">{{ number_format($actionStats['deleted'] ?? 0) }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <h3 style="margin-bottom: 1rem; color: var(--sidebar-bg); font-weight: 700;">
            <i class="fa-solid fa-filter"></i> Shaandhayn (Filter)
        </h3>
        <form action="{{ route('audit.index') }}" method="GET">
            <div class="filters-grid">
                <!-- Action Filter -->
                <div class="filter-group">
                    <label>Nooca Waxqabadka</label>
                    <select name="action">
                        <option value="">Dhammaan</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>La Abuuray</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>La Cusboonaysiiyay</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>La Tirtiray</option>
                        <option value="viewed" {{ request('action') == 'viewed' ? 'selected' : '' }}>La Daawday</option>
                    </select>
                </div>

                <!-- User Filter -->
                <div class="filter-group">
                    <label>Isticmaale</label>
                    <select name="user_id">
                        <option value="">Dhammaan Isticmaalayaasha</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Start Date -->
                <div class="filter-group">
                    <label>Taariikhda Bilowga</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}">
                </div>

                <!-- End Date -->
                <div class="filter-group">
                    <label>Taariikhda Dhammaadka</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}">
                </div>

                <!-- Search -->
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" placeholder="Raadi magaca, waxqabadka, ama faahfaahinta..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fa-solid fa-filter"></i> Shaandhayn
                </button>
                <a href="{{ route('audit.index') }}" class="btn-reset">
                    <i class="fa-solid fa-rotate-left"></i> Dib u Billow
                </a>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="logs-card">
        <h3 style="margin-bottom: 1.5rem; color: var(--sidebar-bg); font-weight: 700;">
            <i class="fa-solid fa-table"></i> Liiska Diiwaanka
        </h3>

        @if($logs->count() > 0)
        <table class="logs-table">
            <thead>
                <tr>
                    <th>Isticmaale</th>
                    <th>Waxqabad</th>
                    <th>Shay</th>
                    <th>Faahfaahin</th>
                    <th>Waqti</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>
                        <div class="user-cell">
                            @if($log->user && $log->user->profile_image)
                            <img src="{{ asset('storage/' . $log->user->profile_image) }}" class="user-avatar" alt="Profile">
                            @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user->name ?? 'N/A') }}&size=35&background=random" class="user-avatar" alt="Avatar">
                            @endif
                            <span class="user-name">{{ $log->user->name ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td>
                        @php
                        $actionClass = strtolower($log->action);
                        $actionText = [
                        'created' => 'La Abuuray',
                        'updated' => 'La Cusboonaysiiyay',
                        'deleted' => 'La Tirtiray',
                        'viewed' => 'La Daawday'
                        ][$log->action] ?? $log->action;
                        @endphp
                        <span class="action-badge {{ $actionClass }}">{{ $actionText }}</span>
                    </td>
                    <td>
                        <span class="resource-tag">{{ $log->table_name }}</span>
                        <small class="text-muted d-block mt-1">ID: {{ $log->record_id }}</small>
                    </td>
                    <td style="font-size: 0.85rem; color: #495057;">
                        {{ Str::limit($log->description ?? $log->details, 50) }}

                        @if(!empty($log->old_values) || !empty($log->new_values))
                        <button type="button" class="btn btn-sm btn-link text-primary p-0 ms-2" data-bs-toggle="modal" data-bs-target="#logModal{{ $log->id }}">
                            <i class="fa-solid fa-eye"></i> Eeg Isbedelka
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Faahfaahinta Waxqabadka</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="fw-bold text-danger mb-2">Hore (Old Values)</h6>
                                                @if(!empty($log->old_values))
                                                <div class="bg-light p-3 rounded" style="font-family: monospace; font-size: 0.85rem;">
                                                    @foreach($log->old_values as $key => $value)
                                                    <div class="mb-1">
                                                        <span class="fw-bold">{{ $key }}:</span>
                                                        <span class="text-danger">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @else
                                                <span class="text-muted fst-italic">Ma jiro xog hore</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6 border-start">
                                                <h6 class="fw-bold text-success mb-2">Cusub (New Values)</h6>
                                                @if(!empty($log->new_values))
                                                <div class="bg-light p-3 rounded" style="font-family: monospace; font-size: 0.85rem;">
                                                    @foreach($log->new_values as $key => $value)
                                                    <div class="mb-1">
                                                        <span class="fw-bold">{{ $key }}:</span>
                                                        <span class="text-success">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                @else
                                                <span class="text-muted fst-italic">Ma jiro xog cusub</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </td>
                    <td>
                        <span class="time-text">{{ $log->created_at->diffForHumans() }}</span>
                        <br>
                        <small style="color: #adb5bd;">{{ $log->created_at->format('d M Y, h:i A') }}</small>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            {{ $logs->links('vendor.pagination.glass') }}
        </div>
        @else
        <div style="text-align: center; padding: 3rem; color: var(--text-sub);">
            <i class="fa-solid fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
            <p style="font-size: 1.1rem; font-weight: 600;">Ma jiraan diiwaano la helay</p>
            <p style="font-size: 0.9rem;">Isku day inaad beddesho shaandhaynahaaga</p>
        </div>
        @endif
    </div>
</div>
@endsection