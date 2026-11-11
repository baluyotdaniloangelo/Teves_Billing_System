<?php

namespace Tests\Feature;

use App\Http\Controllers\CashiersReport_LPG_SellOut_Controller;
use App\Http\Controllers\CashiersReport_LPG_Inventory_Controller;
use App\Http\Controllers\CashiersReport_LPG_Container_Controller;
use App\Http\Controllers\LpgSettingsController;
use App\Services\LpgEmptyInventorySummary;
use App\Services\LpgProductTracking;
use App\Services\LpgSellOutSummary;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class LpgProductTrackingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'lpg_tracking_test',
            'database.connections.lpg_tracking_test' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => ''],
            'activitylog.enabled' => false,
            'lpg_inventory.empty_container_category_ids' => [2],
            'lpg_inventory.kitchen_category_ids' => [4],
            'lpg_inventory.kitchen_main_product_ids' => [3],
            'lpg_inventory.kitchen_product_ids' => [],
            'lpg_inventory.regulator_product_ids' => [],
            'lpg_inventory.regulator_main_product_ids' => [7],
        ]);
        Schema::create('teves_product_table', function (Blueprint $table) {
            $table->increments('product_id');
            $table->string('product_name'); $table->string('product_code')->nullable();
            $table->integer('category_idx'); $table->integer('product_main_idx')->nullable();
            $table->string('product_unit_measurement')->nullable();
            $table->decimal('product_capacity')->nullable(); $table->string('product_capacity_unit')->nullable();
            $table->decimal('product_price')->default(0); $table->softDeletes();
        });
        Schema::create('teves_product_component_table', function (Blueprint $table) {
            $table->integer('product_idx'); $table->integer('component_product_idx');
        });
        (require base_path('database/migrations/2026_11_04_000001_create_teves_lpg_product_tracking_table.php'))->up();
        foreach ([1 => 'Both by default', 2 => 'Empty only', 3 => 'Filled only', 4 => 'Regulator',
            5 => 'Kitchen', 6 => 'Unrelated', 7 => 'Untracked', 8 => 'Archived'] as $id => $name) {
            DB::table('teves_product_table')->insert([
                'product_id' => $id, 'product_name' => $name, 'product_code' => 'TEST-' . $id,
                'category_idx' => $id === 5 ? 4 : ($id === 6 ? 1 : 2),
                'product_main_idx' => $id === 4 ? 7 : ($id === 5 ? 3 : null),
                'deleted_at' => $id === 8 ? now() : null,
            ]);
        }
        DB::table('teves_lpg_product_tracking')->insert([
            ['product_idx' => 2, 'sell_out_enabled' => false, 'empty_inventory_enabled' => true],
            ['product_idx' => 3, 'sell_out_enabled' => true, 'empty_inventory_enabled' => false],
            ['product_idx' => 7, 'sell_out_enabled' => false, 'empty_inventory_enabled' => false],
        ]);
        $this->createReportTables();
    }

    private function createReportTables(): void
    {
        Schema::create('user_tb', function (Blueprint $table) { $table->increments('user_id'); $table->string('user_type'); });
        DB::table('user_tb')->insert([['user_id' => 1, 'user_type' => 'SUAdmin'], ['user_id' => 2, 'user_type' => 'Encoder']]);
        Schema::create('teves_cashiers_report', function (Blueprint $table) {
            $table->increments('cashiers_report_id'); $table->date('report_date');
            $table->string('cashier_report_type'); $table->integer('teves_branch'); $table->softDeletes();
        });
        DB::table('teves_cashiers_report')->insert(['cashiers_report_id' => 1, 'report_date' => '2026-11-04', 'cashier_report_type' => 'LPG', 'teves_branch' => 1]);
        Schema::create('teves_client_table', function (Blueprint $table) { $table->increments('client_id'); $table->string('client_name'); });
        Schema::create('teves_supplier_table', function (Blueprint $table) { $table->increments('supplier_id'); $table->string('supplier_name'); });
        Schema::create('teves_cashiers_report_lpg_inventory', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_inventory_id'); $table->integer('cashiers_report_id');
            $table->integer('product_idx'); $table->integer('supplier_idx')->nullable(); $table->integer('client_idx')->nullable();
            $table->string('inventory_mode'); $table->decimal('quantity');
            $table->string('delivery_reference_no')->nullable(); $table->text('remarks')->nullable();
            $table->date('audit_date')->nullable(); $table->softDeletes();
        });
        Schema::create('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_ar_so_id'); $table->integer('cashiers_report_id');
            $table->integer('product_idx'); $table->integer('client_idx')->nullable();
            $table->string('item_description'); $table->string('transaction_type')->nullable(); $table->string('sale_stock_type')->nullable(); $table->boolean('is_on_loan')->default(false);
            $table->decimal('order_quantity'); $table->decimal('unit_price'); $table->decimal('discount_per_unit')->default(0);
            $table->string('dr_number')->nullable(); $table->softDeletes();
        });
        Schema::create('teves_cashiers_report_lpg_on_loan', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_on_loan_id'); $table->integer('cashiers_report_id'); $table->integer('client_idx')->nullable();
            $table->string('dr_number')->nullable(); $table->text('remarks')->nullable();
            foreach (['borrow', 'return'] as $side) {
                $table->integer($side . '_product_idx')->nullable(); $table->decimal($side . '_quantity')->default(0);
                $table->string($side . '_stock_state')->nullable();
                $table->decimal($side . '_alum_quantity')->default(0);
            }
            $table->softDeletes();
        });
        Schema::create('teves_cashiers_report_lpg_swapping', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_swapping_id'); $table->integer('cashiers_report_id'); $table->integer('client_idx')->nullable();
            foreach (['received', 'replaced'] as $side) {
                $table->integer($side . '_product_idx')->nullable(); $table->decimal($side . '_quantity')->default(0);
                $table->string($side . '_stock_state')->nullable();
            }
            $table->softDeletes();
        });
        Schema::create('teves_cashiers_report_lpg_expenses', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_expenses_id'); $table->integer('cashiers_report_id');
            $table->integer('product_idx')->nullable(); $table->integer('client_idx')->nullable();
            $table->decimal('order_quantity')->default(0); $table->softDeletes();
        });
        Schema::create('teves_cashiers_report_lpg_starting_audits', function (Blueprint $table) {
            $table->increments('audit_id'); $table->integer('report_id'); $table->string('status'); $table->date('audit_date');
        });
        Schema::create('teves_cashiers_report_lpg_starting_audit_items', function (Blueprint $table) {
            $table->integer('audit_id'); $table->integer('product_idx'); $table->decimal('filled_quantity')->default(0);
            $table->decimal('bo_returned_customer_quantity')->default(0); $table->decimal('bo_during_delivery_quantity')->default(0);
        });
        Schema::create('teves_cashiers_report_lpg_empty_inventory', function (Blueprint $table) {
            $table->increments('cashiers_report_lpg_empty_inventory_id'); $table->integer('cashiers_report_id'); $table->integer('product_idx');
            foreach (array_keys(\App\Models\CashiersReportModel_LPG_EmptyInventory::QUANTITY_FIELDS) as $field) $table->integer($field)->default(0);
            $table->text('remarks')->nullable(); $table->text('auto_fields')->nullable(); $table->softDeletes();
        });
    }

    public function test_independent_options_default_to_both_and_exclude_other_product_families(): void
    {
        $this->assertSame([1, 3], LpgProductTracking::ids('sell_out'));
        $this->assertSame([1, 2], LpgProductTracking::ids('empty_inventory'));
        $this->assertTrue(LpgProductTracking::enabled(2, 'empty_inventory'));
        $this->assertFalse(LpgProductTracking::enabled(2, 'sell_out'));
        $this->assertFalse(LpgProductTracking::enabled(4, 'sell_out'));
        $this->assertSame([1, 3, 8], LpgProductTracking::ids('sell_out', true));
    }

    public function test_disabled_sell_out_products_cannot_reappear_from_existing_transactions_or_catalog(): void
    {
        foreach ([1, 2, 3, 7] as $productId) {
            DB::table('teves_cashiers_report_lpg_inventory')->insert([
                'cashiers_report_id' => 1, 'product_idx' => $productId, 'inventory_mode' => 'DELIVERY', 'quantity' => 5,
            ]);
        }
        $summary = new LpgSellOutSummary();
        $this->assertSame([1, 3], $summary->records(1)->pluck('product_idx')->all());
        $response = (new CashiersReport_LPG_SellOut_Controller())->index(Request::create('/', 'POST', ['cashiers_report_id' => 1]), $summary);
        $this->assertSame([1, 3], collect($response->getData(true)['data'])->pluck('product_idx')->all());
        $this->assertSame(4, DB::table('teves_cashiers_report_lpg_inventory')->count());
    }

    public function test_on_loan_sales_order_is_listed_in_both_sales_details_and_on_loan_without_double_deducting_stock(): void
    {
        DB::table('teves_client_table')->insert(['client_id' => 1, 'client_name' => 'On-Loan Customer']);
        DB::table('teves_cashiers_report_lpg_inventory')->insert([
            'cashiers_report_id' => 1, 'product_idx' => 1, 'inventory_mode' => 'BEGINNING', 'quantity' => 50,
        ]);
        DB::table('teves_cashiers_report_lpg_ar_so')->insert([
            ['cashiers_report_id' => 1, 'product_idx' => 1, 'client_idx' => 1, 'item_description' => 'Sales Order',
                'sale_stock_type' => 'CYLINDER_WITH_CONTENT', 'is_on_loan' => true, 'order_quantity' => 4,
                'unit_price' => 32, 'discount_per_unit' => 0],
            ['cashiers_report_id' => 1, 'product_idx' => 1, 'client_idx' => 1, 'item_description' => 'Sales Order',
                'sale_stock_type' => 'REFILL_SWAP', 'is_on_loan' => false, 'order_quantity' => 3,
                'unit_price' => 1290, 'discount_per_unit' => 0],
        ]);
        DB::table('teves_cashiers_report_lpg_on_loan')->insert([
            'cashiers_report_id' => 1, 'client_idx' => 1, 'borrow_product_idx' => 1, 'borrow_quantity' => 2, 'borrow_stock_state' => 'FILLED',
        ]);

        $summary = new LpgSellOutSummary();
        $records = $summary->records(1);
        $this->assertSame(1, $records->where('kind', 'sales')->where('mode', 'ON_LOAN')->count());
        $row = $summary->rows($records, 1)->firstWhere('product_idx', 1);

        $this->assertSame(6.0, $row['on_loan_quantity']); // 4 from the Sales Order + 2 physical loan
        $this->assertSame(3.0, $row['refill_swap_quantity']);
        $this->assertSame(41.0, $row['theoretical_filled_quantity']); // 50 - 4 - 2 - 3
        $this->assertSame(3998.0, $row['total_sales']); // On-Loan Sales Order value is still revenue
    }

    public function test_on_loan_sales_order_appears_in_the_inventory_on_loan_record_list(): void
    {
        DB::table('teves_client_table')->insert(['client_id' => 1, 'client_name' => 'On-Loan Customer']);
        DB::table('teves_cashiers_report_lpg_ar_so')->insert([
            'cashiers_report_id' => 1, 'product_idx' => 1, 'client_idx' => 1,
            'item_description' => 'Sales Order', 'sale_stock_type' => 'CYLINDER_WITH_CONTENT',
            'is_on_loan' => true, 'order_quantity' => 4, 'unit_price' => 32, 'discount_per_unit' => 0,
            'dr_number' => 'SO-100', 'transaction_type' => 'A.R.',
        ]);
        DB::table('teves_cashiers_report_lpg_ar_so')->insert([
            'cashiers_report_id' => 1, 'product_idx' => 1, 'client_idx' => 1,
            'item_description' => 'Cash Sales', 'sale_stock_type' => 'CYLINDER_WITH_CONTENT',
            'is_on_loan' => true, 'order_quantity' => 1, 'unit_price' => 32, 'discount_per_unit' => 0,
            'dr_number' => 'SO-101', 'transaction_type' => 'Cash',
        ]);

        $response = (new CashiersReport_LPG_Container_Controller())->get_lpg_on_loan_list(
            Request::create('/', 'POST', ['CashiersReportId' => 1])
        );
        $rows = collect($response->getData(true));

        $this->assertCount(2, $rows);
        $arRow = $rows->firstWhere('dr_number', 'SO-100');
        $cashRow = $rows->firstWhere('dr_number', 'SO-101');
        $this->assertSame('sales_order', $arRow['record_type']);
        $this->assertSame(1, $arRow['borrow_product_idx']);
        $this->assertSame(4.0, (float) $arRow['borrow_quantity']);
        $this->assertSame('Sales Order / A.R. / On-Loan', $arRow['remarks']);
        $this->assertSame('Sales Order / Cash / On-Loan', $cashRow['remarks']);
    }

    public function test_empty_tracking_filters_automatic_sources_and_existing_manual_rows(): void
    {
        foreach ([1, 2, 3, 7] as $productId) {
            DB::table('teves_cashiers_report_lpg_swapping')->insert([
                'cashiers_report_id' => 1, 'received_product_idx' => $productId,
                'received_quantity' => 2, 'received_stock_state' => 'EMPTY',
            ]);
            DB::table('teves_cashiers_report_lpg_empty_inventory')->insert([
                'cashiers_report_id' => 1, 'product_idx' => $productId, 'beginning_quantity' => 10,
            ]);
        }
        $summary = new LpgEmptyInventorySummary();
        $this->assertSame([1, 2], $summary->sources(1)->pluck('product_idx')->all());
        $this->assertEqualsCanonicalizing([1, 2], $summary->rows(1)->pluck('product_idx')->all());
        $this->assertSame(4, DB::table('teves_cashiers_report_lpg_empty_inventory')->count());
    }

    public function test_admin_can_save_independent_flags_without_overwriting_other_products(): void
    {
        Session::put('loginID', 1);
        $controller = new LpgSettingsController();
        $this->assertCount(4, $controller->index()->getData()['products']);
        $response = $controller->update(Request::create('/', 'POST', ['products' => [
            ['product_idx' => 1, 'sell_out_enabled' => '0', 'empty_inventory_enabled' => '1'],
        ]]));
        $this->assertSame(302, $response->getStatusCode());
        $this->assertFalse(LpgProductTracking::enabled(1, 'sell_out'));
        $this->assertTrue(LpgProductTracking::enabled(1, 'empty_inventory'));
        $this->assertTrue(LpgProductTracking::enabled(3, 'sell_out'));
        $this->assertFalse(LpgProductTracking::enabled(3, 'empty_inventory'));
    }

    public function test_non_admin_cannot_change_global_tracking(): void
    {
        Session::put('loginID', 2);
        try {
            (new LpgSettingsController())->update(Request::create('/', 'POST', ['products' => []]));
            $this->fail('An encoder must not be able to change global LPG settings.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
        $this->assertTrue(LpgProductTracking::enabled(1, 'sell_out'));
    }

    public function test_settings_reject_unrelated_products_without_partial_updates(): void
    {
        Session::put('loginID', 1);
        try {
            (new LpgSettingsController())->update(Request::create('/', 'POST', ['products' => [
                ['product_idx' => 1, 'sell_out_enabled' => false, 'empty_inventory_enabled' => true],
                ['product_idx' => 6, 'sell_out_enabled' => true, 'empty_inventory_enabled' => true],
            ]]));
            $this->fail('A non-LPG product must not be accepted.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('products.1.product_idx', $exception->errors());
        }
        $this->assertTrue(LpgProductTracking::enabled(1, 'sell_out'));
    }

    public function test_empty_only_product_is_rejected_from_filled_starting_audit(): void
    {
        $request = Request::create('/', 'POST', [
            'cashiers_report_id' => 1, 'product_idx' => 2, 'inventory_mode' => 'BEGINNING',
            'quantity' => 10, 'bo_returned_customer_quantity' => 0, 'bo_during_delivery_quantity' => 0, 'audit_date' => '2026-11-04',
        ]);
        $response = (new CashiersReport_LPG_Inventory_Controller())->save_lpg_inventory($request);
        $this->assertSame(422, $response->getStatusCode());
        $this->assertArrayHasKey('product_idx', $response->getData(true)['errors']);
        $this->assertSame(0, DB::table('teves_cashiers_report_lpg_inventory')->count());
    }

    public function test_bulk_settings_save_uses_a_single_json_field_without_losing_last_switch(): void
    {
        Session::put('loginID', 1);
        $products = [];
        for ($id = 100; $id < 350; $id++) {
            DB::table('teves_product_table')->insert(['product_id' => $id, 'product_name' => 'LPG ' . $id, 'category_idx' => 2]);
            $products[$id] = ['product_idx' => $id, 'sell_out_enabled' => true, 'empty_inventory_enabled' => true];
        }
        $response = (new LpgSettingsController())->update(Request::create('/', 'POST', ['products_json' => json_encode($products)]));
        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(250, DB::table('teves_lpg_product_tracking')->where('product_idx', '>=', 100)->count());
        $this->assertTrue(LpgProductTracking::enabled(349, 'empty_inventory'));
    }
}
