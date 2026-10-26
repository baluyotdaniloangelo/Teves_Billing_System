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
            'inventory_mode' => ['required', 'in:BEGINNING,DELIVERY,BO_REPLACEMENT,BO_FROM_CUSTOMER,BO_DURING_DELIVERY,BO_RETURNED_TO_SUPPLIER'],
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
                'teves_cashiers_report_lpg_inventory.bo_returned_customer_quantity',
                'teves_cashiers_report_lpg_inventory.bo_during_delivery_quantity',
                'teves_cashiers_report_lpg_inventory.audit_date',
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
        $result = DB::transaction(function () use ($data, $isBeginning) {
            $record = $isBeginning
                ? CashiersReportModel_LPG_Inventory::where('cashiers_report_id', $data['cashiers_report_id'])
                    ->where('product_idx', $data['product_idx'])->where('inventory_mode', 'BEGINNING')->first()
                : null;
            $isNewRecord = !$record;
            $record = $record ?: new CashiersReportModel_LPG_Inventory();
            if ($isBeginning) $this->assertAuditEditable((int) $data['cashiers_report_id'], $data['audit_date']);
            $this->fillRecord($record, $data, $isNewRecord);
            $record->save();
            if ($isBeginning) $this->syncAuditItem($record, $data['audit_date']);
            return [$record, $isNewRecord];
        });
        [$record, $isNewRecord] = $result;

        return response()->json([
            'status' => true,
            'message' => $this->modeMessage($data['inventory_mode'], $isNewRecord ? 'saved' : 'updated'),
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

        DB::transaction(function () use ($record, $data) {
            if ($data['inventory_mode'] === 'BEGINNING') $this->assertAuditEditable((int) $data['cashiers_report_id'], $data['audit_date']);
            $previousProductId = (int) $record->product_idx;
            $this->fillRecord($record, $data, false);
            $record->save();
            if ($data['inventory_mode'] === 'BEGINNING') {
                if ($previousProductId !== (int) $record->product_idx) {
                    $audit = DB::table('teves_cashiers_report_lpg_starting_audits')->where('report_id', $record->cashiers_report_id)->first();
                    if ($audit) DB::table('teves_cashiers_report_lpg_starting_audit_items')->where('audit_id', $audit->audit_id)->where('product_idx', $previousProductId)->delete();
                }
                $this->syncAuditItem($record, $data['audit_date']);
            }
        });

        return response()->json([
            'status' => true,
            'message' => $this->modeMessage($data['inventory_mode'], 'updated'),
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
        DB::transaction(function () use ($record) {
            if ($record->inventory_mode === 'BEGINNING') {
                $this->assertAuditEditable((int) $record->cashiers_report_id);
                $audit = DB::table('teves_cashiers_report_lpg_starting_audits')->where('report_id', $record->cashiers_report_id)->first();
                if ($audit) DB::table('teves_cashiers_report_lpg_starting_audit_items')->where('audit_id', $audit->audit_id)->where('product_idx', $record->product_idx)->delete();
            }
            $record->delete();
        });

        return response()->json([
            'status' => true,
            'message' => $this->modeMessage($record->inventory_mode, 'deleted'),
        ]);
    }

    private function validator(Request $request, bool $isUpdate = false)
    {
        $rules = [
            'cashiers_report_id' => ['required', 'integer'],
            'inventory_mode' => ['required', 'in:BEGINNING,DELIVERY,BO_REPLACEMENT,BO_FROM_CUSTOMER,BO_DURING_DELIVERY,BO_RETURNED_TO_SUPPLIER'],
            'product_idx' => ['required', 'integer', 'exists:teves_product_table,product_id'],
            'supplier_idx' => ['nullable', 'integer', 'exists:teves_supplier_table,supplier_id'],
            'client_idx' => ['nullable', 'integer', 'exists:teves_client_table,client_id'],
            'delivery_reference_no' => ['nullable', 'string', 'max:100'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'bo_returned_customer_quantity' => ['required_if:inventory_mode,BEGINNING', 'nullable', 'numeric', 'min:0'],
            'bo_during_delivery_quantity' => ['required_if:inventory_mode,BEGINNING', 'nullable', 'numeric', 'min:0'],
            'audit_date' => ['required_if:inventory_mode,BEGINNING', 'nullable', 'date'],
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
            if (in_array($request->input('inventory_mode'), ['DELIVERY', 'BO_REPLACEMENT', 'BO_DURING_DELIVERY', 'BO_RETURNED_TO_SUPPLIER'], true) && !$request->filled('supplier_idx')) {
                $validator->errors()->add('supplier_idx', 'Supplier is required for a delivery.');
            }
            if ($request->input('inventory_mode') === 'BO_FROM_CUSTOMER' && !$request->filled('client_idx')) {
                $validator->errors()->add('client_idx', 'Customer is required for a B.O. returned by a customer.');
            }
        });

        return $validator;
    }

    private function fillRecord(CashiersReportModel_LPG_Inventory $record, array $data, bool $isNewRecord): void
    {
        $supplierModes = ['DELIVERY', 'BO_REPLACEMENT', 'BO_DURING_DELIVERY', 'BO_RETURNED_TO_SUPPLIER'];
        $isBeginning = $data['inventory_mode'] === 'BEGINNING';
        $userId = Session::get('loginID', 0);

        $record->cashiers_report_id = $data['cashiers_report_id'];
        $record->inventory_mode = $data['inventory_mode'];
        $record->product_idx = $data['product_idx'];
        $record->client_idx = $data['inventory_mode'] === 'BO_FROM_CUSTOMER' ? ($data['client_idx'] ?? null) : null;
        $record->supplier_idx = in_array($data['inventory_mode'], $supplierModes, true) ? ($data['supplier_idx'] ?? null) : null;
        $record->delivery_reference_no = (in_array($data['inventory_mode'], $supplierModes, true))
            ? ($data['delivery_reference_no'] ?? null)
            : null;
        $record->quantity = $data['quantity'];
        $record->bo_returned_customer_quantity = $isBeginning ? ($data['bo_returned_customer_quantity'] ?? 0) : 0;
        $record->bo_during_delivery_quantity = $isBeginning ? ($data['bo_during_delivery_quantity'] ?? 0) : 0;
        $record->audit_date = $isBeginning ? ($data['audit_date'] ?? null) : null;
        $record->remarks = $data['remarks'] ?? null;
        $record->updated_at = now();
        $record->updated_by_user_idx = $userId;

        if ($isNewRecord) {
            $record->created_at = now();
            $record->created_by_user_idx = $userId;
        }
    }

    private function assertAuditEditable(int $reportId, ?string $auditDate = null): object
    {
        $report = DB::table('teves_cashiers_report')->where('cashiers_report_id', $reportId)
            ->where('cashier_report_type', 'LPG')->whereNull('deleted_at')->first();
        abort_unless($report, 404, 'LPG report not found.');
        if (Session::get('user_branch_access_type') !== 'ALL') {
            $hasAccess = DB::table('teves_user_branch_access')->where('user_idx', Session::get('loginID'))
                ->where('branch_idx', $report->teves_branch)->exists();
            abort_unless($hasAccess, 403, 'You do not have access to this branch.');
        }
        $audit = DB::table('teves_cashiers_report_lpg_starting_audits')->where('report_id', $reportId)->lockForUpdate()->first();
        if ($audit && !in_array($audit->status, ['DRAFT', 'RETURNED'], true)) {
            abort(422, 'This starting audit is already submitted or approved and cannot be edited.');
        }
        if ($audit && $auditDate && $audit->audit_date !== $auditDate) {
            abort(422, 'All items in one starting audit must use the same audit date.');
        }
        return $audit ?: (object) [];
    }

    private function syncAuditItem(CashiersReportModel_LPG_Inventory $record, string $auditDate): void
    {
        $audit = DB::table('teves_cashiers_report_lpg_starting_audits')->where('report_id', $record->cashiers_report_id)->lockForUpdate()->first();
        if (!$audit) {
            $auditId = DB::table('teves_cashiers_report_lpg_starting_audits')->insertGetId([
                'report_id' => $record->cashiers_report_id, 'audit_date' => $auditDate, 'status' => 'DRAFT',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        } else {
            $auditId = $audit->audit_id;
        }
        DB::table('teves_cashiers_report_lpg_starting_audit_items')->updateOrInsert(
            ['audit_id' => $auditId, 'product_idx' => $record->product_idx],
            ['filled_quantity' => $record->quantity, 'bo_returned_customer_quantity' => $record->bo_returned_customer_quantity ?? 0,
                'bo_during_delivery_quantity' => $record->bo_during_delivery_quantity ?? 0, 'remarks' => $record->remarks,
                'updated_at' => now(), 'created_at' => now()]
        );
    }

    private function modeMessage(string $mode, string $verb): string
    {
        $labels = [
            'BEGINNING' => 'Starting audit', 'DELIVERY' => 'Supplier delivery', 'BO_REPLACEMENT' => 'B.O. replacement',
            'BO_FROM_CUSTOMER' => 'Customer B.O. return', 'BO_DURING_DELIVERY' => 'Delivery B.O.',
            'BO_RETURNED_TO_SUPPLIER' => 'B.O. returned to supplier',
        ];
        $pastVerb = ['saved' => 'saved', 'updated' => 'updated', 'deleted' => 'deleted'][$verb] ?? $verb;
        return ($labels[$mode] ?? 'Inventory record') . ' ' . $pastVerb . ' successfully.';
    }
}
