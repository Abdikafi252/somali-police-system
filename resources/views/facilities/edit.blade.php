@extends('layouts.app')

@section('title', 'Wax ka bedel Xarunta')

@section('content')
<div class="header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: start;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">WAX KA BEDEL XARUNTA</h1>
        <p style="color: var(--text-sub);">Cusboonaysii xogta xarunta iyo maamulkeeda.</p>
    </div>
    <a href="{{ route('facilities.index') }}" style="padding: 0.8rem 1.5rem; background: #fff; color: var(--sidebar-bg); border-radius: 12px; text-decoration: none; font-weight: 700; border: 2px solid var(--border-soft); display: flex; align-items: center; gap: 0.5rem;">
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

<div class="glass-card" style="max-width: 900px; padding: 2.5rem; border-radius: 25px;">
    <form action="{{ route('facilities.update', $facility->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <!-- Name -->
            <div>
                <label for="name" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-building" style="color: #3498db; margin-right: 0.4rem;"></i> Magaca Xarunta <span style="color: red;">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $facility->name) }}" placeholder="Tusaale: Xabsiga Dhexe" 
                    style="width: 100%; padding: 1.1rem; border-radius: 12px; border: 2px solid var(--border-soft); font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 600; background: #f8f9fa; color: #2d3436;" required>
            </div>

            <!-- Type -->
            <div>
                <label for="type" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-tags" style="color: #9b59b6; margin-right: 0.4rem;"></i> Nooca Xarunta <span style="color: red;">*</span>
                </label>
                <div style="position: relative;">
                    <select name="type" id="type" style="width: 100%; padding: 1.1rem; border-radius: 12px; border: 2px solid var(--border-soft); font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 600; appearance: none; background: #f8f9fa; cursor: pointer;" required>
                        <option value="">-- Dooro Nooca --</option>
                        <option value="Station" {{ old('type', $facility->type) == 'Station' ? 'selected' : '' }}>Saldhig (Station)</option>
                        <option value="Checkpoint" {{ old('type', $facility->type) == 'Checkpoint' ? 'selected' : '' }}>Bar-hubinta (Checkpoint)</option>
                        <option value="HQ" {{ old('type', $facility->type) == 'HQ' ? 'selected' : '' }}>Xarun Dhexe (HQ)</option>
                        <option value="Prison" {{ old('type', $facility->type) == 'Prison' ? 'selected' : '' }}>Xabsi (Prison)</option>
                        <option value="Training" {{ old('type', $facility->type) == 'Training' ? 'selected' : '' }}>Tababarka (Training)</option>
                    </select>
                    <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--text-sub);">
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <!-- Location -->
            <div>
                <label for="location" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-location-dot" style="color: #e74c3c; margin-right: 0.4rem;"></i> Goobta / Degmada <span style="color: red;">*</span>
                </label>
                <input type="text" name="location" id="location" value="{{ old('location', $facility->location) }}" placeholder="Tusaale: Degmada Xamar Jajab" 
                    style="width: 100%; padding: 1.1rem; border-radius: 12px; border: 2px solid var(--border-soft); font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 600; background: #f8f9fa; color: #2d3436;" required>
            </div>

            <!-- Security Level -->
            <div>
                <label for="security_level" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-shield-halved" style="color: #27ae60; margin-right: 0.4rem;"></i> Heerka Amniga <span style="color: red;">*</span>
                </label>
                <div style="position: relative;">
                    <select name="security_level" id="security_level" style="width: 100%; padding: 1.1rem; border-radius: 12px; border: 2px solid var(--border-soft); font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 600; appearance: none; background: #f8f9fa; cursor: pointer;" required>
                        <option value="">-- Dooro Heerka --</option>
                        <option value="Low" {{ old('security_level', $facility->security_level) == 'Low' ? 'selected' : '' }}>Hoose (Low)</option>
                        <option value="Medium" {{ old('security_level', $facility->security_level) == 'Medium' ? 'selected' : '' }}>Dhexe (Medium)</option>
                        <option value="High" {{ old('security_level', $facility->security_level) == 'High' ? 'selected' : '' }}>Sare (High)</option>
                    </select>
                    <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--text-sub);">
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 2.5rem;">
            <label for="commander_id" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                <i class="fa-solid fa-user-tie" style="color: #f39c12; margin-right: 0.4rem;"></i> Taliyaha Xarunta
            </label>
            <div style="position: relative;">
                <select name="commander_id" id="commander_id" style="width: 100%; padding: 1.1rem; border-radius: 12px; border: 2px solid var(--border-soft); font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 600; appearance: none; background: #f8f9fa; cursor: pointer;">
                    <option value="">-- Dooro Taliyaha --</option>
                    @foreach($commanders as $commander)
                        <option value="{{ $commander->id }}" {{ old('commander_id', $facility->commander_id) == $commander->id ? 'selected' : '' }}>
                            {{ $commander->name }} ({{ $commander->rank ?? 'SARKAL' }})
                        </option>
                    @endforeach
                </select>
                <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--text-sub);">
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('facilities.index') }}" 
                style="padding: 1rem 2rem; background: #f1f2f6; color: #4b5563; border-radius: 15px; text-decoration: none; font-weight: 800; font-family: 'Outfit'; font-size: 1rem; display: flex; align-items: center; gap: 0.5rem; transition: 0.3s;">
                CIRIIRI (CANCEL)
            </a>
            <button type="submit" style="padding: 1rem 2.5rem; background: linear-gradient(135deg, var(--sidebar-bg), #1e272e); border: none; border-radius: 15px; color: white; font-weight: 900; font-family: 'Outfit'; font-size: 1rem; letter-spacing: 1px; cursor: pointer; display: flex; align-items: center; gap: 0.8rem; box-shadow: 0 10px 20px rgba(0,0,0,0.1); transition: 0.3s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <i class="fa-solid fa-check-circle"></i> KAYDI ISBEDELKA
            </button>
        </div>
    </form>
</div>

<style>
    input:focus, select:focus {
        outline: none;
        border-color: #3498db !important;
        background: #fff !important;
        box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
    }
</style>
@endsection
