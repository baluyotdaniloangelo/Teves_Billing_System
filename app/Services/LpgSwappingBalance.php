<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LpgSwappingBalance
{
    public function balances(int $reportId, int $excludeId = 0, int $excludeKitchenId = 0): Collection
    {
        $query = DB::table('teves_cashiers_report_lpg_swapping')->where('cashiers_report_id', $reportId)->whereNull('deleted_at');
        if ($excludeId) $query->where('cashiers_report_lpg_swapping_id', '<>', $excludeId);
        $balances = [];
        foreach ($query->get(['client_idx', 'received_product_idx', 'received_quantity', 'replaced_product_idx', 'replaced_quantity']) as $row) {
            $this->add($balances, (array) $row);
        }
        $damaged = DB::table('teves_cashiers_report_lpg_kitchen_movements as movement')
            ->join('teves_product_table as product', 'product.product_id', '=', 'movement.product_idx')
            ->where('movement.cashiers_report_id', $reportId)->whereNull('movement.deleted_at')
            ->where('movement.movement_type', 'DAMAGED')->whereNotNull('movement.client_idx')
            ->whereIn('product.category_idx', LpgKitchenProducts::categoryIds())
            ->whereNotIn('product.product_id', LpgRegulatorProducts::ids());
        if ($excludeKitchenId) $damaged->where('movement.cashiers_report_lpg_kitchen_movement_id', '<>', $excludeKitchenId);
        foreach ($damaged->get(['movement.client_idx', 'movement.product_idx', 'movement.quantity']) as $row) {
            $this->add($balances, ['client_idx' => $row->client_idx, 'received_product_idx' => $row->product_idx, 'received_quantity' => $row->quantity]);
        }
        return collect($balances)->map(function ($row) {
            $row['available_quantity'] = $row['received_quantity'] - $row['replaced_quantity'];
            return $row;
        });
    }

    // Shares the report lock used by swapping so damaged receipts cannot be consumed twice.
    public function saveKitchenSource(Model $record): void
    {
        $this->changeKitchenSource($record, false);
    }

    public function deleteKitchenSource(Model $record): void
    {
        $this->changeKitchenSource($record, true);
    }

    private function changeKitchenSource(Model $record, bool $delete): void
    {
        DB::transaction(function () use ($record, $delete) {
            $report = DB::table('teves_cashiers_report')->where('cashiers_report_id', $record->cashiers_report_id)
                ->where('cashier_report_type', 'LPG')->whereNull('deleted_at')->lockForUpdate()->first();
            abort_unless($report, 422, 'Select an active LPG cashier report.');
            $this->assertKitchenChange($record, $delete);
            if ($delete) $record->delete();
            else $record->save();
        });
    }

    public function assertKitchenChange(Model $record, bool $delete = false): void
    {
        $reportId = (int) $record->cashiers_report_id;
        $id = (int) $record->getKey();
        $original = $id ? DB::table('teves_cashiers_report_lpg_kitchen_movements')->where('cashiers_report_id', $reportId)
            ->where('cashiers_report_lpg_kitchen_movement_id', $id)->whereNull('deleted_at')->first() : null;
        if ($record->exists) abort_unless($original, 404, 'Kitchen stock movement no longer exists.');
        $candidate = $delete ? [] : $record->getAttributes();
        $balances = $this->balances($reportId, 0, $id)->all();
        if (($candidate['movement_type'] ?? '') === 'DAMAGED' && !empty($candidate['client_idx'])) {
            $this->add($balances, ['client_idx' => $candidate['client_idx'], 'received_product_idx' => $candidate['product_idx'], 'received_quantity' => $candidate['quantity']]);
        }
        $affected = [];
        foreach ([(array) $original, $candidate] as $source) {
            if (($source['movement_type'] ?? '') === 'DAMAGED' && !empty($source['client_idx']) && !empty($source['product_idx'])) {
                $affected[(int) $source['client_idx'] . ':' . (int) $source['product_idx']] = true;
            }
        }
        foreach (array_keys($affected) as $key) {
            $balance = $balances[$key] ?? ['received_quantity' => 0, 'replaced_quantity' => 0];
            if ($balance['replaced_quantity'] - $balance['received_quantity'] <= 0.0000001) continue;
            throw ValidationException::withMessages(['quantity' => [
                'Cannot reduce, move or delete this damaged receipt because swap replacements already use its quantity. Update or delete those replacements first.',
            ]]);
        }
    }

    // Called inside the report lock so simultaneous replacements cannot consume the same return.
    public function assertChange(Model $record, bool $delete = false): void
    {
        $reportId = (int) $record->cashiers_report_id;
        $id = (int) $record->getKey();
        $original = $id ? DB::table('teves_cashiers_report_lpg_swapping')->where('cashiers_report_id', $reportId)
            ->where('cashiers_report_lpg_swapping_id', $id)->whereNull('deleted_at')->first() : null;
        if ($record->exists) abort_unless($original, 404, 'Swapping entry no longer exists.');
        $candidate = $delete ? [] : $record->getAttributes();
        if (!empty($candidate['received_product_idx']) && !empty($candidate['replaced_product_idx'])
            && (!$original || !$original->received_product_idx || !$original->replaced_product_idx)) {
            throw ValidationException::withMessages(['swapping_entry' => ['Save received and replaced items as separate entries. Record the returned item first.']]);
        }
        $balances = $this->balances($reportId, $id)->all();
        if ($candidate) $this->add($balances, $candidate);
        $affected = [];
        foreach ([(array) $original, $candidate] as $source) {
            foreach (['received', 'replaced'] as $side) {
                if (!empty($source[$side . '_product_idx'])) $affected[(int) $source['client_idx'] . ':' . (int) $source[$side . '_product_idx']] = true;
            }
        }
        foreach (array_keys($affected) as $key) {
            $balance = $balances[$key] ?? ['received_quantity' => 0, 'replaced_quantity' => 0];
            if ($balance['replaced_quantity'] - $balance['received_quantity'] <= 0.0000001) continue;
            $isReplacement = !$delete && !empty($candidate['replaced_product_idx'])
                && $key === ((int) $candidate['client_idx'] . ':' . (int) $candidate['replaced_product_idx']);
            if ($isReplacement) {
                $available = max(0, $balance['received_quantity'] - $balance['replaced_quantity'] + (float) ($candidate['replaced_quantity'] ?? 0));
                throw ValidationException::withMessages(['replaced_quantity' => [
                    'Only ' . number_format($available, 2) . ' returned quantity is available for this customer and product in the current report.',
                ]]);
            }
            throw ValidationException::withMessages(['received_quantity' => [
                'Cannot reduce, move or delete this received item because replacements already use its returned quantity. Update or delete those replacement entries first.',
            ]]);
        }
    }

    private function add(array &$balances, array $entry): void
    {
        foreach (['received', 'replaced'] as $side) {
            $productId = (int) ($entry[$side . '_product_idx'] ?? 0);
            $quantity = (float) ($entry[$side . '_quantity'] ?? 0);
            if (!$productId || $quantity <= 0) continue;
            $clientId = (int) $entry['client_idx'];
            $key = $clientId . ':' . $productId;
            if (!isset($balances[$key])) $balances[$key] = ['client_idx' => $clientId, 'product_idx' => $productId, 'received_quantity' => 0, 'replaced_quantity' => 0];
            $balances[$key][$side . '_quantity'] += $quantity;
        }
    }
}
