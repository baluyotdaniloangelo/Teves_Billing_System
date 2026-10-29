<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class LpgStartingAuditController extends Controller
{
    private const APPROVER_ROLES = ['Admin', 'SUAdmin', 'Accounting_Staff'];

    public function index()
    {
        $this->requireApprover();
        $data = $this->currentUser();
        $title = 'LPG Starting Audit Approvals';
        $audits = $this->visibleAudits()->where('audit.status', 'PENDING')
            ->where('audit.submitted_by', '<>', Session::get('loginID'))->orderBy('audit.submitted_at')->get();
        $approvedAudits = $this->visibleAudits()->where('audit.status', 'APPROVED')
            ->orderByDesc('audit.approved_at')->limit(100)->get();
        $inventoryRevokeRequests = LpgInventoryRevokeController::pendingForCurrentApprover();
        return view('pages.lpg_starting_audits.index', compact('data', 'title', 'audits', 'approvedAudits', 'inventoryRevokeRequests'));
    }

    public function show(int $auditId)
    {
        $this->requireApprover();
        $data = $this->currentUser();
        $title = 'Review LPG Starting Audit';
        $audit = $this->visibleAudits()->where('audit.audit_id', $auditId)->first();
        abort_unless($audit, 404, 'Starting audit not found or you do not have access.');
        $items = DB::table('teves_cashiers_report_lpg_starting_audit_items as item')
            ->leftJoin('teves_product_table as product', 'product.product_id', '=', 'item.product_idx')
            ->where('item.audit_id', $auditId)->orderBy('product.product_name')
            ->get(['item.*', 'product.product_name', 'product.product_unit_measurement', 'product.product_capacity', 'product.product_capacity_unit']);
        return view('pages.lpg_starting_audits.show', compact('data', 'title', 'audit', 'items'));
    }

    public function status(Request $request)
    {
        $reportId = $this->accessibleReportId((int) $request->input('report_id'));
        $audit = DB::table('teves_cashiers_report_lpg_starting_audits as audit')
            ->leftJoin('user_tb as submitter', 'submitter.user_id', '=', 'audit.submitted_by')
            ->where('audit.report_id', $reportId)->first(['audit.*', 'submitter.user_real_name as submitted_by_name']);
        if ($audit) $audit->item_count = DB::table('teves_cashiers_report_lpg_starting_audit_items')->where('audit_id', $audit->audit_id)->count();
        return response()->json(['audit' => $audit]);
    }

    public function submit(Request $request)
    {
        $reportId = $this->accessibleReportId((int) $request->input('report_id'));
        $audit = DB::transaction(function () use ($reportId) {
            $audit = DB::table('teves_cashiers_report_lpg_starting_audits')->where('report_id', $reportId)->lockForUpdate()->first();
            abort_unless($audit, 422, 'Encode at least one Starting Audit item before submitting.');
            abort_unless(in_array($audit->status, ['DRAFT', 'RETURNED'], true), 422, 'This audit has already been submitted or approved.');
            $count = DB::table('teves_cashiers_report_lpg_starting_audit_items')->where('audit_id', $audit->audit_id)->count();
            abort_unless($count > 0, 422, 'Encode at least one Starting Audit item before submitting.');
            DB::table('teves_cashiers_report_lpg_starting_audits')->where('audit_id', $audit->audit_id)->update([
                'status' => 'PENDING', 'submitted_by' => Session::get('loginID'), 'submitted_at' => now(),
                'returned_by' => null, 'returned_at' => null, 'return_reason' => null, 'updated_at' => now(),
            ]);
            return (object) array_merge((array) $audit, ['status' => 'PENDING']);
        });

        $report = $this->reportContext($reportId);
        $approvers = $this->approversForBranch((int) $report->teves_branch);
        $message = "Starting audit for {$report->branch_name} on {$report->report_date} (Shift {$report->shift}) is waiting for approval.";
        foreach ($approvers as $user) {
            $this->notify($user->user_id, $audit->audit_id, 'SUBMITTED', 'LPG Starting Audit needs approval', $message);
            if ($user->user_email_address) {
                try {
                    Mail::raw($message . "\n\nOpen the LPG Starting Audit Approvals page to review it.", function ($mail) use ($user) {
                        $mail->to($user->user_email_address)->subject('LPG Starting Audit needs approval');
                    });
                } catch (\Throwable $exception) {
                    report($exception);
                }
            }
        }
        return response()->json(['status' => true, 'message' => 'Starting Audit submitted for approval. It will not carry forward until approved.']);
    }

    public function approve(int $auditId)
    {
        $this->requireApprover();
        $audit = DB::transaction(function () use ($auditId) {
            $audit = $this->visibleAudits()->where('audit.audit_id', $auditId)->lockForUpdate()->first();
            abort_unless($audit, 404, 'Starting audit not found or you do not have access.');
            abort_unless($audit->status === 'PENDING', 422, 'Only pending audits can be approved.');
            abort_unless((int) $audit->submitted_by !== (int) Session::get('loginID'), 403, 'You cannot approve your own Starting Audit.');
            DB::table('teves_cashiers_report_lpg_starting_audits')->where('audit_id', $auditId)->update([
                'status' => 'APPROVED', 'approved_by' => Session::get('loginID'), 'approved_at' => now(), 'updated_at' => now(),
            ]);
            return $audit;
        });
        $this->notifyOutcome($audit, 'APPROVED', 'LPG Starting Audit approved', 'Your LPG Starting Audit for ' . $audit->branch_name . ' on ' . $audit->report_date . ' was approved. It now carries forward to subsequent reports.');
        return response()->json(['status' => true, 'message' => 'Starting Audit approved. It will now carry forward to subsequent reports.']);
    }

    public function returnForCorrection(Request $request, int $auditId)
    {
        $this->requireApprover();
        $data = $request->validate(['reason' => ['required', 'string', 'max:2000']]);
        $audit = DB::transaction(function () use ($auditId, $data) {
            $audit = $this->visibleAudits()->where('audit.audit_id', $auditId)->lockForUpdate()->first();
            abort_unless($audit, 404, 'Starting audit not found or you do not have access.');
            abort_unless(in_array($audit->status, ['PENDING', 'APPROVED'], true), 422, 'Only pending or approved audits can be returned for correction.');
            if ($audit->status === 'PENDING') {
                abort_unless((int) $audit->submitted_by !== (int) Session::get('loginID'), 403, 'You cannot review your own Starting Audit.');
            } else {
                abort_unless((int) $audit->approved_by !== (int) Session::get('loginID'), 403, 'A different approver must revoke this approval.');
            }
            DB::table('teves_cashiers_report_lpg_starting_audits')->where('audit_id', $auditId)->update([
                'status' => 'RETURNED', 'returned_by' => Session::get('loginID'), 'returned_at' => now(),
                'return_reason' => $data['reason'], 'updated_at' => now(),
            ]);
            return $audit;
        });
        $revoked = $audit->status === 'APPROVED';
        $title = $revoked ? 'LPG Starting Audit approval revoked' : 'LPG Starting Audit returned for correction';
        $message = 'Your LPG Starting Audit for ' . $audit->branch_name . ' on ' . $audit->report_date . ($revoked ? ' had its approval revoked and needs correction: ' : ' needs correction: ') . $data['reason'];
        $this->notifyOutcome($audit, $revoked ? 'REVOKED' : 'RETURNED', $title, $message);
        return response()->json(['status' => true, 'message' => $revoked
            ? 'Approval revoked. The audit no longer carries forward; the encoder can correct it and submit it again.'
            : 'Starting Audit returned to the encoder for correction.']);
    }

    public function notifications()
    {
        $data = $this->currentUser();
        $title = 'LPG Audit Notifications';
        $notifications = DB::table('teves_lpg_audit_notifications as notification')
            ->join('teves_cashiers_report_lpg_starting_audits as audit', 'audit.audit_id', '=', 'notification.audit_id')
            ->join('teves_cashiers_report as report', 'report.cashiers_report_id', '=', 'audit.report_id')
            ->leftJoin('teves_branch_table as branch', 'branch.branch_id', '=', 'report.teves_branch')
            ->where('notification.user_idx', Session::get('loginID'))->orderByDesc('notification.created_at')
            ->get(['notification.*', 'audit.report_id', 'branch.branch_name', 'report.report_date', 'report.shift']);
        return view('pages.lpg_starting_audits.notifications', compact('data', 'title', 'notifications'));
    }

    public function markRead(int $notificationId)
    {
        DB::table('teves_lpg_audit_notifications')->where('notification_id', $notificationId)
            ->where('user_idx', Session::get('loginID'))->whereNull('read_at')->update(['read_at' => now(), 'updated_at' => now()]);
        return response()->json(['status' => true]);
    }

    public function unreadCount()
    {
        $count = DB::table('teves_lpg_audit_notifications')->where('user_idx', Session::get('loginID'))->whereNull('read_at')->count();
        return response()->json(['unread' => $count]);
    }

    private function visibleAudits()
    {
        $query = DB::table('teves_cashiers_report_lpg_starting_audits as audit')
            ->join('teves_cashiers_report as report', 'report.cashiers_report_id', '=', 'audit.report_id')
            ->leftJoin('teves_branch_table as branch', 'branch.branch_id', '=', 'report.teves_branch')
            ->leftJoin('user_tb as encoder', 'encoder.user_id', '=', 'audit.submitted_by')
            ->where('report.cashier_report_type', 'LPG')->whereNull('report.deleted_at')
            ->select('audit.*', 'report.report_date', 'report.shift', 'report.teves_branch', 'report.cashiers_name', 'branch.branch_name', 'encoder.user_real_name as encoder_name');
        if (Session::get('user_branch_access_type') !== 'ALL') {
            $query->whereIn('report.teves_branch', DB::table('teves_user_branch_access')->where('user_idx', Session::get('loginID'))->select('branch_idx'));
        }
        return $query;
    }

    private function accessibleReportId(int $reportId): int
    {
        abort_unless($reportId > 0, 422, 'A valid LPG report is required.');
        $query = DB::table('teves_cashiers_report')->where('cashiers_report_id', $reportId)->where('cashier_report_type', 'LPG')->whereNull('deleted_at');
        if (Session::get('user_branch_access_type') !== 'ALL') {
            $query->whereIn('teves_branch', DB::table('teves_user_branch_access')->where('user_idx', Session::get('loginID'))->select('branch_idx'));
        }
        abort_unless($query->exists(), 404, 'LPG report not found or you do not have access.');
        return $reportId;
    }

    private function reportContext(int $reportId): object
    {
        return DB::table('teves_cashiers_report as report')->leftJoin('teves_branch_table as branch', 'branch.branch_id', '=', 'report.teves_branch')
            ->where('report.cashiers_report_id', $reportId)->first(['report.*', 'branch.branch_name']);
    }

    private function requireApprover(): void
    {
        abort_unless(in_array(Session::get('UserType'), self::APPROVER_ROLES, true), 403, 'You are not authorized to review starting audits.');
    }

    private function currentUser(): object
    {
        $user = DB::table('user_tb')->where('user_id', Session::get('loginID'))->first();
        abort_unless($user, 404, 'User account not found.');
        return $user;
    }

    private function approversForBranch(int $branchId)
    {
        return DB::table('user_tb as user')
            ->whereIn('user.user_type', self::APPROVER_ROLES)->where('user.user_status', 'Active')
            ->where(function ($query) use ($branchId) {
                $query->where('user.user_branch_access_type', 'ALL')->orWhereExists(function ($access) use ($branchId) {
                    $access->selectRaw('1')->from('teves_user_branch_access as access')->whereColumn('access.user_idx', 'user.user_id')->where('access.branch_idx', $branchId);
                });
            })->get(['user.user_id', 'user.user_email_address']);
    }

    private function notify(int $userId, int $auditId, string $type, string $title, string $message): void
    {
        DB::table('teves_lpg_audit_notifications')->updateOrInsert(
            ['user_idx' => $userId, 'audit_id' => $auditId, 'notification_type' => $type],
            ['title' => $title, 'message' => $message, 'read_at' => null, 'created_at' => now(), 'updated_at' => now()]
        );
    }

    private function notifyOutcome(object $audit, string $type, string $title, string $message): void
    {
        if (!$audit->submitted_by) return;
        $this->notify((int) $audit->submitted_by, (int) $audit->audit_id, $type, $title, $message);
        $user = DB::table('user_tb')->where('user_id', $audit->submitted_by)->first(['user_email_address']);
        if ($user && $user->user_email_address) {
            try {
                Mail::raw($message, function ($mail) use ($user, $title) { $mail->to($user->user_email_address)->subject($title); });
            } catch (\Throwable $exception) {
                report($exception);
            }
        }
    }
}
