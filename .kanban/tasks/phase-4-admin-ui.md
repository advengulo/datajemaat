# Phase 4: Admin UI Tasks

## User Management

### Create UserManagementController
- [ ] Create controller: `app/Http/Controllers/Admin/UserManagementController.php`
  - [ ] Apply `role:superadmin` middleware
  - [ ] Implement `index()` method - list all users
  - [ ] Implement `edit($user)` method - edit user form
  - [ ] Implement `update(Request, $user)` method - update user
  - [ ] Implement `resetPassword(Request, $user)` method
  - [ ] Add validation rules

---

### Create User Management Views
- [ ] Create view: `resources/views/admin/users/index.blade.php`
  - [ ] Display users table (ID, name, email, roles, created_at)
  - [ ] Add "Edit" button for each user
  - [ ] Add pagination
- [ ] Create view: `resources/views/admin/users/edit.blade.php`
  - [ ] Form for name, email
  - [ ] Checkboxes for role assignment
  - [ ] Conditional lingkungan assignment (show if lingkungan_admin role selected)
  - [ ] JavaScript to toggle lingkungan section
  - [ ] Separate form for password reset

---

### Add User Management Routes
- [ ] Add routes in `routes/web.php`:
  - [ ] `GET /admin/users` → UserManagementController@index
  - [ ] `GET /admin/users/{user}/edit` → UserManagementController@edit
  - [ ] `PUT /admin/users/{user}` → UserManagementController@update
  - [ ] `POST /admin/users/{user}/reset-password` → UserManagementController@resetPassword

---

## Role Management

### Create RoleController
- [ ] Create controller: `app/Http/Controllers/Admin/RoleController.php`
  - [ ] Apply `role:superadmin` middleware
  - [ ] Implement `index()` method - list roles with counts
  - [ ] Implement `create()` method - create role form
  - [ ] Implement `store(Request)` method - save new role
  - [ ] Implement `edit($role)` method - edit role form
  - [ ] Implement `update(Request, $role)` method - update role
  - [ ] Implement `destroy($role)` method - delete role
  - [ ] Add validation rules
  - [ ] Prevent modification/deletion of system roles

---

### Create Role Management Views
- [ ] Create view: `resources/views/admin/roles/index.blade.php`
  - [ ] Display roles table (name, slug, description, users count, permissions count)
  - [ ] Show "System" badge for system roles
  - [ ] Add "Create New Role" button
  - [ ] Add "Edit" and "Delete" buttons (hide delete for system roles)
- [ ] Create view: `resources/views/admin/roles/create.blade.php`
  - [ ] Form for name, slug, description
  - [ ] Permissions checkboxes grouped by module
  - [ ] Submit button
- [ ] Create view: `resources/views/admin/roles/edit.blade.php`
  - [ ] Reuse create form structure
  - [ ] Pre-populate fields
  - [ ] Pre-check assigned permissions
  - [ ] Disable fields if system role (optional)

---

### Add Role Management Routes
- [ ] Add routes in `routes/web.php`:
  - [ ] Use `Route::resource('roles', RoleController::class)` under admin group
  - [ ] This creates: index, create, store, edit, update, destroy routes

---

## Initial Superadmin User

### Create SuperAdminUserSeeder
- [ ] Create seeder: `database/seeders/SuperAdminUserSeeder.php`
  - [ ] Create user with email: `admin@datajemaat.com`
  - [ ] Set default password (to be changed on first login)
  - [ ] Assign superadmin role

---

### Run Seeder
- [ ] Run: `php artisan db:seed --class=SuperAdminUserSeeder`
- [ ] Verify user created
- [ ] Test login with default credentials
- [ ] Change password immediately

---

## Update Admin Menu

### Add Pending Drafts Badge
- [ ] Update `resources/views/layouts/app.blade.php`:
  - [ ] Add badge to "Pending Approvals" menu item
  - [ ] Display `$pendingDraftsCount` variable
  - [ ] Use warning badge color
  - [ ] Add badge to parent "Admin" menu item as well

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

---

## UI/UX Improvements

### Add Success/Error Messages
- [ ] Use Laravel's flash messages
- [ ] Display success alerts for successful operations
- [ ] Display error alerts for validation errors
- [ ] Add confirmation dialogs for delete operations

### Add Form Validation
- [ ] Client-side validation (JavaScript)
- [ ] Server-side validation (Laravel Request validation)
- [ ] Display validation errors in forms

---

## Documentation
- [ ] Document admin UI features
- [ ] Create user guide for managing users and roles
- [ ] Document default superadmin credentials
- [ ] Create screenshots for documentation
