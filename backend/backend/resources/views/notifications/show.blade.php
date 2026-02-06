@extends('layouts.app')

@section('title', 'Faahfaahinta Ogeysiiska')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-3">
                <a href="{{ route('notifications.index') }}" class="text-decoration-none text-muted">
                    <i class="fa-solid fa-arrow-left me-1"></i> Dib ugu noqo Ogeysiisyada
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0 me-3">
                            @if(isset($notification->data['type']) && $notification->data['type'] == 'suspect_alert')
                                <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fa-solid fa-handcuffs fa-xl"></i>
                                </div>
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fa-solid fa-file-invoice fa-xl"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold">{{ $notification->data['message'] ?? 'Ogeysiis' }}</h4>
                            <div class="text-muted small">
                                <i class="fa-regular fa-clock me-1"></i> {{ $notification->created_at->format('d M Y, h:i A') }}
                                <span class="mx-2">•</span>
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <hr class="border-light-subtle my-4">

                    @if(isset($notification->data['photo']))
                        <div class="mb-4 text-center">
                            <img src="{{ asset('storage/' . $notification->data['photo']) }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 300px;">
                        </div>
                    @endif

                    <div class="notification-content">
                        @if(isset($notification->data['description']))
                            <h6 class="text-uppercase text-muted fw-bold small mb-2">Faahfaahin</h6>
                            <p class="lead fs-6">{{ $notification->data['description'] }}</p>
                        @endif

                        @if(isset($notification->data['type']) && $notification->data['type'] == 'suspect_alert')
                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="text-muted small">Magaca Dambiilaha</div>
                                        <div class="fw-bold">{{ $notification->data['name'] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="text-muted small">Da'da</div>
                                        <div class="fw-bold">{{ $notification->data['age'] }} sano</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="text-muted small">Nooca Dambiga</div>
                                        <div class="fw-bold">{{ $notification->data['crime_type'] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="text-muted small">Lambarka Kiiska</div>
                                        <div class="fw-bold">{{ $notification->data['case_number'] }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-center">
                                @if(isset($notification->data['suspect_id']))
                                    <a href="{{ route('suspects.show', $notification->data['suspect_id']) }}" class="btn btn-danger px-4">
                                        <i class="fa-solid fa-eye me-2"></i> Eeg File-ka Dambiilaha
                                    </a>
                                @endif
                            </div>
                        @elseif(isset($notification->data['crime_id']))
                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="text-muted small">Nooca Dambiga</div>
                                        <div class="fw-bold">{{ $notification->data['crime_type'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <div class="text-muted small">Goobta</div>
                                        <div class="fw-bold">{{ $notification->data['location'] ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-center">
                                <a href="{{ route('crimes.show', $notification->data['crime_id']) }}" class="btn btn-primary px-4">
                                    <i class="fa-solid fa-eye me-2"></i> Eeg Faahfaahinta Dambiga
                                </a>
                            </div>
                        @else
                           <!-- Generic fallback if no specific ID links -->
                        @endif

                        @if(isset($notification->data['reporter_name']))
                            <div class="mt-4 p-3 bg-light bg-opacity-50 rounded-3 border border-light-subtle">
                                <h6 class="text-uppercase text-muted fw-bold small mb-3">Sarkaalka Diiwaangeliyay</h6>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-white shadow-sm p-3 me-3 text-primary">
                                        <i class="fa-solid fa-user-shield fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $notification->data['reporter_name'] }}</div>
                                        <div class="text-muted small">
                                            <i class="fa-solid fa-building-shield me-1"></i> 
                                            {{ $notification->data['reporter_station'] ?? 'Unknown Station' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white border-top p-3 d-flex justify-content-between">
                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Ma hubtaa?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fa-solid fa-trash me-1"></i> Tirtir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
