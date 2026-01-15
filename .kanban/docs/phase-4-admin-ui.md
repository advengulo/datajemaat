# Phase 4: Admin UI Documentation

## Overview
Build administrative interfaces for managing roles, users, permissions, and draft approvals.

---

## Admin Menu Structure

### Sidebar Navigation
```
Admin (superadmin only)
├── Manage Users
├── Manage Roles
├── Pending Approvals [Badge: count]
└── System Logs (future)
```

---

## User Management Interface

### Features
- List all users with roles
- Assign/remove roles
- Assign lingkungan to lingkungan_admin users
- Activate/deactivate users
- Password reset functionality

### UserManagementController
**File:** `app/Http/Controllers/Admin/UserManagementController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\master_lingkungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadmin');
    }

    public function index()
    {
        $users = User::with('roles')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $lingkungans = master_lingkungan::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        $userLingkungans = $user->lingkungans->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'lingkungans', 'userRoles', 'userLingkungans'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
            'lingkungans' => 'nullable|array',
            'lingkungans.*' => 'exists:master_lingkungans,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Sync roles
        $user->roles()->sync($request->roles);

        // Sync lingkungans (only for lingkungan_admin)
        $hasLingkunganRole = Role::whereIn('id', $request->roles)
            ->where('slug', 'lingkungan_admin')
            ->exists();

        if ($hasLingkunganRole && $request->has('lingkungans')) {
            $user->lingkungans()->sync($request->lingkungans);
        } else {
            $user->lingkungans()->detach();
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()
            ->with('success', 'Password reset successfully.');
    }
}
```

---

### View: Users Index
**File:** `resources/views/admin/users/index.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Users</h2>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @foreach($user->roles as $role)
                    <span class="badge badge-primary">{{ $role->name }}</span>
                    @endforeach
                </td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>
@endsection
```

---

### View: Edit User
**File:** `resources/views/admin/users/edit.blade.php`

```blade
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

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
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

    <hr>

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
```

---

## Role Management Interface

### RoleController
**File:** `app/Http/Controllers/Admin/RoleController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:superadmin');
    }

    public function index()
    {
        $roles = Role::withCount('users', 'permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy('module');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:roles',
            'slug' => 'required|string|max:50|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'is_system' => false,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy('module');
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->is_system) {
            return redirect()->back()
                ->with('error', 'Cannot modify system roles.');
        }

        $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'slug' => 'required|string|max:50|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return redirect()->back()
                ->with('error', 'Cannot delete system roles.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete role with assigned users.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
```

---

### View: Roles Index
**File:** `resources/views/admin/roles/index.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Roles</h2>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Create New Role</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Description</th>
                <th>Users</th>
                <th>Permissions</th>
                <th>System</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td><code>{{ $role->slug }}</code></td>
                <td>{{ $role->description }}</td>
                <td>{{ $role->users_count }}</td>
                <td>{{ $role->permissions_count }}</td>
                <td>
                    @if($role->is_system)
                    <span class="badge badge-warning">System</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-primary">Edit</a>

                    @if(!$role->is_system)
                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
```

---

### View: Create/Edit Role
**File:** `resources/views/admin/roles/create.blade.php` and `edit.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($role) ? 'Edit Role: ' . $role->name : 'Create New Role' }}</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ isset($role) ? route('admin.roles.update', $role) : route('admin.roles.store') }}">
        @csrf
        @if(isset($role))
        @method('PUT')
        @endif

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control"
                   value="{{ $role->name ?? old('name') }}" required>
        </div>

        <div class="form-group">
            <label>Slug</label>
            <input type="text" name="slug" class="form-control"
                   value="{{ $role->slug ?? old('slug') }}" required>
            <small class="form-text text-muted">Lowercase, no spaces (e.g., "coordinator")</small>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="2">{{ $role->description ?? old('description') }}</textarea>
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
                               {{ isset($rolePermissions) && in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                        <label class="form-check-label" for="perm{{ $permission->id }}">
                            {{ $permission->name }} <small class="text-muted">({{ $permission->slug }})</small>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($role) ? 'Update' : 'Create' }} Role</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
```

---

## Initial Superadmin Seeder

### SuperAdminUserSeeder
**File:** `database/seeders/SuperAdminUserSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminUserSeeder extends Seeder
{
    public function run()
    {
        $superadminRole = Role::where('slug', 'superadmin')->first();

        if (!$superadminRole) {
            $this->command->error('Superadmin role not found. Run RolesSeeder first.');
            return;
        }

        $user = User::firstOrCreate(
            ['email' => 'admin@datajemaat.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // Change this!
            ]
        );

        $user->roles()->syncWithoutDetaching([$superadminRole->id]);

        $this->command->info('Superadmin user created: admin@datajemaat.com / password');
    }
}
```

---

## Routes for Admin UI

### File: `routes/web.php`

```php
Route::prefix('admin')->middleware(['auth', 'role:superadmin'])->group(function () {
    // User management
    Route::get('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])
        ->name('admin.users.index');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])
        ->name('admin.users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])
        ->name('admin.users.update');
    Route::post('/users/{user}/reset-password', [App\Http\Controllers\Admin\UserManagementController::class, 'resetPassword'])
        ->name('admin.users.reset-password');

    // Role management
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);

    // Draft approvals
    Route::get('/drafts/pending', [App\Http\Controllers\Admin\ApprovalController::class, 'pending'])
        ->name('admin.drafts.pending');
    Route::get('/drafts/{draft}/review', [App\Http\Controllers\Admin\ApprovalController::class, 'review'])
        ->name('admin.drafts.review');
    Route::post('/drafts/{draft}/approve', [App\Http\Controllers\Admin\ApprovalController::class, 'approve'])
        ->name('admin.drafts.approve');
    Route::post('/drafts/{draft}/reject', [App\Http\Controllers\Admin\ApprovalController::class, 'reject'])
        ->name('admin.drafts.reject');
    Route::post('/drafts/{draft}/request-revision', [App\Http\Controllers\Admin\ApprovalController::class, 'requestRevision'])
        ->name('admin.drafts.request-revision');
});
```
