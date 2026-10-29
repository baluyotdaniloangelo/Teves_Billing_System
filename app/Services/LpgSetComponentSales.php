<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class LpgSetComponentSales
{
    /** Component quantities are informational; sale amounts and stock remain on their source products. */
    public function forReport(int $reportId): array
    {
        $sales = DB::table('teves_cashiers_report_lpg_ar_so as sale')
            ->where('sale.cashiers_report_id', $reportId)
            ->whereNull('sale.deleted_at')
            ->whereIn('sale.item_description', ['Sales Order', 'Cash Sales'])
            ->where('sale.order_quantity', '>', 0)
            ->get(['sale.product_idx', 'sale.order_quantity', 'sale.set_components_snapshot']);

        $quantities = [];
        $unknownSales = 0;
        foreach ($sales as $sale) {
            if ($sale->set_components_snapshot === null) {
                $unknownSales++;
                continue;
            }
            $components = json_decode($sale->set_components_snapshot, true);
            if (!is_array($components)) {
                $unknownSales++;
                continue;
            }
            foreach ($components as $component) {
                $id = (int) ($component['product_idx'] ?? 0);
                $perSet = (float) ($component['quantity'] ?? 0);
                if ($id <= 0 || $perSet <= 0) continue;
                $quantities[$id] = ($quantities[$id] ?? 0) + (float) $sale->order_quantity * $perSet;
            }
        }
        return ['quantities' => $quantities, 'unknown_sales' => $unknownSales];
    }
}
