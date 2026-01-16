@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Role: {{ $role->name }}</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ $role->name }}" required {{ $role->is_system ? 'readonly' : '' }}>
                </div>

                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control"
                           value="{{ $role->slug }}" required {{ $role->is_system ? 'readonly' : '' }}>
                    <small class="form-text text-muted">Lowercase, no spaces (e.g., "coordinator")</small>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2" {{ $role->is_system ? 'readonly' : '' }}>{{ $role->description }}</textarea>
                </div>

                <div class="form-group">
                    <label>Permissions</label>
                    @foreach($permissions as $module => $modulePermissions)
                    <div class="card mb-2">
                        <div class="card-header">
                            <strong>{{ ucfirst($module) }}</strong>
                        </div>
                        <div class="card-body">
                            @foreach($modulePermissions as $permission)
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                       class="form-check-input" id="perm{{ $permission->id }}"
                                       {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm{{ $permission->id }}">
                                    {{ $permission->name }} <small class="text-muted">({{ $permission->slug }})</small>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                @if(!$role->is_system)
                <button type="submit" class="btn btn-primary">Update Role</button>
                @else
                <div class="alert alert-warning">
                    This is a system role. You can view permissions but cannot modify the role.
                </div>
                @endif
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
