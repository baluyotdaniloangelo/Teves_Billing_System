<?php

namespace App\Services;

use App\Models\CashiersReportModel_LPG_EmptyInventory as EmptyInventory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LpgEmptyInventorySummary
{
    public const AUTO_FIELDS = ['return_quantity', 'swap_in_quantity', 'sold_quantity', 'borrow_quantity'];
    private $sources = [];
    private $pending = [];

    public static function categoryIds(): array
    {
        return array_map('intval', config('lpg_inventory.empty_container_category_ids', [2]));
    }

    public function entries(string $kind, array $row): array
    {
        $entries = [];
        $push = function ($field, $productId, $quantity, $state = 'EMPTY') use (&$entries, $kind, $row) {
            if (!$productId || $quantity === null) return;
            $entries[] = ['field' => $field, 'product_idx' => (int) $productId, 'quantity' => (float) $quantity,
                'kind' => $kind, 'record_id' => (int) ($row[$kind === 'sale' ? 'cashiers_report_lpg_ar_so_id' : ($kind === 'swap' ? 'cashiers_report_lpg_swapping_id' : 'cashiers_report_lpg_on_loan_id')] ?? 0),
                'stock_state' => $state, 'account' => $row['client_name'] ?? null,
                'reference' => $row['dr_number'] ?? null, 'remarks' => $row['remarks'] ?? ($row['item_description'] ?? null)];
        };
        if ($kind === 'sale' && in_array($row['item_description'] ?? null, ['Sales Order', 'Cash Sales'], true)) {
            if (($row['sale_stock_type'] ?? null) === 'EMPTY_CONTAINER') {
                $push('sold_quantity', $row['product_idx'] ?? null, $row['order_quantity'] ?? null);
            }
        } elseif ($kind === 'swap') {
            $state = $row['received_stock_state'] ?? null;
            if ($state !== 'FILLED') $push('swap_in_quantity', $row['received_product_idx'] ?? null, $row['received_quantity'] ?? null, $state ?: 'UNSPECIFIED');
        } elseif ($kind === 'loan') {
            foreach (['return' => 'return_quantity', 'borrow' => 'borrow_quantity'] as $prefix => $field) {
                $state = $row[$prefix . '_stock_state'] ?? null;
                if ($state !== 'FILLED' && (float) ($row[$prefix . '_quantity'] ?? 0) > 0) {
                    $push($field, $row[$prefix . '_product_idx'] ?? null, $row[$prefix . '_quantity'], $state ?: 'UNSPECIFIED');
                }
            }
        }
        return $entries;
    }

    public function sources(int $reportId): Collection
    {
        if (isset($this->sources[$reportId])) return $this->sources[$reportId];
        $entries = collect();
        $swaps = DB::table('teves_cashiers_report_lpg_swapping as source')->leftJoin('teves_client_table as client', 'client.client_id', '=', 'source.client_idx')
            ->where('source.cashiers_report_id', $reportId)->whereNull('source.deleted_at')->get(['source.*', 'client.client_name']);
        foreach (['sale' => 'teves_cashiers_report_lpg_ar_so', 'swap' => 'teves_cashiers_report_lpg_swapping', 'loan' => 'teves_cashiers_report_lpg_on_loan'] as $kind => $table) {
            $rows = $kind === 'swap' ? $swaps : DB::table($table . ' as source')->leftJoin('teves_client_table as client', 'client.client_id', '=', 'source.client_idx')
                ->where('source.cashiers_report_id', $reportId)->whereNull('source.deleted_at')->get(['source.*', 'client.client_name']);
            foreach ($rows as $row) {
                $attributes = (array) $row;
                foreach ($this->entries($kind, $attributes) as $entry) $entries->push($entry);
            }
        }
        $products = DB::table('teves_product_table')->whereIn('product_id', $entries->pluck('product_idx')->unique()->all())
            ->whereIn('category_idx', self::categoryIds())
            ->whereNotIn('product_id', LpgRegulatorProducts::ids())
            ->get(['product_id', 'product_name', 'product_capacity', 'product_capacity_unit'])->keyBy('product_id');
        $entries = $entries->filter(function ($entry) use ($products) { return $products->has($entry['product_idx']); })
            ->map(function ($entry) use ($products) {
                $product = $products->get($entry['product_idx']);
                $entry['product_name'] = $product->product_name;
                $entry['product_capacity'] = $product->product_capacity;
                $entry['product_capacity_unit'] = $product->product_capacity_unit;
                return $entry;
            })->unique(function ($entry) {
                return !empty($entry['duplicate_swap_id']) ? 'linked-refill:' . $entry['duplicate_swap_id'] : $entry['kind'] . ':' . $entry['record_id'] . ':' . $entry['field'];
            })->values();
        $this->pending[$reportId] = $entries->where('stock_state', 'UNSPECIFIED')->values();
        return $this->sources[$reportId] = $entries->where('stock_state', 'EMPTY')->values();
    }

    public function pending(int $reportId): Collection
    {
        $this->sources($reportId);
        return $this->pending[$reportId];
    }

    public function rows(int $reportId): Collection
    {
        $sources = $this->sources($reportId);
        $rows = EmptyInventory::forReport($reportId)->whereNotIn('teves_cashiers_report_lpg_empty_inventory.product_idx', LpgRegulatorProducts::ids())->get()->keyBy('product_idx');
        foreach ($sources->groupBy('product_idx') as $productId => $entries) {
            if ($rows->has($productId)) continue;
            $source = $entries->first();
            $row = new EmptyInventory(['cashiers_report_id' => $reportId, 'product_idx' => $productId]);
            $row->cashiers_report_lpg_empty_inventory_id = 0;
            $row->product_name = $source['product_name'];
            $row->product_capacity = $source['product_capacity'];
            $row->product_capacity_unit = $source['product_capacity_unit'];
            $rows->put($productId, $row);
        }
        return $rows->map(function ($row) use ($sources) { return $this->decorate($row, $sources); })
            ->sortBy(function ($row) {
                $unit = $row->product_capacity_unit === 'kg' ? 0 : ($row->product_capacity_unit === 'g' ? 1 : 2);
                return sprintf('%d:%012.3f:%s', $unit, (float) $row->product_capacity, $row->product_name);
            }, SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    public function decorate(EmptyInventory $record, ?Collection $sources = null): EmptyInventory
    {
        $sources = ($sources ?: $this->sources((int) $record->cashiers_report_id))->where('product_idx', $record->product_idx);
        $view = clone $record;
        $flags = $record->auto_fields ?: [];
        $manual = [];
        foreach (EmptyInventory::QUANTITY_FIELDS as $field => $label) {
            $manual[$field] = (int) $record->{$field};
            if (in_array($field, self::AUTO_FIELDS, true) && (!empty($flags[$field]) || $sources->where('field', $field)->isNotEmpty())) {
                $flags[$field] = true;
                $view->{$field} = (int) $sources->where('field', $field)->sum('quantity');
            } else $view->{$field} = $manual[$field];
        }
        $view->setAttribute('automatic_fields', array_keys(array_filter($flags)));
        $view->setAttribute('manual_totals', $manual);
        return $view;
    }

    public function saveSource(string $kind, Model $record): void
    {
        DB::transaction(function () use ($kind, $record) {
            $this->lockReport((int) $record->cashiers_report_id);
            if ($kind === 'swap') app(LpgSwappingBalance::class)->assertChange($record);
            if ($kind === 'sale' && (!$record->exists || $record->isDirty('product_idx'))) {
                $record->set_components_snapshot = $this->snapshotSetComponents((int) $record->product_idx);
            }
            $this->adopt($kind, $record->getRawOriginal());
            $record->save();
            $this->adopt($kind, $record->getAttributes());
        });
    }

    private function snapshotSetComponents(int $productId): array
    {
        // Product component editors lock/update this parent row before changing its composition.
        DB::table('teves_product_table')->where('product_id', $productId)->lockForUpdate()->first();

        return DB::table('teves_product_component_table as component')
            ->leftJoin('teves_product_table as product', 'product.product_id', '=', 'component.component_product_idx')
            ->where('component.product_idx', $productId)
            ->orderBy('component.component_product_idx')
            ->get(['component.component_product_idx', 'component.quantity', 'product.product_name',
                'product.product_capacity', 'product.product_capacity_unit', 'product.product_unit_measurement'])
            ->map(function ($component) {
                return [
                    'product_idx' => (int) $component->component_product_idx,
                    'quantity' => (string) $component->quantity,
                    'product_name' => $component->product_name,
                    'product_capacity' => $component->product_capacity,
                    'product_capacity_unit' => $component->product_capacity_unit,
                    'product_unit_measurement' => $component->product_unit_measurement,
                ];
            })->all();
    }

    public function deleteSource(string $kind, Model $record): void
    {
        DB::transaction(function () use ($kind, $record) {
            $this->lockReport((int) $record->cashiers_report_id);
            if ($kind === 'swap') app(LpgSwappingBalance::class)->assertChange($record, true);
            $this->adopt($kind, $record->getAttributes());
            $record->delete();
        });
    }

    private function lockReport(int $reportId): void
    {
        $report = DB::table('teves_cashiers_report')->where('cashiers_report_id', $reportId)->where('cashier_report_type', 'LPG')->whereNull('deleted_at')->lockForUpdate()->first();
        abort_unless($report, 422, 'Select an active LPG cashier report.');
    }

    private function adopt(string $kind, array $source): void
    {
        if (empty($source['cashiers_report_id'])) return;
        foreach ($this->entries($kind, $source) as $entry) {
            if ($entry['stock_state'] !== 'EMPTY') continue;
            $product = DB::table('teves_product_table')->where('product_id', $entry['product_idx'])->whereIn('category_idx', self::categoryIds())->whereNotIn('product_id', LpgRegulatorProducts::ids())->exists();
            if (!$product) continue;
            $row = EmptyInventory::withTrashed()->firstOrNew(['cashiers_report_id' => $source['cashiers_report_id'], 'product_idx' => $entry['product_idx']]);
            if (!$row->exists) $row->created_by_user_idx = Session::get('loginID', 0);
            if ($row->trashed()) {
                foreach (EmptyInventory::QUANTITY_FIELDS as $field => $label) $row->{$field} = 0;
                $row->remarks = null;
                $row->deleted_by_user_id = 0;
                $row->deleted_at = null;
            }
            $flags = $row->auto_fields ?: [];
            $flags[$entry['field']] = true;
            $row->auto_fields = $flags;
            if ($row->isDirty() || !$row->exists) {
                $row->updated_by_user_idx = Session::get('loginID', 0);
                $row->save();
            }
        }
        if ($kind === 'swap' && !empty($source['cashiers_report_lpg_swapping_id'])) {
            foreach (DB::table('teves_cashiers_report_lpg_ar_so')->where('empty_swapping_idx', $source['cashiers_report_lpg_swapping_id'])->where('cashiers_report_id', $source['cashiers_report_id'])->whereNull('deleted_at')->get() as $sale) {
                $linked = (array) $sale;
                $linked['_linked_swap'] = $source;
                $this->adopt('sale', $linked);
            }
        }
    }
}
