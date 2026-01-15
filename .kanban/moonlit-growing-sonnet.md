# DataJemaat: Laravel Upgrade, RBAC & Draft Workflow

**Master Plan Index**

This document serves as the master index for the DataJemaat implementation plan. Each phase has dedicated documentation and task files.

---

## Overview

**[Read Full Overview](.kanban/docs/overview.md)**

Quick summary:
- Upgrade Laravel 8 (PHP 7.1.3) → Laravel 11 (PHP 8.2+)
- Implement custom RBAC system (3 roles: superadmin, snk, lingkungan_admin)
- Build draft/approval workflow for non-superadmin users
- Create admin UI for user/role management

---

## Phases

### Phase 0: Laravel Upgrade
**[Documentation](.kanban/docs/phase-0-laravel-upgrade.md)** | **[Tasks](.kanban/tasks/phase-0-laravel-upgrade.md)**

Upgrade Laravel incrementally: 8 → 9 → 10 → 11

**Status:** Not Started

---

### Phase 1: RBAC Foundation
**[Documentation](.kanban/docs/phase-1-rbac-foundation.md)** | **[Tasks](.kanban/tasks/phase-1-rbac-foundation.md)**

Create core RBAC tables, models, traits, and seeders.

**Status:** In Progress (Migrations ✓, Models ✓, Traits ✓, Seeders: Partial)

**Key Deliverables:**
- 7 migrations (roles, permissions, role_permissions, user_roles, user_lingkungans, menus, menu_permissions)
- 3 models (Role, Permission, Menu)
- 3 traits (HasRoles, HasPermissions, HasLingkunganScope)
- 5 seeders (Roles, Permissions, RolePermissions, Menus, MenuPermissions)

---

### Phase 2: Middleware & Authorization
**[Documentation](.kanban/docs/phase-2-middleware-authorization.md)** | **[Tasks](.kanban/tasks/phase-2-middleware-authorization.md)**

Implement middleware, Blade directives, and route protection.

**Status:** Not Started

**Key Deliverables:**
- 3 middleware (CheckRole, CheckPermission, ScopeLingkungan)
- BladeServiceProvider with custom directives (@role, @permission, @canseemenu)
- Protected routes
- Updated sidebar menu with authorization

---

### Phase 3: Draft/Approval Workflow
**[Documentation](.kanban/docs/phase-3-draft-approval-workflow.md)** | **[Tasks](.kanban/tasks/phase-3-draft-approval-workflow.md)**

Build draft system for non-superadmin users with approval process.

**Status:** Not Started

**Key Deliverables:**
- 3 migrations (jemaat_drafts, simpatisan_drafts, draft_histories)
- 3 models (JemaatDraft, SimpatisanDraft, DraftHistory)
- 2 services (DraftService, JemaatDraftService)
- ApprovalController
- Draft review UI

---

### Phase 4: Admin UI
**[Documentation](.kanban/docs/phase-4-admin-ui.md)** | **[Tasks](.kanban/tasks/phase-4-admin-ui.md)**

Create admin interfaces for role, user, and draft management.

**Status:** Not Started

**Key Deliverables:**
- UserManagementController + views
- RoleController + views
- SuperAdminUserSeeder
- Admin menu with pending drafts badge

---

### Phase 5: Testing & Verification
**[Documentation](.kanban/docs/phase-5-testing-verification.md)** | **[Tasks](.kanban/tasks/phase-5-testing-verification.md)**

Comprehensive testing of all RBAC and workflow features.

**Status:** Not Started

**Key Tests:**
- Role-based access control
- Draft workflow (create, approve, reject, revision)
- Lingkungan scoping for lingkungan_admin
- Security (authorization bypass attempts, SQL injection, XSS)
- Data integrity (transactions, audit trail)

---

## Progress Tracking

### Completed
- [x] Phase 1: Migrations created
- [x] Phase 1: Models created
- [x] Phase 1: Traits created
- [x] Phase 1: Core seeders created (Roles, Permissions, RolePermissions)

### In Progress
- [ ] Phase 1: Menus & MenuPermissions seeders

### Pending
- [ ] Phase 2: Middleware & Authorization
- [ ] Phase 3: Draft/Approval Workflow
- [ ] Phase 4: Admin UI
- [ ] Phase 5: Testing & Verification
- [ ] Phase 0: Laravel Upgrade (can be done in parallel or after RBAC)

---

## Quick Links

### Documentation
- [Overview](.kanban/docs/overview.md)
- [Phase 0: Laravel Upgrade](.kanban/docs/phase-0-laravel-upgrade.md)
- [Phase 1: RBAC Foundation](.kanban/docs/phase-1-rbac-foundation.md)
- [Phase 2: Middleware & Authorization](.kanban/docs/phase-2-middleware-authorization.md)
- [Phase 3: Draft/Approval Workflow](.kanban/docs/phase-3-draft-approval-workflow.md)
- [Phase 4: Admin UI](.kanban/docs/phase-4-admin-ui.md)
- [Phase 5: Testing & Verification](.kanban/docs/phase-5-testing-verification.md)

### Tasks
- [Phase 0: Laravel Upgrade](.kanban/tasks/phase-0-laravel-upgrade.md)
- [Phase 1: RBAC Foundation](.kanban/tasks/phase-1-rbac-foundation.md)
- [Phase 2: Middleware & Authorization](.kanban/tasks/phase-2-middleware-authorization.md)
- [Phase 3: Draft/Approval Workflow](.kanban/tasks/phase-3-draft-approval-workflow.md)
- [Phase 4: Admin UI](.kanban/tasks/phase-4-admin-ui.md)
- [Phase 5: Testing & Verification](.kanban/tasks/phase-5-testing-verification.md)

---

## How to Use This Plan

1. **Start with a phase** - Read the documentation file first to understand the concepts
2. **Follow the tasks** - Use the task file as a checklist for implementation
3. **Update progress** - Check off tasks as you complete them
4. **Reference docs** - Refer back to documentation when you need implementation details

---

## Notes

- Each phase builds on the previous one
- Phase 0 (Laravel Upgrade) can be done before or after RBAC implementation
- Recommended order: Phase 1 → 2 → 3 → 4 → 5 → 0 (or 0 first if you want latest Laravel)
- All file paths are relative to project root: `/Users/iDiv/Documents/apps/datajemaat`
