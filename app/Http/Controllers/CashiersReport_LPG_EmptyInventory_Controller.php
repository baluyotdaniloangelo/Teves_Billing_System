<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportModel_LPG_EmptyInventory as EmptyInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CashiersReport_LPG_EmptyInventory_Controller extends Controller
{
    public function index(Request $request)
    {
        $reportId = $this->reportId($request);
        return response()->json(EmptyInventory::forReport($reportId)->get());
    }

    public function info(Request $request)
    {
        $record = $this->record($request);
        return response()->json(['data' => $record]);
    }

    public function save(Request $request)
    {
        $reportId = $this->reportId($request);
        $data = $this->validateEntry($request);
        $record = DB::transaction(function () use ($reportId, $data) {
            // Serialize additions for this report so duplicate rows cannot be created concurrently.
            DB::table('teves_cashiers_report')->where('cashiers_report_id', $reportId)->lockForUpdate()->first();
            $record = EmptyInventory::withTrashed()->where('cashiers_report_id', $reportId)->where('product_idx', $data['product_idx'])->first();
            if ($record && !$record->trashed()) {
                throw ValidationException::withMessages(['product_idx' => ['This product already has an empty-stock row. Edit its existing totals.']]);
            }
            $record = $record ?: new EmptyInventory();
            $this->fillEntry($record, $data, $reportId);
            if ($record->trashed()) {
                $record->deleted_by_user_id = 0;
                $record->restore();
            } else {
                $record->save();
            }
            return $record;
        });
        return response()->json(['message' => 'Empty-stock inventory saved successfully.', 'data' => $record]);
    }

    public function update(Request $request)
    {
        $record = $this->record($request);
        $data = $this->validateEntry($request);
        $other = EmptyInventory::withTrashed()->where('cashiers_report_id', $record->cashiers_report_id)
            ->where('product_idx', $data['product_idx'])
            ->where('cashiers_report_lpg_empty_inventory_id', '<>', $record->getKey())->exists();
        if ($other) {
            throw ValidationException::withMessages(['product_idx' => ['Another row already uses this product. Edit that row, or add it again if it was deleted.']]);
        }
        $this->fillEntry($record, $data, (int) $record->cashiers_report_id);
        $record->save();
        return response()->json(['message' => 'Empty-stock inventory updated successfully.', 'data' => $record]);
    }

    public function delete(Request $request)
    {
        $this->record($request)->delete();
        return response()->json(['message' => 'Empty-stock inventory deleted successfully.']);
    }

    private function reportId(Request $request): int
    {
        $request->merge(['cashiers_report_id' => $request->input('cashiers_report_id', $request->input('CashiersReportId'))]);
        $data = $request->validate(['cashiers_report_id' => [
            'required', 'integer', Rule::exists('teves_cashiers_report', 'cashiers_report_id')->where(function ($query) {
                $query->where('cashier_report_type', 'LPG')->whereNull('deleted_at');
            }),
        ]]);
        return (int) $data['cashiers_report_id'];
    }

    private function record(Request $request): EmptyInventory
    {
        $reportId = $this->reportId($request);
        $data = $request->validate(['cashiers_report_lpg_empty_inventory_id' => ['required', 'integer']]);
        return EmptyInventory::where('cashiers_report_id', $reportId)->findOrFail($data['cashiers_report_lpg_empty_inventory_id']);
    }

    private function validateEntry(Request $request): array
    {
        $rules = [
            'product_idx' => ['required', 'integer', Rule::exists('teves_product_table', 'product_id')->whereNull('deleted_at')],
            'remarks' => ['nullable', 'string', 'max:5000'],
        ];
        foreach (EmptyInventory::QUANTITY_FIELDS as $field => $label) {
            $rules[$field] = ['required', 'integer', 'min:0', 'max:2147483647'];
        }
        $validator = Validator::make($request->all(), $rules);
        $validator->after(function ($validator) use ($request) {
            if ($validator->errors()->isNotEmpty()) return;
            $record = new EmptyInventory($request->only(array_keys(EmptyInventory::QUANTITY_FIELDS)));
            if ($record->ending_quantity < 0) {
                $validator->errors()->add('beginning_quantity', 'Total deductions cannot exceed beginning inventory plus additions.');
            }
        });
        return $validator->validate();
    }

    private function fillEntry(EmptyInventory $record, array $data, int $reportId): void
    {
        $record->fill($data);
        $record->cashiers_report_id = $reportId;
        $record->updated_by_user_idx = Session::get('loginID', 0);
        if (!$record->exists) $record->created_by_user_idx = Session::get('loginID', 0);
    }
}
