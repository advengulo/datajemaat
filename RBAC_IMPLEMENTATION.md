# RBAC Implementation - Phase 1: Foundation Models

## Overview
This document describes the Role-Based Access Control (RBAC) foundation implemented for the Data Jemaat application.

## Database Schema

### Tables Created

#### 1. `roles`
Stores role definitions with scope information.

| Column | Type | Description |
|--------|------|-------------|
| id | bigIncrements | Primary key |
| name | string(100) | Display name of the role |
| slug | string(100) | Unique identifier (superadmin, snk, lingkungan_admin) |
| description | text | Role description |
| scope | enum | 'global' or 'lingkungan' |
| timestamps | - | created_at, updated_at |

**Indexes:** slug, scope

#### 2. `permissions`
Stores permission definitions organized by module.

| Column | Type | Description |
|--------|------|-------------|
| id | bigIncrements | Primary key |
| name | string(100) | Display name |
| slug | string(100) | Unique identifier (module.action) |
| module | string(50) | Module grouping |
| description | text | Permission description |
| timestamps | - | created_at, updated_at |

**Indexes:** slug, module

#### 3. `role_permissions` (Pivot)
Maps permissions to roles.

| Column | Type | Description |
|--------|------|-------------|
| id | bigIncrements | Primary key |
| role_id | unsignedBigInteger | Foreign key to roles |
| permission_id | unsignedBigInteger | Foreign key to permissions |
| timestamps | - | created_at, updated_at |

**Constraints:**
- Foreign keys with cascade delete
- Unique constraint on (role_id, permission_id)

#### 4. `user_roles` (Pivot)
Maps roles to users with optional lingkungan scoping.

| Column | Type | Description |
|--------|------|-------------|
| id | bigIncrements | Primary key |
| user_id | unsignedBigInteger | Foreign key to users |
| role_id | unsignedBigInteger | Foreign key to roles |
| id_lingkungan | unsignedBigInteger (nullable) | Foreign key to master_lingkungan (for scoped roles) |
| timestamps | - | created_at, updated_at |

**Constraints:**
- Foreign keys with cascade delete
- Unique constraint on (user_id, role_id, id_lingkungan)

#### 5. `menus`
Stores hierarchical menu structure.

| Column | Type | Description |
|--------|------|-------------|
| id | bigIncrements | Primary key |
| name | string(100) | Menu display name |
| slug | string(100) | Unique identifier |
| icon | string(50) | Icon class/name |
| route | string(255) | Laravel route name |
| parent_id | unsignedBigInteger (nullable) | Self-referencing foreign key |
| order | integer | Display order |
| is_active | boolean | Active status (default: true) |
| timestamps | - | created_at, updated_at |

**Indexes:** slug, parent_id, (is_active, order)

#### 6. `menu_permissions` (Pivot)
Maps required permissions to menus.

| Column | Type | Description |
|--------|------|-------------|
| id | bigIncrements | Primary key |
| menu_id | unsignedBigInteger | Foreign key to menus |
| permission_id | unsignedBigInteger | Foreign key to permissions |
| timestamps | - | created_at, updated_at |

**Constraints:**
- Foreign keys with cascade delete
- Unique constraint on (menu_id, permission_id)

---

## Models Created

### 1. Role Model (`app/Models/Role.php`)

**Relationships:**
- `users()` - belongsToMany User (through user_roles pivot)
- `permissions()` - belongsToMany Permission (through role_permissions pivot)

**Key Methods:**
- `hasPermission($permissionSlug)` - Check if role has specific permission
- `hasAnyPermission(array $permissions)` - Check if role has any of the permissions
- `hasAllPermissions(array $permissions)` - Check if role has all permissions

**Scopes:**
- `bySlug($slug)` - Filter by slug
- `globalScope()` - Filter global roles
- `lingkunganScope()` - Filter lingkungan-scoped roles

### 2. Permission Model (`app/Models/Permission.php`)

**Relationships:**
- `roles()` - belongsToMany Role (through role_permissions pivot)
- `menus()` - belongsToMany Menu (through menu_permissions pivot)

**Key Methods:**
- `groupedByModule()` - Get all permissions grouped by module

**Scopes:**
- `byModule($module)` - Filter by module
- `bySlug($slug)` - Filter by slug

### 3. Menu Model (`app/Models/Menu.php`)

**Relationships:**
- `parent()` - belongsTo Menu (self-referencing)
- `children()` - hasMany Menu (self-referencing)
- `permissions()` - belongsToMany Permission (through menu_permissions pivot)

**Key Methods:**
- `isAccessibleBy(array $userPermissions)` - Check if user can access this menu
- `getMenuTreeForUser(array $userPermissions)` - Static method to build menu tree for user

**Scopes:**
- `active()` - Filter active menus
- `parents()` - Filter parent menus only
- `children()` - Filter child menus only
- `ordered()` - Order by display order

### 4. User Model (`app/Models/User.php`) - Enhanced

**New Relationships:**
- `roles()` - belongsToMany Role (through user_roles pivot with id_lingkungan)
- `permissions()` - Computed collection of all permissions through roles

**Authorization Methods:**

**Role Checking:**
- `hasRole($roleSlug)` - Check if user has specific role
- `hasAnyRole(array $roles)` - Check if user has any of the roles
- `hasAllRoles(array $roles)` - Check if user has all roles
- `isSuperAdmin()` - Shortcut to check superadmin role
- `isSnk()` - Shortcut to check SNK role
- `isLingkunganAdmin()` - Shortcut to check lingkungan admin role

**Permission Checking:**
- `getPermissionSlugs()` - Get all permission slugs for user
- `hasPermission($permissionSlug)` - Check if user has specific permission
- `hasAnyPermission(array $permissions)` - Check if user has any of the permissions
- `hasAllPermissions(array $permissions)` - Check if user has all permissions

**Lingkungan Access:**
- `getAccessibleLingkunganIds()` - Get array of lingkungan IDs user can access
- `hasAccessToLingkungan($lingkunganId)` - Check if user can access specific lingkungan

**Menu Access:**
- `getMenuTree()` - Get hierarchical menu structure based on user permissions

---

## Default Roles & Permissions

### Roles Defined

| Role | Slug | Scope | Description |
|------|------|-------|-------------|
| Super Admin | `superadmin` | global | Full access with approval rights |
| SNK | `snk` | global | View and edit draft data only |
| Lingkungan Admin | `lingkungan_admin` | lingkungan | Scoped to assigned lingkungan |

### Permission Modules

#### Jemaat Module
- `jemaat.view` - View church members data
- `jemaat.create` - Create new church member
- `jemaat.update` - Update church member data
- `jemaat.delete` - Delete church member
- `jemaat.approve` - Approve church member data changes
- `jemaat.export` - Export church member data

#### Simpatisan Module
- `simpatisan.view` - View sympathizer data
- `simpatisan.create` - Create new sympathizer
- `simpatisan.update` - Update sympathizer data
- `simpatisan.delete` - Delete sympathizer
- `simpatisan.approve` - Approve sympathizer data changes

#### Kartu Jemaat Module
- `kartu_jemaat.view` - View member cards
- `kartu_jemaat.print` - Print member cards

#### Master Module
- `master.lingkungan` - Manage neighborhood master data
- `master.pekerjaan` - Manage job types master data
- `master.pendidikan` - Manage education levels master data

#### Reports Module
- `reports.view` - View various reports
- `grafik.view` - View statistical graphs
- `rekap.view` - View data recaps

#### Admin Module
- `admin.roles` - Manage user roles
- `admin.permissions` - Manage permissions
- `admin.users` - Manage system users
- `admin.menus` - Manage system menus

### Role-Permission Mapping

| Permission | superadmin | snk | lingkungan_admin |
|-----------|------------|-----|------------------|
| jemaat.view | ✓ | ✓ | ✓ (scoped) |
| jemaat.create | ✓ | - | ✓ (draft) |
| jemaat.update | ✓ | ✓ (draft) | ✓ (draft) |
| jemaat.delete | ✓ | - | - |
| jemaat.approve | ✓ | - | - |
| jemaat.export | ✓ | ✓ | - |
| simpatisan.view | ✓ | ✓ | ✓ (scoped) |
| simpatisan.create | ✓ | - | ✓ (draft) |
| simpatisan.update | ✓ | ✓ (draft) | ✓ (draft) |
| simpatisan.delete | ✓ | - | - |
| simpatisan.approve | ✓ | - | - |
| kartu_jemaat.view | ✓ | ✓ | - |
| kartu_jemaat.print | ✓ | ✓ | - |
| master.* | ✓ | - | - |
| reports.view | ✓ | ✓ | ✓ (scoped) |
| grafik.view | ✓ | ✓ | ✓ (scoped) |
| rekap.view | ✓ | ✓ | ✓ (scoped) |
| admin.* | ✓ | - | - |

**Note:**
- **(scoped)** - Data filtered by assigned lingkungan
- **(draft)** - Can only create/edit draft data, not approve

---

## Migration Files

All migrations are timestamped with `2026_01_15_0000XX` prefix for proper ordering:

1. `2026_01_15_000001_create_roles_table.php`
2. `2026_01_15_000002_create_permissions_table.php`
3. `2026_01_15_000003_create_role_permissions_table.php`
4. `2026_01_15_000004_create_user_roles_table.php`
5. `2026_01_15_000005_create_menus_table.php`
6. `2026_01_15_000006_create_menu_permissions_table.php`

## Seeder

**File:** `database/seeds/RbacSeeder.php`

This seeder creates:
- 3 default roles (superadmin, snk, lingkungan_admin)
- 24 permissions across 6 modules
- Role-permission mappings as specified in the architecture

**To run:**
```bash
php artisan db:seed --class=RbacSeeder
```

---

## Usage Examples

### Checking User Permissions

```php
// Check if user has specific permission
if ($user->hasPermission('jemaat.approve')) {
    // Allow approval
}

// Check if user has any of the permissions
if ($user->hasAnyPermission(['jemaat.view', 'simpatisan.view'])) {
    // Show data
}

// Check if user is super admin
if ($user->isSuperAdmin()) {
    // Full access
}
```

### Checking Lingkungan Access

```php
// Get accessible lingkungan IDs
$lingkunganIds = $user->getAccessibleLingkunganIds();

// Filter query by accessible lingkungan
$jemaat = data_jemaat::whereIn('id_lingkungan', $lingkunganIds)->get();

// Check specific lingkungan access
if ($user->hasAccessToLingkungan($lingkunganId)) {
    // Allow access
}
```

### Getting User Menu

```php
// Get hierarchical menu tree based on user permissions
$menuTree = $user->getMenuTree();

// Returns collection of parent menus with nested children
// Only includes menus the user has permission to access
```

### Assigning Roles to Users

```php
// Assign global role (superadmin or snk)
$user->roles()->attach($roleId);

// Assign lingkungan-scoped role
$user->roles()->attach($lingkunganAdminRoleId, [
    'id_lingkungan' => $lingkunganId
]);

// Assign multiple lingkungan to same user
$user->roles()->attach($lingkunganAdminRoleId, [
    'id_lingkungan' => 1
]);
$user->roles()->attach($lingkunganAdminRoleId, [
    'id_lingkungan' => 2
]);
```

---

## Next Steps (Future Phases)

1. **Phase 2: Middleware & Gates**
   - Create middleware for permission checking
   - Define authorization gates
   - Create policy classes for models

2. **Phase 3: Controller Integration**
   - Add authorization checks to controllers
   - Implement data scoping based on lingkungan
   - Add approval workflow logic

3. **Phase 4: UI Integration**
   - Create role management interface
   - Create user management interface with role assignment
   - Implement dynamic menu rendering based on permissions
   - Add permission-based UI element visibility

4. **Phase 5: Audit & Logging**
   - Log all permission changes
   - Track data approval workflow
   - Audit trail for sensitive operations

---

## File Locations

```
app/Models/
├── Role.php
├── Permission.php
├── Menu.php
└── User.php (enhanced)

database/migrations/
├── 2026_01_15_000001_create_roles_table.php
├── 2026_01_15_000002_create_permissions_table.php
├── 2026_01_15_000003_create_role_permissions_table.php
├── 2026_01_15_000004_create_user_roles_table.php
├── 2026_01_15_000005_create_menus_table.php
└── 2026_01_15_000006_create_menu_permissions_table.php

database/seeds/
└── RbacSeeder.php
```

---

## Notes

- The RBAC system is designed to be flexible and extensible
- All models include proper relationships and helper methods
- Lingkungan scoping is built into the user role assignment
- Menu visibility is automatically handled based on permissions
- Draft/approval workflow logic should be implemented in business layer
- All database operations use proper foreign key constraints for data integrity
