<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportModel_LPG_EmptyInventory as EmptyInventory;
use App\Services\LpgEmptyInventorySummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CashiersReport_LPG_EmptyInventory_Controller extends Controller
{
    public function index(Request $request, LpgEmptyInventorySummary $summary)
    {
        $reportId = $this->reportId($request);
        return response()->json(['data' => $summary->rows($reportId), 'pending_sources' => $summary->pending($reportId)->count()]);
    }

    public function info(Request $request, LpgEmptyInventorySummary $summary)
    {
        $reportId = $this->reportId($request);
        $data = $request->validate([
            'cashiers_report_lpg_empty_inventory_id' => ['required_without:product_idx', 'nullable', 'integer'],
            'product_idx' => ['required_without:cashiers_report_lpg_empty_inventory_id', 'nullable', 'integer'],
        ]);
        $record = !empty($data['cashiers_report_lpg_empty_inventory_id'])
            ? $summary->decorate(EmptyInventory::forReport($reportId)->findOrFail($data['cashiers_report_lpg_empty_inventory_id']))
            : $summary->rows($reportId)->firstWhere('product_idx', (int) $data['product_idx']);
        return response()->json(['data' => $record]);
    }

    public function sources(Request $request, LpgEmptyInventorySummary $summary)
    {
        $reportId = $this->reportId($request);
        $data = $request->validate([
            'field' => ['nullable', Rule::in(array_keys(EmptyInventory::QUANTITY_FIELDS))],
            'product_idx' => ['nullable', 'integer'],
            'pending' => ['nullable', 'boolean'],
        ]);
        $field = $data['field'] ?? null;
        if ($request->boolean('pending')) {
            $sources = $summary->pending($reportId);
        } else {
            abort_unless($field, 422, 'Select an empty-stock column.');
            $sources = $summary->sources($reportId)->where('field', $field)->values();
            foreach ($summary->rows($reportId) as $row) {
                if (in_array($field, $row->automatic_fields, true)) continue;
                $sources->push(['kind' => 'manual', 'record_id' => (int) $row->getKey(), 'product_idx' => (int) $row->product_idx,
                    'product_name' => $row->product_name, 'field' => $field, 'quantity' => (int) $row->{$field},
                    'account' => null, 'reference' => null, 'remarks' => $row->remarks, 'stock_state' => 'MANUAL']);
            }
        }
        if (!empty($data['product_idx'])) $sources = $sources->where('product_idx', (int) $data['product_idx']);
        return response()->json(['data' => $sources->values()]);
    }

    public function save(Request $request)
    {
        $record = $this->persist($request, false);
        return response()->json(['message' => 'Empty-stock inventory saved successfully.', 'data' => app(LpgEmptyInventorySummary::class)->decorate($record)]);
    }

    public function update(Request $request)
    {
        $record = $this->persist($request, true);
        return response()->json(['message' => 'Empty-stock inventory updated successfully.', 'data' => app(LpgEmptyInventorySummary::class)->decorate($record)]);
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

    private function persist(Request $request, bool $isUpdate): EmptyInventory
    {
        $reportId = $this->reportId($request);
        $modeData = $request->validate([
            'entry_mode' => ['nullable', 'in:BEGINNING,ADDITIONS,DEDUCTIONS'],
            'entry_field' => ['nullable', Rule::in(array_keys(EmptyInventory::QUANTITY_FIELDS))],
        ]);
        // Requests from the original combined form still validate and save the full row.
        $mode = $modeData['entry_mode'] ?? 'FULL';
        $entryField = $modeData['entry_field'] ?? null;
        if ($entryField && ($mode === 'FULL' || !array_key_exists($entryField, $this->quantityFields($mode)))) {
            throw ValidationException::withMessages(['entry_field' => ['Select a quantity column that belongs to this entry form.']]);
        }
        if ($isUpdate) $request->validate(['cashiers_report_lpg_empty_inventory_id' => ['required', 'integer']]);
        $data = $this->validateEntry($request, $mode, $entryField);

        return DB::transaction(function () use ($request, $reportId, $mode, $data, $isUpdate, $entryField) {
            // Merge with the latest saved row after obtaining the report lock.
            DB::table('teves_cashiers_report')->where('cashiers_report_id', $reportId)->lockForUpdate()->first();
            $record = $isUpdate
                ? EmptyInventory::where('cashiers_report_id', $reportId)->findOrFail($request->cashiers_report_lpg_empty_inventory_id)
                : EmptyInventory::withTrashed()->where('cashiers_report_id', $reportId)->where('product_idx', $data['product_idx'])->first();

            if (!$isUpdate && $mode === 'FULL' && $record && !$record->trashed()) {
                throw ValidationException::withMessages(['product_idx' => ['This product already has an empty-stock row. Edit its existing totals.']]);
            }
            if ($isUpdate && $mode !== 'FULL' && (int) $record->product_idx !== (int) $data['product_idx']) {
                throw ValidationException::withMessages(['product_idx' => ['Select the product again to load its saved totals.']]);
            }
            if ($isUpdate && $mode === 'FULL' && EmptyInventory::withTrashed()->where('cashiers_report_id', $reportId)
                ->where('product_idx', $data['product_idx'])->where('cashiers_report_lpg_empty_inventory_id', '<>', $record->getKey())->exists()) {
                throw ValidationException::withMessages(['product_idx' => ['Another row already uses this product.']]);
            }
            $record = $record ?: new EmptyInventory();
            if ($record->trashed()) {
                foreach (EmptyInventory::QUANTITY_FIELDS as $field => $label) $record->{$field} = 0;
                $record->remarks = null;
            }
            $this->fillEntry($record, $data, $reportId);
            $view = app(LpgEmptyInventorySummary::class)->decorate($record);
            foreach (array_keys($this->quantityFields($mode, $entryField)) as $field) {
                if (in_array($field, $view->automatic_fields, true)) {
                    throw ValidationException::withMessages([$field => ['This quantity is automatic. Edit its sale, swapping, or on-loan source record.']]);
                }
            }
            if ($view->ending_quantity < 0) {
                $field = array_key_first($this->quantityFields($mode, $entryField));
                throw ValidationException::withMessages([$field => ['Deductions cannot exceed the saved beginning inventory plus additions.']]);
            }
            if ($record->trashed()) {
                $record->deleted_by_user_id = 0;
                $record->restore();
            } else {
                $record->save();
            }
            return $record->refresh();
        });
    }

    private function quantityFields(string $mode, ?string $entryField = null): array
    {
        if ($entryField) return [$entryField => EmptyInventory::QUANTITY_FIELDS[$entryField]];
        switch ($mode) {
            case 'BEGINNING': return ['beginning_quantity' => 'Beginning Inventory'];
            case 'ADDITIONS': return EmptyInventory::ADDITIONS;
            case 'DEDUCTIONS': return EmptyInventory::DEDUCTIONS;
            default: return EmptyInventory::QUANTITY_FIELDS;
        }
    }

    private function validateEntry(Request $request, string $mode, ?string $entryField = null): array
    {
        $rules = [
            'product_idx' => ['required', 'integer', Rule::exists('teves_product_table', 'product_id')->where(function ($query) {
                $query->whereNull('deleted_at')->whereNotIn('product_id', \App\Services\LpgRegulatorProducts::ids());
            })],
            'remarks' => ['sometimes', 'nullable', 'string', 'max:5000'],
        ];
        foreach ($this->quantityFields($mode, $entryField) as $field => $label) {
            $rules[$field] = ['required', 'integer', 'min:0', 'max:2147483647'];
        }
        return Validator::make($request->all(), $rules)->validate();
    }

    private function fillEntry(EmptyInventory $record, array $data, int $reportId): void
    {
        $record->fill($data);
        $record->cashiers_report_id = $reportId;
        $record->updated_by_user_idx = Session::get('loginID', 0);
        if (!$record->exists) $record->created_by_user_idx = Session::get('loginID', 0);
    }
}
