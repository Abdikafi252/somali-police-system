@extends('layouts.app')

@section('title', 'Ogeysiisyada')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Ogeysiisyada</h2>
                    <p class="text-muted mb-0">Dhammaan dhaqdhaqaaqyada muhiimka ah ee nidaamka.</p>
                </div>
                <div>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-light bg-white border shadow-sm text-primary fw-bold px-3">
                            <i class="fa-solid fa-check-double me-2"></i> Wada Akhri
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Notifications Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="min-height: 500px;">
                <div class="card-header bg-white border-bottom py-0 px-0">
                    <ul class="nav nav-tabs nav-fill border-0" id="notificationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-3 fw-bold rounded-0 border-top-0 border-start-0 border-end-0" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" style="color: var(--text-main);">
                                Dhammaan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold rounded-0 border-top-0 border-start-0 border-end-0" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread" type="button" role="tab" style="color: var(--text-main);">
                                Aan la akhriyin
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge bg-danger ms-2 rounded-pill">{{ auth()->user()->unreadNotifications->count() }}</span>
                                @endif
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-0">
                    <div class="tab-content" id="notificationTabsContent">

                        <!-- ALL TAB -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            @forelse($notifications as $notification)
                            <div class="notification-item p-4 border-bottom position-relative {{ $notification->read_at ? 'bg-white' : 'bg-primary bg-opacity-10' }}" style="transition: all 0.2s;">
                                <div class="d-flex gap-3">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0">
                                        @if(isset($notification->data['type']) && $notification->data['type'] == 'suspect_alert')
                                        <div class="avatar-icon bg-danger text-white shadow-sm">
                                            <i class="fa-solid fa-handcuffs"></i>
                                        </div>
                                        @else
                                        <div class="avatar-icon bg-primary text-white shadow-sm">
                                            <i class="fa-solid fa-file-shield"></i>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark">
                                                    {{ $notification->data['message'] ?? 'Ogeysiis' }}
                                                </h6>
                                                @if(isset($notification->data['description']))
                                                <p class="text-muted small mb-2 text-truncate-2" style="max-width: 600px;">
                                                    {{ $notification->data['description'] }}
                                                </p>
                                                @endif

                                                @if(isset($notification->data['location']))
                                                <div class="small text-muted mb-2">
                                                    <i class="fa-solid fa-map-location-dot me-1 text-danger"></i>
                                                    Goobta: <span class="fw-semibold">{{ $notification->data['location'] }}</span>
                                                </div>
                                                @endif

                                                @if(isset($notification->data['location']))
                                                <div class="small text-muted mb-2">
                                                    <i class="fa-solid fa-map-location-dot me-1 text-danger"></i>
                                                    Goobta: <span class="fw-semibold">{{ $notification->data['location'] }}</span>
                                                </div>
                                                @endif

                                                @if(isset($notification->data['type']) && $notification->data['type'] == 'suspect_alert')
                                                <div class="d-flex align-items-center gap-2 mt-2">
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">CID Alert</span>
                                                    <span class="small fw-semibold text-dark">{{ $notification->data['name'] }}</span>
                                                </div>
                                                @endif

                                                @if(isset($notification->data['reporter_name']))
                                                <div class="mt-2 small text-muted bg-light rounded p-2 d-inline-block">
                                                    <i class="fa-solid fa-user-shield me-1 text-primary"></i>
                                                    <span class="fw-semibold text-dark">{{ $notification->data['reporter_name'] }}</span>
                                                    <span class="mx-1 text-muted">|</span>
                                                    <i class="fa-solid fa-building-shield me-1 text-primary"></i>
                                                    <span class="text-secondary">{{ $notification->data['reporter_station'] ?? 'N/A' }}</span>
                                                </div>
                                                @endif
                                            </div>

                                            <div class="text-end ms-3">
                                                <div class="small text-muted mb-2 fw-medium">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </div>

                                                <div class="dropdown">
                                                    <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                        <i class="fa-solid fa-ellipsis"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 rounded-3">
                                                        @if(!$notification->read_at)
                                                        <li>
                                                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                                                @csrf
                                                                <button class="dropdown-item rounded-2">
                                                                    <i class="fa-solid fa-check me-2 text-success"></i> Mark as Read
                                                                </button>
                                                            </form>
                                                        </li>
                                                        @endif
                                                        <li>
                                                            <a class="dropdown-item rounded-2" href="{{ route('notifications.show', $notification->id) }}">
                                                                <i class="fa-solid fa-eye me-2 text-primary"></i> View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="dropdown-item rounded-2 text-danger">
                                                                    <i class="fa-solid fa-trash me-2"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5 my-5">
                                <div class="mb-3 text-muted opacity-25">
                                    <i class="fa-solid fa-bell-slash fa-4x"></i>
                                </div>
                                <h5 class="text-muted fw-bold">Ma jiraan wax ogeysiis ah</h5>
                            </div>
                            @endforelse

                            <div class="p-3">
                                {{ $notifications->links('vendor.pagination.glass') }}
                            </div>
                        </div>

                        <!-- UNREAD TAB -->
                        <div class="tab-pane fade" id="unread" role="tabpanel">
                            @forelse(auth()->user()->unreadNotifications as $notification)
                            <!-- Reusing item structure for consistency -->
                            <div class="notification-item p-4 border-bottom position-relative bg-primary bg-opacity-10" style="transition: all 0.2s;">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        @if(isset($notification->data['type']) && $notification->data['type'] == 'suspect_alert')
                                        <div class="avatar-icon bg-danger text-white shadow-sm">
                                            <i class="fa-solid fa-handcuffs"></i>
                                        </div>
                                        @else
                                        <div class="avatar-icon bg-primary text-white shadow-sm">
                                            <i class="fa-solid fa-file-shield"></i>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark">
                                                    {{ $notification->data['message'] ?? 'Ogeysiis' }}
                                                </h6>
                                                @if(isset($notification->data['description']))
                                                <p class="text-muted small mb-2 text-truncate-2" style="max-width: 600px;">
                                                    {{ $notification->data['description'] }}
                                                </p>
                                                @endif

                                                @if(isset($notification->data['location']))
                                                <div class="small text-muted mb-2">
                                                    <i class="fa-solid fa-map-location-dot me-1 text-danger"></i>
                                                    Goobta: <span class="fw-semibold">{{ $notification->data['location'] }}</span>
                                                </div>
                                                @endif
                                                <div class="mt-2">
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">NEW</span>
                                                </div>

                                                @if(isset($notification->data['reporter_name']))
                                                <div class="mt-2 small text-muted bg-white bg-opacity-50 rounded p-2 d-inline-block border border-primary-subtle">
                                                    <i class="fa-solid fa-user-shield me-1 text-primary"></i>
                                                    <span class="fw-semibold text-dark">{{ $notification->data['reporter_name'] }}</span>
                                                    <span class="mx-1 text-muted">|</span>
                                                    <i class="fa-solid fa-building-shield me-1 text-primary"></i>
                                                    <span class="text-secondary">{{ $notification->data['reporter_station'] ?? 'N/A' }}</span>
                                                </div>
                                                @endif
                                            </div>

                                            <div class="text-end ms-3">
                                                <div class="small text-muted mb-2 fw-medium">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </div>
                                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                                    @csrf
                                                    <button class="btn btn-sm btn-white border shadow-sm">
                                                        <i class="fa-solid fa-check text-success"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5 my-5">
                                <div class="mb-3 text-muted opacity-25">
                                    <i class="fa-solid fa-check-circle fa-4x"></i>
                                </div>
                                <h5 class="text-muted fw-bold">Dhammaan waad akhrisay!</h5>
                            </div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .notification-item:hover {
        background-color: #f8fafc !important;
    }

    .nav-tabs .nav-link.active {
        background-color: transparent;
        border-bottom: 3px solid var(--accent-purple);
        color: var(--accent-purple) !important;
    }

    .nav-tabs .nav-link:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>
@endsection