<?php

namespace App\Services\Workflow;

use App\Models\DraftHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

abstract class DraftService
{
    /**
     * Approve a draft and publish it to the main table.
     *
     * @param mixed $draft
     * @param string|null $note
     * @return bool
     * @throws \Exception
     */
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

    /**
     * Reject a draft.
     *
     * @param mixed $draft
     * @param string $note
     * @return bool
     * @throws \Exception
     */
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

    /**
     * Request revision for a draft.
     *
     * @param mixed $draft
     * @param string $note
     * @return bool
     * @throws \Exception
     */
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

    /**
     * Publish the draft to the main table.
     * Must be implemented by child classes.
     *
     * @param mixed $draft
     * @return void
     */
    abstract protected function publishDraft($draft): void;

    /**
     * Log history for draft state changes.
     *
     * @param mixed $draft
     * @param string $action
     * @param string|null $from
     * @param string $to
     * @param string|null $note
     * @return void
     */
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
