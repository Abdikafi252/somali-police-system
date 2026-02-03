@extends('layouts.app')

@section('title', 'Profile-ka Sarkaalka')

@section('content')
<div class="header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: start;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">PROFILE-KA SARKAALKA</h1>
        <p style="color: var(--text-sub);">Diiwaanka shaqada iyo aqoonsiga rasmiga ah ee sarkaalka.</p>
    </div>
    <div style="display: flex; gap: 0.8rem;">
        @if(auth()->user()->role->slug == 'admin')
        <a href="{{ route('users.edit', $user->id) }}" class="btn-primary" style="text-decoration: none; padding: 0.8rem 1.5rem; background: var(--sidebar-bg); border: none;">
            <i class="fa-solid fa-pen-to-square"></i> Bedel Xogta
        </a>
        @endif
        <a href="{{ route('users.index') }}" style="padding: 0.8rem 1.5rem; background: #fff; color: var(--sidebar-bg); border-radius: 12px; text-decoration: none; font-weight: 700; border: 2px solid var(--border-soft); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fa-solid fa-arrow-left"></i> Liiska
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 320px 1fr; gap: 2rem; align-items: start;">
    <!-- Profile Sidebar Card -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="glass-card" style="padding: 2rem; text-align: center; position: relative; overflow: hidden;">
            <!-- Rank Badge Over Profile -->
            <div style="position: absolute; top: 1rem; right: 1rem; background: #f1c40f; color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; box-shadow: 0 4px 10px rgba(241, 196, 15, 0.3);">
                <i class="fa-solid fa-medal"></i> {{ $user->rank ?? 'ASKARI' }}
            </div>

            <div style="margin-bottom: 1.5rem;">
                @if($user->profile_image)
                    <img src="{{ asset('storage/' . $user->profile_image) }}" 
                        style="width: 140px; height: 140px; border-radius: 30px; border: 5px solid #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.1); object-fit: cover;">
                @else
                    <div style="width: 140px; height: 140px; border-radius: 30px; background: linear-gradient(135deg, #3498db, #2980b9); display: flex; align-items: center; justify-content: center; margin: 0 auto; border: 5px solid #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                        <i class="fa-solid fa-user-shield" style="font-size: 4rem; color: white;"></i>
                    </div>
                @endif
            </div>

            <h2 style="font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.3rem; font-family: 'Outfit';">{{ $user->name }}</h2>
            <p style="color: var(--text-sub); font-size: 0.85rem; margin-bottom: 1.5rem;">{{ $user->role->name ?? 'Ma jiro' }}</p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem; margin-bottom: 1.5rem;">
                <div style="background: rgba(52, 152, 219, 0.05); padding: 1rem; border-radius: 15px; border: 1px solid rgba(52, 152, 219, 0.1);">
                    <small style="display: block; color: var(--text-sub); margin-bottom: 0.2rem; font-size: 0.65rem; text-transform: uppercase;">Kiisaska</small>
                    <strong style="font-size: 1.2rem; color: #3498db;">{{ $user->cases->count() }}</strong>
                </div>
                <div style="background: rgba(26, 188, 156, 0.05); padding: 1rem; border-radius: 15px; border: 1px solid rgba(26, 188, 156, 0.1);">
                    <small style="display: block; color: var(--text-sub); margin-bottom: 0.2rem; font-size: 0.65rem; text-transform: uppercase;">Dambiyada</small>
                    <strong style="font-size: 1.2rem; color: #1abc9c;">{{ $user->crimes->count() }}</strong>
                </div>
            </div>

            <span style="
                display: block;
                padding: 0.6rem; 
                border-radius: 12px; 
                font-size: 0.8rem; 
                font-weight: 800;
                background: {{ $user->status == 'active' ? '#e8f5e9' : '#ffebee' }};
                color: {{ $user->status == 'active' ? '#2e7d32' : '#c62828' }};
            ">
                <i class="fa-solid {{ $user->status == 'active' ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i> 
                {{ $user->status == 'active' ? 'XUBIN FIRFIRCOON' : 'MA FIRFIRCONA' }}
            </span>
        </div>

        <!-- Quick Contacts -->
        <div class="glass-card" style="padding: 1.5rem;">
            <h5 style="color: var(--sidebar-bg); font-weight: 800; margin-bottom: 1rem; font-size: 0.8rem; text-transform: uppercase;">Xogta Xiriirka</h5>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.8rem;">
                    <div style="width: 35px; height: 35px; border-radius: 10px; background: #f1f2f6; display: flex; align-items: center; justify-content: center; color: var(--sidebar-bg);">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div style="overflow: hidden; text-overflow: ellipsis;">
                        <small style="display: block; color: var(--text-sub); font-size: 0.7rem;">Email-ka</small>
                        <span style="font-weight: 700; font-size: 0.85rem;">{{ $user->email }}</span>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.8rem;">
                    <div style="width: 35px; height: 35px; border-radius: 10px; background: #f1f2f6; display: flex; align-items: center; justify-content: center; color: var(--sidebar-bg);">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <small style="display: block; color: var(--text-sub); font-size: 0.7rem;">Gobolka</small>
                        <span style="font-weight: 700; font-size: 0.85rem;">{{ $user->region_id ?? 'Lama diwaangelin' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Stats Row -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
            <div class="glass-card" style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem; border-bottom: 4px solid #3498db;">
                <div style="font-size: 2rem; color: rgba(52, 152, 219, 0.2);"><i class="fa-solid fa-calendar-check"></i></div>
                <div>
                    <small style="display: block; color: var(--text-sub); font-size: 0.75rem;">Ku soo biiray</small>
                    <span style="font-weight: 800; color: var(--sidebar-bg);">{{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
            <div class="glass-card" style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem; border-bottom: 4px solid #9b59b6;">
                <div style="font-size: 2rem; color: rgba(155, 89, 182, 0.2);"><i class="fa-solid fa-shield-halved"></i></div>
                <div>
                    <small style="display: block; color: var(--text-sub); font-size: 0.75rem;">Saldhigga Hadda</small>
                    <span style="font-weight: 800; color: var(--sidebar-bg);">{{ $user->station->station_name ?? 'MA MAGACAWNA' }}</span>
                </div>
            </div>
            <div class="glass-card" style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem; border-bottom: 4px solid #f1c40f;">
                <div style="font-size: 2rem; color: rgba(241, 196, 15, 0.2);"><i class="fa-solid fa-star"></i></div>
                <div>
                    <small style="display: block; color: var(--text-sub); font-size: 0.75rem;">Darajada</small>
                    <span style="font-weight: 800; color: var(--sidebar-bg);">{{ $user->rank ?? 'ASKARI' }}</span>
                </div>
            </div>
        </div>

        <div class="glass-card" style="padding: 0;">
            <!-- Tabs Header -->
            <div style="display: flex; border-bottom: 2px solid var(--border-soft); padding: 0 1.5rem;">
                <button onclick="showTab('history')" id="tab-history-btn" style="padding: 1.5rem 2rem; background: none; border: none; border-bottom: 3px solid #3498db; color: var(--sidebar-bg); font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem;">
                    <i class="fa-solid fa-history"></i> TAARIIKHDA SHAQADA
                </button>
                <button onclick="showTab('audit')" id="tab-audit-btn" style="padding: 1.5rem 2rem; background: none; border: none; border-bottom: 3px solid transparent; color: var(--text-sub); font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem;">
                    <i class="fa-solid fa-list-check"></i> DABA-GALKA (AUDIT)
                </button>
            </div>

            <!-- Tab: History -->
            <div id="tab-history" style="padding: 2rem;">
                <h4 style="font-weight: 800; color: var(--sidebar-bg); margin-bottom: 1.5rem; font-size: 1.1rem;">History-ga Geeynta Sarkaalka</h4>
                <div style="display: flex; flex-direction: column; gap: 1.2rem; position: relative; padding-left: 2rem; border-left: 2px dashed #ddd;">
                    @forelse($user->deployments->sortByDesc('created_at') as $deployment)
                    <div style="position: relative; background: #fff; padding: 1.5rem; border-radius: 15px; border: 1px solid var(--border-soft); box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                        <div style="position: absolute; left: -2.7rem; top: 1.5rem; width: 20px; height: 20px; border-radius: 50%; background: #3498db; border: 4px solid #fff; box-shadow: 0 0 0 3px #3498db1a;"></div>
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.8rem;">
                            <div>
                                <h5 style="margin: 0; font-weight: 800; color: var(--sidebar-bg);">{{ $deployment->station->station_name ?? $deployment->facility->name ?? 'MA MAGACAWNA' }}</h5>
                                <span style="font-size: 0.75rem; color: var(--text-sub);">Xilka: {{ $deployment->duty_type }}</span>
                            </div>
                            <span style="background: #f1f2f6; color: var(--sidebar-bg); padding: 4px 10px; border-radius: 20px; font-size: 0.65rem; font-weight: 800;">
                                {{ $deployment->created_at->format('d M Y') }}
                            </span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 1.5rem;">
                            <span style="font-size: 0.8rem; color: var(--text-main);"><i class="fa-solid fa-clock" style="color: #3498db; margin-right: 0.4rem;"></i> {{ $deployment->shift }}</span>
                            <span style="font-size: 0.8rem; font-weight: 700; color: {{ $deployment->status == 'on_duty' ? '#27ae60' : '#e74c3c' }}">
                                <i class="fa-solid fa-circle" style="font-size: 0.5rem; margin-right: 0.4rem;"></i> {{ $deployment->status == 'on_duty' ? 'Hadda ayuu joogaa' : 'Waa laga bedelay' }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div style="padding: 3rem; text-align: center; color: var(--text-sub); background: #fdfdfd; border-radius: 20px; border: 2px dashed #eee;">
                        <i class="fa-solid fa-map-location-dot fa-3x" style="opacity: 0.2; margin-bottom: 1rem;"></i>
                        <p style="font-weight: 600;">Wali ma jiro taariikh geeyn oo diiwaangashan.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab: Audit -->
            <div id="tab-audit" style="display: none; padding: 2rem;">
                <h4 style="font-weight: 800; color: var(--sidebar-bg); margin-bottom: 1.5rem; font-size: 1.1rem;">Dhammaan Waxqabadka</h4>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @forelse($auditLogs as $log)
                    <div style="padding: 1rem; background: #fff; border-radius: 12px; border: 1px solid var(--border-soft); display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: 
                            {{ $log->action == 'CREATE' ? '#e8f5e9' : ($log->action == 'UPDATE' ? '#fff3e0' : '#ffebee') }}; 
                            display: flex; align-items: center; justify-content: center; color: 
                            {{ $log->action == 'CREATE' ? '#2e7d32' : ($log->action == 'UPDATE' ? '#f57c00' : '#c62828') }};">
                            <i class="fa-solid {{ $log->action == 'CREATE' ? 'fa-plus' : ($log->action == 'UPDATE' ? 'fa-pen' : 'fa-trash') }}"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="font-weight: 800; color: var(--sidebar-bg); font-size: 0.85rem;">{{ $log->description }}</span>
                                <span style="font-size: 0.7rem; color: var(--text-sub);">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <small style="color: var(--text-sub); text-transform: uppercase; letter-spacing: 1px; font-size: 0.6rem; font-weight: 800;">
                                {{ $log->table_name }} • RECORD #{{ $log->record_id }}
                            </small>
                        </div>
                    </div>
                    @empty
                    <div style="padding: 3rem; text-align: center; color: var(--text-sub);">
                        <i class="fa-solid fa-folder-open fa-3x" style="opacity: 0.1; margin-bottom: 1rem;"></i>
                        <p>Ma jiraan audit logs wali.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.getElementById('tab-history').style.display = 'none';
    document.getElementById('tab-audit').style.display = 'none';
    
    // Deactivate all buttons
    document.getElementById('tab-history-btn').style.borderBottomColor = 'transparent';
    document.getElementById('tab-history-btn').style.color = 'var(--text-sub)';
    document.getElementById('tab-history-btn').style.fontWeight = '700';
    
    document.getElementById('tab-audit-btn').style.borderBottomColor = 'transparent';
    document.getElementById('tab-audit-btn').style.color = 'var(--text-sub)';
    document.getElementById('tab-audit-btn').style.fontWeight = '700';
    
    // Show active tab
    document.getElementById('tab-' + tabName).style.display = 'block';
    
    // Activate button
    const activeBtn = document.getElementById('tab-' + tabName + '-btn');
    activeBtn.style.borderBottomColor = '#3498db';
    activeBtn.style.color = 'var(--sidebar-bg)';
    activeBtn.style.fontWeight = '800';
}
</script>
@endsection
