<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportModel_LPG_Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CashiersReport_LPG_Expenses_Controller extends Controller
{
    public function get_lpg_expenses_list(Request $request)
    {
        $data = CashiersReportModel_LPG_Expenses::query()
            ->leftJoin(
                'teves_client_table',
                'teves_client_table.client_id',
                '=',
                'teves_cashiers_report_lpg_expenses.client_idx'
            )
            ->leftJoin(
                'teves_product_table',
                'teves_product_table.product_id',
                '=',
                'teves_cashiers_report_lpg_expenses.product_idx'
            )
            ->where(
                'teves_cashiers_report_lpg_expenses.cashiers_report_id',
                $request->CashiersReportId
            )
            ->whereNull('teves_cashiers_report_lpg_expenses.deleted_at')
            ->orderBy('teves_cashiers_report_lpg_expenses.cashiers_report_lpg_expenses_id', 'asc')
            ->get([
                'teves_cashiers_report_lpg_expenses.cashiers_report_lpg_expenses_id',
                'teves_cashiers_report_lpg_expenses.cashiers_report_id',
                'teves_cashiers_report_lpg_expenses.client_idx',
                'teves_client_table.client_name',
                'teves_cashiers_report_lpg_expenses.expense_type',
                'teves_cashiers_report_lpg_expenses.purpose',
                'teves_cashiers_report_lpg_expenses.product_idx',
                'teves_product_table.product_name',
                'teves_product_table.product_unit_measurement',
                'teves_cashiers_report_lpg_expenses.item_description',
                'teves_cashiers_report_lpg_expenses.order_quantity',
                'teves_cashiers_report_lpg_expenses.unit_price',
                'teves_cashiers_report_lpg_expenses.amount',
                'teves_cashiers_report_lpg_expenses.remarks',
            ]);

        return response()->json($data);
    }

    public function cashiers_report_lpg_expense_info(Request $request)
    {
        $data = CashiersReportModel_LPG_Expenses::query()
            ->leftJoin(
                'teves_client_table',
                'teves_client_table.client_id',
                '=',
                'teves_cashiers_report_lpg_expenses.client_idx'
            )
            ->leftJoin(
                'teves_product_table',
                'teves_product_table.product_id',
                '=',
                'teves_cashiers_report_lpg_expenses.product_idx'
            )
            ->where(
                'teves_cashiers_report_lpg_expenses.cashiers_report_lpg_expenses_id',
                $request->cashiers_report_lpg_expenses_id
            )
            ->whereNull('teves_cashiers_report_lpg_expenses.deleted_at')
            ->get([
                'teves_cashiers_report_lpg_expenses.cashiers_report_lpg_expenses_id',
                'teves_cashiers_report_lpg_expenses.cashiers_report_id',
                'teves_cashiers_report_lpg_expenses.client_idx',
                'teves_client_table.client_name',
                'teves_cashiers_report_lpg_expenses.expense_type',
                'teves_cashiers_report_lpg_expenses.purpose',
                'teves_cashiers_report_lpg_expenses.product_idx',
                'teves_product_table.product_name',
                'teves_product_table.product_unit_measurement',
                'teves_cashiers_report_lpg_expenses.item_description',
                'teves_cashiers_report_lpg_expenses.order_quantity',
                'teves_cashiers_report_lpg_expenses.unit_price',
                'teves_cashiers_report_lpg_expenses.amount',
                'teves_cashiers_report_lpg_expenses.remarks',
            ]);

        return response()->json($data);
    }

    public function save_lpg_expense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cashiers_report_id' => ['required', 'integer'],
            'client_idx' => ['nullable', 'integer', 'exists:teves_client_table,client_id'],
            'expense_type' => ['required', 'in:OPEX,NON-OPEX'],
            'purpose' => ['nullable', 'string'],
            'product_idx' => ['nullable', 'integer', 'exists:teves_product_table,product_id'],
            'item_description' => ['nullable', 'string', 'max:255'],
            'order_quantity' => ['nullable', 'required_with:product_idx', 'numeric', 'min:0.01'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'amount' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $values = $this->prepareExpenseValues($data);
        if (isset($values['error'])) {
            return response()->json([
                'status' => false,
                'errors' => $values['error'],
            ], 422);
        }

        $now = now();
        $userId = Session::get('loginID', 0);

        $record = new CashiersReportModel_LPG_Expenses();
        $record->cashiers_report_id = $data['cashiers_report_id'];
        $record->client_idx = $data['client_idx'] ?? null;
        $record->expense_type = $data['expense_type'];
        $record->purpose = $data['purpose'] ?? null;
        $record->product_idx = $data['product_idx'] ?? null;
        $record->item_description = $data['item_description'] ?? null;
        $record->order_quantity = $values['order_quantity'];
        $record->unit_price = $values['unit_price'];
        $record->amount = $values['amount'];
        $record->remarks = $data['remarks'] ?? null;
        $record->created_at = $now;
        $record->created_by_user_idx = $userId;
        $record->updated_at = $now;
        $record->updated_by_user_idx = $userId;
        $record->save();

        return response()->json([
            'status' => true,
            'message' => 'LPG expense saved successfully.',
            'data' => $record,
        ]);
    }

    public function update_lpg_expense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cashiers_report_lpg_expenses_id' => ['required', 'integer'],
            'cashiers_report_id' => ['required', 'integer'],
            'client_idx' => ['nullable', 'integer', 'exists:teves_client_table,client_id'],
            'expense_type' => ['required', 'in:OPEX,NON-OPEX'],
            'purpose' => ['nullable', 'string'],
            'product_idx' => ['nullable', 'integer', 'exists:teves_product_table,product_id'],
            'item_description' => ['nullable', 'string', 'max:255'],
            'order_quantity' => ['nullable', 'required_with:product_idx', 'numeric', 'min:0.01'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'amount' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $values = $this->prepareExpenseValues($data);
        if (isset($values['error'])) {
            return response()->json([
                'status' => false,
                'errors' => $values['error'],
            ], 422);
        }

        $record = CashiersReportModel_LPG_Expenses::where(
                'cashiers_report_lpg_expenses_id',
                $data['cashiers_report_lpg_expenses_id']
            )
            ->where('cashiers_report_id', $data['cashiers_report_id'])
            ->firstOrFail();

        $record->client_idx = $data['client_idx'] ?? null;
        $record->expense_type = $data['expense_type'];
        $record->purpose = $data['purpose'] ?? null;
        $record->product_idx = $data['product_idx'] ?? null;
        $record->item_description = $data['item_description'] ?? null;
        $record->order_quantity = $values['order_quantity'];
        $record->unit_price = $values['unit_price'];
        $record->amount = $values['amount'];
        $record->remarks = $data['remarks'] ?? null;
        $record->updated_at = now();
        $record->updated_by_user_idx = Session::get('loginID', 0);
        $record->save();

        return response()->json([
            'status' => true,
            'message' => 'LPG expense updated successfully.',
            'data' => $record,
        ]);
    }

    public function delete_lpg_expense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cashiers_report_lpg_expenses_id' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $record = CashiersReportModel_LPG_Expenses::findOrFail(
            $request->cashiers_report_lpg_expenses_id
        );
        $record->delete();

        return response()->json([
            'status' => true,
            'message' => 'LPG expense deleted successfully.',
        ]);
    }

    private function prepareExpenseValues(array $data): array
    {
        if (!empty($data['product_idx'])) {
            $product = DB::table('teves_product_table')
                ->where('product_id', $data['product_idx'])
                ->first(['product_price']);

            if (!$product) {
                return ['error' => ['product_idx' => ['Selected product was not found.']]];
            }

            $quantity = (float) ($data['order_quantity'] ?? 0);
            $unitPrice = (float) $product->product_price;

            return [
                'order_quantity' => $quantity,
                'unit_price' => $unitPrice,
                'amount' => round($quantity * $unitPrice, 2),
            ];
        }

        return [
            'order_quantity' => isset($data['order_quantity']) ? (float) $data['order_quantity'] : null,
            'unit_price' => isset($data['unit_price']) ? (float) $data['unit_price'] : null,
            'amount' => round((float) $data['amount'], 2),
        ];
    }
}
