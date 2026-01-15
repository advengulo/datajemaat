# Phase 1: RBAC Foundation Documentation

## Overview
Establish the core Role-Based Access Control system with database tables, models, traits, and initial data seeding.

---

## Role Model

| Role | Slug | Description | Scope |
|------|------|-------------|-------|
| Super Admin | `superadmin` | Full access, approval rights | Global |
| SNK | `snk` | View + edit (draft only) | Global |
| Lingkungan Admin | `lingkungan_admin` | Scoped to assigned lingkungan | `id_lingkungan` filtered |

---

## Permission Structure

### Module-based Permissions
```
jemaat.view, jemaat.create, jemaat.update, jemaat.delete, jemaat.approve, jemaat.export
simpatisan.view, simpatisan.create, simpatisan.update, simpatisan.delete, simpatisan.approve
kartu_jemaat.view, kartu_jemaat.print
master.lingkungan, master.pekerjaan, master.pendidikan
reports.view, grafik.view, rekap.view
admin.roles, admin.permissions, admin.users, admin.menus
```

---

## Role-Permission Mapping

| Permission | superadmin | snk | lingkungan_admin |
|------------|------------|-----|------------------|
| jemaat.view | ✓ | ✓ | ✓ (scoped) |
| jemaat.create | ✓ | - | ✓ (draft) |
| jemaat.update | ✓ | ✓ (draft) | ✓ (draft) |
| jemaat.approve | ✓ | - | - |
| simpatisan.view | ✓ | ✓ | ✓ (scoped) |
| simpatisan.update | ✓ | ✓ (draft) | ✓ (draft) |
| kartu_jemaat.view | ✓ | ✓ | - |
| admin.* | ✓ | - | - |

---

## Database Schema

### Table: `roles`
```sql
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    slug VARCHAR(50) NOT NULL UNIQUE,
    description TEXT NULL,
    is_system BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Columns:**
- `is_system`: Prevents deletion of core roles (superadmin, snk, lingkungan_admin)

---

### Table: `permissions`
```sql
CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    module VARCHAR(50) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Columns:**
- `module`: Groups permissions (e.g., "jemaat", "simpatisan", "admin")
- Used for UI organization and bulk permission management

---

### Table: `role_permissions`
```sql
CREATE TABLE role_permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    UNIQUE KEY (role_id, permission_id)
);
```

---

### Table: `user_roles`
```sql
CREATE TABLE user_roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    role_id BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    UNIQUE KEY (user_id, role_id)
);
```

**Design Note:** Many-to-many allows multiple roles per user if needed in future.

---

### Table: `user_lingkungans`
```sql
CREATE TABLE user_lingkungans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    lingkungan_id BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lingkungan_id) REFERENCES master_lingkungans(id) ON DELETE CASCADE,
    UNIQUE KEY (user_id, lingkungan_id)
);
```

**Purpose:** Scopes `lingkungan_admin` users to specific lingkungan(s).

---

### Table: `menus`
```sql
CREATE TABLE menus (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id BIGINT UNSIGNED NULL,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) NULL,
    route_name VARCHAR(100) NULL,
    order_index INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (parent_id) REFERENCES menus(id) ON DELETE CASCADE
);
```

**Columns:**
- `parent_id`: For nested menus (NULL = top-level menu)
- `order_index`: Display order in sidebar
- `is_active`: Allows temporary menu hiding without deletion

---

### Table: `menu_permissions`
```sql
CREATE TABLE menu_permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    menu_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
    UNIQUE KEY (menu_id, permission_id)
);
```

**Logic:** User sees menu if they have ANY of the linked permissions.

---

## Model Relationships

### Role Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_system'];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
}
```

### Permission Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'slug', 'module', 'description'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_permissions');
    }
}
```

### Menu Model
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['parent_id', 'name', 'slug', 'icon', 'route_name', 'order_index', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order_index');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'menu_permissions');
    }
}
```

---

## Traits

### HasRoles Trait
```php
<?php

namespace App\Traits;

use App\Models\Role;

trait HasRoles
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            return $this->roles()->where('slug', $roles)->exists();
        }

        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    public function assignRole(string|int|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        } elseif (is_int($role)) {
            $role = Role::findOrFail($role);
        }

        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    public function removeRole(string|int|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        } elseif (is_int($role)) {
            $role = Role::findOrFail($role);
        }

        $this->roles()->detach($role->id);
    }
}
```

### HasPermissions Trait
```php
<?php

namespace App\Traits;

use App\Models\Permission;

trait HasPermissions
{
    public function permissions()
    {
        return $this->hasManyThrough(
            Permission::class,
            Role::class,
            'user_roles.user_id',
            'role_permissions.permission_id',
            'id',
            'user_roles.role_id'
        );
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('slug', $permission);
            })
            ->exists();
    }

    public function hasAnyPermission(array $permissions): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissions) {
                $query->whereIn('slug', $permissions);
            })
            ->exists();
    }

    public function getAllPermissions()
    {
        return Permission::whereHas('roles', function ($query) {
            $query->whereIn('role_id', $this->roles->pluck('id'));
        })->get();
    }
}
```

### HasLingkunganScope Trait
```php
<?php

namespace App\Traits;

use App\Models\master_lingkungan;

trait HasLingkunganScope
{
    public function lingkungans()
    {
        return $this->belongsToMany(
            master_lingkungan::class,
            'user_lingkungans',
            'user_id',
            'lingkungan_id'
        );
    }

    public function assignLingkungan(int $lingkunganId): void
    {
        $this->lingkungans()->syncWithoutDetaching([$lingkunganId]);
    }

    public function removeLingkungan(int $lingkunganId): void
    {
        $this->lingkungans()->detach($lingkunganId);
    }

    public function canAccessLingkungan(int $lingkunganId): bool
    {
        if ($this->hasRole('superadmin')) {
            return true;
        }

        if ($this->hasRole('lingkungan_admin')) {
            return $this->lingkungans()->where('id', $lingkunganId)->exists();
        }

        return true; // SNK and other roles have global access
    }
}
```

---

## Updated User Model
```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasRoles;
use App\Traits\HasPermissions;
use App\Traits\HasLingkunganScope;

class User extends Authenticatable
{
    use HasRoles, HasPermissions, HasLingkunganScope;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
```

---

## Seeder Data

### RolesSeeder
```php
[
    ['name' => 'Super Admin', 'slug' => 'superadmin', 'description' => 'Full system access', 'is_system' => true],
    ['name' => 'SNK', 'slug' => 'snk', 'description' => 'View and edit with approval', 'is_system' => true],
    ['name' => 'Lingkungan Admin', 'slug' => 'lingkungan_admin', 'description' => 'Scoped to assigned lingkungan', 'is_system' => true],
]
```

### PermissionsSeeder (sample)
```php
// Jemaat permissions
['name' => 'View Jemaat', 'slug' => 'jemaat.view', 'module' => 'jemaat'],
['name' => 'Create Jemaat', 'slug' => 'jemaat.create', 'module' => 'jemaat'],
['name' => 'Update Jemaat', 'slug' => 'jemaat.update', 'module' => 'jemaat'],
['name' => 'Delete Jemaat', 'slug' => 'jemaat.delete', 'module' => 'jemaat'],
['name' => 'Approve Jemaat', 'slug' => 'jemaat.approve', 'module' => 'jemaat'],
['name' => 'Export Jemaat', 'slug' => 'jemaat.export', 'module' => 'jemaat'],

// Admin permissions
['name' => 'Manage Roles', 'slug' => 'admin.roles', 'module' => 'admin'],
['name' => 'Manage Users', 'slug' => 'admin.users', 'module' => 'admin'],
['name' => 'Manage Permissions', 'slug' => 'admin.permissions', 'module' => 'admin'],
```

### MenusSeeder (sample)
```php
// Top-level menu
['id' => 1, 'parent_id' => null, 'name' => 'Jemaat', 'slug' => 'jemaat', 'icon' => 'fas fa-user-friends', 'route_name' => null, 'order_index' => 1],

// Submenu
['id' => 2, 'parent_id' => 1, 'name' => 'Data Jemaat', 'slug' => 'jemaat.data', 'icon' => null, 'route_name' => 'data-jemaat.index', 'order_index' => 1],
['id' => 3, 'parent_id' => 1, 'name' => 'Tambah Jemaat', 'slug' => 'jemaat.create', 'icon' => null, 'route_name' => 'tambah-jemaat.index', 'order_index' => 2],
```
