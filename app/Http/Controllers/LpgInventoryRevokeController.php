<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportModel_LPG_Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class LpgInventoryRevokeController extends Controller
{
    private const APPROVER_ROLES = ['Admin', 'SUAdmin', 'Accounting_Staff'];

    public function request(Request $request)
    {
        $input = $request->validate([
            'cashiers_report_lpg_inventory_id' => ['required', 'integer'],
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        $record = $this->visibleInventory((int) $input['cashiers_report_lpg_inventory_id']);
        abort_unless($record, 404, 'Inventory transaction not found or you do not have access.');
        abort_unless(!$record->deleted_at, 422, 'This transaction is already revoked.');
        abort_if($record->inventory_mode === 'BEGINNING', 422, 'Beginning Inventory is managed through the Starting Audit approval and revoke process.');
        abort_if(DB::table('teves_lpg_inventory_revoke_requests')->where('inventory_id', $record->cashiers_report_lpg_inventory_id)->where('status', 'PENDING')->exists(), 422, 'A revoke request is already pending for this transaction.');

        $requestId = DB::table('teves_lpg_inventory_revoke_requests')->insertGetId([
            'inventory_id' => $record->cashiers_report_lpg_inventory_id,
            'report_id' => $record->cashiers_report_id,
            'requested_by' => Session::get('loginID'),
            'request_reason' => $input['reason'],
            'status' => 'PENDING',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $report = DB::table('teves_cashiers_report as report')
            ->leftJoin('teves_branch_table as branch', 'branch.branch_id', '=', 'report.teves_branch')
            ->where('report.cashiers_report_id', $record->cashiers_report_id)
            ->first(['report.teves_branch', 'report.report_date', 'branch.branch_name']);
        $approvers = $this->approversForBranch((int) $report->teves_branch);
        $number = $this->transactionNumber((int) $record->cashiers_report_lpg_inventory_id);
        $message = "A revoke request for {$number} ({$record->product_name}) at " . ($report->branch_name ?? 'the LPG branch') . ' is waiting for approval.';
        foreach ($approvers as $approver) {
            if (!$approver->user_email_address) continue;
            try {
                Mail::raw($message . "\n\nOpen the LPG Starting Audit Approvals page to review it.", function ($mail) use ($approver) {
                    $mail->to($approver->user_email_address)->subject('LPG inventory revoke request needs approval');
                });
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return response()->json(['status' => true, 'request_id' => $requestId, 'message' => "Revoke request for {$number} submitted. The transaction remains active until an approver approves it."]);
    }

    public function approve(int $requestId)
    {
        $this->requireApprover();
        $request = DB::transaction(function () use ($requestId) {
            $request = $this->visibleRequests()->where('request.request_id', $requestId)->lockForUpdate()->first();
            abort_unless($request, 404, 'Revoke request not found or you do not have access.');
            abort_unless($request->status === 'PENDING', 422, 'This revoke request has already been reviewed.');
            abort_unless((int) $request->requested_by !== (int) Session::get('loginID'), 403, 'You cannot approve your own revoke request.');

            $record = CashiersReportModel_LPG_Inventory::where('cashiers_report_lpg_inventory_id', $request->inventory_id)->lockForUpdate()->first();
            abort_unless($record && !$record->deleted_at, 422, 'The inventory transaction has already been removed.');
            if ($record->inventory_mode === 'BEGINNING') {
                abort_if(DB::table('teves_cashiers_report_lpg_starting_audits')->where('report_id', $record->cashiers_report_id)->whereIn('status', ['PENDING', 'APPROVED'])->exists(), 422, 'This Beginning Inventory is part of a submitted or approved Starting Audit. Return the Starting Audit for correction instead.');
            }

            $record->delete();
            DB::table('teves_lpg_inventory_revoke_requests')->where('request_id', $requestId)->update([
                'status' => 'APPROVED', 'reviewed_by' => Session::get('loginID'), 'reviewed_at' => now(), 'updated_at' => now(),
            ]);
            return $request;
        });

        $request->transaction_no = $this->transactionNumber((int) $request->inventory_id);
        $this->notifyRequester($request, 'APPROVED', 'LPG inventory revoke request approved', 'Your revoke request for ' . $request->transaction_no . ' was approved. The inventory transaction is now voided.');
        return response()->json(['status' => true, 'message' => "{$request->transaction_no} approved and voided. The transaction record remains available in the activity log."]);
    }

    public function reject(Request $httpRequest, int $requestId)
    {
        $this->requireApprover();
        $input = $httpRequest->validate(['reason' => ['required', 'string', 'max:2000']]);
        $request = DB::transaction(function () use ($requestId, $input) {
            $request = $this->visibleRequests()->where('request.request_id', $requestId)->lockForUpdate()->first();
            abort_unless($request, 404, 'Revoke request not found or you do not have access.');
            abort_unless($request->status === 'PENDING', 422, 'This revoke request has already been reviewed.');
            abort_unless((int) $request->requested_by !== (int) Session::get('loginID'), 403, 'You cannot review your own revoke request.');
            DB::table('teves_lpg_inventory_revoke_requests')->where('request_id', $requestId)->update([
                'status' => 'REJECTED', 'reviewed_by' => Session::get('loginID'), 'reviewed_at' => now(), 'decision_reason' => $input['reason'], 'updated_at' => now(),
            ]);
            return $request;
        });

        $request->transaction_no = $this->transactionNumber((int) $request->inventory_id);
        $this->notifyRequester($request, 'REJECTED', 'LPG inventory revoke request declined', 'Your revoke request for ' . $request->transaction_no . ' was declined: ' . $input['reason']);
        return response()->json(['status' => true, 'message' => "{$request->transaction_no} revoke request declined."]);
    }

    public static function pendingForCurrentApprover()
    {
        $requests = self::visibleRequestQuery()->where('request.status', 'PENDING')->orderBy('request.created_at')->get();
        foreach ($requests as $request) {
            $request->transaction_no = 'LPG-INV-' . str_pad((string) $request->inventory_id, 8, '0', STR_PAD_LEFT);
        }
        return $requests;
    }

    private function visibleInventory(int $id)
    {
        return DB::table('teves_cashiers_report_lpg_inventory as inventory')
            ->join('teves_cashiers_report as report', 'report.cashiers_report_id', '=', 'inventory.cashiers_report_id')
            ->leftJoin('teves_product_table as product', 'product.product_id', '=', 'inventory.product_idx')
            ->where('inventory.cashiers_report_lpg_inventory_id', $id)->where('report.cashier_report_type', 'LPG')->whereNull('report.deleted_at')
            ->when(Session::get('user_branch_access_type') !== 'ALL', function ($query) {
                $query->whereIn('report.teves_branch', DB::table('teves_user_branch_access')->where('user_idx', Session::get('loginID'))->select('branch_idx'));
            })->first(['inventory.*', 'product.product_name']);
    }

    private function visibleRequests()
    {
        return self::visibleRequestQuery();
    }

    private static function visibleRequestQuery()
    {
        $query = DB::table('teves_lpg_inventory_revoke_requests as request')
            ->join('teves_cashiers_report_lpg_inventory as inventory', 'inventory.cashiers_report_lpg_inventory_id', '=', 'request.inventory_id')
            ->whereColumn('inventory.cashiers_report_id', 'request.report_id')
            ->join('teves_cashiers_report as report', 'report.cashiers_report_id', '=', 'request.report_id')
            ->leftJoin('teves_branch_table as branch', 'branch.branch_id', '=', 'report.teves_branch')
            ->leftJoin('teves_product_table as product', 'product.product_id', '=', 'inventory.product_idx')
            ->leftJoin('user_tb as requester', 'requester.user_id', '=', 'request.requested_by')
            ->where('report.cashier_report_type', 'LPG')->whereNull('report.deleted_at')
            ->select('request.*', 'inventory.inventory_mode', 'inventory.quantity', 'product.product_name', 'product.product_unit_measurement', 'report.report_date', 'report.shift', 'report.teves_branch', 'branch.branch_name', 'requester.user_real_name as requester_name');
        if (Session::get('user_branch_access_type') !== 'ALL') {
            $query->whereIn('report.teves_branch', DB::table('teves_user_branch_access')->where('user_idx', Session::get('loginID'))->select('branch_idx'));
        }
        return $query;
    }

    private function approversForBranch(int $branchId)
    {
        return DB::table('user_tb as user')->whereIn('user.user_type', self::APPROVER_ROLES)->where('user.user_status', 'Active')
            ->where(function ($query) use ($branchId) {
                $query->where('user.user_branch_access_type', 'ALL')->orWhereExists(function ($access) use ($branchId) {
                    $access->selectRaw('1')->from('teves_user_branch_access as access')->whereColumn('access.user_idx', 'user.user_id')->where('access.branch_idx', $branchId);
                });
            })->get(['user.user_id', 'user.user_email_address']);
    }

    private function notifyRequester(object $request, string $status, string $title, string $message): void
    {
        $user = DB::table('user_tb')->where('user_id', $request->requested_by)->first(['user_email_address']);
        if ($user && $user->user_email_address) {
            try {
                Mail::raw($message, function ($mail) use ($user, $title) { $mail->to($user->user_email_address)->subject($title); });
            } catch (\Throwable $exception) {
                report($exception);
            }
        }
    }

    private function transactionNumber(int $id): string
    {
        return 'LPG-INV-' . str_pad((string) $id, 8, '0', STR_PAD_LEFT);
    }

    private function requireApprover(): void
    {
        abort_unless(in_array(Session::get('UserType'), self::APPROVER_ROLES, true), 403, 'You are not authorized to review LPG inventory revoke requests.');
    }
}
