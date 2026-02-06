@extends('layouts.app')

@section('title', 'Profile-kayga')

@section('content')
<div class="profile-header" style="position: relative; margin-bottom: 5rem;">
    <!-- Cover/Hero Section -->
    <div style="height: 200px; background: linear-gradient(135deg, var(--sidebar-bg) 0%, #2c3e50 100%); border-radius: 20px; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.1;"></div>
        <div style="position: absolute; bottom: 20px; right: 20px; color: rgba(255,255,255,0.8); font-size: 0.9rem; font-weight: 600;">
            <i class="fa-solid fa-shield-halved"></i> Somali Police Force
        </div>
    </div>

    <!-- Profile Info Floating Card -->
    <div style="position: absolute; bottom: -60px; left: 40px; display: flex; align-items: end; gap: 1.5rem;">
        <div style="position: relative;">
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" style="width: 140px; height: 140px; border-radius: 20px; object-fit: cover; border: 5px solid white; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1abc9c&color=fff&size=140" style="width: 140px; height: 140px; border-radius: 20px; border: 5px solid white; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
            @endif
            <div style="position: absolute; bottom: 10px; right: -5px; background: #2ecc71; width: 25px; height: 25px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);" title="Active"></div>
        </div>
        
        <div style="margin-bottom: 0.5rem;">
            <h1 style="margin: 0; font-size: 2rem; font-weight: 800; color: var(--sidebar-bg); text-shadow: 2px 2px 0px white;">{{ $user->name }}</h1>
            <div style="display: flex; gap: 0.8rem; margin-top: 0.5rem;">
                <span style="padding: 0.4rem 1rem; background: var(--sidebar-bg); color: white; border-radius: 20px; font-size: 0.8rem; font-weight: 600; box-shadow: 0 4px 10px rgba(44, 62, 80, 0.2);">
                    {{ $user->role->name ?? 'User' }}
                </span>
                <span style="padding: 0.4rem 1rem; background: white; color: #f39c12; border: 1px solid #f39c12; border-radius: 20px; font-size: 0.8rem; font-weight: 700;">
                    <i class="fa-solid fa-star"></i> {{ $user->rank ?? 'N/A' }}
                </span>
            </div>
        </div>
    </div>

    <div style="position: absolute; bottom: -50px; right: 40px;">
        <a href="{{ route('profile.edit') }}" class="btn-primary" style="background: white; color: var(--sidebar-bg); border: 1px solid var(--border-soft); box-shadow: 0 5px 15px rgba(0,0,0,0.05); padding: 0.8rem 1.5rem; font-weight: 700; border-radius: 12px; transition: transform 0.2s;">
            <i class="fa-solid fa-pen-to-square"></i> Bedel Xogtaada
        </a>
    </div>
</div>

@if(session('success'))
<div style="background: #e8f5e9; color: #2e7d32; padding: 1.2rem; border-radius: 15px; margin-bottom: 2rem; border-left: 5px solid #4caf50; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 15px rgba(46, 204, 113, 0.1);">
    <i class="fa-solid fa-circle-check fa-lg"></i> 
    <span style="font-weight: 600;">{{ session('success') }}</span>
</div>
@endif

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Left Column -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Personal Info -->
        <div class="glass-card" style="padding: 2rem; background: white; border-radius: 20px; border: 1px solid rgba(255,255,255,0.8);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; color: var(--sidebar-bg); font-weight: 800;">
                    <i class="fa-solid fa-id-card" style="color: #3498db; margin-right: 0.5rem;"></i> Macluumaadka Gaarka ah
                </h3>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <label style="display: block; color: var(--text-sub); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem;">Email Address</label>
                    <div style="font-size: 1rem; font-weight: 700; color: var(--sidebar-bg); display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-envelope" style="color: #bdc3c7;"></i> {{ $user->email }}
                    </div>
                </div>
                
                <div>
                    <label style="display: block; color: var(--text-sub); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem;">Darajada (Rank)</label>
                    <div style="font-size: 1rem; font-weight: 700; color: #f39c12; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-star"></i> {{ $user->rank ?? 'Lama cayimin' }}
                    </div>
                </div>

                <div>
                    <label style="display: block; color: var(--text-sub); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem;">Saldhiga / Xarunta</label>
                    <div style="font-size: 1rem; font-weight: 700; color: var(--sidebar-bg); display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-building-shield" style="color: #bdc3c7;"></i> 
                        {{ $user->station ? $user->station->station_name : 'Xarunta Dhexe (HQ)' }}
                    </div>
                </div>

                <div>
                    <label style="display: block; color: var(--text-sub); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem;">Taariikhda la diiwaangeliyay</label>
                    <div style="font-size: 1rem; font-weight: 700; color: var(--sidebar-bg); display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-calendar" style="color: #bdc3c7;"></i> {{ $user->created_at->format('d M, Y') }}
                    </div>
                </div>

                <div>
                    <label style="display: block; color: var(--text-sub); font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem;">Status</label>
                    <div style="font-size: 1rem; font-weight: 700; color: #27ae60; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-circle-check"></i> {{ ucfirst($user->status ?? 'active') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity (New Feature) -->
        <div class="glass-card" style="padding: 2rem; background: white; border-radius: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; color: var(--sidebar-bg); font-weight: 800;">
                    <i class="fa-solid fa-clock-rotate-left" style="color: #9b59b6; margin-right: 0.5rem;"></i> Dhaqdhaqaaqyadii Ugu Dambeeyay
                </h3>
            </div>

            <div class="activity-timeline">
                @forelse($auditLogs as $log)
                <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; position: relative;">
                    <!-- Line -->
                    @if(!$loop->last)
                    <div style="position: absolute; left: 19px; top: 35px; bottom: -25px; width: 2px; background: #f0f2f5;"></div>
                    @endif
                    
                    <!-- Icon -->
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.05); z-index: 1;">
                        @if($log->action == 'CREATE')
                            <i class="fa-solid fa-plus" style="color: #27ae60;"></i>
                        @elseif($log->action == 'UPDATE')
                            <i class="fa-solid fa-pen" style="color: #f39c12;"></i>
                        @elseif($log->action == 'DELETE')
                            <i class="fa-solid fa-trash" style="color: #e74c3c;"></i>
                        @else
                            <i class="fa-solid fa-info" style="color: #3498db;"></i>
                        @endif
                    </div>

                    <!-- Content -->
                    <div style="flex: 1; padding-top: 0.2rem;">
                        <div style="font-weight: 700; color: var(--sidebar-bg); font-size: 0.95rem;">
                            {{ $log->description }}
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.3rem;">
                            <span style="font-size: 0.8rem; color: var(--text-sub);">
                                {{ $log->created_at->diffForHumans() }}
                            </span>
                            <span style="font-size: 0.75rem; background: #f1f2f6; padding: 0.2rem 0.6rem; border-radius: 6px; color: #7f8c8d; font-weight: 600;">
                                {{ $log->table_name }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; color: var(--text-sub); padding: 2rem;">
                    <i class="fa-solid fa-ghost" style="opacity: 0.2; font-size: 2rem; margin-bottom: 1rem;"></i>
                    <p>Ma jiraan dhaqdhaqaaqyo dhowaan dhacay.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Settings -->
    <div class="glass-card" style="padding: 2rem; background: white; border-radius: 20px; height: fit-content;">
        <h3 style="margin-top: 0; color: var(--sidebar-bg); font-weight: 800; margin-bottom: 1.5rem;">
            <i class="fa-solid fa-lock" style="color: #e74c3c; margin-right: 0.5rem;"></i> Amniga Password-ka
        </h3>

        <form action="{{ route('profile.password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Password-ka Hadda</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-key" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #bdc3c7;"></i>
                    <input type="password" name="current_password" required class="form-control" style="width: 100%; padding: 0.9rem 1rem 0.9rem 2.8rem; border-radius: 12px; border: 1px solid var(--border-soft); background: #f8f9fa;">
                </div>
                @error('current_password')
                    <span style="color: #e74c3c; font-size: 0.8rem; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Password-ka Cusub</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #bdc3c7;"></i>
                    <input type="password" name="new_password" required class="form-control" style="width: 100%; padding: 0.9rem 1rem 0.9rem 2.8rem; border-radius: 12px; border: 1px solid var(--border-soft); background: #f8f9fa;">
                </div>
                @error('new_password')
                    <span style="color: #e74c3c; font-size: 0.8rem; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Xaqiiji Password-ka</label>
                <div style="position: relative;">
                    <i class="fa-solid fa-check-double" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #bdc3c7;"></i>
                    <input type="password" name="new_password_confirmation" required class="form-control" style="width: 100%; padding: 0.9rem 1rem 0.9rem 2.8rem; border-radius: 12px; border: 1px solid var(--border-soft); background: #f8f9fa;">
                </div>
            </div>

            <button type="submit" class="btn-primary" style="background: #e74c3c; border: none; padding: 0.9rem 2rem; width: 100%; font-weight: 700; border-radius: 12px; transition: background 0.3s;">
                Bedel Password-ka
            </button>
        </form>
    </div>
</div>
@endsection

