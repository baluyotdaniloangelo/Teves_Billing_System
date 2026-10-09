<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportModel_LPG_Cash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CashiersReport_LPG_Cash_Controller extends Controller
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

    public function get_lpg_cash_info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'CashiersReportId' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $record = CashiersReportModel_LPG_Cash::query()
            ->where('cashiers_report_id', $request->CashiersReportId)
            ->first();

        if (!$record) {
            $record = new CashiersReportModel_LPG_Cash();
            $record->cashiers_report_id = (int) $request->CashiersReportId;
            $record->cash_drop = 0;
            foreach (array_keys(self::DENOMINATIONS) as $field) {
                $record->{$field} = 0;
            }
        }

        return response()->json([
            'status' => true,
            'data' => $this->appendCashTotals($record),
        ]);
    }

    /**
     * Save or replace the cash count for this cashier report.
     */
    public function save_lpg_cash(Request $request)
    {
        $rules = [
            'cashiers_report_id' => ['required', 'integer'],
            'user_idx' => ['nullable', 'integer'],
            'cash_drop' => ['nullable', 'numeric', 'min:0'],
        ];

        foreach (array_keys(self::DENOMINATIONS) as $field) {
            $rules[$field] = ['nullable', 'integer', 'min:0'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please check the cash count details.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $userId = Session::get('loginID', 0);

        $record = DB::transaction(function () use ($data, $userId) {
            $record = CashiersReportModel_LPG_Cash::query()
                ->where('cashiers_report_id', $data['cashiers_report_id'])
                ->lockForUpdate()
                ->first();

            $now = now();
            if (!$record) {
                $record = new CashiersReportModel_LPG_Cash();
                $record->cashiers_report_id = $data['cashiers_report_id'];
                $record->created_at = $now;
                $record->created_by_user_idx = $userId;
            }

            $record->user_idx = $data['user_idx'] ?? $userId;
            foreach (array_keys(self::DENOMINATIONS) as $field) {
                $record->{$field} = (int) ($data[$field] ?? 0);
            }
            $record->cash_drop = (float) ($data['cash_drop'] ?? 0);
            $record->updated_at = $now;
            $record->updated_by_user_idx = $userId;
            $record->save();

            return $record;
        });

        return response()->json([
            'status' => true,
            'message' => 'LPG cash count saved successfully.',
            'data' => $this->appendCashTotals($record),
        ]);
    }

    private function appendCashTotals(CashiersReportModel_LPG_Cash $record): array
    {
        $data = $record->toArray();
        $cashOnHand = 0;

        foreach (self::DENOMINATIONS as $field => $value) {
            $cashOnHand += $value * (int) ($record->{$field} ?? 0);
        }

        $cashOnHand = round($cashOnHand, 2);
        $cashDrop = round((float) ($record->cash_drop ?? 0), 2);
        $data['cash_on_hand'] = $cashOnHand;
        $data['total_actual_cash'] = round($cashOnHand + $cashDrop, 2);

        return $data;
    }
}
