<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CashDepositVarianceReport
{
    private const DENOMINATIONS = [
        'one_thousand_deno' => 1000,
        'five_hundred_deno' => 500,
        'two_hundred_deno' => 200,
        'one_hundred_deno' => 100,
        'fifty_deno' => 50,
        'twenty_deno' => 20,
        'ten_deno' => 10,
        'five_deno' => 5,
        'one_deno' => 1,
        'twenty_five_cent_deno' => 0.25,
    ];

    public function build(Collection $branches, string $start, string $end, bool $includeUnassigned): array
    {
        $branchIds = $branches->pluck('branch_id')->all();
        $exclusiveEnd = Carbon::parse($end)->addDay()->toDateString();
        $cashExpression = collect(self::DENOMINATIONS)
            ->map(function ($value, $field) {
                return 'COALESCE(cash.' . $field . ', 0) * ' . $value;
            })->implode(' + ');

        $fuel = DB::table('teves_cashiers_report as report')
            ->join('teves_cashiers_report_p5 as cash', 'cash.cashiers_report_id', '=', 'report.cashiers_report_id')
            ->whereIn('report.teves_branch', $branchIds)
            ->whereNull('report.deleted_at')
            ->where(function ($query) {
                $query->where('report.cashier_report_type', 'FUEL')
                    ->orWhereNull('report.cashier_report_type');
            })
            ->where('report.report_date', '>=', $start)
            ->where('report.report_date', '<', $exclusiveEnd)
            ->groupBy('report.teves_branch')
            ->selectRaw('report.teves_branch as branch_idx, COUNT(*) as entry_count, SUM(' . $cashExpression . ') as amount')
            ->get()->keyBy('branch_idx');

        $lpg = DB::table('teves_cashiers_report as report')
            ->join('teves_cashiers_report_lpg_cash as cash', 'cash.cashiers_report_id', '=', 'report.cashiers_report_id')
            ->whereIn('report.teves_branch', $branchIds)
            ->where('report.cashier_report_type', 'LPG')
            ->whereNull('report.deleted_at')
            ->whereNull('cash.deleted_at')
            ->where('report.report_date', '>=', $start)
            ->where('report.report_date', '<', $exclusiveEnd)
            ->groupBy('report.teves_branch')
            ->selectRaw('report.teves_branch as branch_idx, COUNT(*) as entry_count, SUM(' . $cashExpression . ') as amount')
            ->get()->keyBy('branch_idx');

        $deposits = DB::table('teves_cash_deposits')
            ->whereIn('branch_idx', $branchIds)
            ->whereNull('deleted_at')
            ->where('cash_deposit_date', '>=', $start)
            ->where('cash_deposit_date', '<', $exclusiveEnd)
            ->groupBy('branch_idx')
            ->selectRaw('branch_idx, COUNT(*) as entry_count, SUM(cash_deposit_amount) as amount')
            ->get()->keyBy('branch_idx');

        $rows = $branches->map(function ($branch) use ($fuel, $lpg, $deposits) {
            $id = $branch->branch_id;
            $fuelCents = (int) round((float) ($fuel->get($id)->amount ?? 0) * 100);
            $lpgCents = (int) round((float) ($lpg->get($id)->amount ?? 0) * 100);
            $depositCents = (int) round((float) ($deposits->get($id)->amount ?? 0) * 100);

            return [
                'branch_code' => $branch->branch_code,
                'branch_name' => $branch->branch_name,
                'fuel' => $fuelCents / 100,
                'lpg' => $lpgCents / 100,
                'on_hand' => ($fuelCents + $lpgCents) / 100,
                'deposited' => $depositCents / 100,
                'variance' => ($depositCents - $fuelCents - $lpgCents) / 100,
                'entry_count' => (int) ($fuel->get($id)->entry_count ?? 0)
                    + (int) ($lpg->get($id)->entry_count ?? 0)
                    + (int) ($deposits->get($id)->entry_count ?? 0),
            ];
        })->filter(function ($row) use ($branches) {
            return $branches->count() === 1 || $row['entry_count'] > 0;
        })->values();

        $unassigned = $includeUnassigned
            ? DB::table('teves_cash_deposits')->whereNull('branch_idx')->whereNull('deleted_at')
                ->where('cash_deposit_date', '>=', $start)
                ->where('cash_deposit_date', '<', $exclusiveEnd)
                ->selectRaw('COUNT(*) as entry_count, COALESCE(SUM(cash_deposit_amount), 0) as amount')->first()
            : null;

        return [
            'rows' => $rows,
            'totals' => [
                'fuel' => $rows->sum('fuel'),
                'lpg' => $rows->sum('lpg'),
                'on_hand' => $rows->sum('on_hand'),
                'deposited' => $rows->sum('deposited'),
                'variance' => $rows->sum('variance'),
            ],
            'unassigned' => $unassigned,
        ];
    }
}
