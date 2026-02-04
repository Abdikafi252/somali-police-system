@extends('layouts.app')

@section('title', 'Case Details - ' . $case->case_number)

@section('content')
<div class="header-section" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit'; margin-bottom: 0.5rem;">
            {{ $case->case_number }}
        </h1>
        <p style="color: var(--text-sub);">
            Crime: <span style="font-weight: 600;">{{ $case->crime->crime_type ?? 'N/A' }}</span> • 
            Status: <span class="badge {{ strtolower($case->status) }}">{{ $case->status }}</span>
        </p>
    </div>
    <div style="display: flex; gap: 1rem;">
        @if(in_array($case->status, ['Open', 'Assigned']))
        <a href="{{ route('cases.edit', $case) }}" class="btn-primary" style="padding: 0.6rem 1.2rem; font-size: 0.9rem;">
            <i class="fa-solid fa-pen-to-square"></i> Edit Case
        </a>
        @endif
        <a href="{{ route('cases.index') }}" class="btn-secondary" style="padding: 0.6rem 1.2rem; font-size: 0.9rem;">
            Back to List
        </a>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Left Column -->
    <div class="main-column" style="display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Crime Details -->
        <div class="edu-card">
            <h3 style="margin-bottom: 1rem;">Crime Information</h3>
            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border-color);">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <span style="display: block; font-size: 0.8rem; color: #64748b;">Crime Type</span>
                        <span style="font-weight: 600; color: #1e293b;">{{ $case->crime->crime_type ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.8rem; color: #64748b;">Occurred At</span>
                        <span style="font-weight: 600; color: #1e293b;">{{ $case->crime->crime_date ? \Carbon\Carbon::parse($case->crime->crime_date)->format('d M, Y h:i A') : 'N/A' }}</span>
                    </div>
                    <div style="grid-column: span 2;">
                        <span style="display: block; font-size: 0.8rem; color: #64748b;">Location</span>
                        <span style="font-weight: 600; color: #1e293b;">{{ $case->crime->location ?? 'Unknown' }}</span>
                    </div>
                    <div style="grid-column: span 2;">
                        <span style="display: block; font-size: 0.8rem; color: #64748b;">Description</span>
                        <p style="margin-top: 0.5rem; font-size: 0.9rem; line-height: 1.5; color: #334155;">
                            {{ $case->crime->description ?? 'No description provided.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suspects -->
        <div class="edu-card">
            <h3 style="margin-bottom: 1rem;">Suspects Linked</h3>
            @if($case->crime && $case->crime->suspects->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                    @foreach($case->crime->suspects as $suspect)
                    <div style="border: 1px solid var(--border-color); border-radius: 12px; padding: 1rem; display: flex; align-items: center; gap: 1rem;">
                        <img src="{{ $suspect->photo ? asset('storage/' . $suspect->photo) : 'https://ui-avatars.com/api/?name=' . urlencode($suspect->name) }}" 
                             style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $suspect->name }}</div>
                            <div style="font-size: 0.8rem; color: #64748b;">{{ $suspect->age }} yrs • {{ $suspect->gender }}</div>
                            <span style="font-size: 0.7rem; padding: 2px 6px; background: #fee2e2; color: #ef4444; border-radius: 4px; display: inline-block; margin-top: 4px;">
                                {{ $suspect->arrest_status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div style="padding: 1.5rem; text-align: center; color: #94a3b8; background: #f8fafc; border-radius: 12px;">
                    No suspects recorded for this case.
                </div>
            @endif
        </div>

        <!-- Investigation Progress -->
        <div class="edu-card">
            <h3 style="margin-bottom: 1rem;">Investigation Timeline</h3>
            @if($case->logs && $case->logs->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($case->logs as $log)
                    <div style="display: flex; gap: 1rem;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div style="width: 12px; height: 12px; background: var(--accent-lime); border-radius: 50%;"></div>
                            <div style="width: 2px; flex: 1; background: #e2e8f0; margin-top: 4px;"></div>
                        </div>
                        <div style="padding-bottom: 1.5rem;">
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $log->activity_type }}</div>
                            <div style="font-size: 0.85rem; color: #475569; margin-top: 2px;">{{ $log->description }}</div>
                            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 4px;">
                                <i class="fa-regular fa-clock"></i> {{ $log->created_at->diffForHumans() }} 
                                by {{ $log->officer->name ?? 'System' }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div style="padding: 1.5rem; text-align: center; color: #94a3b8; background: #f8fafc; border-radius: 12px;">
                    No investigation logs available yet.
                </div>
            @endif
        </div>

    </div>

    <!-- Right Column -->
    <div class="right-column" style="display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Assigned Officer -->
        <div class="edu-card">
            <h3 style="margin-bottom: 1rem; font-size: 1rem;">Assigned Officer</h3>
            @if($case->assignedOfficer)
            <div style="display: flex; align-items: center; gap: 1rem;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($case->assignedOfficer->name) }}&background=C6F048&color=1C1E26" 
                     style="width: 60px; height: 60px; border-radius: 50%; border: 3px solid #f1f5f9;">
                <div>
                    <div style="font-weight: 700; color: #1e293b;">{{ $case->assignedOfficer->name }}</div>
                    <div style="font-size: 0.85rem; color: #64748b;">Role: {{ $case->assignedOfficer->role->name ?? 'Officer' }}</div>
                    <div style="font-size: 0.8rem; color: #10b981; margin-top: 4px;">
                        <i class="fa-solid fa-circle-check"></i> Active
                    </div>
                </div>
            </div>
            @else
            <div style="background: #fff1f2; color: #be123c; padding: 1rem; border-radius: 8px; font-size: 0.9rem;">
                <i class="fa-solid fa-triangle-exclamation"></i> No officer assigned yet.
            </div>
            @endif
        </div>

        <!-- Case Metadata -->
        <div class="edu-card">
            <h3 style="margin-bottom: 1rem; font-size: 1rem;">Case Metadata</h3>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                <li style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                    <span style="color: #64748b;">Created</span>
                    <span style="font-weight: 600;">{{ $case->created_at->format('d M Y') }}</span>
                </li>
                <li style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                    <span style="color: #64748b;">Last Updated</span>
                    <span style="font-weight: 600;">{{ $case->updated_at->diffForHumans() }}</span>
                </li>
                <li style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                    <span style="color: #64748b;">Priority</span>
                    <span style="font-weight: 600; color: #f59e0b;">High</span>
                </li>
            </ul>
        </div>

        <!-- Documents -->
        
        <div class="edu-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="margin: 0; font-size: 1rem;">Documents/Evidence</h3>
            </div>
            @if($case->crime && $case->crime->evidence && $case->crime->evidence->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 0.8rem;">
                    @foreach($case->crime->evidence as $evidence)
                    <a href="{{ asset('storage/' . $evidence->file_path) }}" target="_blank" style="text-decoration: none; display: flex; align-items: center; gap: 0.8rem; padding: 0.8rem; background: #f8fafc; border-radius: 8px; transition: 0.2s;">
                        <div style="width: 32px; height: 32px; background: #e0f2fe; color: #0284c7; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid {{ $evidence->file_type == 'image' ? 'fa-image' : 'fa-file-pdf' }}"></i>
                        </div>
                        <div style="flex: 1; overflow: hidden;">
                            <div style="font-size: 0.85rem; color: #334155; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Evidence #{{ $evidence->id }}</div>
                            <div style="font-size: 0.75rem; color: #94a3b8;">{{ strtoupper($evidence->file_type) }}</div>
                        </div>
                        <i class="fa-solid fa-download" style="color: #cbd5e1;"></i>
                    </a>
                    @endforeach
                </div>
            @else
                <div style="color: #94a3b8; font-size: 0.85rem; font-style: italic;">No documents uploaded.</div>
            @endif
        </div>

    </div>
</div>

<style>
    .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge.open { background: #dcfce7; color: #166534; }
    .badge.assigned { background: #dbeafe; color: #1e40af; }
    .badge.closed { background: #f1f5f9; color: #475569; }
    
    .edu-card h3 { color: #1e293b; font-size: 1.1rem; font-weight: 700; }
</style>
@endsection
