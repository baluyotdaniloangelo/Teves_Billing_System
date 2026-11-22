<?php

namespace App\Services;

use App\Models\ReceivablesModel;
use App\Models\TevesBranchModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BillingReceivablesDashboard
{
    private const DUE = 'COALESCE(teves_receivable_table.receivable_amount, 0)';
    private const BALANCE = 'COALESCE(teves_receivable_table.receivable_remaining_balance, teves_receivable_table.receivable_amount, 0)';

    public function filters(Request $request): array
    {
        $branch = filter_var($request->input('branch'), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);
        $status = strtolower((string) $request->input('status', 'all'));
        $period = strtolower((string) $request->input('period', 'all'));

        return [
            'branch' => $branch ?: null,
            'status' => in_array($status, ['pending', 'partial', 'paid'], true) ? $status : 'all',
            'period' => in_array($period, ['today', 'last_7_days', 'this_month'], true) ? $period : 'all',
            'search' => substr(trim((string) $request->input('filter_search', '')), 0, 100),
        ];
    }

    public function branches(User $user)
    {
        return TevesBranchModel::query()
            ->when($user->user_branch_access_type === 'BYBRANCH', function ($query) use ($user) {
                $query->whereIn('branch_id', function ($access) use ($user) {
                    $access->select('branch_idx')
                        ->from('teves_user_branch_access')
                        ->where('user_idx', $user->user_id);
                });
            })
            ->orderBy('branch_name')
            ->get(['branch_id', 'branch_code', 'branch_name']);
    }

    public function query(User $user, array $filters = []): Builder
    {
        $query = ReceivablesModel::query()
            ->join('teves_client_table', function ($join) {
                $join->on('teves_client_table.client_id', '=', 'teves_receivable_table.client_idx')
                    ->whereNull('teves_client_table.deleted_at');
            })
            ->leftJoin('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_receivable_table.company_header')
            ->where('teves_receivable_table.sales_order_idx', 0)
            ->whereNull('teves_receivable_table.deleted_at');

        if ($user->user_branch_access_type === 'BYBRANCH') {
            $query->whereIn('teves_receivable_table.company_header', function ($access) use ($user) {
                $access->select('branch_idx')
                    ->from('teves_user_branch_access')
                    ->where('user_idx', $user->user_id);
            });
        }

        if (!empty($filters['branch'])) {
            $query->where('teves_receivable_table.company_header', (int) $filters['branch']);
        }

        switch ($filters['status'] ?? 'all') {
            case 'paid':
                $query->whereRaw('(' . self::DUE . ' > 0 AND ' . self::BALANCE . ' <= 0)');
                break;
            case 'partial':
                $query->whereRaw('(' . self::BALANCE . ' > 0 AND ' . self::BALANCE . ' < ' . self::DUE . ')');
                break;
            case 'pending':
                $query->whereRaw('(' . self::DUE . ' <= 0 OR ' . self::BALANCE . ' >= ' . self::DUE . ')');
                break;
        }

        $today = Carbon::today();
        switch ($filters['period'] ?? 'all') {
            case 'today':
                $query->whereDate('teves_receivable_table.billing_date', $today->toDateString());
                break;
            case 'last_7_days':
                $query->whereBetween('teves_receivable_table.billing_date', [
                    $today->copy()->subDays(6)->toDateString(), $today->toDateString(),
                ]);
                break;
            case 'this_month':
                $query->whereBetween('teves_receivable_table.billing_date', [
                    $today->copy()->startOfMonth()->toDateString(), $today->toDateString(),
                ]);
                break;
        }

        if ($search = trim((string) ($filters['search'] ?? ''))) {
            $query->where(function ($matches) use ($search) {
                $matches->where('teves_receivable_table.control_number', 'like', '%' . $search . '%')
                    ->orWhere('teves_client_table.client_name', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

    public function summary(User $user, array $filters = []): array
    {
        $due = self::DUE;
        $balance = self::BALANCE;
        $totals = $this->query($user, $filters)
            ->selectRaw("SUM($due) AS total_due")
            ->selectRaw("SUM(CASE WHEN $due > $balance THEN $due - $balance ELSE 0 END) AS collected")
            ->selectRaw("SUM(CASE WHEN $balance > 0 THEN $balance ELSE 0 END) AS outstanding")
            ->selectRaw("SUM(CASE WHEN $balance > 0 THEN 1 ELSE 0 END) AS open_accounts")
            ->first();

        return [
            'total_due' => round((float) ($totals->total_due ?? 0), 2),
            'collected' => round((float) ($totals->collected ?? 0), 2),
            'outstanding' => round((float) ($totals->outstanding ?? 0), 2),
            'open_accounts' => (int) ($totals->open_accounts ?? 0),
        ];
    }
}
