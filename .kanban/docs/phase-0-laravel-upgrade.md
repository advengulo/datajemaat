# Phase 0: Laravel Upgrade Documentation

## Overview
Upgrade from Laravel 8 (PHP 7.1.3) to Laravel 11 (PHP 8.2+) through incremental version steps.

## Version Path
```
Laravel 8 (PHP 7.1.3) → Laravel 9 (PHP 8.0) → Laravel 10 (PHP 8.1) → Laravel 11 (PHP 8.2+)
```

---

## Step A: Laravel 8 → 9

### composer.json changes
```json
{
  "require": {
    "php": "^8.0",
    "laravel/framework": "^9.0",
    "laravel/ui": "^4.0",
    "barryvdh/laravel-dompdf": "^2.0",
    "yajra/laravel-datatables-oracle": "^10.0"
  },
  "require-dev": {
    "nunomaduro/collision": "^6.0",
    "phpunit/phpunit": "^9.5",
    "spatie/laravel-ignition": "^1.0"
  }
}
```

### Breaking Changes to Address

1. **Remove deprecated packages:**
   - `fideloper/proxy` (built into Laravel 9)
   - `laravel/legacy-factories` (convert to class-based)
   - `facade/ignition` → `spatie/laravel-ignition`
   - `fzaninotto/faker` → `fakerphp/faker`

2. **Update TrustProxies middleware:**
   ```php
   // app/Http/Middleware/TrustProxies.php
   use Illuminate\Http\Middleware\TrustProxies as Middleware;
   use Illuminate\Http\Request;

   class TrustProxies extends Middleware
   {
       protected $proxies;
       protected $headers = Request::HEADER_X_FORWARDED_FOR |
                            Request::HEADER_X_FORWARDED_HOST |
                            Request::HEADER_X_FORWARDED_PORT |
                            Request::HEADER_X_FORWARDED_PROTO |
                            Request::HEADER_X_FORWARDED_AWS_ELB;
   }
   ```

---

## Step B: Laravel 9 → 10

### composer.json changes
```json
{
  "require": {
    "php": "^8.1",
    "laravel/framework": "^10.0"
  },
  "require-dev": {
    "nunomaduro/collision": "^7.0",
    "phpunit/phpunit": "^10.0"
  }
}
```

### Breaking Changes

1. **Convert `$dates` to `$casts` in all models:**
   ```php
   // Before (Laravel 8/9):
   protected $dates = ['jemaat_tanggal_lahir', 'jemaat_tanggal_baptis'];

   // After (Laravel 10+):
   protected $casts = [
       'jemaat_tanggal_lahir' => 'datetime',
       'jemaat_tanggal_baptis' => 'datetime',
       'jemaat_tanggal_sidi' => 'datetime',
       'jemaat_tanggal_bergabung' => 'datetime',
       'jemaat_tanggal_perkawinan' => 'datetime',
   ];
   ```

2. **Update middleware closure syntax if needed**

---

## Step C: Laravel 10 → 11

### composer.json changes
```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^11.0",
    "barryvdh/laravel-dompdf": "^3.0",
    "yajra/laravel-datatables-oracle": "^11.0"
  },
  "require-dev": {
    "nunomaduro/collision": "^8.0",
    "phpunit/phpunit": "^11.0"
  }
}
```

### Major Changes

1. **New bootstrap/app.php structure** (optional legacy mode):
   ```php
   <?php

   use Illuminate\Foundation\Application;
   use Illuminate\Foundation\Configuration\Exceptions;
   use Illuminate\Foundation\Configuration\Middleware;

   return Application::configure(basePath: dirname(__DIR__))
       ->withRouting(
           web: __DIR__.'/../routes/web.php',
           commands: __DIR__.'/../routes/console.php',
           health: '/up',
       )
       ->withMiddleware(function (Middleware $middleware) {
           //
       })
       ->withExceptions(function (Exceptions $exceptions) {
           //
       })->create();
   ```

2. **Middleware registration moves to bootstrap**
3. **Health routes added automatically** (`/up` endpoint)

---

## Critical Model Updates

### File: `app/Models/data_jemaat.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class data_jemaat extends Model
{
    protected $table = 'data_jemaats';

    protected $fillable = [
        'jemaat_nama_lengkap',
        'jemaat_tanggal_lahir',
        // ... other fields
    ];

    // IMPORTANT: Replace $dates with $casts for Laravel 10+
    protected $casts = [
        'jemaat_tanggal_lahir' => 'datetime',
        'jemaat_tanggal_baptis' => 'datetime',
        'jemaat_tanggal_sidi' => 'datetime',
        'jemaat_tanggal_bergabung' => 'datetime',
        'jemaat_tanggal_perkawinan' => 'datetime',
        'jemaat_kk_status' => 'boolean',
        'is_simpatisan' => 'boolean',
    ];

    // Relationships
    public function lingkungan()
    {
        return $this->belongsTo(master_lingkungan::class, 'id_lingkungan');
    }
}
```

---

## Upgrade Execution Strategy

### Pre-Upgrade Checklist
- [ ] Set up staging environment
- [ ] Full database backup
- [ ] Create new branch for each upgrade (e.g., `upgrade/laravel-9`)
- [ ] Document current PHP version

### Upgrade Process (per version)
1. Update `composer.json` with new version constraints
2. Run `composer update`
3. Clear caches: `php artisan cache:clear && php artisan config:clear`
4. Address breaking changes listed above
5. Run migrations: `php artisan migrate`
6. Run tests (if available)
7. Manual testing of core features
8. Commit changes
9. Merge to staging for further testing

### Post-Upgrade Verification
- [ ] Authentication works
- [ ] Data CRUD operations functional
- [ ] PDF generation working (dompdf)
- [ ] DataTables rendering correctly
- [ ] No console errors in browser
- [ ] Check logs for deprecation warnings

---

## Common Issues & Solutions

### Issue: Composer dependency conflicts
**Solution:** Use `composer why-not laravel/framework ^9.0` to identify blocking packages, update them first.

### Issue: Class not found errors
**Solution:** Run `composer dump-autoload` and clear Laravel caches.

### Issue: Middleware errors
**Solution:** Check `app/Http/Kernel.php` middleware registration syntax matches new Laravel version.

### Issue: Database migration failures
**Solution:** Ensure MySQL/MariaDB version compatibility, check for reserved keywords in table/column names.

---

## Rollback Plan

If upgrade fails at any step:
1. Restore database from backup
2. `git reset --hard` to previous commit
3. Run `composer install` to restore previous dependencies
4. Clear all caches
5. Verify application works on previous version
