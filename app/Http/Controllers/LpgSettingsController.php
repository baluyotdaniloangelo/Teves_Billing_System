<?php

namespace App\Http\Controllers;

use App\Services\LpgProductTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class LpgSettingsController extends Controller
{
    public function index()
    {
        $data = $this->requireAdmin();
        $title = 'LPG Settings';
        $products = LpgProductTracking::catalogQuery()
            ->leftJoin('teves_lpg_product_tracking as tracking', 'tracking.product_idx', '=', 'product.product_id')
            ->orderBy('product.product_name')->get([
                'product.product_id', 'product.product_code', 'product.product_name',
                'product.product_capacity', 'product.product_capacity_unit', 'product.product_unit_measurement',
                DB::raw('COALESCE(tracking.sell_out_enabled, 1) as sell_out_enabled'),
                DB::raw('COALESCE(tracking.empty_inventory_enabled, 1) as empty_inventory_enabled'),
            ]);

        return view('pages.lpg_settings.index', compact('data', 'title', 'products'));
    }

    public function update(Request $request)
    {
        $user = $this->requireAdmin();
        // One payload avoids PHP truncating a large form at max_input_vars.
        if ($request->has('products_json')) {
            $payload = $request->validate(['products_json' => ['required', 'string', 'json', 'max:1000000']]);
            $request->merge(['products' => json_decode($payload['products_json'], true)]);
        }
        $allowedIds = LpgProductTracking::catalogQuery()->pluck('product.product_id')->all();
        $input = $request->validate([
            'products' => ['required', 'array', 'max:5000'],
            'products.*.product_idx' => ['required', 'integer', 'distinct', Rule::in($allowedIds)],
            'products.*.sell_out_enabled' => ['required', 'boolean'],
            'products.*.empty_inventory_enabled' => ['required', 'boolean'],
        ], [
            'products.*.product_idx.in' => 'One of the selected products is no longer an active LPG product. Refresh the settings page before saving.',
        ]);

        DB::transaction(function () use ($input, $user) {
            $now = now();
            $rows = array_map(function ($product) use ($user, $now) {
                return [
                    'product_idx' => (int) $product['product_idx'],
                    'sell_out_enabled' => (bool) $product['sell_out_enabled'],
                    'empty_inventory_enabled' => (bool) $product['empty_inventory_enabled'],
                    'updated_by' => (int) $user->user_id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }, $input['products']);
            DB::table('teves_lpg_product_tracking')->upsert($rows, ['product_idx'], [
                'sell_out_enabled', 'empty_inventory_enabled', 'updated_by', 'updated_at',
            ]);
        });

        return redirect()->route('lpg.settings.index')->with('success', 'LPG product tracking settings saved. Refresh any open LPG reports to apply the changes.');
    }

    private function requireAdmin(): object
    {
        $user = DB::table('user_tb')->where('user_id', Session::get('loginID'))->first();
        abort_unless($user && in_array($user->user_type, ['Admin', 'SUAdmin'], true), 403, 'Only administrators can change LPG settings.');
        return $user;
    }
}
