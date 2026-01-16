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

    /**
     * Display list of pending drafts.
     *
     * @return \Illuminate\View\View
     */
    public function pending()
    {
        $drafts = $this->draftService->getPendingReviewDrafts();
        return view('admin.drafts.pending', compact('drafts'));
    }

    /**
     * Show draft review page.
     *
     * @param JemaatDraft $draft
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function review(JemaatDraft $draft)
    {
        if ($draft->status !== 'pending_review') {
            return redirect()->route('admin.drafts.pending')
                ->with('error', 'Draft is not in pending review status.');
        }

        $draftData = is_array($draft->draft_data) ? $draft->draft_data : json_decode($draft->draft_data, true);
        return view('admin.drafts.review', compact('draft', 'draftData'));
    }

    /**
     * Approve a draft and publish it.
     *
     * @param Request $request
     * @param JemaatDraft $draft
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Reject a draft.
     *
     * @param Request $request
     * @param JemaatDraft $draft
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Request revision for a draft.
     *
     * @param Request $request
     * @param JemaatDraft $draft
     * @return \Illuminate\Http\RedirectResponse
     */
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
