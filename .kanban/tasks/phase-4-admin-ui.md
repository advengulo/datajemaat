# Phase 4: Admin UI Tasks

## User Management

### Create UserManagementController
- [x] Create controller: `app/Http/Controllers/Admin/UserManagementController.php`
  - [x] Apply `role:superadmin` middleware
  - [x] Implement `index()` method - list all users
  - [x] Implement `edit($user)` method - edit user form
  - [x] Implement `update(Request, $user)` method - update user
  - [x] Implement `resetPassword(Request, $user)` method
  - [x] Add validation rules

---

### Create User Management Views
- [x] Create view: `resources/views/admin/users/index.blade.php`
  - [x] Display users table (ID, name, email, roles, created_at)
  - [x] Add "Edit" button for each user
  - [x] Add pagination
- [x] Create view: `resources/views/admin/users/edit.blade.php`
  - [x] Form for name, email
  - [x] Checkboxes for role assignment
  - [x] Conditional lingkungan assignment (show if lingkungan_admin role selected)
  - [x] JavaScript to toggle lingkungan section
  - [x] Separate form for password reset

---

### Add User Management Routes
- [x] Add routes in `routes/web.php`:
  - [x] `GET /admin/users` → UserManagementController@index
  - [x] `GET /admin/users/{user}/edit` → UserManagementController@edit
  - [x] `PUT /admin/users/{user}` → UserManagementController@update
  - [x] `POST /admin/users/{user}/reset-password` → UserManagementController@resetPassword

---

## Role Management

### Create RoleController
- [x] Create controller: `app/Http/Controllers/Admin/RoleController.php`
  - [x] Apply `role:superadmin` middleware
  - [x] Implement `index()` method - list roles with counts
  - [x] Implement `create()` method - create role form
  - [x] Implement `store(Request)` method - save new role
  - [x] Implement `edit($role)` method - edit role form
  - [x] Implement `update(Request, $role)` method - update role
  - [x] Implement `destroy($role)` method - delete role
  - [x] Add validation rules
  - [x] Prevent modification/deletion of system roles

---

### Create Role Management Views
- [x] Create view: `resources/views/admin/roles/index.blade.php`
  - [x] Display roles table (name, slug, description, users count, permissions count)
  - [x] Show "System" badge for system roles
  - [x] Add "Create New Role" button
  - [x] Add "Edit" and "Delete" buttons (hide delete for system roles)
- [x] Create view: `resources/views/admin/roles/create.blade.php`
  - [x] Form for name, slug, description
  - [x] Permissions checkboxes grouped by module
  - [x] Submit button
- [x] Create view: `resources/views/admin/roles/edit.blade.php`
  - [x] Reuse create form structure
  - [x] Pre-populate fields
  - [x] Pre-check assigned permissions
  - [x] Disable fields if system role (optional)

---

### Add Role Management Routes
- [x] Add routes in `routes/web.php`:
  - [x] Use `Route::resource('roles', RoleController::class)` under admin group
  - [x] This creates: index, create, store, edit, update, destroy routes

---

## Initial Superadmin User

### Create SuperAdminUserSeeder
- [x] Create seeder: `database/seeders/SuperAdminUserSeeder.php`
  - [x] Create user with email: `admin@datajemaat.com`
  - [x] Set default password (to be changed on first login)
  - [x] Assign superadmin role

---

### Run Seeder
- [ ] Run: `php artisan db:seed --class=SuperAdminUserSeeder` (requires `composer install` first)
- [ ] Verify user created
- [ ] Test login with default credentials
- [ ] Change password immediately

---

## Update Admin Menu

### Add Pending Drafts Badge
- [x] Update `resources/views/layouts/app.blade.php`:
  - [x] Add badge to "Pending Approvals" menu item
  - [x] Display `$pendingDraftsCount` variable
  - [x] Use warning badge color
  - [x] Add "Manage Users" and "Manage Roles" menu items

---

## Testing

### User Management Testing
- [ ] As Superadmin:
  - [ ] Access user management page
  - [ ] Edit user → assign role
  - [ ] Edit user → assign lingkungan (for lingkungan_admin)
  - [ ] Verify lingkungan section toggles based on role selection
  - [ ] Reset user password
  - [ ] Verify changes saved to database

### Role Management Testing
- [ ] As Superadmin:
  - [ ] Access role management page
  - [ ] Create new custom role
  - [ ] Assign permissions to role
  - [ ] Edit custom role → update permissions
  - [ ] Try to edit system role → verify cannot delete
  - [ ] Try to delete role with assigned users → verify error
  - [ ] Delete custom role with no users → success

### Access Control Testing
- [ ] As SNK:
  - [ ] Try to access `/admin/users` → 403 error
  - [ ] Try to access `/admin/roles` → 403 error
- [ ] As Lingkungan Admin:
  - [ ] Try to access admin routes → 403 error

**Note:** Testing requires running `composer install` and seeding the database first. See `ADMIN_SETUP.md` for complete testing guide.

---

## UI/UX Improvements

### Add Success/Error Messages
- [x] Use Laravel's flash messages
- [x] Display success alerts for successful operations
- [x] Display error alerts for validation errors
- [x] Add confirmation dialogs for delete operations

### Add Form Validation
- [x] Server-side validation (Laravel Request validation)
- [x] Display validation errors in forms
- [ ] Client-side validation (JavaScript) - optional enhancement

---

## Documentation
- [x] Document admin UI features - see `ADMIN_UI_GUIDE.md`
- [x] Create user guide for managing users and roles - see `ADMIN_UI_GUIDE.md`
- [x] Document default superadmin credentials - see `ADMIN_SETUP.md`
- [x] Create setup and testing guide - see `ADMIN_SETUP.md`
- [ ] Create screenshots for documentation - requires running application
