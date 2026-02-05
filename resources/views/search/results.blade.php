@extends('layouts.app')

@section('title', 'Natiijada Raadinta: ' . $query)

@section('css')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.95);
        --glass-border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .search-container {
        padding: 0 0 2rem 0;
    }

    .results-header {
        margin-bottom: 2rem;
    }

    .results-header h1 {
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        color: #1e293b;
        font-size: 1.8rem;
    }

    .results-header p {
        color: #64748b;
        font-size: 1.1rem;
    }

    .search-section {
        margin-bottom: 2.5rem;
    }

    .section-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: #1e293b;
        font-size: 1.3rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .result-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .result-card {
        background: var(--glass-bg);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        padding: 1.5rem;
        transition: transform 0.2s;
        text-decoration: none;
        color: inherit;
        display: block;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .result-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-color: #3498db;
    }

    .result-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .result-title {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .result-subtitle {
        color: #64748b;
        font-size: 0.9rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 20px;
        border: 2px dashed #cbd5e1;
    }
</style>
@endsection

@section('content')
<div class="search-container">
    <div class="results-header">
        <h1>Natiijada Raadinta</h1>
        <p>Waxaan raadinay: <strong>"{{ $query }}"</strong></p>
    </div>

    @if($cases->isEmpty() && $suspects->isEmpty() && $crimes->isEmpty() && $officers->isEmpty())
        <div class="glass-card" style="padding: 4rem 2rem; text-align: center; border: 2px dashed #e2e8f0; background: rgba(255,255,255,0.5);">
            <div style="width: 100px; height: 100px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fa-solid fa-cloud-moon" style="font-size: 3rem; color: #cbd5e1;"></i>
            </div>
            <h3 style="font-family: 'Outfit'; font-weight: 800; color: #1e293b; font-size: 1.5rem; margin-bottom: 0.5rem;">Waxba Lama Helin (No Results)</h3>
            <p style="color: #64748b; font-size: 1rem; max-width: 400px; margin: 0 auto 2rem;">
                Ma jiro xog ku haboon <strong>"{{ $query }}"</strong>. Fadlan isku day inaad raadiso Magac, ID, ama Lambarka Kiiska si kale.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="{{ route('dashboard') }}" class="btn-primary" style="text-decoration: none;">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <button onclick="document.querySelector('.search-bar input').focus()" class="btn-secondary" style="background: #fff; border: 1px solid #e2e8f0;">
                    <i class="fa-solid fa-rotate-left"></i> Dib u raadi
                </button>
            </div>
        </div>
    @else

        <!-- Cases Results -->
        @if($cases->isNotEmpty())
        <div class="search-section">
            <div class="section-title">
                <i class="fa-solid fa-briefcase" style="color: #3498db;"></i>
                Kiisaska ({{ $cases->count() }})
            </div>
            <div class="result-grid">
                @foreach($cases as $case)
                <a href="{{ route('cases.show', $case->id) }}" class="result-card">
                    <div class="result-icon" style="background: rgba(52, 152, 219, 0.1); color: #3498db;">
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                    <div class="result-title">{{ $case->title }}</div>
                    <div class="result-subtitle">
                        <span style="font-weight: 600;">{{ $case->case_number }}</span> • {{ $case->status }}
                    </div>
                    @if($case->description)
                        <p style="margin: 0.5rem 0 0; font-size: 0.85rem; color: #94a3b8; line-height: 1.4;">
                            {{ Str::limit($case->description, 60) }}
                        </p>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Suspects Results -->
        @if($suspects->isNotEmpty())
        <div class="search-section">
            <div class="section-title">
                <i class="fa-solid fa-users-viewfinder" style="color: #e74c3c;"></i>
                Eedaysanayaasha ({{ $suspects->count() }})
            </div>
            <div class="result-grid">
                @foreach($suspects as $suspect)
                <a href="{{ route('suspects.show', $suspect->id) }}" class="result-card">
                    <div class="result-icon" style="background: rgba(231, 76, 60, 0.1); color: #e74c3c;">
                        <i class="fa-solid fa-user-secret"></i>
                    </div>
                    <div class="result-title">{{ $suspect->name }}</div>
                    <div class="result-subtitle">
                        ID: {{ $suspect->national_id ?? 'N/A' }} • {{ $suspect->gender ?? '' }}
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Crimes Results -->
        @if($crimes->isNotEmpty())
        <div class="search-section">
            <div class="section-title">
                <i class="fa-solid fa-file-invoice" style="color: #f1c40f;"></i>
                Dambiyada ({{ $crimes->count() }})
            </div>
            <div class="result-grid">
                @foreach($crimes as $crime)
                <a href="{{ route('crimes.show', $crime->id) }}" class="result-card">
                    <div class="result-icon" style="background: rgba(241, 196, 15, 0.1); color: #f1c40f;">
                        <i class="fa-solid fa-handcuffs"></i>
                    </div>
                    <div class="result-title">{{ $crime->crime_type }}</div>
                    <div class="result-subtitle">
                        {{ $crime->location }} • {{ $crime->created_at->format('d M, Y') }}
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Officers Results -->
        @if($officers->isNotEmpty())
        <div class="search-section">
            <div class="section-title">
                <i class="fa-solid fa-user-shield" style="color: #27ae60;"></i>
                Ciidanka ({{ $officers->count() }})
            </div>
            <div class="result-grid">
                @foreach($officers as $officer)
                <a href="{{ route('users.show', $officer->id) }}" class="result-card">
                    <div class="result-icon" style="background: rgba(46, 204, 113, 0.1); color: #2ecc71;">
                        @if($officer->profile_image)
                            <img src="{{ asset('storage/' . $officer->profile_image) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                        @else
                            <i class="fa-solid fa-user"></i>
                        @endif
                    </div>
                    <div class="result-title">{{ $officer->name }}</div>
                    <div class="result-subtitle">
                        {{ $officer->rank }} • {{ $officer->station->station_name ?? 'N/A' }}
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    @endif
</div>
@endsection
