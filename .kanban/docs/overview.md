# DataJemaat: Laravel Upgrade, RBAC & Draft Workflow Implementation Plan

## Executive Summary

### Problem Statement
The DataJemaat application (Laravel 8, PHP 7.1.3) needs:
1. **Upgrade to PHP 8.4 compatible Laravel version** - Current stack is outdated
2. **Role-Based Access Control** - Currently all authenticated users have equal access
3. **Menu & Permission System** - No authorization checks exist in Blade views
4. **Draft/Approval Workflow** - Non-admin edits should require superadmin approval

### Why This Approach
- **Incremental Laravel upgrade** (8→9→10→11) minimizes risk vs direct jump
- **Custom RBAC over packages** (Spatie) for full control and learning
- **Service-Repository pattern preserved** - Aligns with existing architecture
- **JSON draft storage** - Flexible schema for different entity types
- **Blade directives** - No frontend framework change required

## Project Phases

### Phase 0: Laravel Upgrade
**Docs:** [Laravel Upgrade Documentation](./phase-0-laravel-upgrade.md)
**Tasks:** [Laravel Upgrade Tasks](../tasks/phase-0-laravel-upgrade.md)

Upgrade from Laravel 8 (PHP 7.1.3) to Laravel 11 (PHP 8.2+) in incremental steps.

---

### Phase 1: RBAC Foundation
**Docs:** [RBAC Foundation Documentation](./phase-1-rbac-foundation.md)
**Tasks:** [RBAC Foundation Tasks](../tasks/phase-1-rbac-foundation.md)

Create core RBAC tables, models, traits, and seeders.

---

### Phase 2: Middleware & Authorization
**Docs:** [Middleware & Authorization Documentation](./phase-2-middleware-authorization.md)
**Tasks:** [Middleware & Authorization Tasks](../tasks/phase-2-middleware-authorization.md)

Implement middleware, Blade directives, and route protection.

---

### Phase 3: Draft/Approval Workflow
**Docs:** [Draft/Approval Workflow Documentation](./phase-3-draft-approval-workflow.md)
**Tasks:** [Draft/Approval Workflow Tasks](../tasks/phase-3-draft-approval-workflow.md)

Build draft system for non-superadmin users with approval process.

---

### Phase 4: Admin UI
**Docs:** [Admin UI Documentation](./phase-4-admin-ui.md)
**Tasks:** [Admin UI Tasks](../tasks/phase-4-admin-ui.md)

Create admin interfaces for role, user, and draft management.

---

### Phase 5: Testing & Verification
**Docs:** [Testing & Verification Documentation](./phase-5-testing-verification.md)
**Tasks:** [Testing & Verification Tasks](../tasks/phase-5-testing-verification.md)

Comprehensive testing of all RBAC and workflow features.

---

## Quick Reference

### Critical Files to Modify

| File | Changes |
|------|---------|
| `app/Models/User.php` | Add HasRoles, HasPermissions, HasLingkunganScope traits |
| `app/Models/data_jemaat.php` | Convert `$dates` to `$casts` (for Laravel 10+) |
| `app/Services/DataJemaatService.php` | Add draft workflow integration |
| `app/Http/Kernel.php` | Register new middleware |
| `routes/web.php` | Add middleware groups and admin routes |
| `resources/views/layouts/app.blade.php` | Add authorization directives to menu |
| `app/Providers/AppServiceProvider.php` | Add `$pendingDraftsCount` to view composer |
| `composer.json` | Update dependencies for each Laravel version |

### New Files Summary

- **Models:** 6 (Role, Permission, Menu, JemaatDraft, SimpatisanDraft, DraftHistory)
- **Traits:** 3 (HasRoles, HasPermissions, HasLingkunganScope)
- **Services:** 4 (DraftService, JemaatDraftService, RoleService, MenuService)
- **Middleware:** 3 (CheckRole, CheckPermission, ScopeLingkungan)
- **Controllers:** 3 (ApprovalController, RoleController, UserManagementController)
- **Migrations:** 10 total
- **Seeders:** 6 total

---

## Security & Best Practices

### Authorization Security
- **Middleware-first approach** - Routes protected before controller logic
- **Double-check in Service layer** - `canPublishDirectly()` validates even if middleware bypassed
- **Blade directives** hide UI only - actual security enforced at route/controller level

### Data Integrity
- **Transactions** wrap all draft operations (create, approve, reject)
- **Foreign key constraints** with `ON DELETE CASCADE/SET NULL` as appropriate
- **Audit trail** via `draft_histories` - all state changes logged with performer
- **JSON draft_data** preserves exact submitted data for approval review

### Scoping Security
- **Global scope** applied via middleware for `lingkungan_admin`
- **Superadmin bypass** explicit in middleware check
- **No data leakage** - queries automatically filtered before reaching controller

### Rollback Strategy
- Each Laravel upgrade done in separate branch
- Full database backup before RBAC migrations
- Seeders idempotent (can re-run safely)
- Draft system can be disabled via config flag during initial deployment
