# Phase 2: Middleware & Authorization Tasks

## Middleware Creation

### Create Authorization Middleware
- [ ] Create middleware: `app/Http/Middleware/CheckRole.php`
  - [ ] Implement `handle()` method
  - [ ] Support single and multiple roles (pipe-separated)
  - [ ] Return 403 if unauthorized
- [ ] Create middleware: `app/Http/Middleware/CheckPermission.php`
  - [ ] Implement `handle()` method
  - [ ] Support single and multiple permissions
  - [ ] Return 403 if unauthorized
- [ ] Create middleware: `app/Http/Middleware/ScopeLingkungan.php`
  - [ ] Check user role
  - [ ] Skip for superadmin
  - [ ] Apply global scope for lingkungan_admin
  - [ ] Return 403 if no lingkungan assigned

---

## Register Middleware

### Update Kernel
- [ ] Register middleware in `app/Http/Kernel.php`
  - [ ] Add `'role' => CheckRole::class`
  - [ ] Add `'permission' => CheckPermission::class`
  - [ ] Add `'scope.lingkungan' => ScopeLingkungan::class`

---

## Blade Directives

### Create BladeServiceProvider
- [ ] Create provider: `app/Providers/BladeServiceProvider.php`
  - [ ] Register `@role` directive
  - [ ] Register `@permission` directive
  - [ ] Register `@canseemenu` directive

### Register Provider
- [ ] Add BladeServiceProvider to `config/app.php`
- [ ] Clear config cache: `php artisan config:clear`
- [ ] Clear view cache: `php artisan view:clear`

---

## Route Protection

### Update Routes
- [ ] Update `routes/web.php`:
  - [ ] Wrap jemaat routes with `scope.lingkungan` middleware
  - [ ] Add `permission:jemaat.view` to data-jemaat route
  - [ ] Add `permission:jemaat.create|jemaat.update` to tambah-jemaat route
  - [ ] Wrap admin routes with `role:superadmin` middleware
  - [ ] Add admin routes for users, roles, drafts

---

## Update Views

### Update Sidebar Menu
- [ ] Update `resources/views/layouts/app.blade.php`:
  - [ ] Wrap Jemaat menu with `@canseemenu('jemaat')`
  - [ ] Wrap submenu items with `@permission` directives
  - [ ] Wrap Simpatisan menu with `@canseemenu('simpatisan')`
  - [ ] Wrap Admin menu with `@role('superadmin')`
  - [ ] Add pending drafts count badge

---

## View Composer

### Add Pending Drafts Count
- [ ] Update `app/Providers/AppServiceProvider.php`:
  - [ ] Create view composer for all views
  - [ ] Check if user is superadmin
  - [ ] Query pending drafts count
  - [ ] Share `$pendingDraftsCount` variable

---

## Testing

### Manual Testing
- [ ] Test as Superadmin:
  - [ ] All menus visible
  - [ ] All routes accessible
  - [ ] No 403 errors
- [ ] Test as SNK:
  - [ ] Jemaat menu visible
  - [ ] Admin menu hidden
  - [ ] Accessing `/admin/users` returns 403
- [ ] Test as Lingkungan Admin:
  - [ ] Jemaat menu visible
  - [ ] Admin menu hidden
  - [ ] Only assigned lingkungan data visible
- [ ] Test unauthenticated:
  - [ ] Protected routes redirect to login
  - [ ] Menus not visible

### Blade Directive Testing
- [ ] Test `@role('superadmin')` shows content only for superadmin
- [ ] Test `@permission('jemaat.view')` hides content without permission
- [ ] Test `@canseemenu('jemaat')` displays menu with correct permissions

---

## Documentation
- [ ] Document middleware usage
- [ ] Document Blade directive usage
- [ ] Create authorization guide for developers
