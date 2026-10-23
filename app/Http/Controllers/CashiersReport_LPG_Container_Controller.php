<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportModel_LPG_OnLoan;
use App\Models\CashiersReportModel_LPG_Swapping;
use App\Services\LpgEmptyInventorySummary;
use App\Services\LpgKitchenProducts;
use App\Services\LpgRegulatorProducts;
use App\Services\LpgSwappingGroups;
use App\Services\LpgSwappingBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CashiersReport_LPG_Container_Controller extends Controller
{
    public function get_lpg_swapping_list(Request $request)
    {
        $reportId = $this->lpgReportId($request);

        $records = CashiersReportModel_LPG_Swapping::query()
                ->leftJoin('teves_client_table', 'teves_client_table.client_id', '=', 'teves_cashiers_report_lpg_swapping.client_idx')
                ->leftJoin('teves_product_table as received_product', 'received_product.product_id', '=', 'teves_cashiers_report_lpg_swapping.received_product_idx')
                ->leftJoin('teves_product_table as replaced_product', 'replaced_product.product_id', '=', 'teves_cashiers_report_lpg_swapping.replaced_product_idx')
                ->where('teves_cashiers_report_lpg_swapping.cashiers_report_id', $reportId)
                ->whereNull('teves_cashiers_report_lpg_swapping.deleted_at')
                ->orderBy('teves_cashiers_report_lpg_swapping.cashiers_report_lpg_swapping_id')
                ->get([
                    'teves_cashiers_report_lpg_swapping.*',
                    'teves_client_table.client_name',
                    'received_product.product_name as received_product_name',
                    'received_product.product_unit_measurement as received_product_unit_measurement',
                    'replaced_product.product_name as replaced_product_name',
                    'replaced_product.product_unit_measurement as replaced_product_unit_measurement',
                ]);

        return response()->json($request->boolean('grouped') ? app(LpgSwappingGroups::class)->rows($records, $reportId) : $records);
    }

    public function lpg_swapping_info(Request $request)
    {
        $request->validate(['cashiers_report_lpg_swapping_id' => ['required', 'integer']]);

        return response()->json(['status' => true, 'data' => CashiersReportModel_LPG_Swapping::findOrFail($request->cashiers_report_lpg_swapping_id)]);
    }

    public function lpg_swapping_return_balances(Request $request, LpgSwappingBalance $balance)
    {
        $data = $request->validate([
            'cashiers_report_id' => ['required', 'integer', Rule::exists('teves_cashiers_report', 'cashiers_report_id')->where(function ($query) {
                $query->where('cashier_report_type', 'LPG')->whereNull('deleted_at');
            })],
            'exclude_id' => ['nullable', 'integer', 'min:0'],
        ]);
        $excludeId = (int) ($data['exclude_id'] ?? 0);
        if ($excludeId) CashiersReportModel_LPG_Swapping::where('cashiers_report_id', $data['cashiers_report_id'])->findOrFail($excludeId);
        return response()->json(['data' => $balance->balances((int) $data['cashiers_report_id'], $excludeId)->values()]);
    }

    public function save_lpg_swapping(Request $request)
    {
        $validator = $this->swappingValidator($request);
        if ($validator->fails()) return response()->json(['status' => false, 'errors' => $validator->errors()], 422);

        $record = new CashiersReportModel_LPG_Swapping();
        $this->fillSwapping($record, $validator->validated(), true);
        app(LpgEmptyInventorySummary::class)->saveSource('swap', $record);

        return response()->json(['status' => true, 'message' => 'LPG swapping record saved successfully.', 'data' => $record]);
    }

    public function update_lpg_swapping(Request $request)
    {
        $validator = $this->swappingValidator($request, true);
        if ($validator->fails()) return response()->json(['status' => false, 'errors' => $validator->errors()], 422);

        $data = $validator->validated();
        $record = CashiersReportModel_LPG_Swapping::where('cashiers_report_lpg_swapping_id', $data['cashiers_report_lpg_swapping_id'])
            ->where('cashiers_report_id', $data['cashiers_report_id'])
            ->firstOrFail();
        $this->fillSwapping($record, $data, false);
        app(LpgEmptyInventorySummary::class)->saveSource('swap', $record);

        return response()->json(['status' => true, 'message' => 'LPG swapping record updated successfully.', 'data' => $record]);
    }

    public function delete_lpg_swapping(Request $request)
    {
        $request->validate(['cashiers_report_lpg_swapping_id' => ['required', 'integer']]);
        app(LpgEmptyInventorySummary::class)->deleteSource('swap', CashiersReportModel_LPG_Swapping::findOrFail($request->cashiers_report_lpg_swapping_id));

        return response()->json(['status' => true, 'message' => 'LPG swapping record deleted successfully.']);
    }

    public function get_lpg_on_loan_list(Request $request)
    {
        $reportId = $this->lpgReportId($request);

        return response()->json(
            CashiersReportModel_LPG_OnLoan::query()
                ->leftJoin('teves_client_table', 'teves_client_table.client_id', '=', 'teves_cashiers_report_lpg_on_loan.client_idx')
                ->leftJoin('teves_product_table as borrow_product', 'borrow_product.product_id', '=', 'teves_cashiers_report_lpg_on_loan.borrow_product_idx')
                ->leftJoin('teves_product_table as return_product', 'return_product.product_id', '=', 'teves_cashiers_report_lpg_on_loan.return_product_idx')
                ->where('teves_cashiers_report_lpg_on_loan.cashiers_report_id', $reportId)
                ->whereNull('teves_cashiers_report_lpg_on_loan.deleted_at')
                ->orderBy('teves_cashiers_report_lpg_on_loan.cashiers_report_lpg_on_loan_id')
                ->get([
                    'teves_cashiers_report_lpg_on_loan.*',
                    'teves_client_table.client_name',
                    'borrow_product.product_name as borrow_product_name',
                    'borrow_product.product_unit_measurement as borrow_product_unit_measurement',
                    'return_product.product_name as return_product_name',
                    'return_product.product_unit_measurement as return_product_unit_measurement',
                ])
        );
    }

    public function lpg_on_loan_info(Request $request)
    {
        $request->validate(['cashiers_report_lpg_on_loan_id' => ['required', 'integer']]);

        return response()->json(['status' => true, 'data' => CashiersReportModel_LPG_OnLoan::findOrFail($request->cashiers_report_lpg_on_loan_id)]);
    }

    public function save_lpg_on_loan(Request $request)
    {
        $validator = $this->onLoanValidator($request);
        if ($validator->fails()) return response()->json(['status' => false, 'errors' => $validator->errors()], 422);

        $record = new CashiersReportModel_LPG_OnLoan();
        $this->fillOnLoan($record, $validator->validated(), true);
        app(LpgEmptyInventorySummary::class)->saveSource('loan', $record);

        return response()->json(['status' => true, 'message' => 'LPG on-loan record saved successfully.', 'data' => $record]);
    }

    public function update_lpg_on_loan(Request $request)
    {
        $validator = $this->onLoanValidator($request, true);
        if ($validator->fails()) return response()->json(['status' => false, 'errors' => $validator->errors()], 422);

        $data = $validator->validated();
        $record = CashiersReportModel_LPG_OnLoan::where('cashiers_report_lpg_on_loan_id', $data['cashiers_report_lpg_on_loan_id'])
            ->where('cashiers_report_id', $data['cashiers_report_id'])
            ->firstOrFail();
        $this->fillOnLoan($record, $data, false);
        app(LpgEmptyInventorySummary::class)->saveSource('loan', $record);

        return response()->json(['status' => true, 'message' => 'LPG on-loan record updated successfully.', 'data' => $record]);
    }

    public function delete_lpg_on_loan(Request $request)
    {
        $request->validate(['cashiers_report_lpg_on_loan_id' => ['required', 'integer']]);
        app(LpgEmptyInventorySummary::class)->deleteSource('loan', CashiersReportModel_LPG_OnLoan::findOrFail($request->cashiers_report_lpg_on_loan_id));

        return response()->json(['status' => true, 'message' => 'LPG on-loan record deleted successfully.']);
    }

    private function lpgReportId(Request $request): int
    {
        $request->validate([
            'CashiersReportId' => ['nullable', 'integer'],
            'cashiers_report_id' => ['nullable', 'integer'],
        ]);

        $reportId = $request->input('cashiers_report_id', $request->input('CashiersReportId'));
        abort_unless($reportId, 422, 'Cashier report ID is required.');

        return (int) $reportId;
    }
    private function swappingValidator(Request $request, bool $isUpdate = false)
    {
        $rules = [
            'cashiers_report_id' => ['required', 'integer'],
            'client_idx' => ['required', 'integer', 'exists:teves_client_table,client_id'],
            'received_product_idx' => ['nullable', 'integer', 'exists:teves_product_table,product_id'],
            'received_quantity' => ['nullable', 'numeric', 'min:0'],
            'replaced_product_idx' => ['nullable', 'integer', 'exists:teves_product_table,product_id'],
            'replaced_quantity' => ['nullable', 'numeric', 'min:0'],
            'received_stock_state' => ['nullable', 'in:EMPTY,FILLED'],
            'replaced_stock_state' => ['nullable', 'in:EMPTY,FILLED'],
            'remarks' => ['nullable', 'string'],
        ];
        if ($isUpdate) $rules['cashiers_report_lpg_swapping_id'] = ['required', 'integer'];

        $validator = Validator::make($request->all(), $rules);
        $validator->after(function ($validator) use ($request) {
            $this->validateStockStates($validator, $request, ['received', 'replaced']);
            foreach (['received', 'replaced'] as $prefix) {
                $quantity = (float) $request->input($prefix . '_quantity', 0);
                if ($quantity > 0 && !$request->filled($prefix . '_product_idx')) {
                    $validator->errors()->add($prefix . '_product_idx', 'Select the item for this quantity.');
                }
                if ($request->filled($prefix . '_product_idx') && $quantity <= 0) {
                    $validator->errors()->add($prefix . '_quantity', 'Enter a quantity greater than zero for this item.');
                }
            }
            if ((float) $request->input('received_quantity', 0) <= 0 && (float) $request->input('replaced_quantity', 0) <= 0) {
                $validator->errors()->add('swapping_entry', 'Enter a received or replaced item and quantity.');
            }
        });
        return $validator;
    }

    private function onLoanValidator(Request $request, bool $isUpdate = false)
    {
        $rules = [
            'cashiers_report_id' => ['required', 'integer'],
            'client_idx' => ['required', 'integer', 'exists:teves_client_table,client_id'],
            'dr_number' => ['nullable', 'string', 'max:100'],
            'borrow_product_idx' => ['nullable', 'integer', 'exists:teves_product_table,product_id'],
            'borrow_quantity' => ['nullable', 'numeric', 'min:0'],
            'borrow_alum_quantity' => ['nullable', 'numeric', 'min:0'],
            'return_product_idx' => ['nullable', 'integer', 'exists:teves_product_table,product_id'],
            'return_quantity' => ['nullable', 'numeric', 'min:0'],
            'return_alum_quantity' => ['nullable', 'numeric', 'min:0'],
            'borrow_stock_state' => ['nullable', 'in:EMPTY,FILLED'],
            'return_stock_state' => ['nullable', 'in:EMPTY,FILLED'],
            'remarks' => ['nullable', 'string'],
        ];
        if ($isUpdate) $rules['cashiers_report_lpg_on_loan_id'] = ['required', 'integer'];

        $validator = Validator::make($request->all(), $rules);
        $validator->after(function ($validator) use ($request) {
            $this->validateStockStates($validator, $request, ['borrow', 'return']);
            $borrowQuantity = (float) $request->input('borrow_quantity', 0);
            $returnQuantity = (float) $request->input('return_quantity', 0);
            $borrowAlum = (float) $request->input('borrow_alum_quantity', 0);
            $returnAlum = (float) $request->input('return_alum_quantity', 0);

            if ($borrowQuantity > 0 && !$request->filled('borrow_product_idx')) {
                $validator->errors()->add('borrow_product_idx', 'Select the borrowed item when borrow quantity is greater than zero.');
            }
            if ($returnQuantity > 0 && !$request->filled('return_product_idx')) {
                $validator->errors()->add('return_product_idx', 'Select the returned item when return quantity is greater than zero.');
            }
            if ($borrowQuantity <= 0 && $returnQuantity <= 0 && $borrowAlum <= 0 && $returnAlum <= 0) {
                $validator->errors()->add('borrow_quantity', 'Enter at least one borrow or return quantity.');
            }
        });

        return $validator;
    }

    private function fillSwapping(CashiersReportModel_LPG_Swapping $record, array $data, bool $isNew): void
    {
        $record->cashiers_report_id = $data['cashiers_report_id'];
        $record->client_idx = $data['client_idx'];
        $record->received_product_idx = $data['received_product_idx'] ?? null;
        $record->received_quantity = $data['received_quantity'] ?? 0;
        $record->replaced_product_idx = $data['replaced_product_idx'] ?? null;
        $record->replaced_quantity = $data['replaced_quantity'] ?? 0;
        $record->received_stock_state = $record->received_product_idx ? ($data['received_stock_state'] ?? null) : null;
        $record->replaced_stock_state = $record->replaced_product_idx ? ($data['replaced_stock_state'] ?? null) : null;
        $record->remarks = $data['remarks'] ?? null;
        $this->timestamps($record, $isNew);
    }

    private function fillOnLoan(CashiersReportModel_LPG_OnLoan $record, array $data, bool $isNew): void
    {
        $record->cashiers_report_id = $data['cashiers_report_id'];
        $record->client_idx = $data['client_idx'];
        $record->dr_number = $data['dr_number'] ?? null;
        $record->borrow_product_idx = $data['borrow_product_idx'] ?? null;
        $record->borrow_quantity = $data['borrow_quantity'] ?? 0;
        $record->borrow_alum_quantity = $data['borrow_alum_quantity'] ?? 0;
        $record->return_product_idx = $data['return_product_idx'] ?? null;
        $record->return_quantity = $data['return_quantity'] ?? 0;
        $record->return_alum_quantity = $data['return_alum_quantity'] ?? 0;
        $record->borrow_stock_state = $data['borrow_stock_state'] ?? null;
        $record->return_stock_state = $data['return_stock_state'] ?? null;
        $record->remarks = $data['remarks'] ?? null;
        $this->timestamps($record, $isNew);
    }

    private function validateStockStates($validator, Request $request, array $prefixes): void
    {
        foreach ($prefixes as $prefix) {
            $quantity = (float) $request->input($prefix . '_quantity', 0);
            $productId = (int) $request->input($prefix . '_product_idx');
            if ($quantity <= 0 || !$productId || LpgKitchenProducts::isProduct($productId) || LpgRegulatorProducts::isProduct($productId)) continue;
            if (!$request->filled($prefix . '_stock_state')) $validator->errors()->add($prefix . '_stock_state', 'Select Empty or Filled for this item.');
            if ($request->input($prefix . '_stock_state') === 'EMPTY' && ($quantity !== floor($quantity) || $quantity > 2147483647)) {
                $validator->errors()->add($prefix . '_quantity', 'Empty containers must be a whole number of pieces, up to 2,147,483,647.');
            }
        }
    }

    private function timestamps($record, bool $isNew): void
    {
        $userId = Session::get('loginID', 0);
        $record->updated_at = now();
        $record->updated_by_user_idx = $userId;
        if ($isNew) {
            $record->created_at = now();
            $record->created_by_user_idx = $userId;
        }
    }
}



