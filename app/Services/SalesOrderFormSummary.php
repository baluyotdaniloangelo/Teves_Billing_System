<?php

namespace App\Services;

use App\Models\ReceivablesModel;
use App\Models\ReceivablesPaymentModel;
use App\Models\SalesOrderComponentModel;
use App\Models\SalesOrderDeliveryModel;
use App\Models\SalesOrderModel;

class SalesOrderFormSummary
{
    public function forOrder(SalesOrderModel $order, ?ReceivablesModel $receivable): array
    {
        $lines = SalesOrderComponentModel::where('sales_order_idx', $order->getKey())
            ->get(['product_idx', 'order_quantity', 'order_total_amount']);
        $itemValue = (float) $lines->sum('order_total_amount');
        $total = $receivable && (float) $receivable->receivable_amount > 0
            ? (float) $receivable->receivable_amount
            : (float) ($order->sales_order_total_due ?: $itemValue);

        $receivableIds = ReceivablesModel::where('sales_order_idx', $order->getKey())->select('receivable_id');
        $paid = (float) ReceivablesPaymentModel::whereIn('receivable_idx', $receivableIds)
            ->where(function ($query) {
                $query->where('receivable_mode_of_payment', '<>', 'Post-Dated Check')
                    ->orWhereNull('receivable_mode_of_payment');
            })->sum('receivable_payment_amount');

        $deliveredByProduct = SalesOrderDeliveryModel::where('sales_order_idx', $order->getKey())
            ->selectRaw('sales_order_component_product_idx, SUM(sales_order_delivery_quantity) as delivered_quantity')
            ->groupBy('sales_order_component_product_idx')
            ->pluck('delivered_quantity', 'sales_order_component_product_idx')
            ->map(function ($quantity) { return max(0, (float) $quantity); })->all();

        $deliveredValue = 0.0;
        foreach ($lines as $line) {
            $ordered = max(0, (float) $line->order_quantity);
            $available = $deliveredByProduct[$line->product_idx] ?? 0;
            $fulfilled = min($ordered, $available);
            if ($ordered > 0) {
                $deliveredValue += (float) $line->order_total_amount * $fulfilled / $ordered;
            }
            $deliveredByProduct[$line->product_idx] = max(0, $available - $fulfilled);
        }

        $progress = $itemValue > 0 ? min(100, max(0, round($deliveredValue / $itemValue * 100))) : 0;
        $balance = $total - $paid;
        $status = (int) $order->sales_order_quotation === 1 ? 'Quotation'
            : ($total > 0 && $balance <= 0 ? 'Paid' : ($paid > 0 ? 'Partially paid' : 'Unpaid'));

        return [
            'order_total' => round($total, 2),
            'payments_received' => round($paid, 2),
            'balance_due' => round($balance, 2),
            'item_count' => $lines->count(),
            'payment_percentage' => $total > 0 ? min(100, max(0, round($paid / $total * 100, 1))) : 0,
            'delivery_progress' => $progress,
            'delivered_value' => round($deliveredValue, 2),
            'delivery_basis' => round($itemValue, 2),
            'status' => $status,
        ];
    }
}
