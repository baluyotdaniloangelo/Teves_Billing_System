<?php

namespace App\Http\Controllers;

use App\Services\LpgRegulatorSummary;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CashiersReport_LPG_Regulator_Controller extends Controller
{
    public function index(Request $request, LpgRegulatorSummary $summary)
    {
        return response()->json(['data' => $summary->rows($this->reportId($request))]);
    }

    public function sources(Request $request, LpgRegulatorSummary $summary)
    {
        $reportId = $this->reportId($request);
        $data = $request->validate(['mode' => ['required', Rule::in(LpgRegulatorSummary::FILTERS)], 'product_idx' => ['nullable', 'integer']]);
        $records = $summary->sources($summary->records($reportId), $data['mode']);
        if (!empty($data['product_idx'])) $records = $records->where('product_idx', (int) $data['product_idx']);
        return response()->json(['data' => $records->values()]);
    }

    private function reportId(Request $request): int
    {
        $data = $request->validate(['cashiers_report_id' => ['required', 'integer', Rule::exists('teves_cashiers_report', 'cashiers_report_id')->where(function ($query) {
            $query->where('cashier_report_type', 'LPG')->whereNull('deleted_at');
        })]]);
        return (int) $data['cashiers_report_id'];
    }
}
