@extends('layouts.app')

@section('title', 'Xilsaar Askari')

@section('content')
<div class="header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: start;">
    <div>
        <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">XILSAAR ASKARI SHAQO</h1>
        <p style="color: var(--text-sub);">U xilsaar mid ka mid ah askarta shaqo ama waajibaad qaran.</p>
    </div>
    <a href="{{ route('deployments.index') }}" style="padding: 0.8rem 1.5rem; background: #fff; color: var(--sidebar-bg); border-radius: 12px; text-decoration: none; font-weight: 700; border: 2px solid var(--border-soft); display: flex; align-items: center; gap: 0.5rem;">
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
    <form action="{{ route('deployments.store') }}" method="POST">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <!-- Officer Selection -->
            <div>
                <label for="user_id" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-user-shield" style="color: #3498db; margin-right: 0.4rem;"></i> Dooro Askariga
                </label>
                <div style="position: relative;">
                    <select name="user_id" id="user_id" style="width: 100%; padding: 1rem 1.2rem; border-radius: 12px; border: 2px solid var(--border-soft); font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 600; appearance: none; background: #f8f9fa; cursor: pointer; color: #2d3436;" required>
                        <option value="">-- Dooro Askariga --</option>
                        @foreach($officers as $officer)
                            <option value="{{ $officer->id }}">{{ $officer->name }} ({{ $officer->rank ?? 'ASKARI' }})</option>
                        @endforeach
                    </select>
                    <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--text-sub);">
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                </div>
            </div>

            <!-- Facility Selection -->
            <div>
                <label for="facility_id" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-building-shield" style="color: #27ae60; margin-right: 0.4rem;"></i> Dooro Xarunta (Facility)
                </label>
                <div style="position: relative;">
                    <select name="facility_id" id="facility_id" style="width: 100%; padding: 1rem 1.2rem; border-radius: 12px; border: 2px solid var(--border-soft); font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 600; appearance: none; background: #f8f9fa; cursor: pointer; color: #2d3436;" required>
                        <option value="">-- Dooro Xarunta --</option>
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}">{{ $facility->name }} ({{ $facility->type }})</option>
                        @endforeach
                    </select>
                    <div style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--text-sub);">
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <!-- Duty Type -->
            <div>
                <label for="duty_type" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-briefcase" style="color: #8e44ad; margin-right: 0.4rem;"></i> Nooca Shaqada
                </label>
                <input type="text" name="duty_type" id="duty_type" placeholder="Tusaale: Ilaalada Gate-ka, Roondayn, iwm" 
                    style="width: 100%; padding: 1rem 1.2rem; border-radius: 12px; border: 2px solid var(--border-soft); font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 600; background: #f8f9fa; color: #2d3436;" required>
            </div>

            <!-- Shift Selection -->
            <div>
                <label for="shift" style="display: block; font-weight: 800; color: var(--sidebar-bg); margin-bottom: 0.8rem; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-clock" style="color: #f39c12; margin-right: 0.4rem;"></i> Dooro Shift-iga
                </label>
                <div style="display: flex; gap: 1rem;">
                    <label style="flex: 1; position: relative;">
                        <input type="radio" name="shift" value="Maalin" style="display: none;" checked>
                        <div class="shift-option" style="padding: 1rem; border: 2px solid var(--border-soft); border-radius: 12px; text-align: center; cursor: pointer; transition: 0.3s; font-weight: 800; font-size: 0.85rem;">
                            <i class="fa-solid fa-sun" style="display: block; font-size: 1.2rem; margin-bottom: 0.4rem;"></i> MAALIN (DAY)
                        </div>
                    </label>
                    <label style="flex: 1; position: relative;">
                        <input type="radio" name="shift" value="Habeen" style="display: none;">
                        <div class="shift-option" style="padding: 1rem; border: 2px solid var(--border-soft); border-radius: 12px; text-align: center; cursor: pointer; transition: 0.3s; font-weight: 800; font-size: 0.85rem;">
                            <i class="fa-solid fa-moon" style="display: block; font-size: 1.2rem; margin-bottom: 0.4rem;"></i> HABEEN (NIGHT)
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div style="margin-top: 3rem;">
            <button type="submit" style="width: 100%; padding: 1.2rem; background: linear-gradient(135deg, var(--sidebar-bg), #1e272e); border: none; border-radius: 15px; color: white; font-weight: 900; font-family: 'Outfit'; font-size: 1.1rem; letter-spacing: 1px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.8rem; box-shadow: 0 10px 20px rgba(0,0,0,0.1); transition: 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)'">
                <i class="fa-solid fa-paper-plane"></i> XILSAAR SHAQADA (DEPLOY ASKAR)
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
    
    input[type="radio"]:checked + .shift-option {
        background: var(--sidebar-bg);
        color: white;
        border-color: var(--sidebar-bg);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .shift-option:hover {
        background: #f1f2f6;
    }
    
    input[type="radio"]:checked + .shift-option:hover {
        background: var(--sidebar-bg);
    }
</style>
@endsection
