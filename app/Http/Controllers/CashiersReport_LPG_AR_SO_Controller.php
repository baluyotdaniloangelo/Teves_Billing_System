<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportModel_LPG_AR_SO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CashiersReport_LPG_AR_SO_Controller extends Controller
{
    public function get_lpg_ar_so_list(Request $request)
    {
        $data = CashiersReportModel_LPG_AR_SO::query()
            ->leftJoin(
                'teves_client_table',
                'teves_client_table.client_id',
                '=',
                'teves_cashiers_report_lpg_ar_so.client_idx'
            )
            ->leftJoin(
                'teves_product_table',
                'teves_product_table.product_id',
                '=',
                'teves_cashiers_report_lpg_ar_so.product_idx'
            )
            ->where(
                'teves_cashiers_report_lpg_ar_so.cashiers_report_id',
                $request->CashiersReportId
            )
            ->whereNull('teves_cashiers_report_lpg_ar_so.deleted_at')
            ->orderBy('teves_cashiers_report_lpg_ar_so.cashiers_report_lpg_ar_so_id', 'asc')
            ->get([
                'teves_cashiers_report_lpg_ar_so.cashiers_report_lpg_ar_so_id',
                'teves_cashiers_report_lpg_ar_so.cashiers_report_id',
                'teves_cashiers_report_lpg_ar_so.ar_date',
                'teves_cashiers_report_lpg_ar_so.client_idx',
                'teves_client_table.client_name',
                'teves_cashiers_report_lpg_ar_so.product_idx',
                'teves_product_table.product_name',
                'teves_product_table.product_unit_measurement',
                'teves_cashiers_report_lpg_ar_so.dr_number',
                'teves_cashiers_report_lpg_ar_so.item_description',
                'teves_cashiers_report_lpg_ar_so.order_quantity',
                'teves_cashiers_report_lpg_ar_so.unit_price',
                'teves_cashiers_report_lpg_ar_so.discount_per_unit',
                'teves_cashiers_report_lpg_ar_so.order_total_amount',
                DB::raw('(teves_cashiers_report_lpg_ar_so.unit_price - teves_cashiers_report_lpg_ar_so.discount_per_unit) AS discounted_unit_price'),
                DB::raw('(teves_cashiers_report_lpg_ar_so.order_quantity * teves_cashiers_report_lpg_ar_so.discount_per_unit) AS discount_total_amount'),
                DB::raw('(teves_cashiers_report_lpg_ar_so.order_quantity * (teves_cashiers_report_lpg_ar_so.unit_price - teves_cashiers_report_lpg_ar_so.discount_per_unit)) AS net_amount'),
            ]);

        return response()->json($data);
    }

    public function cashiers_report_lpg_ar_so_info(Request $request)
    {
        $data = CashiersReportModel_LPG_AR_SO::query()
            ->leftJoin(
                'teves_client_table',
                'teves_client_table.client_id',
                '=',
                'teves_cashiers_report_lpg_ar_so.client_idx'
            )
            ->leftJoin(
                'teves_product_table',
                'teves_product_table.product_id',
                '=',
                'teves_cashiers_report_lpg_ar_so.product_idx'
            )
            ->where(
                'teves_cashiers_report_lpg_ar_so.cashiers_report_lpg_ar_so_id',
                $request->cashiers_report_lpg_ar_so_id
            )
            ->whereNull('teves_cashiers_report_lpg_ar_so.deleted_at')
            ->get([
                'teves_cashiers_report_lpg_ar_so.cashiers_report_lpg_ar_so_id',
                'teves_cashiers_report_lpg_ar_so.cashiers_report_id',
                'teves_cashiers_report_lpg_ar_so.ar_date',
                'teves_cashiers_report_lpg_ar_so.client_idx',
                'teves_client_table.client_name',
                'teves_cashiers_report_lpg_ar_so.product_idx',
                'teves_product_table.product_name',
                'teves_product_table.product_unit_measurement',
                'teves_cashiers_report_lpg_ar_so.dr_number',
                'teves_cashiers_report_lpg_ar_so.item_description',
                'teves_cashiers_report_lpg_ar_so.order_quantity',
                'teves_cashiers_report_lpg_ar_so.unit_price',
                'teves_cashiers_report_lpg_ar_so.discount_per_unit',
                'teves_cashiers_report_lpg_ar_so.order_total_amount',
                DB::raw('(teves_cashiers_report_lpg_ar_so.unit_price - teves_cashiers_report_lpg_ar_so.discount_per_unit) AS discounted_unit_price'),
                DB::raw('(teves_cashiers_report_lpg_ar_so.order_quantity * teves_cashiers_report_lpg_ar_so.discount_per_unit) AS discount_total_amount'),
                DB::raw('(teves_cashiers_report_lpg_ar_so.order_quantity * (teves_cashiers_report_lpg_ar_so.unit_price - teves_cashiers_report_lpg_ar_so.discount_per_unit)) AS net_amount'),
            ]);

        return response()->json($data);
    }

    public function save_lpg_ar_so(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cashiers_report_id' => ['required', 'integer'],
            'ar_date' => ['nullable', 'date'],
            'client_idx' => ['required', 'integer', 'exists:teves_client_table,client_id'],
            'product_idx' => ['required', 'integer', 'exists:teves_product_table,product_id'],
            'dr_number' => ['nullable', 'string', 'max:100'],
            'item_description' => ['required', 'in:Sales Order,Cash Sales,Return'],
            'order_quantity' => ['required', 'numeric', 'min:0.01'],
            'discount_per_unit' => ['required', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $unitPrice = (float) DB::table('teves_product_table')
            ->where('product_id', $data['product_idx'])
            ->value('product_price');
        $discountPerUnit = (float) $data['discount_per_unit'];

        if ($discountPerUnit > $unitPrice) {
            return response()->json([
                'status' => false,
                'errors' => [
                    'discount_per_unit' => ['Discount per unit cannot exceed the product SRP.'],
                ],
            ], 422);
        }

        $now = now();
        $userId = Session::get('loginID', 0);

        $record = new CashiersReportModel_LPG_AR_SO();
        $record->cashiers_report_id = $data['cashiers_report_id'];
        $record->ar_date = now();
        $record->client_idx = $data['client_idx'];
        $record->product_idx = $data['product_idx'];
        $record->dr_number = $data['dr_number'] ?? null;
        $record->item_description = $data['item_description'] ?? 'n/a';
        $record->order_quantity = $data['order_quantity'];
        $record->unit_price = $unitPrice;
        $record->discount_per_unit = $discountPerUnit;
        $record->order_total_amount = round(
            (float) $data['order_quantity'] * $unitPrice,
            2
        );
        $record->created_at = $now;
        $record->created_by_user_idx = $userId;
        $record->updated_at = $now;
        $record->updated_by_user_idx = $userId;
        $record->save();

        return response()->json([
            'status' => true,
            'message' => 'LPG AR sales order saved successfully.',
            'data' => $record,
        ]);
    }

    public function update_lpg_ar_so(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cashiers_report_lpg_ar_so_id' => ['required', 'integer'],
            'cashiers_report_id' => ['required', 'integer'],
            'ar_date' => ['nullable', 'date'],
            'client_idx' => ['required', 'integer', 'exists:teves_client_table,client_id'],
            'product_idx' => ['required', 'integer', 'exists:teves_product_table,product_id'],
            'dr_number' => ['nullable', 'string', 'max:100'],
            'item_description' => ['required', 'in:Sales Order,Cash Sales,Return'],
            'order_quantity' => ['required', 'numeric', 'min:0.01'],
            'discount_per_unit' => ['required', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $unitPrice = (float) DB::table('teves_product_table')
            ->where('product_id', $data['product_idx'])
            ->value('product_price');
        $discountPerUnit = (float) $data['discount_per_unit'];

        if ($discountPerUnit > $unitPrice) {
            return response()->json([
                'status' => false,
                'errors' => [
                    'discount_per_unit' => ['Discount per unit cannot exceed the product SRP.'],
                ],
            ], 422);
        }

        $record = CashiersReportModel_LPG_AR_SO::where(
                'cashiers_report_lpg_ar_so_id',
                $data['cashiers_report_lpg_ar_so_id']
            )
            ->where('cashiers_report_id', $data['cashiers_report_id'])
            ->firstOrFail();

        $record->client_idx = $data['client_idx'];
        $record->product_idx = $data['product_idx'];
        $record->dr_number = $data['dr_number'] ?? null;
        $record->item_description = $data['item_description'] ?? 'n/a';
        $record->order_quantity = $data['order_quantity'];
        $record->unit_price = $unitPrice;
        $record->discount_per_unit = $discountPerUnit;
        $record->order_total_amount = round(
            (float) $data['order_quantity'] * $unitPrice,
            2
        );
        $record->updated_at = now();
        $record->updated_by_user_idx = Session::get('loginID', 0);
        $record->save();

        return response()->json([
            'status' => true,
            'message' => 'LPG AR sales order updated successfully.',
            'data' => $record,
        ]);
    }

    public function delete_lpg_ar_so(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cashiers_report_lpg_ar_so_id' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $record = CashiersReportModel_LPG_AR_SO::findOrFail(
            $request->cashiers_report_lpg_ar_so_id
        );
        $record->delete();

        return response()->json([
            'status' => true,
            'message' => 'LPG AR sales order deleted successfully.',
        ]);
    }
}
