# Installation Steps for DataJemaat Admin UI

## Current Status
✅ **Composer dependencies installed successfully** (131 packages)
⏳ **Database seeder pending** - requires database configuration

---

## Next Steps to Complete Installation

### 1. Configure Database Connection

Copy the example environment file:
```bash
cp .env.example .env
```

Edit `.env` and configure your database settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 2. Generate Application Key
```bash
php artisan key:generate
```

### 3. Run Migrations
```bash
# Run all migrations to create database tables
php artisan migrate
```

### 4. Run Seeders (In Order)

Run the seeders in this specific order:

```bash
# Phase 1: Permissions & Roles
php artisan db:seed --class=PermissionsSeeder
php artisan db:seed --class=RolesSeeder

# Phase 4: Create Superadmin User
php artisan db:seed --class=SuperAdminUserSeeder
```

**Alternative:** Run all seeders at once:
```bash
php artisan db:seed
```

### 5. Login to Admin Panel

Once seeding is complete, you can login with:
- **URL:** `http://your-domain.com/login`
- **Email:** `admin@datajemaat.com`
- **Password:** `password`

**⚠️ IMPORTANT:** Change the default password immediately after first login!

---

## Verification Steps

### Check Superadmin User Was Created

```bash
php artisan tinker
```

Then in tinker:
```php
// Find the admin user
$admin = App\Models\User::where('email', 'admin@datajemaat.com')->first();

// Check if user exists
echo $admin ? "✓ Admin user exists\n" : "✗ Admin user not found\n";

// Check assigned roles
$admin->roles->pluck('name'); // Should show: ["Superadmin"]

exit
```

### Test Admin Access

1. Start the development server:
   ```bash
   php artisan serve
   ```

2. Visit `http://localhost:8000/login`

3. Login with superadmin credentials

4. Navigate to:
   - `/admin/users` - User management
   - `/admin/roles` - Role management
   - `/admin/drafts/pending` - Pending approvals

All should be accessible without 403 errors.

---

## Troubleshooting

### "SQLSTATE[HY000] [1045] Access denied"
**Cause:** Database credentials in `.env` are incorrect or database doesn't exist.

**Solution:**
1. Create the database: `CREATE DATABASE your_database_name;`
2. Verify credentials in `.env` are correct
3. Test connection: `php artisan migrate:status`

### "Class 'SuperAdminUserSeeder' not found"
**Cause:** Autoloader needs to be refreshed.

**Solution:**
```bash
composer dump-autoload
```

### "Superadmin role not found"
**Cause:** RolesSeeder hasn't been run yet.

**Solution:**
```bash
php artisan db:seed --class=PermissionsSeeder
php artisan db:seed --class=RolesSeeder
```

Then run SuperAdminUserSeeder again.

### Cannot access admin routes (403 Forbidden)
**Cause:** User doesn't have superadmin role.

**Solution:**
Manually assign superadmin role:
```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'your-email@example.com')->first();
$role = App\Models\Role::where('slug', 'superadmin')->first();
$user->roles()->attach($role->id);
exit
```

---

## Production Deployment Checklist

Before deploying to production:

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Generate secure `APP_KEY`
- [ ] Use strong database credentials
- [ ] Change default superadmin password
- [ ] Review all created roles and permissions
- [ ] Test all admin functionality
- [ ] Set up proper backups
- [ ] Configure SSL/HTTPS
- [ ] Set up proper file permissions

---

## What's Already Completed

### ✅ Phase 4 Implementation
All code has been successfully implemented:

1. **Controllers:**
   - `UserManagementController.php` - User management
   - `RoleController.php` - Role management

2. **Views:**
   - User management views (index, edit)
   - Role management views (index, create, edit)

3. **Routes:**
   - All admin routes configured
   - Middleware protection applied

4. **Database:**
   - `SuperAdminUserSeeder.php` created
   - Ready to create admin account

5. **Documentation:**
   - `ADMIN_UI_GUIDE.md` - User guide
   - `ADMIN_SETUP.md` - Testing guide
   - `PHASE_4_IMPLEMENTATION_SUMMARY.md` - Implementation overview
   - `INSTALLATION_STEPS.md` - This file

### ✅ Composer Dependencies
All 131 packages installed successfully, including:
- Laravel Framework 11.47.0
- Laravel UI 4.6.1
- Laravel Debugbar 3.16.3
- PHPUnit 11.5.47
- And many more...

---

## Support Resources

- **Admin User Guide:** `ADMIN_UI_GUIDE.md`
- **Setup & Testing:** `ADMIN_SETUP.md`
- **Implementation Details:** `PHASE_4_IMPLEMENTATION_SUMMARY.md`
- **Task Checklist:** `.kanban/tasks/phase-4-admin-ui.md`
- **Technical Docs:** `.kanban/docs/phase-4-admin-ui.md`

---

## Quick Start (After Database Setup)

```bash
# 1. Copy environment file
cp .env.example .env

# 2. Configure database in .env (edit with your settings)

# 3. Generate key
php artisan key:generate

# 4. Run migrations
php artisan migrate

# 5. Seed database
php artisan db:seed

# 6. Start server
php artisan serve

# 7. Login at http://localhost:8000/login
# Email: admin@datajemaat.com
# Password: password (change immediately!)
```

---

**Installation prepared on:** 2026-01-16
**Phase 4 Status:** Code Complete, Database Setup Pending
