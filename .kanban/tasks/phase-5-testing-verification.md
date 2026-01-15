# Phase 5: Testing & Verification Tasks

## Test User Setup

### Create Test Users
- [ ] Create Superadmin test user (if not using seeded one)
- [ ] Create SNK test user via database or admin UI
- [ ] Create Lingkungan Admin test user
- [ ] Assign lingkungan to Lingkungan Admin (e.g., Lingkungan ID 1)
- [ ] Document test user credentials

---

## Superadmin Role Tests

### Menu & Navigation
- [ ] Login as Superadmin
- [ ] Verify all menus visible (Jemaat, Simpatisan, Kartu Jemaat, Admin)
- [ ] Verify Admin menu has submenu items
- [ ] Verify pending drafts badge appears (if drafts exist)

### Data Access
- [ ] View jemaat data → verify all lingkungan visible
- [ ] View simpatisan data → verify all lingkungan visible
- [ ] Verify no data filtering applied

### CRUD Operations
- [ ] Create jemaat → verify published directly to `data_jemaats`
- [ ] Update jemaat → verify updated directly (no draft)
- [ ] Delete jemaat → verify deleted directly
- [ ] Verify no draft records created for superadmin actions

### Approval Workflow
- [ ] Navigate to `/admin/drafts/pending`
- [ ] Verify all pending drafts listed
- [ ] Review a draft → verify data displayed
- [ ] Approve a draft → verify published to main table
- [ ] Reject a draft → verify status = rejected
- [ ] Request revision → verify status = revision_required
- [ ] Check `draft_histories` table → verify all actions logged

### Admin Functions
- [ ] Navigate to user management
- [ ] Create/edit user roles
- [ ] Assign lingkungan to lingkungan_admin
- [ ] Navigate to role management
- [ ] Create custom role
- [ ] Try to delete system role → verify prevented
- [ ] Delete custom role (with no users)
- [ ] Reset user password

---

## SNK Role Tests

### Menu & Navigation
- [ ] Login as SNK
- [ ] Verify Jemaat menu visible
- [ ] Verify Simpatisan menu visible
- [ ] Verify Kartu Jemaat menu visible
- [ ] Verify Admin menu NOT visible
- [ ] Try to access `/admin/users` → verify 403 error

### Data Access
- [ ] View jemaat data → verify all lingkungan visible (no scoping)
- [ ] View simpatisan data → verify all lingkungan visible

### CRUD Operations (Draft Workflow)
- [ ] Create jemaat → verify redirect message: "Submitted for approval"
- [ ] Check `jemaat_drafts` table → verify record created
- [ ] Verify draft status = 'pending_review'
- [ ] Verify `operation_type` = 'create'
- [ ] Verify `draft_data` JSON contains all form fields
- [ ] Update existing jemaat → verify draft created
- [ ] Verify `operation_type` = 'update'
- [ ] Verify `jemaat_id` populated

### Exports & Reports
- [ ] Access Kartu Jemaat → verify accessible
- [ ] Print Kartu Jemaat → verify works
- [ ] Export jemaat data → verify works
- [ ] View reports/graphs → verify accessible

---

## Lingkungan Admin Role Tests

### Menu & Navigation
- [ ] Login as Lingkungan Admin
- [ ] Verify Jemaat menu visible
- [ ] Verify Admin menu NOT visible
- [ ] Try to access `/admin/*` → verify 403 error

### Data Access (Scoping)
- [ ] View jemaat data → verify ONLY assigned lingkungan visible
- [ ] Count records → verify matches assigned lingkungan count
- [ ] Try to access jemaat from unassigned lingkungan (via direct URL/ID)
  - [ ] Verify 404 or 403 error
- [ ] Verify DataTables pagination only shows scoped data

### CRUD Operations (Draft + Scoping)
- [ ] Create jemaat for assigned lingkungan → verify draft created
- [ ] Try to create jemaat for unassigned lingkungan (if form allows)
  - [ ] Verify validation error or 403
- [ ] Update jemaat from assigned lingkungan → verify draft created
- [ ] Try to update jemaat from unassigned lingkungan
  - [ ] Verify 404 or 403

### Edge Cases
- [ ] Remove lingkungan assignment from user
- [ ] Try to access data → verify 403 with message "No lingkungan assigned"
- [ ] Reassign lingkungan
- [ ] Verify access restored to newly assigned lingkungan

---

## Draft Workflow Tests

### Draft Creation
- [ ] Verify draft record in `jemaat_drafts` table
- [ ] Verify `draft_data` JSON structure correct
- [ ] Verify `submitted_by` = current user ID
- [ ] Verify `submitted_at` timestamp populated
- [ ] Verify `status` = 'pending_review'

### Approval Process
- [ ] Login as Superadmin
- [ ] Approve draft with optional note
- [ ] Verify data published to `data_jemaats`
- [ ] Verify draft status = 'approved'
- [ ] Verify `reviewed_by` = superadmin user ID
- [ ] Verify `reviewed_at` timestamp populated
- [ ] Verify `draft_histories` logged with action = 'approved'

### Rejection Process
- [ ] Reject draft without note → verify validation error
- [ ] Reject draft with note → verify accepted
- [ ] Verify draft status = 'rejected'
- [ ] Verify data NOT published to main table
- [ ] Verify `reviewer_note` saved
- [ ] Verify history logged

### Revision Request Process
- [ ] Request revision without note → verify validation error
- [ ] Request revision with note → verify accepted
- [ ] Verify draft status = 'revision_required'
- [ ] Verify submitter can re-edit (if implemented)
- [ ] Verify history logged

---

## Authorization Tests

### Permission Checks
- [ ] Create user with no roles
- [ ] Try to access protected routes → verify 403 or redirect
- [ ] Remove `jemaat.view` from role
- [ ] Try to access `/data-jemaat` → verify 403

### Blade Directives
- [ ] Inspect HTML as SNK
- [ ] Verify Admin menu not rendered in HTML
- [ ] Verify permission-protected elements hidden
- [ ] Test `@role`, `@permission`, `@canseemenu` directives

### Route Protection
- [ ] Use Postman or curl to access protected routes
- [ ] Verify middleware blocks unauthorized access
- [ ] Verify API/form submissions check permissions server-side

---

## Data Integrity Tests

### Transaction Rollback
- [ ] Force approval error (e.g., invalid data, database constraint)
- [ ] Verify draft status unchanged
- [ ] Verify no partial data in main table
- [ ] Verify database rolled back

### Audit Trail
- [ ] Check `draft_histories` table after multiple operations
- [ ] Verify all actions logged (created, submitted, approved, rejected, etc.)
- [ ] Verify `performed_by` always populated
- [ ] Verify timestamps correct

### Foreign Key Constraints
- [ ] Try to delete user with drafts
- [ ] Verify drafts remain (reviewed_by set to NULL)
- [ ] Delete role → verify user_roles cascade delete
- [ ] Delete permission → verify role_permissions cascade delete

---

## Performance Tests

### Lingkungan Scoping
- [ ] Enable SQL query logging
- [ ] Access data as Lingkungan Admin
- [ ] Verify global scope applied in query
- [ ] Verify query includes `WHERE id_lingkungan IN (...)`
- [ ] Test with large dataset (if possible)

### Menu Loading
- [ ] Check number of queries for sidebar menu
- [ ] Verify relationships eager-loaded
- [ ] Check page load time (should be < 200ms)

---

## Security Tests

### Authorization Bypass Attempts
- [ ] Manually craft POST request to `/admin/roles` as SNK
- [ ] Verify 403 response
- [ ] Try to manipulate form data to change lingkungan
- [ ] Verify server-side validation blocks it
- [ ] Try to change `submitted_by` in draft
- [ ] Verify uses Auth::id() instead

### SQL Injection
- [ ] Enter SQL in search fields
- [ ] Verify properly escaped/parameterized
- [ ] Test lingkungan scope with malicious input

### XSS Prevention
- [ ] Submit draft with `<script>alert('XSS')</script>` in field
- [ ] View draft in approval page
- [ ] Verify HTML escaped (displays as text)
- [ ] Test reviewer notes with HTML entities

---

## Edge Cases

### User Assignment
- [ ] Assign multiple roles to one user
- [ ] Verify combined permissions work
- [ ] Remove all roles → verify no access
- [ ] Assign role back → verify access restored

### Draft Edge Cases
- [ ] Try to approve already-approved draft
- [ ] Verify error handling
- [ ] Delete jemaat with pending draft
- [ ] Verify draft `jemaat_id` set to NULL
- [ ] Verify draft still reviewable

### Validation
- [ ] Create role with duplicate slug → verify error
- [ ] Assign non-existent permission → verify error
- [ ] Submit empty required fields in draft → verify validation

---

## Final Verification Checklist

### Functionality
- [ ] All RBAC features working
- [ ] All draft workflow states functional
- [ ] All admin UI pages accessible and functional
- [ ] No PHP errors in logs
- [ ] No JavaScript console errors

### Security
- [ ] No unauthorized access possible
- [ ] All routes protected
- [ ] Blade directives hiding sensitive UI
- [ ] Server-side validation working

### Data Integrity
- [ ] Transactions working
- [ ] Audit trail complete
- [ ] Foreign keys enforcing integrity
- [ ] No orphaned records

### Performance
- [ ] Page load times acceptable
- [ ] Queries optimized
- [ ] No N+1 query issues

---

## Sign-off

### Before Production
- [ ] All tests passed
- [ ] Documentation complete
- [ ] Database backup taken
- [ ] Rollback plan documented
- [ ] User training completed
- [ ] Production deployment plan ready

### Post-Deployment
- [ ] Monitor logs for 24 hours
- [ ] Verify all features work in production
- [ ] Collect user feedback
- [ ] Document any issues for future fixes
