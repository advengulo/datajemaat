# Phase 1 RBAC - Quick Start Guide

## TL;DR - Deploy in 3 Steps

```bash
# 1. Configure database
cp .env.example .env
# Edit .env with your database credentials

# 2. Run migrations
php artisan migrate

# 3. Run seeders
php artisan db:seed
```

## Verify Installation

```bash
php verify-phase1.php
```

Expected: ✅ **44/44 checks passed**

## Quick Test

```bash
php artisan tinker
```

```php
// Test roles
Role::count();  // 3

// Test permissions
Permission::count();  // 50+

// Test user methods
$user = User::first();
$user->assignRole('superadmin');
$user->hasRole('superadmin');  // true
$user->hasPermission('jemaat.view');  // true

// Test menus
Menu::count();  // 15+
```

## What You Got

- ✅ 3 Roles (superadmin, snk, lingkungan_admin)
- ✅ 50+ Permissions across 6 modules
- ✅ Dynamic menu system (15+ menus)
- ✅ Lingkungan-based data scoping
- ✅ Full RBAC foundation ready to use

## Usage in Code

### Check Permission
```php
if ($user->hasPermission('jemaat.view')) {
    // Show jemaat data
}

// OR use helper
if ($user->canView('jemaat')) {
    // Show jemaat data
}
```

### Apply Lingkungan Scope
```php
$query = Jemaat::query();
$query = $user->applyLingkunganScope($query);
$jemaats = $query->get();
```

### Dynamic Menus
```php
$menuTree = Menu::getMenuTreeForUser($user->getPermissionSlugs()->toArray());
```

## Files to Review

1. **PHASE-1-SUMMARY.md** - Quick overview
2. **PHASE-1-COMPLETED.md** - Full documentation with examples
3. **.kanban/tasks/phase-1-rbac-foundation.md** - Task checklist

## Need Help?

Run the verification script:
```bash
php verify-phase1.php
```

Check logs:
```bash
tail -f storage/logs/laravel.log
```

---

**Status**: ✅ Phase 1 Complete - Ready for deployment!
