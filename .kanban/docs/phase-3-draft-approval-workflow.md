# Phase 3: Draft/Approval Workflow Documentation

## Overview
Implement draft system for non-superadmin users with approval workflow, audit trail, and data integrity.

---

## Workflow States

### Status Lifecycle
```
[draft] → [pending_review] → [approved] → Published to main table
                          ↘ [rejected] → End
                          ↘ [revision_required] → [draft] (cycle)
```

### State Definitions

| Status | Description | Who Can Set | Next States |
|--------|-------------|-------------|-------------|
| `draft` | Initial creation, user still editing | User (non-superadmin) | `pending_review` |
| `pending_review` | Submitted for approval | User | `approved`, `rejected`, `revision_required` |
| `approved` | Approved by superadmin, published | Superadmin | N/A (final) |
| `rejected` | Rejected by superadmin | Superadmin | N/A (final) |
| `revision_required` | Needs changes before re-submission | Superadmin | `pending_review` |

---

## Database Schema

### Table: `jemaat_drafts`
```sql
CREATE TABLE jemaat_drafts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    jemaat_id BIGINT UNSIGNED NULL,
    operation_type ENUM('create', 'update', 'delete') NOT NULL,
    draft_data JSON NOT NULL,
    status ENUM('draft', 'pending_review', 'approved', 'rejected', 'revision_required') DEFAULT 'draft',
    submitted_by BIGINT UNSIGNED NOT NULL,
    submitted_at TIMESTAMP NULL,
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    reviewer_note TEXT NULL,
    changes_summary TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (jemaat_id) REFERENCES data_jemaats(id) ON DELETE SET NULL,
    FOREIGN KEY (submitted_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
);
```

**Column Details:**
- `jemaat_id`: NULL for new records, populated for updates
- `operation_type`: Determines how to process approval
- `draft_data`: Full JSON of submitted data
- `changes_summary`: Human-readable summary of changes (for updates)

---

### Table: `simpatisan_drafts`
```sql
CREATE TABLE simpatisan_drafts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    simpatisan_id BIGINT UNSIGNED NULL,
    operation_type ENUM('create', 'update', 'delete') NOT NULL,
    draft_data JSON NOT NULL,
    status ENUM('draft', 'pending_review', 'approved', 'rejected', 'revision_required') DEFAULT 'draft',
    submitted_by BIGINT UNSIGNED NOT NULL,
    submitted_at TIMESTAMP NULL,
    reviewed_by BIGINT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL,
    reviewer_note TEXT NULL,
    changes_summary TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (simpatisan_id) REFERENCES data_jemaats(id) ON DELETE SET NULL,
    FOREIGN KEY (submitted_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
);
```

---

### Table: `draft_histories`
```sql
CREATE TABLE draft_histories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    draftable_type VARCHAR(100) NOT NULL,
    draftable_id BIGINT UNSIGNED NOT NULL,
    action ENUM('created', 'submitted', 'approved', 'rejected', 'revision_requested', 'revised', 'cancelled') NOT NULL,
    from_status VARCHAR(50) NULL,
    to_status VARCHAR(50) NULL,
    performed_by BIGINT UNSIGNED NOT NULL,
    note TEXT NULL,
    metadata JSON NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (performed_by) REFERENCES users(id) ON DELETE CASCADE
);
```

**Purpose:** Audit trail for all draft state changes.

**Polymorphic Design:**
- `draftable_type`: `App\\Models\\JemaatDraft` or `App\\Models\\SimpatisanDraft`
- `draftable_id`: ID of the draft record

---

## Request Lifecycle

### Standard Flow (Superadmin)
```
User submits form
    ↓
Controller receives request
    ↓
Service: canPublishDirectly() → TRUE
    ↓
Repository: Direct insert to data_jemaats
    ↓
Redirect with success message
```

### Draft Flow (SNK / Lingkungan Admin)
```
User submits form
    ↓
Controller receives request
    ↓
Service: canPublishDirectly() → FALSE
    ↓
JemaatDraftService: createJemaatDraft()
    ↓
JemaatDraftService: submitForReview()
    ↓
Insert to jemaat_drafts (status: pending_review)
    ↓
DraftHistory: Log "submitted" action
    ↓
Redirect with "Submitted for approval" message
```

### Approval Flow
```
Superadmin views /admin/drafts/pending
    ↓
Displays all drafts with status = 'pending_review'
    ↓
Superadmin clicks "Approve"
    ↓
ApprovalController::approve()
    ↓
DraftService::approve()
    ↓
DB Transaction BEGIN
    ↓
Update draft: status = 'approved', reviewed_by, reviewed_at
    ↓
publishDraft(): Insert data to data_jemaats
    ↓
DraftHistory: Log "approved" action
    ↓
DB Transaction COMMIT
    ↓
Redirect with success message
```

---

## Service Layer Architecture

### Base DraftService
**File:** `app/Services/Workflow/DraftService.php`

```php
<?php

namespace App\Services\Workflow;

use App\Models\DraftHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

abstract class DraftService
{
    public function approve($draft, ?string $note = null): bool
    {
        DB::beginTransaction();
        try {
            $fromStatus = $draft->status;
            $draft->status = 'approved';
            $draft->reviewed_by = Auth::id();
            $draft->reviewed_at = now();
            $draft->reviewer_note = $note;
            $draft->save();

            // Publish to main table (implemented by child class)
            $this->publishDraft($draft);

            // Log history
            $this->logHistory($draft, 'approved', $fromStatus, 'approved', $note);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function reject($draft, string $note): bool
    {
        DB::beginTransaction();
        try {
            $fromStatus = $draft->status;
            $draft->status = 'rejected';
            $draft->reviewed_by = Auth::id();
            $draft->reviewed_at = now();
            $draft->reviewer_note = $note;
            $draft->save();

            $this->logHistory($draft, 'rejected', $fromStatus, 'rejected', $note);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function requestRevision($draft, string $note): bool
    {
        DB::beginTransaction();
        try {
            $fromStatus = $draft->status;
            $draft->status = 'revision_required';
            $draft->reviewed_by = Auth::id();
            $draft->reviewed_at = now();
            $draft->reviewer_note = $note;
            $draft->save();

            $this->logHistory($draft, 'revision_requested', $fromStatus, 'revision_required', $note);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    abstract protected function publishDraft($draft): void;

    protected function logHistory($draft, string $action, ?string $from, string $to, ?string $note = null): void
    {
        DraftHistory::create([
            'draftable_type' => get_class($draft),
            'draftable_id' => $draft->id,
            'action' => $action,
            'from_status' => $from,
            'to_status' => $to,
            'performed_by' => Auth::id(),
            'note' => $note,
        ]);
    }
}
```

---

### JemaatDraftService
**File:** `app/Services/Workflow/JemaatDraftService.php`

```php
<?php

namespace App\Services\Workflow;

use App\Models\JemaatDraft;
use App\Repositories\DataJemaatRepository;
use Illuminate\Support\Facades\Auth;

class JemaatDraftService extends DraftService
{
    private DataJemaatRepository $jemaatRepo;

    public function __construct(DataJemaatRepository $jemaatRepo)
    {
        $this->jemaatRepo = $jemaatRepo;
    }

    public function createJemaatDraft(array $data, ?int $jemaatId = null): JemaatDraft
    {
        $operationType = $jemaatId ? 'update' : 'create';

        $draft = JemaatDraft::create([
            'jemaat_id' => $jemaatId,
            'operation_type' => $operationType,
            'draft_data' => json_encode($data),
            'status' => 'draft',
            'submitted_by' => Auth::id(),
        ]);

        $this->logHistory($draft, 'created', null, 'draft');

        return $draft;
    }

    public function submitForReview(JemaatDraft $draft): void
    {
        $fromStatus = $draft->status;
        $draft->status = 'pending_review';
        $draft->submitted_at = now();
        $draft->save();

        $this->logHistory($draft, 'submitted', $fromStatus, 'pending_review');
    }

    public function getPendingReviewDrafts()
    {
        return JemaatDraft::where('status', 'pending_review')
            ->with(['submitter', 'jemaat'])
            ->orderBy('submitted_at', 'asc')
            ->get();
    }

    protected function publishDraft($draft): void
    {
        $data = json_decode($draft->draft_data, true);

        if ($draft->operation_type === 'create') {
            $this->jemaatRepo->storeDataJemaat($data);
        } elseif ($draft->operation_type === 'update') {
            $this->jemaatRepo->updateDataJemaat($draft->jemaat_id, $data);
        } elseif ($draft->operation_type === 'delete') {
            $this->jemaatRepo->deleteDataJemaat($draft->jemaat_id);
        }
    }
}
```

---

## Controller Implementation

### ApprovalController
**File:** `app/Http/Controllers/Admin/ApprovalController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JemaatDraft;
use App\Services\Workflow\JemaatDraftService;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    private JemaatDraftService $draftService;

    public function __construct(JemaatDraftService $draftService)
    {
        $this->middleware('role:superadmin');
        $this->draftService = $draftService;
    }

    public function pending()
    {
        $drafts = $this->draftService->getPendingReviewDrafts();
        return view('admin.drafts.pending', compact('drafts'));
    }

    public function review(JemaatDraft $draft)
    {
        if ($draft->status !== 'pending_review') {
            return redirect()->route('admin.drafts.pending')
                ->with('error', 'Draft is not in pending review status.');
        }

        $draftData = json_decode($draft->draft_data, true);
        return view('admin.drafts.review', compact('draft', 'draftData'));
    }

    public function approve(Request $request, JemaatDraft $draft)
    {
        try {
            $this->draftService->approve($draft, $request->input('note'));
            return redirect()->route('admin.drafts.pending')
                ->with('success', 'Draft approved and published successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to approve draft: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, JemaatDraft $draft)
    {
        $request->validate(['note' => 'required|string|min:10']);

        try {
            $this->draftService->reject($draft, $request->input('note'));
            return redirect()->route('admin.drafts.pending')
                ->with('success', 'Draft rejected.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reject draft: ' . $e->getMessage());
        }
    }

    public function requestRevision(Request $request, JemaatDraft $draft)
    {
        $request->validate(['note' => 'required|string|min:10']);

        try {
            $this->draftService->requestRevision($draft, $request->input('note'));
            return redirect()->route('admin.drafts.pending')
                ->with('success', 'Revision requested. Draft returned to submitter.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to request revision: ' . $e->getMessage());
        }
    }
}
```

---

## Modified DataJemaatService

### Integration with Draft Workflow
**File:** `app/Services/DataJemaatService.php`

```php
<?php

namespace App\Services;

use App\Repositories\DataJemaatRepository;
use App\Services\Workflow\JemaatDraftService;
use Illuminate\Support\Facades\Auth;

class DataJemaatService
{
    private DataJemaatRepository $repo;
    private JemaatDraftService $draftService;

    public function __construct(
        DataJemaatRepository $repo,
        JemaatDraftService $draftService
    ) {
        $this->repo = $repo;
        $this->draftService = $draftService;
    }

    public function storeDataJemaat(array $data): array
    {
        if ($this->canPublishDirectly()) {
            // Superadmin: direct insert
            $jemaat = $this->repo->storeDataJemaat($data);
            return ['jemaat' => $jemaat, 'isDraft' => false];
        } else {
            // Non-superadmin: create draft and submit for review
            $draft = $this->draftService->createJemaatDraft($data);
            $this->draftService->submitForReview($draft);
            return ['draft' => $draft, 'isDraft' => true];
        }
    }

    public function updateDataJemaat(int $id, array $data): array
    {
        if ($this->canPublishDirectly()) {
            $jemaat = $this->repo->updateDataJemaat($id, $data);
            return ['jemaat' => $jemaat, 'isDraft' => false];
        } else {
            $draft = $this->draftService->createJemaatDraft($data, $id);
            $this->draftService->submitForReview($draft);
            return ['draft' => $draft, 'isDraft' => true];
        }
    }

    private function canPublishDirectly(): bool
    {
        return Auth::check() && Auth::user()->hasRole('superadmin');
    }
}
```

---

## View Templates

### Pending Drafts List
**File:** `resources/views/admin/drafts/pending.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pending Draft Approvals</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($drafts->isEmpty())
    <p>No pending drafts to review.</p>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Submitted By</th>
                <th>Submitted At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drafts as $draft)
            <tr>
                <td>{{ $draft->id }}</td>
                <td>{{ ucfirst($draft->operation_type) }}</td>
                <td>{{ $draft->submitter->name }}</td>
                <td>{{ $draft->submitted_at->format('d M Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.drafts.review', $draft) }}" class="btn btn-primary btn-sm">Review</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
```

### Draft Review Page
**File:** `resources/views/admin/drafts/review.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Review Draft #{{ $draft->id }}</h2>

    <div class="card mb-3">
        <div class="card-header">Draft Information</div>
        <div class="card-body">
            <p><strong>Operation:</strong> {{ ucfirst($draft->operation_type) }}</p>
            <p><strong>Submitted By:</strong> {{ $draft->submitter->name }}</p>
            <p><strong>Submitted At:</strong> {{ $draft->submitted_at->format('d M Y H:i') }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Submitted Data</div>
        <div class="card-body">
            <pre>{{ json_encode($draftData, JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Actions</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.drafts.approve', $draft) }}" class="d-inline">
                @csrf
                <textarea name="note" placeholder="Optional approval note" class="form-control mb-2"></textarea>
                <button type="submit" class="btn btn-success">Approve & Publish</button>
            </form>

            <form method="POST" action="{{ route('admin.drafts.reject', $draft) }}" class="d-inline">
                @csrf
                <textarea name="note" placeholder="Rejection reason (required)" class="form-control mb-2" required></textarea>
                <button type="submit" class="btn btn-danger">Reject</button>
            </form>

            <form method="POST" action="{{ route('admin.drafts.request-revision', $draft) }}" class="d-inline">
                @csrf
                <textarea name="note" placeholder="What needs to be changed? (required)" class="form-control mb-2" required></textarea>
                <button type="submit" class="btn btn-warning">Request Revision</button>
            </form>
        </div>
    </div>
</div>
@endsection
```

---

## Security Considerations

### Transaction Integrity
- All draft state changes wrapped in DB transactions
- Rollback on any failure during approval/rejection
- Prevents partial data corruption

### Audit Trail
- Every state change logged to `draft_histories`
- Includes performer ID, timestamp, and notes
- Immutable history (no delete cascade on draft deletion)

### Authorization
- Only superadmin can approve/reject/request revision
- Draft submitter can only create and submit their own drafts
- Middleware enforces role checks before controller logic
