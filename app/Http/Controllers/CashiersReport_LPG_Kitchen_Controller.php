<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportModel_LPG_KitchenMovement;
use App\Services\LpgKitchenProducts;
use App\Services\LpgKitchenSummary;
use App\Services\LpgActualInventorySummary;
use App\Services\LpgSwappingBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class CashiersReport_LPG_Kitchen_Controller extends Controller
{
    public function index(Request $request, LpgKitchenSummary $summary, LpgActualInventorySummary $actual)
    {
        $reportId = $this->reportId($request);
        $rows = $summary->rows($summary->records($reportId));
        $setSales = app(\App\Services\LpgSetComponentSales::class)->forReport($reportId);
        return response()->json(['data' => $actual->rows($reportId, $summary->withCatalog($reportId, $rows)),
            'unknown_set_sale_snapshots' => $setSales['unknown_sales']]);
    }

    public function sources(Request $request, LpgKitchenSummary $summary)
    {
        $reportId = $this->reportId($request);
        $data = $request->validate(['mode' => ['required', Rule::in(LpgKitchenSummary::MODES)], 'product_idx' => ['nullable', 'integer']]);
        $records = $summary->records($reportId);
        $records = $data['mode'] === 'TOTAL_SOLD' ? $records->whereIn('mode', ['WALK_IN', 'SALES_ORDER']) : $records->where('mode', $data['mode']);
        if (!empty($data['product_idx'])) $records = $records->where('product_idx', $data['product_idx']);
        return response()->json(['data' => $records->values()]);
    }

    public function info(Request $request)
    {
        return response()->json(['data' => $this->record($request)]);
    }

    public function save(Request $request)
    {
        $reportId = $this->reportId($request);
        $data = $request->validate([
            'cashiers_report_lpg_kitchen_movement_id' => ['nullable', 'integer', 'min:0'],
            'movement_type' => ['required', 'in:DAMAGED,CONSIGNMENT'],
            'product_idx' => ['required', 'integer', Rule::exists('teves_product_table', 'product_id')->where(function ($query) {
                $query->whereIn('product_id', LpgKitchenProducts::ids())->whereNull('deleted_at');
            })],
            'client_idx' => ['required', 'integer', 'exists:teves_client_table,client_id'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'quantity' => ['required', 'numeric', 'min:0.01', 'max:9999999999.99', 'regex:/^\d+(\.\d{1,2})?$/'],
            'remarks' => ['nullable', 'string', 'max:5000'],
        ]);
        $record = !empty($data['cashiers_report_lpg_kitchen_movement_id'])
            ? CashiersReportModel_LPG_KitchenMovement::where('cashiers_report_id', $reportId)->findOrFail($data['cashiers_report_lpg_kitchen_movement_id'])
            : new CashiersReportModel_LPG_KitchenMovement();
        if (!$record->exists) $record->created_by_user_idx = Session::get('loginID', 0);
        $record->cashiers_report_id = $reportId;
        $record->movement_type = $data['movement_type'];
        $record->product_idx = $data['product_idx'];
        $record->client_idx = $data['client_idx'];
        $record->reference_no = $data['reference_no'] ?? null;
        $record->quantity = $data['quantity'];
        $record->remarks = $data['remarks'] ?? null;
        $record->updated_by_user_idx = Session::get('loginID', 0);
        app(LpgSwappingBalance::class)->saveKitchenSource($record);
        return response()->json(['message' => 'Kitchen stock movement saved.', 'data' => $record]);
    }

    public function delete(Request $request)
    {
        app(LpgSwappingBalance::class)->deleteKitchenSource($this->record($request));
        return response()->json(['message' => 'Kitchen stock movement deleted.']);
    }

    private function record(Request $request): CashiersReportModel_LPG_KitchenMovement
    {
        $reportId = $this->reportId($request);
        $data = $request->validate(['cashiers_report_lpg_kitchen_movement_id' => ['required', 'integer']]);
        return CashiersReportModel_LPG_KitchenMovement::where('cashiers_report_id', $reportId)->findOrFail($data['cashiers_report_lpg_kitchen_movement_id']);
    }

    private function reportId(Request $request): int
    {
        $data = $request->validate(['cashiers_report_id' => ['required', 'integer', Rule::exists('teves_cashiers_report', 'cashiers_report_id')->where(function ($query) {
            $query->where('cashier_report_type', 'LPG')->whereNull('deleted_at');
        })]]);
        return (int) $data['cashiers_report_id'];
    }
}
