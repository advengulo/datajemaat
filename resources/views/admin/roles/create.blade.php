@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New Role</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.roles.store') }}">
                @csrf

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control"
                           value="{{ old('slug') }}" required>
                    <small class="form-text text-muted">Lowercase, no spaces (e.g., "coordinator")</small>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
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
                                       {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm{{ $permission->id }}">
                                    {{ $permission->name }} <small class="text-muted">({{ $permission->slug }})</small>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary">Create Role</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
