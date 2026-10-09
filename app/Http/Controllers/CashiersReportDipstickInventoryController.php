<?php

namespace App\Http\Controllers;

use App\Models\CashiersReportDipstickInventoryModel;
use Illuminate\Http\Request;
use Session;

class CashiersReportDipstickInventoryController extends Controller
{
    public function saveDipstickInventory(Request $request)
    {
        $validated = $request->validate([
            'CashiersReportId' => 'required|integer',
            'product_idx' => 'required|integer',
            'tank_idx' => 'required|integer',
            'beginning_inventory' => 'required|numeric',
            'sales_in_liters_inventory' => 'required|numeric',
            'ugt_pumping_inventory' => 'required|numeric',
            'delivery_inventory' => 'required|numeric',
            'ending_inventory' => 'required|numeric',
            'dipstick_inventory_id' => 'nullable|integer',
        ], [
            'product_idx.required' => 'Product is Required',
            'tank_idx.required' => 'Tank is Required',
            'beginning_inventory.required' => 'Beginning Inventory is Required',
            'sales_in_liters_inventory.required' => 'Sales in Liters is Required',
            'ugt_pumping_inventory.required' => 'UGT Pumping is Required',
            'delivery_inventory.required' => 'Delivery is Required',
            'ending_inventory.required' => 'Ending Inventory is Required',
        ]);

        $bookStock = ($validated['beginning_inventory']
            - $validated['sales_in_liters_inventory']
            - $validated['ugt_pumping_inventory'])
            + $validated['delivery_inventory'];
        // Preserve existing server behavior: Ending Inventory - Book Stock.
        $variance = $validated['ending_inventory'] - $bookStock;
        $dipstickInventoryId = $validated['dipstick_inventory_id'] ?? null;

        $dipstickInventory = $dipstickInventoryId
            ? CashiersReportDipstickInventoryModel::findOrFail($dipstickInventoryId)
            : new CashiersReportDipstickInventoryModel();

        if (!$dipstickInventoryId) {
            $dipstickInventory->user_idx = Session::get('loginID');
            $dipstickInventory->cashiers_report_idx = $validated['CashiersReportId'];
            $dipstickInventory->created_by_user_id = Session::get('loginID');
        } else {
            $dipstickInventory->updated_by_user_id = Session::get('loginID');
        }

        $dipstickInventory->product_idx = $validated['product_idx'];
        $dipstickInventory->tank_idx = $validated['tank_idx'];
        $dipstickInventory->beginning_inventory = $validated['beginning_inventory'];
        $dipstickInventory->sales_in_liters = $validated['sales_in_liters_inventory'];
        $dipstickInventory->ugt_pumping = $validated['ugt_pumping_inventory'];
        $dipstickInventory->delivery = $validated['delivery_inventory'];
        $dipstickInventory->ending_inventory = $validated['ending_inventory'];
        $dipstickInventory->book_stock = $bookStock;
        $dipstickInventory->variance = $variance;
        $dipstickInventory->save();

        return response()->json([
            'success' => $dipstickInventoryId
                ? 'Product Inventory Successfully Updated!'
                : 'Product Inventory Successfully Created!',
        ]);
    }

    public function getDipstickInventory(Request $request)
    {
        $data = CashiersReportDipstickInventoryModel::join(
            'teves_product_tank_table',
            'teves_product_tank_table.tank_id',
            '=',
            'teves_cashiers_report_p6.tank_idx'
        )
            ->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p6.product_idx')
            ->where('teves_cashiers_report_p6.cashiers_report_idx', $request->CashiersReportId)
            ->orderBy('teves_cashiers_report_p6.product_idx', 'asc')
            ->get([
                'teves_product_table.product_id',
                'teves_product_table.product_name',
                'teves_product_tank_table.tank_id',
                'teves_product_tank_table.tank_name',
                'teves_product_tank_table.tank_capacity',
                'teves_cashiers_report_p6.cashiers_report_p6_id as dipstick_inventory_id',
                'teves_cashiers_report_p6.beginning_inventory',
                'teves_cashiers_report_p6.sales_in_liters',
                'teves_cashiers_report_p6.ugt_pumping',
                'teves_cashiers_report_p6.delivery',
                'teves_cashiers_report_p6.ending_inventory',
                'teves_cashiers_report_p6.book_stock',
                'teves_cashiers_report_p6.variance',
            ]);

        return response()->json($data);
    }

    public function getDipstickInventoryInfo(Request $request)
    {
        $data = CashiersReportDipstickInventoryModel::join(
            'teves_product_tank_table',
            'teves_product_tank_table.tank_id',
            '=',
            'teves_cashiers_report_p6.tank_idx'
        )
            ->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p6.product_idx')
            ->where('teves_cashiers_report_p6.cashiers_report_p6_id', $request->dipstick_inventory_id)
            ->get([
                'teves_product_table.product_id',
                'teves_product_table.product_name',
                'teves_product_tank_table.tank_id',
                'teves_product_tank_table.tank_name',
                'teves_product_tank_table.tank_capacity',
                'teves_cashiers_report_p6.cashiers_report_p6_id as dipstick_inventory_id',
                'teves_cashiers_report_p6.beginning_inventory',
                'teves_cashiers_report_p6.sales_in_liters',
                'teves_cashiers_report_p6.ugt_pumping',
                'teves_cashiers_report_p6.delivery',
                'teves_cashiers_report_p6.ending_inventory',
                'teves_cashiers_report_p6.book_stock',
                'teves_cashiers_report_p6.variance',
            ]);

        return response()->json($data);
    }

    public function deleteDipstickInventory(Request $request)
    {
        $validated = $request->validate([
            'dipstick_inventory_id' => 'required|integer',
        ]);

        CashiersReportDipstickInventoryModel::findOrFail($validated['dipstick_inventory_id'])->delete();

        return response()->json(['success' => 'Deleted']);
    }
}
