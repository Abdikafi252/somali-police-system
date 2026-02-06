@extends('layouts.app')

@section('title', 'Wax ka bedel Profile-ka')

@section('content')
<div class="header" style="margin-bottom: 2rem;">
    <h1 style="font-weight: 800; color: var(--sidebar-bg); font-family: 'Outfit';">WAX KA BEDEL PROFILE-KAYGA</h1>
    <p style="color: var(--text-sub);">Cusbooneysii magacaaga, email-kaaga iyo sawirkaaga.</p>
</div>

<div class="glass-card" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="display: flex; gap: 2rem; align-items: start; margin-bottom: 2rem;">
            <!-- Image Upload Preview -->
            <div style="width: 150px; text-align: center;">
                <label for="profile_image" style="cursor: pointer; display: block;">
                    <div style="position: relative; width: 120px; height: 120px; margin: 0 auto; border-radius: 50%; overflow: hidden; border: 3px solid var(--border-soft);">
                        @if($user->profile_image)
                            <img id="imagePreview" src="{{ asset('storage/' . $user->profile_image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <img id="imagePreview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.5); color: white; padding: 5px; font-size: 0.8rem;">
                            <i class="fa-solid fa-camera"></i>
                        </div>
                    </div>
                </label>
                <input type="file" name="profile_image" id="profile_image" style="display: none;" onchange="previewImage(this)">
                <p style="font-size: 0.8rem; color: var(--text-sub); margin-top: 0.5rem;">Click image to change</p>
            </div>

            <!-- Basic Info Fields -->
            <div style="flex: 1;">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Magaca Buuxa</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                    @error('name')
                        <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; color: var(--text-sub); margin-bottom: 0.5rem; font-weight: 600;">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-soft); background: rgba(255,255,255,0.5);">
                    @error('email')
                        <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-soft);">
            <button type="submit" class="btn-primary" style="background: var(--sidebar-bg); border: none; padding: 0.8rem 2rem; font-weight: 700;">
                <i class="fa-solid fa-save"></i> Keydi Isbedelka
            </button>
            <a href="{{ route('profile.show') }}" class="btn-primary" style="background: white; color: var(--text-sub); border: 1px solid var(--border-soft); padding: 0.8rem 2rem; font-weight: 600;">
                Ka Noqo
            </a>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
