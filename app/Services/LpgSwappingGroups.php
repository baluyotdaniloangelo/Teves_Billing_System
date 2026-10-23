<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LpgSwappingGroups
{
    public function rows(Collection $records, ?int $reportId = null): Collection
    {
        if ($reportId !== null) $records = $records->concat($this->damagedReceipts($reportId));
        $groups = [];
        foreach ($records as $record) {
            $entry = is_array($record) ? $record : ($record instanceof \Illuminate\Database\Eloquent\Model ? $record->toArray() : (array) $record);
            $entry['source_kind'] = $entry['source_kind'] ?? 'swapping';
            $entry['source_id'] = (int) ($entry['source_id'] ?? $entry['cashiers_report_lpg_swapping_id']);
            foreach (['received', 'replaced'] as $side) {
                $productId = (int) ($entry[$side . '_product_idx'] ?? 0);
                $quantity = (float) ($entry[$side . '_quantity'] ?? 0);
                if (!$productId || $quantity <= 0) continue;
                $key = (int) $entry['cashiers_report_id'] . ':' . (int) $entry['client_idx'] . ':' . $productId;
                if (!isset($groups[$key])) {
                    $groups[$key] = [
                        'group_key' => $key, 'cashiers_report_id' => (int) $entry['cashiers_report_id'],
                        'client_idx' => (int) $entry['client_idx'], 'client_name' => $entry['client_name'] ?? null,
                        'product_idx' => $productId, 'product_name' => $entry[$side . '_product_name'] ?? null,
                        'received_product_idx' => null, 'received_product_name' => null, 'received_product_unit_measurement' => null, 'received_quantity' => 0,
                        'replaced_product_idx' => null, 'replaced_product_name' => null, 'replaced_product_unit_measurement' => null, 'replaced_quantity' => 0,
                        'entries' => [],
                    ];
                }
                $group = &$groups[$key];
                $group[$side . '_product_idx'] = $productId;
                $group[$side . '_product_name'] = $entry[$side . '_product_name'] ?? null;
                $group[$side . '_product_unit_measurement'] = $entry[$side . '_product_unit_measurement'] ?? null;
                $group[$side . '_quantity'] += $quantity;
                // A legacy record with both sides for the same item is one editable source.
                $group['entries'][$entry['source_kind'] . ':' . $entry['source_id']] = $entry;
                unset($group);
            }
        }
        return collect($groups)->map(function ($group) {
            $group['received_quantity'] = round($group['received_quantity'], 2);
            $group['replaced_quantity'] = round($group['replaced_quantity'], 2);
            $entries = collect($group['entries']);
            $group['remarks'] = $entries->pluck('remarks')->filter(function ($remarks) { return $remarks !== null && trim($remarks) !== ''; })->unique()->implode(' / ');
            if ($group['remarks'] === '') $group['remarks'] = null;
            $group['entries'] = $entries->values()->all();
            $group['entry_count'] = $entries->count();
            $group['cashiers_report_lpg_swapping_id'] = $entries->count() === 1 && $entries->first()['source_kind'] === 'swapping'
                ? (int) $entries->first()['cashiers_report_lpg_swapping_id'] : null;
            return $group;
        })->sortBy(function ($group) { return ($group['client_name'] ?? '') . ' ' . ($group['product_name'] ?? ''); }, SORT_NATURAL | SORT_FLAG_CASE)->values();
    }

    private function damagedReceipts(int $reportId): Collection
    {
        return DB::table('teves_cashiers_report_lpg_kitchen_movements as movement')
            ->join('teves_product_table as product', 'product.product_id', '=', 'movement.product_idx')
            ->leftJoin('teves_client_table as client', 'client.client_id', '=', 'movement.client_idx')
            ->where('movement.cashiers_report_id', $reportId)->whereNull('movement.deleted_at')
            ->where('movement.movement_type', 'DAMAGED')->whereNotNull('movement.client_idx')
            ->whereIn('product.category_idx', LpgKitchenProducts::categoryIds())
            ->whereNotIn('product.product_id', LpgRegulatorProducts::ids())
            ->orderBy('movement.cashiers_report_lpg_kitchen_movement_id')
            ->get(['movement.*', 'client.client_name', 'product.product_name', 'product.product_unit_measurement'])
            ->map(function ($row) {
                return [
                    'source_kind' => 'kitchen_damaged', 'source_id' => (int) $row->cashiers_report_lpg_kitchen_movement_id,
                    'cashiers_report_lpg_swapping_id' => null, 'cashiers_report_id' => (int) $row->cashiers_report_id,
                    'client_idx' => (int) $row->client_idx, 'client_name' => $row->client_name,
                    'received_product_idx' => (int) $row->product_idx, 'received_product_name' => $row->product_name,
                    'received_product_unit_measurement' => $row->product_unit_measurement, 'received_quantity' => (float) $row->quantity,
                    'replaced_product_idx' => null, 'replaced_quantity' => 0, 'remarks' => $row->remarks,
                ];
            });
    }
}
