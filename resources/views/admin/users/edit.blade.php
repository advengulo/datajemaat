@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit User: {{ $user->name }}</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                </div>

                <div class="form-group">
                    <label>Roles</label>
                    @foreach($roles as $role)
                    <div class="form-check">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                               class="form-check-input role-checkbox"
                               id="role{{ $role->id }}"
                               {{ in_array($role->id, $userRoles) ? 'checked' : '' }}
                               data-slug="{{ $role->slug }}">
                        <label class="form-check-label" for="role{{ $role->id }}">
                            {{ $role->name }} <small class="text-muted">({{ $role->description }})</small>
                        </label>
                    </div>
                    @endforeach
                </div>

                <div class="form-group" id="lingkungan-group" style="display: none;">
                    <label>Assigned Lingkungan (for Lingkungan Admin only)</label>
                    @foreach($lingkungans as $lingkungan)
                    <div class="form-check">
                        <input type="checkbox" name="lingkungans[]" value="{{ $lingkungan->id }}"
                               class="form-check-input" id="lingkungan{{ $lingkungan->id }}"
                               {{ in_array($lingkungan->id, $userLingkungans) ? 'checked' : '' }}>
                        <label class="form-check-label" for="lingkungan{{ $lingkungan->id }}">
                            {{ $lingkungan->lingkungan }}
                        </label>
                    </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <hr>

    <div class="card">
        <div class="card-body">
            <h4>Reset Password</h4>
            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                @csrf
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning">Reset Password</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleCheckboxes = document.querySelectorAll('.role-checkbox');
    const lingkunganGroup = document.getElementById('lingkungan-group');

    function toggleLingkunganSection() {
        const hasLingkunganRole = Array.from(roleCheckboxes).some(cb =>
            cb.checked && cb.dataset.slug === 'lingkungan_admin'
        );
        lingkunganGroup.style.display = hasLingkunganRole ? 'block' : 'none';
    }

    roleCheckboxes.forEach(cb => cb.addEventListener('change', toggleLingkunganSection));
    toggleLingkunganSection(); // Initial check
});
</script>
@endsection
