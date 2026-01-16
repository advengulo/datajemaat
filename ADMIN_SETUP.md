# Admin UI Setup & Testing Guide

## Prerequisites

Before using the Admin UI features, ensure you have:
1. Completed Phase 1 (RBAC Foundation)
2. Completed Phase 2 (Middleware & Authorization)
3. Completed Phase 3 (Draft/Approval Workflow)
4. Run all previous seeders

---

## Installation Steps

### 1. Install Dependencies
```bash
composer install
```

### 2. Run Database Seeders

Run seeders in this order:
```bash
# Run all seeders (if using DatabaseSeeder)
php artisan db:seed

# Or run individually:
php artisan db:seed --class=PermissionsSeeder
php artisan db:seed --class=RolesSeeder
php artisan db:seed --class=SuperAdminUserSeeder
```

### 3. Verify Installation

Check that the superadmin user was created:
```bash
php artisan tinker
```

Then in tinker:
```php
$admin = App\Models\User::where('email', 'admin@datajemaat.com')->first();
$admin->roles; // Should show 'superadmin' role
exit
```

---

## Testing Checklist

### User Management Testing

#### As Superadmin:
- [ ] Access `/admin/users` - should see user list
- [ ] Click Edit on a user
- [ ] Update user's name and email - should save successfully
- [ ] Assign a new role - should update and show badge
- [ ] Assign "Lingkungan Admin" role - lingkungan section should appear
- [ ] Select lingkungan(s) - should save associations
- [ ] Uncheck "Lingkungan Admin" - lingkungan section should hide
- [ ] Reset user password - should accept min 8 chars with confirmation
- [ ] Verify changes in database

#### As Non-Superadmin (SNK or Lingkungan Admin):
- [ ] Try to access `/admin/users` - should get 403 Forbidden
- [ ] Try to access `/admin/roles` - should get 403 Forbidden

---

### Role Management Testing

#### As Superadmin:
- [ ] Access `/admin/roles` - should see all roles
- [ ] Verify system roles show "System" badge
- [ ] Click "Create New Role"
- [ ] Create custom role with permissions - should save successfully
- [ ] Edit custom role - should allow updates
- [ ] Try to edit system role - fields should be readonly or show warning
- [ ] Delete system role - should show error message
- [ ] Assign users to custom role
- [ ] Try to delete role with users - should show error
- [ ] Remove users from role, then delete - should succeed
- [ ] Verify permission checkboxes grouped by module

#### Permission Assignment:
- [ ] Create role with only `jemaat.view` permission
- [ ] Assign to test user
- [ ] Login as test user - should see jemaat menu but no edit buttons
- [ ] Try to access edit URL directly - should get 403

---

### Menu & Navigation Testing

#### Admin Menu Visibility:
- [ ] Login as Superadmin - Admin menu should appear in sidebar
- [ ] Admin menu should show:
  - Lingkungan
  - Manage Users
  - Manage Roles
  - Pending Approvals (with badge if pending drafts exist)
- [ ] Login as SNK - Admin menu should NOT appear
- [ ] Login as Lingkungan Admin - Admin menu should NOT appear

#### Pending Approvals Badge:
- [ ] Create a draft (as SNK or Lingkungan Admin)
- [ ] Login as Superadmin
- [ ] Badge should show count next to "Pending Approvals"
- [ ] Approve the draft
- [ ] Badge count should decrease

---

### Integration Testing

#### User Role Flow:
1. [ ] Create test user account
2. [ ] Assign SNK role via admin panel
3. [ ] Login as test user - should have SNK permissions
4. [ ] Create a jemaat draft
5. [ ] Login as Superadmin - draft should appear in pending approvals
6. [ ] Edit test user, change to Lingkungan Admin
7. [ ] Assign lingkungan
8. [ ] Login as test user - should only see assigned lingkungan data

#### Password Reset Flow:
1. [ ] Reset user password via admin panel
2. [ ] Logout
3. [ ] Try old password - should fail
4. [ ] Try new password - should succeed

---

## Common Testing Scenarios

### Scenario 1: New Employee Onboarding
```
Story: New data entry staff needs access to add jemaat in specific lingkungan

Steps:
1. Create user account (or have them register)
2. Superadmin: Go to Manage Users
3. Find user, click Edit
4. Assign "Lingkungan Admin" role
5. Select their assigned lingkungan
6. Save
7. User logs in - can only manage their lingkungan
```

### Scenario 2: Role Permission Update
```
Story: SNK role needs export permission added

Steps:
1. Superadmin: Go to Manage Roles
2. Find SNK role, click Edit
3. Check "jemaat.export" permission
4. Save
5. All users with SNK role now have export access
```

### Scenario 3: Creating Department-Specific Role
```
Story: Youth ministry needs view-only access to youth member data

Steps:
1. Superadmin: Create new role "Youth Coordinator"
2. Slug: "youth-coordinator"
3. Assign only: jemaat.view, laporan.view
4. Save
5. Assign role to youth ministry staff
6. They can view but not edit data
```

---

## Validation Testing

### User Update Validation:
- [ ] Try to save user without name - should show error
- [ ] Try to save user with invalid email - should show error
- [ ] Try to use duplicate email - should show error
- [ ] Try to save user without any roles - should show error

### Role Validation:
- [ ] Try to create role without name - should show error
- [ ] Try to create role with duplicate slug - should show error
- [ ] Try to create role with spaces in slug - should show error

### Password Reset Validation:
- [ ] Try password < 8 characters - should show error
- [ ] Try mismatched confirmation - should show error
- [ ] Valid password - should succeed

---

## Security Testing

### Authorization Tests:
- [ ] Non-superadmin cannot access `/admin/users`
- [ ] Non-superadmin cannot access `/admin/roles`
- [ ] Direct URL access blocked for unauthorized users
- [ ] Cannot delete system roles via UI
- [ ] Cannot modify system roles via UI

### Data Integrity Tests:
- [ ] Removing Lingkungan Admin role removes lingkungan assignments
- [ ] Deleting role with users is prevented
- [ ] Role-permission associations are maintained correctly
- [ ] User-role associations are maintained correctly

---

## Database Verification

After testing, verify data integrity:

```bash
php artisan tinker
```

Check user roles:
```php
$user = User::find(1);
$user->roles->pluck('name'); // Should show assigned roles
```

Check role permissions:
```php
$role = Role::where('slug', 'snk')->first();
$role->permissions->pluck('slug'); // Should show assigned permissions
```

Check lingkungan assignments:
```php
$user = User::find(1);
$user->lingkungans->pluck('lingkungan'); // Should show assigned lingkungan
```

---

## Troubleshooting

### Issue: Cannot access admin panel
**Solution:**
```php
// Manually assign superadmin role
$user = User::find(1);
$role = Role::where('slug', 'superadmin')->first();
$user->roles()->attach($role->id);
```

### Issue: Lingkungan section not toggling
**Solution:**
- Clear browser cache
- Check JavaScript console for errors
- Verify role slug is exactly 'lingkungan_admin'

### Issue: Permissions not working
**Solution:**
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Issue: Badge count not showing
**Solution:**
- Verify JemaatDraft model exists
- Check drafts have status 'pending_review'
- Clear view cache: `php artisan view:clear`

---

## Performance Considerations

For production deployments:
1. Add database indexes on frequently queried columns
2. Cache role and permission queries
3. Use eager loading for user roles: `User::with('roles')->get()`
4. Consider adding pagination limits for large user lists

---

## Next Steps

After successful testing:
1. Document any custom roles created
2. Train staff on using the admin interface
3. Set up regular security audits
4. Implement password rotation policies
5. Monitor user activity logs

---

## Support & Documentation

- Main documentation: `.kanban/docs/phase-4-admin-ui.md`
- User guide: `ADMIN_UI_GUIDE.md`
- RBAC documentation: `.kanban/docs/phase-1-rbac.md`
- Workflow documentation: `.kanban/docs/phase-3-workflow.md`
