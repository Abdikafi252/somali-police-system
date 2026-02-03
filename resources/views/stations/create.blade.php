@extends('layouts.app')

@section('title', 'Ku dar Saldhig')

@section('content')
<div class="header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: start;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">KU DAR SALDHIG CUSUB</h1>
        <p style="color: var(--text-sub);">Diiwaangeli saldhig boolis oo cusub nidaamka.</p>
    </div>
    <a href="{{ route('stations.index') }}" style="padding: 0.8rem 1.5rem; background: #fff; color: var(--sidebar-bg); border-radius: 12px; text-decoration: none; font-weight: 700; border: 2px solid var(--border-soft); display: flex; align-items: center; gap: 0.5rem;">
        <i class="fa-solid fa-arrow-left"></i> Ku laabo Liiska
    </a>
</div>

@if($errors->any())
<div style="background: #ffebee; color: #c62828; padding: 1.2rem; border-radius: 15px; margin-bottom: 1.5rem; border-left: 5px solid #ef5350; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
    <strong style="display: block; margin-bottom: 0.5rem; font-weight: 800;"><i class="fa-solid fa-triangle-exclamation"></i> Khaladaad ayaa ka dhex jira foomka:</strong>
    <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.85rem;">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="glass-card" style="max-width: 800px; padding: 2.5rem; border-radius: 25px;">
    <form action="{{ route('stations.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="station_name" class="form-label">
                <i class="fa-solid fa-building-columns" style="color: #3498db; margin-right: 0.4rem;"></i> Magaca Saldhigga <span style="color: red;">*</span>
            </label>
            <input type="text" name="station_name" id="station_name" value="{{ old('station_name') }}" placeholder="Tusaale: Saldhigga Galbeed" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="location" class="form-label">
                <i class="fa-solid fa-location-dot" style="color: #e74c3c; margin-right: 0.4rem;"></i> Goobta / Degmada <span style="color: red;">*</span>
            </label>
            <input type="text" name="location" id="location" value="{{ old('location') }}" placeholder="Tusaale: Degmada Hodan, Muqdisho" class="form-control" required>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2.5rem;">
            <a href="{{ route('stations.index') }}" class="btn-secondary" style="text-decoration: none;">Jooji</a>
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> KAYDI SALDHIGGA
            </button>
        </div>
    </form>
</div>

<style>
    input:focus {
        outline: none;
        border-color: #3498db !important;
        background: #fff !important;
        box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
    }
</style>
@endsection
