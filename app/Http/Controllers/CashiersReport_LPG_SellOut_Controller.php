<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportModel_LPG_ActualInventory;
use App\Services\LpgSellOutSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class CashiersReport_LPG_SellOut_Controller extends Controller
{
    public function index(Request $request, LpgSellOutSummary $summary)
    {
        $reportId = $this->reportId($request);
        $records = $summary->records($reportId);
        $rows = $summary->rows($records, $reportId)->keyBy('product_idx');
        $openings = $summary->openingBalances($reportId);
        $products = DB::table('teves_product_table as product')
            ->whereIn('product.category_idx', \App\Services\LpgEmptyInventorySummary::categoryIds())
            ->whereNull('product.deleted_at')->orderBy('product.product_name')
            ->get(['product.product_id', 'product.product_name', 'product.product_unit_measurement', 'product.product_capacity', 'product.product_capacity_unit']);
        foreach ($products as $product) {
            $id = (int) $product->product_id;
            $row = $rows->get($id) ?: [
                'product_idx' => $id, 'product_name' => $product->product_name,
                'product_unit_measurement' => $product->product_unit_measurement,
                'product_capacity' => $product->product_capacity, 'product_capacity_unit' => $product->product_capacity_unit,
            ];
            foreach (LpgSellOutSummary::MODES as $mode) {
                $key = strtolower($mode);
                $row[$key . '_quantity'] = $row[$key . '_quantity'] ?? 0;
                $row[$key . '_count'] = $row[$key . '_count'] ?? 0;
                if (in_array($mode, ['REFILL_SWAP', 'CYLINDER_WITH_CONTENT', 'UNCLASSIFIED'], true)) {
                    $row[$key . '_unit_price'] = $row[$key . '_unit_price'] ?? null;
                    $row[$key . '_mixed_prices'] = $row[$key . '_mixed_prices'] ?? false;
                    $row[$key . '_amount'] = $row[$key . '_amount'] ?? 0;
                }
            }
            $opening = $openings->get($id);
            $hasLocalAudit = (int) ($row['beginning_count'] ?? 0) > 0;
            if (!$hasLocalAudit) {
                $row['beginning_count'] = $opening ? 1 : 0;
                $row['beginning_filled_quantity'] = $opening['filled'] ?? 0;
                $row['beginning_bo_returned_customer_quantity'] = $opening['bo_customer'] ?? 0;
                $row['beginning_bo_during_delivery_quantity'] = $opening['bo_delivery'] ?? 0;
                $row['audit_date'] = $opening['audit_date'] ?? null;
            }
            $row['total_sales'] = $row['total_sales'] ?? 0;
            $row['net_sales'] = $row['net_sales'] ?? 0;
            $rows->put($id, $row);
        }
        $rows = $rows->values()->map(function ($row) {
            $openingFilled = $row['beginning_filled_quantity'] ?? 0;
            $salesQty = (float) ($row['refill_swap_quantity'] ?? 0) + (float) ($row['cylinder_with_content_quantity'] ?? 0) + (float) ($row['unclassified_quantity'] ?? 0);
            $row['theoretical_filled_quantity'] = ($row['beginning_count'] ?? 0) ? round($openingFilled + ($row['delivery_quantity'] ?? 0) + ($row['bo_replacement_quantity'] ?? 0) + ($row['return_quantity'] ?? 0) - ($row['on_loan_quantity'] ?? 0) - $salesQty - ($row['expense_quantity'] ?? 0), 2) : null;
            $row['ending_quantity'] = $row['theoretical_filled_quantity'];
            $customerBo = (float) ($row['beginning_bo_returned_customer_quantity'] ?? 0) + (float) ($row['bo_from_customer_quantity'] ?? 0);
            $deliveryBo = (float) ($row['beginning_bo_during_delivery_quantity'] ?? 0) + (float) ($row['bo_during_delivery_quantity'] ?? 0);
            $deductions = (float) ($row['bo_replacement_quantity'] ?? 0) + (float) ($row['bo_returned_to_supplier_quantity'] ?? 0);
            $deliveryDeduction = min($deliveryBo, $deductions);
            $row['theoretical_bo_during_delivery_quantity'] = round(max(0, $deliveryBo - $deliveryDeduction), 2);
            $row['theoretical_bo_returned_customer_quantity'] = round(max(0, $customerBo - max(0, $deductions - $deliveryDeduction)), 2);
            $row['actual_filled_quantity'] = $row['theoretical_filled_quantity'];
            $row['actual_bo_returned_customer_quantity'] = $row['theoretical_bo_returned_customer_quantity'];
            $row['actual_bo_during_delivery_quantity'] = $row['theoretical_bo_during_delivery_quantity'];
            $row['total_sales'] = $row['total_sales'] ?? 0;
            return $row;
        });
        return response()->json(['data' => $rows]);
    }

    public function sources(Request $request, LpgSellOutSummary $summary)
    {
        $reportId = $this->reportId($request);
        $data = $request->validate(['mode' => ['required', Rule::in(LpgSellOutSummary::MODES)], 'product_idx' => ['nullable', 'integer']]);
        $records = $summary->records($reportId)->where('mode', $data['mode']);
        if (!empty($data['product_idx'])) $records = $records->where('product_idx', $data['product_idx']);
        if ($data['mode'] === 'BEGINNING') {
            $openings = $summary->openingBalances($reportId);
            $recordedProducts = $records->pluck('product_idx')->map(fn ($id) => (int) $id)->all();
            foreach ($openings as $productId => $opening) {
                if (in_array((int) $productId, $recordedProducts, true)) continue;
                if (!empty($data['product_idx']) && (int) $data['product_idx'] !== (int) $productId) continue;
                $product = DB::table('teves_product_table')->where('product_id', $productId)
                    ->first(['product_name', 'product_unit_measurement', 'product_capacity', 'product_capacity_unit']);
                if (!$product) continue;
                $records->push([
                    'mode' => 'BEGINNING', 'kind' => 'audit_carry_forward', 'record_id' => 0,
                    'product_idx' => (int) $productId, 'product_name' => $product->product_name,
                    'product_unit_measurement' => $product->product_unit_measurement,
                    'product_capacity' => $product->product_capacity, 'product_capacity_unit' => $product->product_capacity_unit,
                    'quantity' => $opening['filled'],
                    'bo_returned_customer_quantity' => $opening['bo_customer'],
                    'bo_during_delivery_quantity' => $opening['bo_delivery'],
                    'unit_price' => null, 'amount' => null,
                    'account' => 'Carried from starting audit', 'reference' => $opening['audit_date'],
                    'remarks' => 'B.O. returned from customers: ' . $opening['bo_customer'] . '; B.O. during delivery: ' . $opening['bo_delivery'],
                ]);
            }
        }
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
