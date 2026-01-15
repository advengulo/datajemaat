# Phase 0: Laravel Upgrade Tasks

## Pre-Upgrade Preparation
- [ ] Set up staging environment
- [ ] Full database backup
- [ ] Document current PHP version
- [ ] Create branch: `upgrade/laravel-9`

---

## Step A: Laravel 8 → 9

### Pre-Upgrade Preparation
- [ ] Set up staging environment
- [ ] Full database backup
- [ ] Document current PHP version
- [x] Create branch: `upgrade/laravel-9`

---

### Update Dependencies
- [x] Update `composer.json` with Laravel 9 requirements
- [x] Update PHP version to `^8.0`
- [x] Update `laravel/framework` to `^9.0`
- [x] Update `laravel/ui` to `^4.0`
- [x] Update `barryvdh/laravel-dompdf` to `^2.0`
- [x] Update `yajra/laravel-datatables-oracle` to `^10.0`
- [x] Update `nunomaduro/collision` to `^6.0`
- [x] Update `phpunit/phpunit` to `^9.5`
- [x] Add `spatie/laravel-ignition` `^1.0`
- [x] Add `fakerphp/faker`

### Remove Deprecated Packages
- [x] Remove `fideloper/proxy`
- [x] Remove `laravel/legacy-factories`
- [x] Remove `facade/ignition`
- [x] Remove `fzaninotto/faker`

### Run Upgrade
- [x] Run `composer update`
- [x] Fix TrustProxies middleware
- [x] Fix config/trustedproxy.php
- [x] Clear all caches: `php artisan cache:clear && php artisan config:clear`
- [x] Verify Laravel version (9.52.21)
- [ ] Test authentication
- [ ] Test CRUD operations
- [ ] Test PDF generation
- [ ] Test DataTables
- [ ] Check browser console for errors
- [ ] Review Laravel logs for deprecation warnings

### Commit Changes
- [x] Commit all changes
- [ ] Tag as `laravel-9`
- [ ] Merge to staging branch

---

## Step B: Laravel 9 → 10

### Update Dependencies
- [ ] Create branch: `upgrade/laravel-10`
- [ ] Update `composer.json` with Laravel 10 requirements
- [ ] Update PHP version to `^8.1`
- [ ] Update `laravel/framework` to `^10.0`
- [ ] Update `nunomaduro/collision` to `^7.0`
- [ ] Update `phpunit/phpunit` to `^10.0`

### Breaking Changes
- [ ] Convert `$dates` to `$casts` in `app/Models/data_jemaat.php`
- [ ] Convert `$dates` to `$casts` in all other models (if any)
- [ ] Update middleware closure syntax (if needed)

### Run Upgrade
- [ ] Run `composer update`
- [ ] Clear all caches
- [ ] Run migrations
- [ ] Test all features from Laravel 9 checklist
- [ ] Verify date fields display correctly

### Commit Changes
- [ ] Commit all changes
- [ ] Tag as `laravel-10`
- [ ] Merge to staging branch

---

## Step C: Laravel 10 → 11

### Update Dependencies
- [ ] Create branch: `upgrade/laravel-11`
- [ ] Update `composer.json` with Laravel 11 requirements
- [ ] Update PHP version to `^8.2`
- [ ] Update `laravel/framework` to `^11.0`
- [ ] Update `barryvdh/laravel-dompdf` to `^3.0`
- [ ] Update `yajra/laravel-datatables-oracle` to `^11.0`
- [ ] Update `nunomaduro/collision` to `^8.0`
- [ ] Update `phpunit/phpunit` to `^11.0`

### Breaking Changes
- [ ] Update `bootstrap/app.php` with new structure (or use legacy mode)
- [ ] Move middleware registration to bootstrap (if using new structure)
- [ ] Verify health route `/up` accessible

### Run Upgrade
- [ ] Run `composer update`
- [ ] Clear all caches
- [ ] Run migrations
- [ ] Test all features comprehensively
- [ ] Performance testing
- [ ] Load testing (if possible)

### Commit Changes
- [ ] Commit all changes
- [ ] Tag as `laravel-11`
- [ ] Merge to staging branch

---

## Final Verification
- [ ] All features working on Laravel 11
- [ ] No console errors
- [ ] No PHP errors in logs
- [ ] Performance acceptable
- [ ] Create deployment documentation
- [ ] Plan production deployment

---

## Production Deployment
- [ ] Schedule maintenance window
- [ ] Backup production database
- [ ] Deploy to production
- [ ] Run migrations
- [ ] Verify all features
- [ ] Monitor for 24 hours
- [ ] Document any issues encountered
