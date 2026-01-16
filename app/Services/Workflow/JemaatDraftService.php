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

    /**
     * Create a new jemaat draft.
     *
     * @param array $data
     * @param int|null $jemaatId
     * @return JemaatDraft
     */
    public function createJemaatDraft(array $data, ?int $jemaatId = null): JemaatDraft
    {
        $operationType = $jemaatId ? 'update' : 'create';

        $draft = JemaatDraft::create([
            'jemaat_id' => $jemaatId,
            'operation_type' => $operationType,
            'draft_data' => $data,
            'status' => 'draft',
            'submitted_by' => Auth::id(),
        ]);

        $this->logHistory($draft, 'created', null, 'draft');

        return $draft;
    }

    /**
     * Submit a draft for review.
     *
     * @param JemaatDraft $draft
     * @return void
     */
    public function submitForReview(JemaatDraft $draft): void
    {
        $fromStatus = $draft->status;
        $draft->status = 'pending_review';
        $draft->submitted_at = now();
        $draft->save();

        $this->logHistory($draft, 'submitted', $fromStatus, 'pending_review');
    }

    /**
     * Get all pending review drafts.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPendingReviewDrafts()
    {
        return JemaatDraft::where('status', 'pending_review')
            ->with(['submitter', 'jemaat'])
            ->orderBy('submitted_at', 'asc')
            ->get();
    }

    /**
     * Publish the draft to the main data_jemaats table.
     *
     * @param JemaatDraft $draft
     * @return void
     */
    protected function publishDraft($draft): void
    {
        $data = is_array($draft->draft_data) ? $draft->draft_data : json_decode($draft->draft_data, true);

        if ($draft->operation_type === 'create') {
            // For create operation, we need to pass the data as array
            $this->jemaatRepo->storeDataJemaat($data);
        } elseif ($draft->operation_type === 'update') {
            // For update operation, pass id and data
            $this->jemaatRepo->updateDataJemaat($data, $draft->jemaat_id);
        } elseif ($draft->operation_type === 'delete') {
            // For delete operation, implement delete method if exists
            // $this->jemaatRepo->deleteDataJemaat($draft->jemaat_id);
        }
    }
}
