<?php

namespace Tests\Feature;

use App\Http\Controllers\SalesOrderController;
use App\Models\ReceivablesModel;
use App\Models\SalesOrderModel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SalesOrderBalanceTest extends TestCase
{
    public function test_payment_entries_block_order_and_product_changes_even_for_admins()
    {
        config(['database.default'=>'lock_test', 'database.connections.lock_test'=>[
            'driver'=>'sqlite', 'database'=>':memory:', 'prefix'=>'',
        ]]);
        Schema::create('teves_receivable_table', function (Blueprint $table) {
            $table->integer('receivable_id');
            $table->integer('sales_order_idx');
            $table->softDeletes();
        });
        Schema::create('teves_receivable_payment', function (Blueprint $table) {
            $table->integer('receivable_idx');
            $table->string('receivable_mode_of_payment');
            $table->integer('receivable_payment_amount');
            $table->softDeletes();
        });
        Schema::create('teves_sales_order_component_table', function (Blueprint $table) {
            $table->integer('sales_order_component_id');
            $table->integer('sales_order_idx');
            $table->softDeletes();
        });
        DB::table('teves_receivable_table')->insert(['receivable_id'=>20, 'sales_order_idx'=>1]);
        // Any active entry locks the order, including an uncleared check with zero amount.
        DB::table('teves_receivable_payment')->insert(['receivable_idx'=>20,
            'receivable_mode_of_payment'=>'Post-Dated Check', 'receivable_payment_amount'=>0]);
        DB::table('teves_sales_order_component_table')->insert(['sales_order_component_id'=>10, 'sales_order_idx'=>1]);
        session()->put(['loginID'=>1, 'UserType'=>'SUAdmin']);
        $controller = app(SalesOrderController::class);
        foreach (['update_sales_order_post', 'sales_order_component_compose', 'delete_sales_order_component_confirmed'] as $method) {
            try {
                $controller->$method(\Illuminate\Http\Request::create('/', 'POST', [
                    'sales_order_id'=>1, 'sales_order_component_id'=>10,
                ]));
                $this->fail($method.' accepted an order with payment entries');
            } catch (\Illuminate\Validation\ValidationException $error) {
                $this->assertArrayHasKey('payment_lock', $error->errors());
            }
        }
        $this->assertSame(1, DB::table('teves_sales_order_component_table')->count());
        $check = new \ReflectionMethod(SalesOrderController::class, 'assertOrderHasNoPayments');
        $check->setAccessible(true);
        $check->invoke($controller, 2); // An unrelated unpaid order remains editable.
        DB::table('teves_receivable_payment')->update(['deleted_at'=>'2026-11-02']);
        $check->invoke($controller, 1); // Deleted entries no longer lock an order.
        $this->assertTrue(true);
    }

    public function test_revised_totals_preserve_payments_and_update_both_statuses()
    {
        config(['database.default' => 'balance_test', 'database.connections.balance_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        Schema::create('teves_sales_order_table', function (Blueprint $table) {
            $table->integer('sales_order_id')->primary();
            $table->string('sales_order_payment_status');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('teves_receivable_payment', function (Blueprint $table) {
            $table->integer('receivable_idx');
            $table->string('receivable_mode_of_payment')->nullable();
            $table->decimal('receivable_payment_amount', 15, 2);
            $table->softDeletes();
        });
        DB::table('teves_sales_order_table')->insert(['sales_order_id'=>1, 'sales_order_payment_status'=>'100.00%']);
        DB::table('teves_receivable_payment')->insert([
            ['receivable_idx'=>20, 'receivable_mode_of_payment'=>'Cash', 'receivable_payment_amount'=>2600000, 'deleted_at'=>null],
            ['receivable_idx'=>20, 'receivable_mode_of_payment'=>'Cash', 'receivable_payment_amount'=>1000000, 'deleted_at'=>null],
            ['receivable_idx'=>20, 'receivable_mode_of_payment'=>'Post-Dated Check', 'receivable_payment_amount'=>900000, 'deleted_at'=>null],
            ['receivable_idx'=>20, 'receivable_mode_of_payment'=>'Cash', 'receivable_payment_amount'=>800000, 'deleted_at'=>'2026-01-01'],
            ['receivable_idx'=>99, 'receivable_mode_of_payment'=>'Cash', 'receivable_payment_amount'=>700000, 'deleted_at'=>null],
        ]);
        $sync = new \ReflectionMethod(SalesOrderController::class, 'syncReceivablePaymentBalance');
        $sync->setAccessible(true);
        foreach ([
            [3600000, 20, '0.00', '100.00%'], // Saving information on a fully paid order.
            [4000000, 20, '400000.00', '90.00%'], // Price/quantity increase.
            [3000000, 20, '-600000.00', '120.00%'], // Product removal or tax adjustment leaves credit.
            [1000, 21, '1000.00', 'Pending'], // No payments.
            [0, 21, '0.00', 'Pending'], // Removing the final unpaid item does not divide by zero.
        ] as [$due, $id, $balance, $status]) {
            $receivable = new ReceivablesModel();
            $receivable->receivable_id = $id;
            $receivable->sales_order_idx = 1;
            $receivable->receivable_amount = $due;
            SalesOrderModel::withoutEvents(function () use ($sync, $receivable) {
                $sync->invoke(app(SalesOrderController::class), $receivable);
            });
            $this->assertSame($balance, $receivable->receivable_remaining_balance);
            $this->assertSame($status, $receivable->receivable_status);
            $this->assertSame($status, SalesOrderModel::find(1)->sales_order_payment_status);
        }
        $this->assertSame(5, DB::table('teves_receivable_payment')->count());
    }
}
