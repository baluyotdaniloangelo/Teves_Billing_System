<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FuelDashboardTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Isolated test connection: never migrate or write the configured application database.
        config(['database.default' => 'dashboard_test', 'database.connections.dashboard_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
        ], 'session.driver' => 'array']);
        Schema::create('user_tb', function (Blueprint $table) {
            $table->integer('user_id'); $table->string('user_type');
            $table->string('user_real_name'); $table->string('user_name');
        });
        Schema::create('teves_branch_table', function (Blueprint $table) {
            $table->integer('branch_id'); $table->string('branch_code'); $table->string('branch_name');
        });
        Schema::create('teves_user_branch_access', function (Blueprint $table) {
            $table->integer('user_idx'); $table->integer('branch_idx');
        });
        Schema::create('teves_cashiers_report', function (Blueprint $table) {
            $table->integer('teves_branch'); $table->date('report_date'); $table->timestamp('deleted_at')->nullable();
            foreach (['total_sales', 'total_cash_sales', 'non_cash_payment', 'short_over'] as $field) $table->decimal($field, 14, 2);
        });
        DB::table('user_tb')->insert(['user_id'=>1, 'user_type'=>'SUAdmin', 'user_real_name'=>'Test Admin', 'user_name'=>'test']);
        DB::table('teves_branch_table')->insert([
            ['branch_id'=>1, 'branch_code'=>'ALPHA', 'branch_name'=>'Alpha station'],
            ['branch_id'=>2, 'branch_code'=>'BETA', 'branch_name'=>'Beta station'],
        ]);
        DB::table('teves_user_branch_access')->insert(['user_idx'=>1, 'branch_idx'=>1]);
        foreach ([['2026-10-10', 1, 100], ['2026-10-22', 1, 200], ['2026-10-23', 1, 300], ['2026-10-23', 2, 900]] as $row) {
            DB::table('teves_cashiers_report')->insert(['report_date'=>$row[0], 'teves_branch'=>$row[1], 'total_sales'=>$row[2], 'total_cash_sales'=>$row[2], 'non_cash_payment'=>0, 'short_over'=>0, 'deleted_at'=>null]);
        }
        Carbon::setTestNow(Carbon::parse('2026-10-24 12:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_date_and_station_filters_use_the_requested_range()
    {
        $this->withSession(['loginID'=>1, 'user_branch_access_type'=>'ALL'])
            ->get('/dashboard?start_date=2026-10-10&end_date=2026-10-22&company_header=1')
            ->assertOk()->assertViewIs('dashboard.fuel')
            ->assertViewHas('start_date', '2026-10-10')
            ->assertViewHas('result', function ($rows) { return $rows->count() === 2 && (float)$rows->sum('total_sales') === 300.0; });
    }

    public function test_restricted_users_only_see_accessible_branches_and_reports()
    {
        $this->withSession(['loginID'=>1, 'user_branch_access_type'=>'BYBRANCH'])
            ->get('/dashboard?start_date=2026-10-22&end_date=2026-10-23')
            ->assertOk()->assertViewHas('branches', function ($rows) { return $rows->pluck('branch_id')->all() === [1]; })
            ->assertViewHas('result', function ($rows) { return $rows->count() === 2 && (float)$rows->sum('total_sales') === 500.0; });
        $this->from('/dashboard')->get('/dashboard?company_header=2')->assertRedirect('/dashboard')->assertSessionHasErrors('company_header');
    }

    public function test_reversed_dates_are_rejected()
    {
        $this->withSession(['loginID'=>1])->from('/dashboard')
            ->get('/dashboard?start_date=2026-10-24&end_date=2026-10-22')
            ->assertRedirect('/dashboard')->assertSessionHasErrors('end_date');
    }

    public function test_empty_period_renders_without_dividing_by_zero()
    {
        $this->withSession(['loginID'=>1])->get('/dashboard?start_date=2026-09-01&end_date=2026-09-02')
            ->assertOk()->assertSee('No reports match this selection.')->assertSee('₱0.00');
    }

    public function test_dashboard_keeps_existing_authentication_and_role_restrictions()
    {
        $this->get('/dashboard')->assertRedirect('/');
        DB::table('user_tb')->where('user_id', 1)->update(['user_type'=>'Encoder']);
        $this->withSession(['loginID'=>1])->get('/dashboard')->assertRedirect('/billing');
    }
}
