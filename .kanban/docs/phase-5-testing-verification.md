# Phase 5: Testing & Verification Documentation

## Overview
Comprehensive testing checklist to verify RBAC, draft workflow, and authorization functionality.

---

## Test Scenarios by Role

### 1. Superadmin Role Tests

#### Menu & Navigation
- [ ] **Test:** Superadmin can see all menus (Jemaat, Simpatisan, Kartu Jemaat, Admin)
- [ ] **Test:** Admin menu displays with submenu items (Users, Roles, Pending Approvals)
- [ ] **Test:** Pending drafts count badge appears when drafts exist

#### Data Access
- [ ] **Test:** Can view jemaat data from all lingkungan
- [ ] **Test:** Can view simpatisan data from all lingkungan
- [ ] **Test:** No global scope restriction applied

#### CRUD Operations
- [ ] **Test:** Create jemaat → directly published to `data_jemaats` (no draft)
- [ ] **Test:** Update jemaat → directly updates record (no draft)
- [ ] **Test:** Delete jemaat → directly deletes record
- [ ] **Test:** Same for simpatisan operations

#### Approval Workflow
- [ ] **Test:** Can view all pending drafts
- [ ] **Test:** Can approve draft → data published to main table
- [ ] **Test:** Can reject draft → status updated, reviewer note saved
- [ ] **Test:** Can request revision → status changed, draft returned to submitter
- [ ] **Test:** All actions logged to `draft_histories`

#### Admin Functions
- [ ] **Test:** Can create new roles
- [ ] **Test:** Can edit custom roles (not system roles)
- [ ] **Test:** Cannot delete system roles (superadmin, snk, lingkungan_admin)
- [ ] **Test:** Can assign/remove user roles
- [ ] **Test:** Can assign lingkungan to lingkungan_admin users
- [ ] **Test:** Can reset user passwords

---

### 2. SNK Role Tests

#### Menu & Navigation
- [ ] **Test:** Can see Jemaat menu
- [ ] **Test:** Can see Simpatisan menu
- [ ] **Test:** Can see Kartu Jemaat menu
- [ ] **Test:** Cannot see Admin menu
- [ ] **Test:** Cannot access `/admin/*` routes (403 error)

#### Data Access
- [ ] **Test:** Can view jemaat data from all lingkungan (no scoping)
- [ ] **Test:** Can view simpatisan data from all lingkungan
- [ ] **Test:** No lingkungan restriction applied

#### CRUD Operations
- [ ] **Test:** View jemaat → displays all records
- [ ] **Test:** Create jemaat → creates draft with status `pending_review`
- [ ] **Test:** Update jemaat → creates draft with operation_type = 'update'
- [ ] **Test:** Draft data stored in JSON format
- [ ] **Test:** Redirect message: "Data submitted for approval"

#### Approval Workflow
- [ ] **Test:** Cannot access approval interface
- [ ] **Test:** Cannot approve own drafts
- [ ] **Test:** Can view own draft submission history

#### Exports & Reports
- [ ] **Test:** Can print Kartu Jemaat
- [ ] **Test:** Can export jemaat data
- [ ] **Test:** Can view reports and graphs

---

### 3. Lingkungan Admin Role Tests

#### Menu & Navigation
- [ ] **Test:** Can see Jemaat menu
- [ ] **Test:** Can see Simpatisan menu (if assigned permission)
- [ ] **Test:** Cannot see Kartu Jemaat menu
- [ ] **Test:** Cannot see Admin menu
- [ ] **Test:** Cannot access `/admin/*` routes (403 error)

#### Data Access - Scoping
- [ ] **Test:** Can only view jemaat from assigned lingkungan(s)
- [ ] **Test:** Cannot view jemaat from unassigned lingkungan
- [ ] **Test:** Trying to access other lingkungan record by ID → 404 or 403
- [ ] **Test:** DataTables/pagination only shows scoped data

#### CRUD Operations
- [ ] **Test:** Create jemaat → creates draft (status: pending_review)
- [ ] **Test:** Can only create jemaat for assigned lingkungan
- [ ] **Test:** Trying to create for unassigned lingkungan → validation error or 403
- [ ] **Test:** Update jemaat → creates draft with operation_type = 'update'
- [ ] **Test:** Cannot update jemaat from unassigned lingkungan

#### Edge Cases
- [ ] **Test:** Lingkungan admin with no assigned lingkungan → 403 error with message
- [ ] **Test:** Lingkungan admin with multiple lingkungans → can access all assigned
- [ ] **Test:** Removing lingkungan assignment → user loses access immediately

---

## Draft Workflow Tests

### Draft Creation
- [ ] **Test:** Draft record created in `jemaat_drafts` table
- [ ] **Test:** `draft_data` JSON contains all submitted form fields
- [ ] **Test:** `submitted_by` set to current user ID
- [ ] **Test:** `status` = 'draft' initially
- [ ] **Test:** `operation_type` = 'create' for new records
- [ ] **Test:** `operation_type` = 'update' for edits
- [ ] **Test:** `jemaat_id` NULL for create, populated for update

### Draft Submission
- [ ] **Test:** Submitting draft changes status to 'pending_review'
- [ ] **Test:** `submitted_at` timestamp populated
- [ ] **Test:** Draft appears in superadmin's pending list
- [ ] **Test:** DraftHistory logged with action = 'submitted'

### Approval Process
- [ ] **Test:** Approving draft:
  - [ ] Status updated to 'approved'
  - [ ] `reviewed_by` set to approver's ID
  - [ ] `reviewed_at` timestamp populated
  - [ ] Data published to `data_jemaats` table
  - [ ] DraftHistory logged with action = 'approved'
  - [ ] Transaction commits successfully

### Rejection Process
- [ ] **Test:** Rejecting draft:
  - [ ] Reviewer note required (validation error if missing)
  - [ ] Status updated to 'rejected'
  - [ ] `reviewed_by` and `reviewed_at` populated
  - [ ] `reviewer_note` saved
  - [ ] Data NOT published to main table
  - [ ] DraftHistory logged with action = 'rejected'

### Revision Request Process
- [ ] **Test:** Requesting revision:
  - [ ] Reviewer note required
  - [ ] Status updated to 'revision_required'
  - [ ] `reviewed_by` and `reviewed_at` populated
  - [ ] Submitter can re-edit and re-submit
  - [ ] DraftHistory logged with action = 'revision_requested'

---

## Authorization Tests

### Permission Checks
- [ ] **Test:** User without `jemaat.view` cannot access `/data-jemaat`
- [ ] **Test:** User without `jemaat.create` cannot access `/tambah-jemaat`
- [ ] **Test:** User without `admin.users` cannot access `/admin/users`
- [ ] **Test:** Middleware redirects to 403 page with appropriate message

### Blade Directive Tests
- [ ] **Test:** `@permission('jemaat.view')` hides content for users without permission
- [ ] **Test:** `@role('superadmin')` shows content only for superadmin
- [ ] **Test:** `@canseemenu('jemaat')` hides menu if user lacks any linked permissions
- [ ] **Test:** Menu with no permissions displays for all authenticated users

### Route Protection
- [ ] **Test:** Direct URL access to protected route returns 403
- [ ] **Test:** API requests (if any) check permissions
- [ ] **Test:** Form submissions validate permissions server-side

---

## Data Integrity Tests

### Transaction Rollback
- [ ] **Test:** Force approval failure (e.g., invalid data) → draft status unchanged
- [ ] **Test:** Database rolled back on error during publish
- [ ] **Test:** No partial data in `data_jemaats` if transaction fails

### Audit Trail
- [ ] **Test:** All draft state changes logged to `draft_histories`
- [ ] **Test:** `performed_by` always populated
- [ ] **Test:** Timestamp (`created_at`) recorded for each action
- [ ] **Test:** History preserved even if draft deleted

### Foreign Key Constraints
- [ ] **Test:** Deleting user with drafts → drafts remain (ON DELETE SET NULL for reviewer)
- [ ] **Test:** Deleting role → user_roles entries cascade delete
- [ ] **Test:** Deleting permission → role_permissions cascade delete

---

## Performance Tests

### Lingkungan Scoping
- [ ] **Test:** Global scope applied efficiently (check query with SQL log)
- [ ] **Test:** Large dataset (1000+ records) filters correctly by lingkungan
- [ ] **Test:** Pagination works correctly with scope

### Menu Loading
- [ ] **Test:** Menu permissions loaded efficiently (eager load relationships)
- [ ] **Test:** Pending drafts count query optimized (cache if possible)

---

## Security Tests

### Authorization Bypass Attempts
- [ ] **Test:** Manually crafting POST request to `/admin/roles` as SNK → 403
- [ ] **Test:** Modifying form hidden field to different lingkungan → validation error
- [ ] **Test:** Submitting draft with manipulated `submitted_by` → uses Auth::id()

### SQL Injection
- [ ] **Test:** Search functionality properly escapes input
- [ ] **Test:** Lingkungan scope uses parameterized queries

### XSS Prevention
- [ ] **Test:** Draft data with `<script>` tags displayed safely (escaped in Blade)
- [ ] **Test:** Reviewer notes with HTML entities rendered as text

---

## Edge Cases & Error Handling

### User Assignment
- [ ] **Test:** User with multiple roles → has combined permissions
- [ ] **Test:** User with no roles → cannot access protected routes
- [ ] **Test:** Removing user's only role → loses all access

### Draft Edge Cases
- [ ] **Test:** Approving already-approved draft → error message
- [ ] **Test:** Deleting jemaat with pending draft → draft jemaat_id set to NULL
- [ ] **Test:** Concurrent approval attempts → first wins, second fails gracefully

### Validation
- [ ] **Test:** Creating role with duplicate slug → validation error
- [ ] **Test:** Assigning non-existent permission to role → validation error
- [ ] **Test:** Required fields in draft data validated before save

---

## Manual Testing Workflow

### Setup Test Users
```sql
-- Superadmin (already exists from seeder)
-- Email: admin@datajemaat.com

-- SNK user
INSERT INTO users (name, email, password) VALUES ('SNK User', 'snk@test.com', '$2y$10$...');
INSERT INTO user_roles (user_id, role_id) VALUES (2, 2);

-- Lingkungan Admin (Lingkungan ID 1)
INSERT INTO users (name, email, password) VALUES ('Lingkungan Admin', 'lingkungan@test.com', '$2y$10$...');
INSERT INTO user_roles (user_id, role_id) VALUES (3, 3);
INSERT INTO user_lingkungans (user_id, lingkungan_id) VALUES (3, 1);
```

### Testing Steps
1. **Login as each user type**
2. **Navigate through all menus** → verify visibility
3. **Attempt CRUD operations** → verify authorization
4. **Submit drafts** (as SNK/Lingkungan Admin)
5. **Approve/Reject drafts** (as Superadmin)
6. **Check audit logs** in `draft_histories`
7. **Verify data in tables** matches expected state

---

## Automated Testing (Future)

### Feature Tests to Write
```php
// tests/Feature/RBACTest.php
public function test_superadmin_can_access_admin_panel()
public function test_snk_cannot_access_admin_panel()
public function test_lingkungan_admin_sees_only_scoped_data()

// tests/Feature/DraftWorkflowTest.php
public function test_snk_create_jemaat_creates_draft()
public function test_superadmin_create_jemaat_publishes_directly()
public function test_draft_approval_publishes_to_main_table()
public function test_draft_rejection_does_not_publish()
```

---

## Sign-off Checklist

### Before Going to Production
- [ ] All role-based tests passed
- [ ] All draft workflow tests passed
- [ ] Security tests passed (no bypass possible)
- [ ] Performance acceptable (scoped queries fast)
- [ ] Database backup taken
- [ ] Rollback plan documented
- [ ] Superadmin account secured (strong password)
- [ ] All seeders run successfully
- [ ] Error logging configured
- [ ] User training documentation prepared
