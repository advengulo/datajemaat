# Phase 1: RBAC Foundation Tasks

## Migrations

### Create Core RBAC Tables
- [x] Create migration: `create_roles_table`
- [x] Create migration: `create_permissions_table`
- [x] Create migration: `create_role_permissions_table`
- [x] Create migration: `create_user_roles_table`
- [x] Create migration: `create_user_lingkungans_table`
- [x] Create migration: `create_menus_table`
- [x] Create migration: `create_menu_permissions_table`

---

## Models

### Create RBAC Models
- [x] Create model: `app/Models/Role.php`
  - [x] Define fillable fields
  - [x] Add casts for `is_system`
  - [x] Add relationships: `permissions()`, `users()`
- [x] Create model: `app/Models/Permission.php`
  - [x] Define fillable fields
  - [x] Add relationships: `roles()`, `menus()`
- [x] Create model: `app/Models/Menu.php`
  - [x] Define fillable fields
  - [x] Add casts for `is_active`, `order_index`
  - [x] Add relationships: `parent()`, `children()`, `permissions()`

---

## Traits

### Create Authorization Traits
- [x] Create trait: `app/Traits/HasRoles.php`
  - [x] `roles()` relationship
  - [x] `hasRole(string|array $roles)` method
  - [x] `assignRole()` method
  - [x] `removeRole()` method
- [x] Create trait: `app/Traits/HasPermissions.php`
  - [x] `permissions()` relationship
  - [x] `hasPermission(string $permission)` method
  - [x] `hasAnyPermission(array $permissions)` method
  - [x] `getAllPermissions()` method
- [x] Create trait: `app/Traits/HasLingkunganScope.php`
  - [x] `lingkungans()` relationship
  - [x] `assignLingkungan(int $id)` method
  - [x] `removeLingkungan(int $id)` method
  - [x] `canAccessLingkungan(int $id)` method

---

## Update User Model
- [x] Add `HasRoles` trait to `app/Models/User.php`
- [x] Add `HasPermissions` trait to `app/Models/User.php`
- [x] Add `HasLingkunganScope` trait to `app/Models/User.php`

---

## Seeders

### Create Data Seeders
- [x] Create seeder: `database/seeders/RolesSeeder.php`
  - [x] Seed 3 system roles (superadmin, snk, lingkungan_admin)
- [x] Create seeder: `database/seeders/PermissionsSeeder.php`
  - [x] Seed jemaat permissions (view, create, update, delete, approve, export)
  - [x] Seed simpatisan permissions
  - [x] Seed kartu_jemaat permissions
  - [x] Seed master data permissions
  - [x] Seed reports permissions
  - [x] Seed admin permissions
- [x] Create seeder: `database/seeders/RolePermissionsSeeder.php`
  - [x] Assign permissions to superadmin
  - [x] Assign permissions to snk
  - [x] Assign permissions to lingkungan_admin
- [x] Create seeder: `database/seeders/MenusSeeder.php`
  - [x] Seed top-level menus (Jemaat, Simpatisan, Kartu Jemaat, etc.)
  - [x] Seed submenus
- [ ] Create seeder: `database/seeders/MenuPermissionsSeeder.php`
  - [ ] Link menus to required permissions

---

## Run Migrations & Seeders
- [x] Run: `php artisan migrate`
- [x] Run: `php artisan db:seed --class=RolesSeeder`
- [x] Run: `php artisan db:seed --class=PermissionsSeeder`
- [x] Run: `php artisan db:seed --class=RolePermissionsSeeder`
- [ ] Run: `php artisan db:seed --class=MenusSeeder`
- [ ] Run: `php artisan db:seed --class=MenuPermissionsSeeder`

---

## Verification
- [x] Verify `roles` table has 3 records
- [x] Verify `permissions` table populated
- [x] Verify `role_permissions` relationships correct
- [ ] Verify `menus` table populated
- [ ] Verify `menu_permissions` relationships correct
- [ ] Test User model methods:
  - [ ] `$user->hasRole('superadmin')`
  - [ ] `$user->hasPermission('jemaat.view')`
  - [ ] `$user->assignRole('snk')`
  - [ ] `$user->lingkungans`

---

## Documentation
- [ ] Document database schema
- [ ] Document seeder data
- [ ] Create README for Phase 1 completion
