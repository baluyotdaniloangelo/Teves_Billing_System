<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportModel_LPG_Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CashiersReport_LPG_Inventory_Controller extends Controller
{
    public function get_lpg_inventory_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'CashiersReportId' => ['required', 'integer'],
            'inventory_mode' => ['required', 'in:BEGINNING,DELIVERY,BO_REPLACEMENT'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $data = CashiersReportModel_LPG_Inventory::query()
            ->leftJoin(
                'teves_product_table',
                'teves_product_table.product_id',
                '=',
                'teves_cashiers_report_lpg_inventory.product_idx'
            )
            ->leftJoin(
                'teves_supplier_table',
                'teves_supplier_table.supplier_id',
                '=',
                'teves_cashiers_report_lpg_inventory.supplier_idx'
            )
            ->leftJoin(
                'teves_client_table',
                'teves_client_table.client_id',
                '=',
                'teves_cashiers_report_lpg_inventory.client_idx'
            )
            ->where('teves_cashiers_report_lpg_inventory.cashiers_report_id', $request->CashiersReportId)
            ->where('teves_cashiers_report_lpg_inventory.inventory_mode', $request->inventory_mode)
            ->whereNull('teves_cashiers_report_lpg_inventory.deleted_at')
            ->orderBy('teves_cashiers_report_lpg_inventory.cashiers_report_lpg_inventory_id', 'asc')
            ->get([
                'teves_cashiers_report_lpg_inventory.cashiers_report_lpg_inventory_id',
                'teves_cashiers_report_lpg_inventory.cashiers_report_id',
                'teves_cashiers_report_lpg_inventory.inventory_mode',
                'teves_cashiers_report_lpg_inventory.product_idx',
                'teves_product_table.product_name',
                'teves_product_table.product_unit_measurement',
                'teves_cashiers_report_lpg_inventory.client_idx',
                'teves_client_table.client_name',
                'teves_cashiers_report_lpg_inventory.supplier_idx',
                'teves_supplier_table.supplier_name',
                'teves_cashiers_report_lpg_inventory.delivery_reference_no',
                'teves_cashiers_report_lpg_inventory.quantity',
                'teves_cashiers_report_lpg_inventory.remarks',
            ]);

        return response()->json($data);
    }

    public function lpg_inventory_info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cashiers_report_lpg_inventory_id' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $record = CashiersReportModel_LPG_Inventory::query()
            ->leftJoin(
                'teves_product_table',
                'teves_product_table.product_id',
                '=',
                'teves_cashiers_report_lpg_inventory.product_idx'
            )
            ->where(
                'teves_cashiers_report_lpg_inventory.cashiers_report_lpg_inventory_id',
                $request->cashiers_report_lpg_inventory_id
            )
            ->whereNull('teves_cashiers_report_lpg_inventory.deleted_at')
            ->firstOrFail([
                'teves_cashiers_report_lpg_inventory.*',
                'teves_product_table.product_name',
                'teves_product_table.product_unit_measurement',
            ]);

        return response()->json(['status' => true, 'data' => $record]);
    }

    public function save_lpg_inventory(Request $request)
    {
        $validator = $this->validator($request);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $isBeginning = $data['inventory_mode'] === 'BEGINNING';

        $record = $isBeginning
            ? CashiersReportModel_LPG_Inventory::where('cashiers_report_id', $data['cashiers_report_id'])
                ->where('product_idx', $data['product_idx'])
                ->where('inventory_mode', 'BEGINNING')
                ->first()
            : null;

        $isNewRecord = !$record;
        $record = $record ?: new CashiersReportModel_LPG_Inventory();
        $this->fillRecord($record, $data, $isNewRecord);
        $record->save();

        return response()->json([
            'status' => true,
            'message' => $isBeginning && !$isNewRecord
                ? 'Beginning inventory updated successfully.'
                : ($isBeginning
                    ? 'Beginning inventory saved successfully.'
                    : ($data['inventory_mode'] === 'BO_REPLACEMENT'
                        ? 'B.O. replacement saved successfully.'
                        : 'LPG delivery saved successfully.')),
            'data' => $record,
        ]);
    }

    public function update_lpg_inventory(Request $request)
    {
        $validator = $this->validator($request, true);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $record = CashiersReportModel_LPG_Inventory::where(
            'cashiers_report_lpg_inventory_id',
            $data['cashiers_report_lpg_inventory_id']
        )
            ->where('cashiers_report_id', $data['cashiers_report_id'])
            ->firstOrFail();

        if ($data['inventory_mode'] === 'BEGINNING') {
            $exists = CashiersReportModel_LPG_Inventory::where('cashiers_report_id', $data['cashiers_report_id'])
                ->where('product_idx', $data['product_idx'])
                ->where('inventory_mode', 'BEGINNING')
                ->where('cashiers_report_lpg_inventory_id', '<>', $record->cashiers_report_lpg_inventory_id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'errors' => ['product_idx' => ['A beginning inventory record already exists for this product.']],
                ], 422);
            }
        }

        $this->fillRecord($record, $data, false);
        $record->save();

        return response()->json([
            'status' => true,
            'message' => $data['inventory_mode'] === 'BEGINNING'
                ? 'Beginning inventory updated successfully.'
                : ($data['inventory_mode'] === 'BO_REPLACEMENT'
                    ? 'B.O. replacement updated successfully.'
                    : 'LPG delivery updated successfully.'),
            'data' => $record,
        ]);
    }

    public function delete_lpg_inventory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cashiers_report_lpg_inventory_id' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $record = CashiersReportModel_LPG_Inventory::findOrFail(
            $request->cashiers_report_lpg_inventory_id
        );
        $record->delete();

        return response()->json([
            'status' => true,
            'message' => $record->inventory_mode === 'BEGINNING'
                ? 'Beginning inventory deleted successfully.'
                : ($record->inventory_mode === 'BO_REPLACEMENT'
                    ? 'B.O. replacement deleted successfully.'
                    : 'LPG delivery deleted successfully.'),
        ]);
    }

    private function validator(Request $request, bool $isUpdate = false)
    {
        $rules = [
            'cashiers_report_id' => ['required', 'integer'],
            'inventory_mode' => ['required', 'in:BEGINNING,DELIVERY,BO_REPLACEMENT'],
            'product_idx' => ['required', 'integer', 'exists:teves_product_table,product_id'],
            'supplier_idx' => ['nullable', 'integer', 'exists:teves_supplier_table,supplier_id'],
            'delivery_reference_no' => ['nullable', 'string', 'max:100'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ];

        if ($isUpdate) {
            $rules['cashiers_report_lpg_inventory_id'] = ['required', 'integer'];
        }

        $validator = Validator::make($request->all(), $rules);
        $allowedProductIds = DB::table('teves_product_table')
            ->whereIn('category_idx', \App\Services\LpgEmptyInventorySummary::categoryIds())
            ->whereNull('deleted_at')->pluck('product_id')->map(function ($id) { return (int) $id; })->all();
        $allowedProductIds = array_values(array_unique(array_merge(
            $allowedProductIds,
            \App\Services\LpgKitchenProducts::ids(),
            \App\Services\LpgRegulatorProducts::ids()
        )));

        $validator->after(function ($validator) use ($request, $allowedProductIds) {
            if ($request->filled('product_idx') && !DB::table('teves_product_table')
                ->where('product_id', $request->input('product_idx'))
                ->whereIn('product_id', $allowedProductIds)
                ->whereNull('deleted_at')->exists()) {
                $validator->errors()->add('product_idx', 'Select a product from the LPG or Kitchen inventory list.');
            }
            if ($request->input('inventory_mode') === 'DELIVERY' && !$request->filled('supplier_idx')) {
                $validator->errors()->add('supplier_idx', 'Supplier is required for a delivery.');
            }
            if ($request->input('inventory_mode') === 'BO_REPLACEMENT' && !$request->filled('supplier_idx')) {
                $validator->errors()->add('supplier_idx', 'Supplier is required for a B.O. replacement.');
            }
        });

        return $validator;
    }

    private function fillRecord(CashiersReportModel_LPG_Inventory $record, array $data, bool $isNewRecord): void
    {
        $isDelivery = $data['inventory_mode'] === 'DELIVERY';
        $isBoReplacement = $data['inventory_mode'] === 'BO_REPLACEMENT';
        $userId = Session::get('loginID', 0);

        $record->cashiers_report_id = $data['cashiers_report_id'];
        $record->inventory_mode = $data['inventory_mode'];
        $record->product_idx = $data['product_idx'];
        $record->client_idx = null;
        $record->supplier_idx = ($isDelivery || $isBoReplacement) ? $data['supplier_idx'] : null;
        $record->delivery_reference_no = ($isDelivery || $isBoReplacement)
            ? ($data['delivery_reference_no'] ?? null)
            : null;
        $record->quantity = $data['quantity'];
        $record->remarks = $data['remarks'] ?? null;
        $record->updated_at = now();
        $record->updated_by_user_idx = $userId;

        if ($isNewRecord) {
            $record->created_at = now();
            $record->created_by_user_idx = $userId;
        }
    }
}
