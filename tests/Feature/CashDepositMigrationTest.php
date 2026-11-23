<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CashDepositMigrationTest extends TestCase
{
    public function test_legacy_deposits_are_copied_without_the_report_relationship(): void
    {
        config([
            'database.default' => 'cash_deposit_migration_test',
            'database.connections.cash_deposit_migration_test' => [
                'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
            ],
        ]);
        Schema::create('teves_cashiers_report_p9', function (Blueprint $table) {
            $table->increments('cashiers_report_p9_id');
            $table->integer('cashiers_report_idx')->nullable();
            $table->integer('bank_idx')->nullable();
            $table->string('cash_deposit_bank')->nullable();
            $table->dateTime('cash_deposit_date')->nullable();
            $table->decimal('cash_deposit_amount', 15, 2);
            $table->string('cash_deposit_reference')->nullable();
            $table->text('cash_deposit_remarks')->nullable();
            $table->string('cash_deposit_photo')->nullable();
            $table->integer('created_by_user_id')->nullable();
            $table->integer('updated_by_user_id')->nullable();
            $table->integer('deleted_by_user_id')->default(0);
            $table->timestamps(); $table->softDeletes();
        });
        Schema::create('teves_cashiers_report', function (Blueprint $table) {
            $table->integer('cashiers_report_id')->primary();
            $table->integer('teves_branch');
        });
        DB::table('teves_cashiers_report')->insert(['cashiers_report_id'=>42, 'teves_branch'=>10]);
        DB::table('teves_cashiers_report_p9')->insert([
            'cashiers_report_p9_id' => 7, 'cashiers_report_idx' => 42,
            'bank_idx' => 5, 'cash_deposit_bank' => 'Sample Bank', 'cash_deposit_date' => '2026-11-24 09:00:00',
            'cash_deposit_amount' => 750, 'cash_deposit_reference' => 'OLD-7',
            'cash_deposit_remarks' => 'Existing record',
            'cash_deposit_photo' => 'cash_deposits/old.jpg',
            'created_by_user_id' => 3, 'updated_by_user_id' => null,
            'deleted_by_user_id' => 0, 'created_at' => '2026-11-24 09:00:00',
            'updated_at' => '2026-11-24 09:00:00', 'deleted_at' => null,
        ]);

        $migration = require database_path('migrations/2026_11_24_000001_create_teves_cash_deposits_table.php');
        $migration->up();
        $branchMigration = require database_path('migrations/2026_11_24_000002_add_branch_to_cash_deposits_table.php');
        $branchMigration->up();

        $this->assertDatabaseHas('teves_cash_deposits', [
            'cash_deposit_id' => 7, 'bank_idx' => 5, 'cash_deposit_bank' => 'Sample Bank',
            'cash_deposit_reference' => 'OLD-7', 'created_by_user_id' => 3, 'branch_idx' => 10,
        ]);
        $this->assertFalse(Schema::hasColumn('teves_cash_deposits', 'cashiers_report_idx'));
        $this->assertDatabaseHas('teves_cashiers_report_p9', [
            'cashiers_report_p9_id' => 7, 'cashiers_report_idx' => 42,
        ]);
    }
}
