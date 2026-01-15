# Phase 3: Draft/Approval Workflow Tasks

## Migrations

### Create Draft Tables
- [ ] Create migration: `create_jemaat_drafts_table`
  - [ ] Add all columns (id, jemaat_id, operation_type, draft_data, status, etc.)
  - [ ] Add foreign keys with proper cascading
  - [ ] Add indexes on status, submitted_by
- [ ] Create migration: `create_simpatisan_drafts_table`
  - [ ] Mirror jemaat_drafts structure
- [ ] Create migration: `create_draft_histories_table`
  - [ ] Polymorphic design (draftable_type, draftable_id)
  - [ ] Add action ENUM
  - [ ] Add foreign keys

---

## Models

### Create Draft Models
- [ ] Create model: `app/Models/JemaatDraft.php`
  - [ ] Define fillable fields
  - [ ] Add casts for JSON fields
  - [ ] Add relationships: `submitter()`, `reviewer()`, `jemaat()`
  - [ ] Add scope for pending reviews
- [ ] Create model: `app/Models/SimpatisanDraft.php`
  - [ ] Mirror JemaatDraft structure
- [ ] Create model: `app/Models/DraftHistory.php`
  - [ ] Define fillable fields
  - [ ] Add polymorphic relationship: `draftable()`
  - [ ] Add relationship: `performer()`

---

## Services

### Create Workflow Services
- [ ] Create service: `app/Services/Workflow/DraftService.php`
  - [ ] Implement `approve($draft, $note)` method
  - [ ] Implement `reject($draft, $note)` method
  - [ ] Implement `requestRevision($draft, $note)` method
  - [ ] Implement `logHistory()` protected method
  - [ ] Define abstract `publishDraft()` method
  - [ ] Wrap all operations in DB transactions
- [ ] Create service: `app/Services/Workflow/JemaatDraftService.php`
  - [ ] Extend DraftService
  - [ ] Implement `createJemaatDraft($data, $jemaatId)` method
  - [ ] Implement `submitForReview($draft)` method
  - [ ] Implement `getPendingReviewDrafts()` method
  - [ ] Implement `publishDraft($draft)` method
  - [ ] Handle create, update, delete operation types

---

## Repository Updates

### Update DataJemaatRepository
- [ ] Ensure `storeDataJemaat()` accepts array parameter
- [ ] Ensure `updateDataJemaat()` accepts ID and array parameter
- [ ] Ensure `deleteDataJemaat()` accepts ID parameter
- [ ] All methods return appropriate values

---

## Service Layer Integration

### Modify DataJemaatService
- [ ] Inject JemaatDraftService in constructor
- [ ] Create `canPublishDirectly()` private method
  - [ ] Return true if user is superadmin
  - [ ] Return false otherwise
- [ ] Update `storeDataJemaat($data)` method:
  - [ ] Check if can publish directly
  - [ ] If yes: call repository directly
  - [ ] If no: create draft and submit for review
  - [ ] Return array with 'isDraft' flag
- [ ] Update `updateDataJemaat($id, $data)` method:
  - [ ] Apply same logic as store

---

## Controller

### Create ApprovalController
- [ ] Create controller: `app/Http/Controllers/Admin/ApprovalController.php`
  - [ ] Inject JemaatDraftService
  - [ ] Apply `role:superadmin` middleware
  - [ ] Implement `pending()` method
  - [ ] Implement `review($draft)` method
  - [ ] Implement `approve(Request, $draft)` method
  - [ ] Implement `reject(Request, $draft)` method
  - [ ] Implement `requestRevision(Request, $draft)` method
  - [ ] Add validation for reviewer notes
  - [ ] Add try-catch for error handling

---

## Routes

### Add Approval Routes
- [ ] Add routes in `routes/web.php` under admin group:
  - [ ] `GET /admin/drafts/pending` → ApprovalController@pending
  - [ ] `GET /admin/drafts/{draft}/review` → ApprovalController@review
  - [ ] `POST /admin/drafts/{draft}/approve` → ApprovalController@approve
  - [ ] `POST /admin/drafts/{draft}/reject` → ApprovalController@reject
  - [ ] `POST /admin/drafts/{draft}/request-revision` → ApprovalController@requestRevision

---

## Views

### Create Draft Views
- [ ] Create view: `resources/views/admin/drafts/pending.blade.php`
  - [ ] Display list of pending drafts
  - [ ] Show draft ID, type, submitter, submitted date
  - [ ] Add "Review" button linking to review page
- [ ] Create view: `resources/views/admin/drafts/review.blade.php`
  - [ ] Display draft information
  - [ ] Display submitted data (JSON pretty-printed)
  - [ ] Add form for approval (with optional note)
  - [ ] Add form for rejection (with required note)
  - [ ] Add form for revision request (with required note)

---

## Controller Updates

### Update DataJemaatController
- [ ] Update `store()` method:
  - [ ] Call service layer's `storeDataJemaat()`
  - [ ] Check `isDraft` flag in response
  - [ ] Redirect with appropriate message
- [ ] Update `update()` method:
  - [ ] Apply same logic as store

---

## Testing

### Unit Tests (if time permits)
- [ ] Test `canPublishDirectly()` returns true for superadmin
- [ ] Test `canPublishDirectly()` returns false for SNK
- [ ] Test draft creation saves to jemaat_drafts table
- [ ] Test approval publishes to data_jemaats table
- [ ] Test rejection does not publish data

### Manual Testing
- [ ] As SNK:
  - [ ] Create jemaat → draft created, status pending_review
  - [ ] Update jemaat → draft created with operation_type = update
  - [ ] Verify draft_data JSON contains all fields
- [ ] As Superadmin:
  - [ ] View pending drafts list
  - [ ] Review draft → see all data
  - [ ] Approve draft → data published, status = approved
  - [ ] Reject draft → status = rejected, no data published
  - [ ] Request revision → status = revision_required
- [ ] Verify draft_histories:
  - [ ] All actions logged
  - [ ] `performed_by` populated
  - [ ] Timestamps correct

### Transaction Testing
- [ ] Force error during approval → verify rollback
- [ ] Database state unchanged if error occurs
- [ ] No partial data in main table

---

## Documentation
- [ ] Document draft workflow states
- [ ] Document service layer architecture
- [ ] Create flowchart for approval process
- [ ] Document API methods for developers
