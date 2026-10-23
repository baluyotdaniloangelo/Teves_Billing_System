<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportModel_LPG_ActualInventory;
use App\Services\LpgSellOutSummary;
use App\Services\LpgActualInventorySummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class CashiersReport_LPG_SellOut_Controller extends Controller
{
    public function index(Request $request, LpgSellOutSummary $summary, LpgActualInventorySummary $actual)
    {
        $reportId = $this->reportId($request);
        $records = $summary->records($reportId);
        return response()->json(['data' => $actual->rows($reportId, $summary->rows($records))]);
    }

    public function sources(Request $request, LpgSellOutSummary $summary)
    {
        $reportId = $this->reportId($request);
        $data = $request->validate(['mode' => ['required', Rule::in(LpgSellOutSummary::MODES)], 'product_idx' => ['nullable', 'integer']]);
        $records = $summary->records($reportId)->where('mode', $data['mode']);
        if (!empty($data['product_idx'])) $records = $records->where('product_idx', $data['product_idx']);
        return response()->json(['data' => $records->values()]);
    }

    public function saveActual(Request $request)
    {
        $reportId = $this->reportId($request);
        $data = $request->validate([
            'product_idx' => ['required', 'integer', Rule::exists('teves_product_table', 'product_id')],
            'actual_quantity' => ['required', 'numeric', 'min:0', 'max:9999999999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            'remarks' => ['nullable', 'string', 'max:5000'],
        ]);
        $record = DB::transaction(function () use ($reportId, $data) {
            DB::table('teves_cashiers_report')->where('cashiers_report_id', $reportId)->lockForUpdate()->first();
            $record = CashiersReportModel_LPG_ActualInventory::firstOrNew(['cashiers_report_id' => $reportId, 'product_idx' => $data['product_idx']]);
            if (!$record->exists) $record->created_by_user_idx = Session::get('loginID', 0);
            $record->actual_quantity = $data['actual_quantity'];
            if (array_key_exists('remarks', $data)) $record->remarks = $data['remarks'];
            $record->updated_by_user_idx = Session::get('loginID', 0);
            $record->save();
            return $record;
        });
        return response()->json(['message' => 'Actual ending inventory saved.', 'data' => $record]);
    }

    private function reportId(Request $request): int
    {
        $data = $request->validate(['cashiers_report_id' => ['required', 'integer', Rule::exists('teves_cashiers_report', 'cashiers_report_id')->where(function ($query) {
            $query->where('cashier_report_type', 'LPG')->whereNull('deleted_at');
        })]]);
        return (int) $data['cashiers_report_id'];
    }
}
