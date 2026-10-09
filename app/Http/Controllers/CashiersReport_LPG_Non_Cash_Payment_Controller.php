<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportModel_LPG_Non_Cash_Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CashiersReport_LPG_Non_Cash_Payment_Controller extends Controller
{
    public function get_lpg_non_cash_payment_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'CashiersReportId' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = CashiersReportModel_LPG_Non_Cash_Payment::query()
            ->where('cashiers_report_id', $request->CashiersReportId)
            ->orderBy('cashiers_report_lpg_non_cash_payment_id', 'asc')
            ->get([
                'cashiers_report_lpg_non_cash_payment_id',
                'cashiers_report_id',
                'mode_of_payment',
                'payer_name',
                'payer_number',
                'reference_no',
                'check_expiry_date',
                'amount',
            ]);

        return response()->json($data);
    }

    public function lpg_non_cash_payment_info(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cashiers_report_lpg_non_cash_payment_id' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $record = CashiersReportModel_LPG_Non_Cash_Payment::query()
            ->where('cashiers_report_lpg_non_cash_payment_id', $request->cashiers_report_lpg_non_cash_payment_id)
            ->first();

        if (!$record) {
            return response()->json(['message' => 'Non-cash payment record not found.'], 404);
        }

        return response()->json($record);
    }

    public function save_lpg_non_cash_payment(Request $request)
    {
        $validator = Validator::make($request->all(), $this->paymentRules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please check the payment details.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $now = now();
        $userId = Session::get('loginID', 0);

        $record = new CashiersReportModel_LPG_Non_Cash_Payment();
        $record->cashiers_report_id = $data['cashiers_report_id'];
        $record->mode_of_payment = $data['mode_of_payment'];
        $record->payer_name = $data['payer_name'] ?? null;
        $record->payer_number = $data['payer_number'] ?? null;
        $record->reference_no = $data['reference_no'] ?? null;
        $record->check_expiry_date = $data['mode_of_payment'] === 'check'
            ? ($data['check_expiry_date'] ?? null)
            : null;
        $record->amount = $data['amount'];
        $record->created_at = $now;
        $record->created_by_user_idx = $userId;
        $record->updated_at = $now;
        $record->updated_by_user_idx = $userId;
        $record->save();

        return response()->json([
            'status' => true,
            'message' => 'Non-cash payment saved successfully.',
            'data' => $record,
        ]);
    }

    public function update_lpg_non_cash_payment(Request $request)
    {
        $rules = $this->paymentRules();
        $rules['cashiers_report_lpg_non_cash_payment_id'] = ['required', 'integer'];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please check the payment details.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $record = CashiersReportModel_LPG_Non_Cash_Payment::query()
            ->where('cashiers_report_lpg_non_cash_payment_id', $data['cashiers_report_lpg_non_cash_payment_id'])
            ->where('cashiers_report_id', $data['cashiers_report_id'])
            ->first();

        if (!$record) {
            return response()->json(['message' => 'Non-cash payment record not found.'], 404);
        }

        $record->mode_of_payment = $data['mode_of_payment'];
        $record->payer_name = $data['payer_name'] ?? null;
        $record->payer_number = $data['payer_number'] ?? null;
        $record->reference_no = $data['reference_no'] ?? null;
        $record->check_expiry_date = $data['mode_of_payment'] === 'check'
            ? ($data['check_expiry_date'] ?? null)
            : null;
        $record->amount = $data['amount'];
        $record->updated_at = now();
        $record->updated_by_user_idx = Session::get('loginID', 0);
        $record->save();

        return response()->json([
            'status' => true,
            'message' => 'Non-cash payment updated successfully.',
            'data' => $record,
        ]);
    }

    public function delete_lpg_non_cash_payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cashiers_report_lpg_non_cash_payment_id' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $record = CashiersReportModel_LPG_Non_Cash_Payment::find(
            $request->cashiers_report_lpg_non_cash_payment_id
        );

        if (!$record) {
            return response()->json(['message' => 'Non-cash payment record not found.'], 404);
        }

        $record->delete();

        return response()->json([
            'status' => true,
            'message' => 'Non-cash payment deleted successfully.',
        ]);
    }

    private function paymentRules(): array
    {
        return [
            'cashiers_report_id' => ['required', 'integer'],
            'mode_of_payment' => ['required', 'in:limitless,credit_debit,gcash,check'],
            'payer_name' => ['nullable', 'string', 'max:255'],
            'payer_number' => ['nullable', 'string', 'max:100'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'check_expiry_date' => ['nullable', 'required_if:mode_of_payment,check', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
