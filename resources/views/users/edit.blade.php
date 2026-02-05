@extends('layouts.app')

@section('title', 'Cusboonaysii Isticmaale')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">CUSBOONAYSII ISTICMAALE</h1>
    <p style="color: var(--text-sub);">Cusboonaysii macluumaadka isticmaalaha.</p>
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
    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 1.5rem; text-align: center;">
            <label for="profile_image" style="cursor: pointer;">
                <div style="width: 100px; height: 100px; border-radius: 50%; background: #f1f2f6; display: inline-flex; align-items: center; justify-content: center; overflow: hidden; border: 2px dashed var(--border-soft); position: relative;">
                    @if($user->profile_image)
                        <img id="preview" src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
                            <i class="fa-solid fa-camera" style="color: white; font-size: 1.5rem;"></i>
                        </div>
                    @else
                        <i class="fa-solid fa-camera" style="font-size: 2rem; color: var(--text-sub);"></i>
                        <img id="preview" src="#" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                    @endif
                </div>
                <div style="margin-top: 0.5rem; color: var(--accent-blue); font-size: 0.9rem;">Bedel Sawirka</div>
            </label>
            <input type="file" name="profile_image" id="profile_image" style="display: none;" onchange="previewImage(this)">
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--sidebar-bg);">
                    Magaca Buuxa <span style="color: red;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-soft); border-radius: 8px; font-size: 0.95rem;">
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--sidebar-bg);">
                    Email <span style="color: red;">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-soft); border-radius: 8px; font-size: 0.95rem;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--sidebar-bg);">
                    Password Cusub (Ka tag marka aadan rabinin inaad beddesho)
                </label>
                <input type="password" name="password"
                    style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-soft); border-radius: 8px; font-size: 0.95rem;">
                <small style="color: var(--text-sub);">Ka tag marka aadan rabinin inaad password-ka beddesho</small>
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--sidebar-bg);">
                    Doorka (Role) <span style="color: red;">*</span>
                </label>
                <select name="role_id" required
                    style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-soft); border-radius: 8px; font-size: 0.95rem;">
                    <option value="">Dooro doorka...</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--sidebar-bg);">
                    Saldhigga
                </label>
                <select name="station_id"
                    style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-soft); border-radius: 8px; font-size: 0.95rem;">
                    <option value="">Dooro saldhigga...</option>
                    @foreach($stations as $station)
                    <option value="{{ $station->id }}" {{ old('station_id', $user->station_id) == $station->id ? 'selected' : '' }}>
                        {{ $station->station_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--sidebar-bg);">
                    Gobolka
                </label>
                <select name="region_id"
                    style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-soft); border-radius: 8px; font-size: 0.95rem;">
                    <option value="">Dooro gobolka...</option>
                    <option value="Banadir" {{ old('region_id', $user->region_id) == 'Banadir' ? 'selected' : '' }}>Banadir</option>
                    <option value="Jubaland" {{ old('region_id', $user->region_id) == 'Jubaland' ? 'selected' : '' }}>Jubaland</option>
                    <option value="Southwest" {{ old('region_id', $user->region_id) == 'Southwest' ? 'selected' : '' }}>Southwest</option>
                    <option value="Puntland" {{ old('region_id', $user->region_id) == 'Puntland' ? 'selected' : '' }}>Puntland</option>
                    <option value="Galmudug" {{ old('region_id', $user->region_id) == 'Galmudug' ? 'selected' : '' }}>Galmudug</option>
                    <option value="Hirshabelle" {{ old('region_id', $user->region_id) == 'Hirshabelle' ? 'selected' : '' }}>Hirshabelle</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--sidebar-bg);">
                    Darajada (Rank)
                </label>
                <select name="rank"
                    style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-soft); border-radius: 8px; font-size: 0.95rem;">
                    <option value="">Dooro darajada...</option>
                    @foreach($ranks as $key => $value)
                        <option value="{{ $key }}" {{ old('rank', $user->rank) == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--sidebar-bg);">
                    Taariikhda Magacaabista
                </label>
                <input type="date" name="appointed_date" value="{{ old('appointed_date', $user->appointed_date) }}"
                    style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-soft); border-radius: 8px; font-size: 0.95rem;">
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--sidebar-bg);">
                Xaalada <span style="color: red;">*</span>
            </label>
            <select name="status" required
                style="width: 100%; padding: 0.8rem; border: 1px solid var(--border-soft); border-radius: 8px; font-size: 0.95rem;">
                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Firfircoon (Active)</option>
                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Ma firfircona (Inactive)</option>
            </select>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <a href="{{ route('users.index') }}" 
                style="padding: 0.8rem 1.5rem; background: #e9ecef; color: #495057; border-radius: 8px; text-decoration: none; font-weight: 600;">
                Jooji
            </a>
            <button type="submit" class="btn-primary" style="padding: 0.8rem 1.5rem; border: none; cursor: pointer;">
                <i class="fa-solid fa-save"></i> Cusboonaysii
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
                var preview = document.getElementById('preview');
                preview.src = e.target.result;
                preview.style.display = 'block';
                
                // If there was an icon showing, hide it (logic depends on initial state, but this works for basic preview)
                var icon = document.querySelector('.fa-camera');
                if(icon && icon.parentElement.querySelector('img').style.display === 'none') {
                    icon.style.display = 'none';
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
