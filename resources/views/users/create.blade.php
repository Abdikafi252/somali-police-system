@extends('layouts.app')

@section('title', 'Ku dar Isticmaale')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">KU DAR ISTICMAALE CUSUB</h1>
    <p style="color: var(--text-sub);">Buuxi macluumaadka isticmaalaha cusub.</p>
</div>

@if($errors->any())
<div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #dc3545;">
    <strong>Khaladaad jiraan:</strong>
    <ul style="margin: 0.5rem 0 0 1.5rem;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="glass-card">
    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="margin-bottom: 1.5rem; text-align: center;">
            <label for="profile_image" style="cursor: pointer;">
                <div style="width: 100px; height: 100px; border-radius: 50%; background: #f1f2f6; display: inline-flex; align-items: center; justify-content: center; overflow: hidden; border: 2px dashed var(--border-soft);">
                    <i class="fa-solid fa-camera" style="font-size: 2rem; color: var(--text-sub);"></i>
                    <img id="preview" src="#" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                </div>
                <div style="margin-top: 0.5rem; color: var(--accent-blue); font-size: 0.9rem;">Soo Geli Sawir</div>
            </label>
            <input type="file" name="profile_image" id="profile_image" style="display: none;" onchange="previewImage(this)">
        </div>

        <div class="row" style="display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <label class="form-label">Magaca Buuxa <span style="color: red;">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-control">
            </div>

            <div style="flex: 1; min-width: 300px;">
                <label class="form-label">Email <span style="color: red;">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required class="form-control">
            </div>
        </div>

        <div class="row" style="display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <label class="form-label">Password <span style="color: red;">*</span></label>
                <input type="password" name="password" required class="form-control">
            </div>

            <div style="flex: 1; min-width: 300px;">
                <label class="form-label">Doorka (Role) <span style="color: red;">*</span></label>
                <select name="role_id" required class="form-select">
                    <option value="">Dooro doorka...</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row" style="display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <label class="form-label">Saldhigga</label>
                <select name="station_id" class="form-select">
                    <option value="">Dooro saldhigga...</option>
                    @foreach($stations as $station)
                    <option value="{{ $station->id }}" {{ old('station_id') == $station->id ? 'selected' : '' }}>
                        {{ $station->station_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1; min-width: 300px;">
                <label class="form-label">Gobolka</label>
                <select name="region_id" class="form-select">
                    <option value="">Dooro gobolka...</option>
                    <option value="Banadir" {{ old('region_id') == 'Banadir' ? 'selected' : '' }}>Banadir</option>
                    <option value="Jubaland" {{ old('region_id') == 'Jubaland' ? 'selected' : '' }}>Jubaland</option>
                    <option value="Southwest" {{ old('region_id') == 'Southwest' ? 'selected' : '' }}>Southwest</option>
                    <option value="Puntland" {{ old('region_id') == 'Puntland' ? 'selected' : '' }}>Puntland</option>
                    <option value="Galmudug" {{ old('region_id') == 'Galmudug' ? 'selected' : '' }}>Galmudug</option>
                    <option value="Hirshabelle" {{ old('region_id') == 'Hirshabelle' ? 'selected' : '' }}>Hirshabelle</option>
                </select>
            </div>
        </div>

        <div class="row" style="display: flex; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <label class="form-label">Darajada (Rank)</label>
                <select name="rank" class="form-select">
                    <option value="">Dooro darajada...</option>
                    @foreach($ranks as $key => $value)
                        <option value="{{ $key }}" {{ old('rank') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1; min-width: 300px;">
                <label class="form-label">Taariikhda Magacaabista</label>
                <input type="date" name="appointed_date" value="{{ old('appointed_date') }}" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Xaalada <span style="color: red;">*</span></label>
            <select name="status" required class="form-select">
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Firfircoon (Active)</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ma firfircona (Inactive)</option>
            </select>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
            <a href="{{ route('users.index') }}" 
                style="padding: 0.8rem 2rem; background: #e2e8f0; color: #475569; border-radius: 12px; text-decoration: none; font-weight: 600; transition: all 0.3s;"
                onmouseover="this.style.background='#cbd5e1'" onmouseout="this.style.background='#e2e8f0'">
                Jooji
            </a>
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> Kaydi
            </button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('preview').style.display = 'block';
                document.querySelector('.fa-camera').style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
