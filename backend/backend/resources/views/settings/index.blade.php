@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between mb-5">
                <div>
                    <h2 class="fw-bold text-dark mb-1" style="font-family: 'Outfit', sans-serif;">Settings</h2>
                    <p class="text-muted mb-0">Maamul akoonkaaga iyo dookhyada nidaamka.</p>
                </div>
            </div>

            <div class="row g-4">
                <!-- Sidebar Navigation (Optional for future expansion, kept simple for now) -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <a href="#notifications" class="list-group-item list-group-item-action active p-3 d-flex align-items-center gap-3 border-0" data-bs-toggle="list" style="border-left: 4px solid var(--accent-purple);">
                                    <div style="width: 35px; height: 35px; background: rgba(102, 126, 234, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--accent-purple);">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Ogeysiisyada</h6>
                                        <small class="text-muted">Habeynta fariimaha</small>
                                    </div>
                                </a>
                                <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action p-3 d-flex align-items-center gap-3 border-0">
                                    <div style="width: 35px; height: 35px; background: rgba(46, 204, 113, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #2ecc71;">
                                        <i class="fa-solid fa-user-shield"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">Profile-kaaga</h6>
                                        <small class="text-muted">Macluumaadka shaqsiga</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                     <!-- Quick Info Card -->
                     <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="bg-white bg-opacity-25 rounded-circle p-2">
                                <i class="fa-solid fa-shield-halved fa-lg"></i>
                            </div>
                            <h5 class="mb-0 fw-bold">Amniga</h5>
                        </div>
                        <p class="small opacity-75 mb-0">Si aad u xaqiijiso amniga koontadaada, fadlan si joogto ah u bedel lambarkaaga sirta ah.</p>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="col-md-8">
                    <div class="tab-content">
                        <!-- Notification Settings -->
                        <div class="tab-pane fade show active" id="notifications">
                            <div class="card border-0 shadow-sm rounded-4 mb-4">
                                <div class="card-header bg-white border-bottom py-3 px-4">
                                    <h5 class="card-title mb-0 fw-bold">Dookha Ogeysiisyada</h5>
                                </div>
                                <div class="card-body p-4">
                                    <form action="{{ route('settings.notifications') }}" method="POST">
                                        @csrf
                                        
                                        <div class="vstack gap-4">
                                            <!-- Email -->
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="text-primary bg-white shadow-sm rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fa-solid fa-envelope"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold mb-1">Email Notifications</h6>
                                                        <p class="text-muted small mb-0">Hel ogeysiisyada muhiimka ah email ahaan.</p>
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch custom-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" name="notification_email" {{ $settings->notification_email ? 'checked' : '' }} style="width: 3rem; height: 1.5rem;">
                                                </div>
                                            </div>

                                            <!-- Push -->
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="text-warning bg-white shadow-sm rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fa-solid fa-mobile-screen"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold mb-1">Push Notifications</h6>
                                                        <p class="text-muted small mb-0">Hel ogeysiisyada browser-ka dhexdiisa.</p>
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch custom-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" name="notification_push" {{ $settings->notification_push ? 'checked' : '' }} style="width: 3rem; height: 1.5rem;">
                                                </div>
                                            </div>

                                            <!-- Sound -->
                                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="text-danger bg-white shadow-sm rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fa-solid fa-volume-high"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold mb-1">Notification Sounds</h6>
                                                        <p class="text-muted small mb-0">Daar codka marka ogeysiis cusub yimaado.</p>
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch custom-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" name="notification_sound" {{ $settings->notification_sound ? 'checked' : '' }} style="width: 3rem; height: 1.5rem;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-end mt-4 pt-3 border-top">
                                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-bold shadow-sm">
                                                <i class="fa-solid fa-save me-2"></i> Keydi Isbedelka
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-switch .form-check-input:checked {
        background-color: var(--accent-purple);
        border-color: var(--accent-purple);
    }
    .list-group-item.active {
        background-color: #f8f9fa;
        color: var(--text-main);
        border-color: transparent;
    }
</style>
@endsection
