<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportModel_LPG_OnLoan;
use App\Models\CashiersReportModel_LPG_Swapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CashiersReport_LPG_Container_Controller extends Controller
{
    public function get_lpg_swapping_list(Request $request)
    {
        $reportId = $this->lpgReportId($request);

        return response()->json(
            CashiersReportModel_LPG_Swapping::query()
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
                ])
        );
    }

    public function lpg_swapping_info(Request $request)
    {
        $request->validate(['cashiers_report_lpg_swapping_id' => ['required', 'integer']]);

        return response()->json(['status' => true, 'data' => CashiersReportModel_LPG_Swapping::findOrFail($request->cashiers_report_lpg_swapping_id)]);
    }

    public function save_lpg_swapping(Request $request)
    {
        $validator = $this->swappingValidator($request);
        if ($validator->fails()) return response()->json(['status' => false, 'errors' => $validator->errors()], 422);

        $record = new CashiersReportModel_LPG_Swapping();
        $this->fillSwapping($record, $validator->validated(), true);
        $record->save();

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
        $record->save();

        return response()->json(['status' => true, 'message' => 'LPG swapping record updated successfully.', 'data' => $record]);
    }

    public function delete_lpg_swapping(Request $request)
    {
        $request->validate(['cashiers_report_lpg_swapping_id' => ['required', 'integer']]);
        CashiersReportModel_LPG_Swapping::findOrFail($request->cashiers_report_lpg_swapping_id)->delete();

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
        $record->save();

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
        $record->save();

        return response()->json(['status' => true, 'message' => 'LPG on-loan record updated successfully.', 'data' => $record]);
    }

    public function delete_lpg_on_loan(Request $request)
    {
        $request->validate(['cashiers_report_lpg_on_loan_id' => ['required', 'integer']]);
        CashiersReportModel_LPG_OnLoan::findOrFail($request->cashiers_report_lpg_on_loan_id)->delete();

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
            'received_product_idx' => ['required', 'integer', 'exists:teves_product_table,product_id'],
            'received_quantity' => ['required', 'numeric', 'min:0.01'],
            'replaced_product_idx' => ['required', 'integer', 'exists:teves_product_table,product_id'],
            'replaced_quantity' => ['required', 'numeric', 'min:0.01'],
            'remarks' => ['nullable', 'string'],
        ];
        if ($isUpdate) $rules['cashiers_report_lpg_swapping_id'] = ['required', 'integer'];

        return Validator::make($request->all(), $rules);
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
            'remarks' => ['nullable', 'string'],
        ];
        if ($isUpdate) $rules['cashiers_report_lpg_on_loan_id'] = ['required', 'integer'];

        $validator = Validator::make($request->all(), $rules);
        $validator->after(function ($validator) use ($request) {
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
        $record->received_product_idx = $data['received_product_idx'];
        $record->received_quantity = $data['received_quantity'];
        $record->replaced_product_idx = $data['replaced_product_idx'];
        $record->replaced_quantity = $data['replaced_quantity'];
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
        $record->remarks = $data['remarks'] ?? null;
        $this->timestamps($record, $isNew);
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



