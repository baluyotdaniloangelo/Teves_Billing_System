<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\BillingReceivablesDashboard;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BillingReceivablesDashboardTest extends TestCase
{
    private function makeTables(): void
    {
        config(['database.default' => 'billing_receivables_test', 'database.connections.billing_receivables_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);

        Schema::create('teves_receivable_table', function (Blueprint $table) {
            $table->integer('receivable_id')->primary();
            $table->integer('sales_order_idx');
            $table->integer('client_idx');
            $table->integer('company_header');
            $table->string('control_number');
            $table->date('billing_date');
            $table->decimal('receivable_amount', 15, 2);
            $table->decimal('receivable_remaining_balance', 15, 2)->nullable();
            $table->string('receivable_status')->nullable();
            $table->string('receivable_lock_status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('user_tb', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->string('user_branch_access_type');
        });
        Schema::create('teves_client_table', function (Blueprint $table) {
            $table->integer('client_id')->primary();
            $table->string('client_name');
            $table->softDeletes();
        });
        Schema::create('teves_branch_table', function (Blueprint $table) {
            $table->integer('branch_id')->primary();
            $table->string('branch_code');
            $table->string('branch_name');
        });
        Schema::create('teves_user_branch_access', function (Blueprint $table) {
            $table->integer('user_idx');
            $table->integer('branch_idx');
        });

        DB::table('teves_branch_table')->insert([
            ['branch_id' => 1, 'branch_code' => 'A', 'branch_name' => 'Branch A'],
            ['branch_id' => 2, 'branch_code' => 'B', 'branch_name' => 'Branch B'],
        ]);
        DB::table('teves_client_table')->insert([
            ['client_id' => 1, 'client_name' => 'Client A', 'deleted_at' => null],
            ['client_id' => 2, 'client_name' => 'Client B', 'deleted_at' => null],
            ['client_id' => 3, 'client_name' => 'Deleted Client', 'deleted_at' => '2026-01-01'],
        ]);
        DB::table('teves_user_branch_access')->insert(['user_idx' => 42, 'branch_idx' => 1]);
        DB::table('user_tb')->insert(['user_id' => 42, 'user_branch_access_type' => 'BYBRANCH']);

        $today = Carbon::today();
        $records = [
            ['receivable_id' => 1, 'sales_order_idx' => 0, 'client_idx' => 1, 'company_header' => 1, 'control_number' => 'A-AR-1', 'billing_date' => $today->toDateString(), 'receivable_amount' => 1000, 'receivable_remaining_balance' => 1000],
            ['receivable_id' => 2, 'sales_order_idx' => 0, 'client_idx' => 2, 'company_header' => 1, 'control_number' => 'A-AR-2', 'billing_date' => $today->copy()->subDay()->toDateString(), 'receivable_amount' => 500, 'receivable_remaining_balance' => 200],
            ['receivable_id' => 3, 'sales_order_idx' => 0, 'client_idx' => 1, 'company_header' => 1, 'control_number' => 'A-AR-3', 'billing_date' => $today->toDateString(), 'receivable_amount' => 400, 'receivable_remaining_balance' => 0],
            ['receivable_id' => 4, 'sales_order_idx' => 0, 'client_idx' => 1, 'company_header' => 2, 'control_number' => 'B-AR-4', 'billing_date' => $today->toDateString(), 'receivable_amount' => 999, 'receivable_remaining_balance' => 999],
            ['receivable_id' => 5, 'sales_order_idx' => 77, 'client_idx' => 1, 'company_header' => 1, 'control_number' => 'SO-AR-5', 'billing_date' => $today->toDateString(), 'receivable_amount' => 2000, 'receivable_remaining_balance' => 2000],
            ['receivable_id' => 6, 'sales_order_idx' => 0, 'client_idx' => 1, 'company_header' => 1, 'control_number' => 'A-AR-6', 'billing_date' => $today->toDateString(), 'receivable_amount' => 3000, 'receivable_remaining_balance' => 3000, 'deleted_at' => '2026-01-01'],
            ['receivable_id' => 7, 'sales_order_idx' => 0, 'client_idx' => 3, 'company_header' => 1, 'control_number' => 'A-AR-7', 'billing_date' => $today->toDateString(), 'receivable_amount' => 4000, 'receivable_remaining_balance' => 4000],
        ];
        DB::table('teves_receivable_table')->insert(array_map(function ($record) {
            return array_merge([
                'deleted_at' => null,
                'created_at' => Carbon::today()->toDateTimeString(),
                'updated_at' => Carbon::today()->toDateTimeString(),
                'receivable_status' => 'Pending',
                'receivable_lock_status' => '00000',
            ], $record);
        }, $records));
    }

    private function user(string $accessType): User
    {
        $user = new User();
        $user->user_id = 42;
        $user->user_branch_access_type = $accessType;
        return $user;
    }

    public function test_summary_matches_billing_records_and_enforces_branch_access(): void
    {
        $this->makeTables();
        $dashboard = new BillingReceivablesDashboard();
        $user = $this->user('BYBRANCH');

        $this->assertEquals([
            'total_due' => 1900.0,
            'collected' => 700.0,
            'outstanding' => 1200.0,
            'open_accounts' => 2,
        ], $dashboard->summary($user));
        $this->assertCount(1, $dashboard->branches($user));
        $this->assertSame([], $dashboard->query($user, ['branch' => 2, 'status' => 'pending'])->pluck('receivable_id')->all());
    }

    public function test_status_period_and_search_filters_use_the_same_records_as_the_table(): void
    {
        $this->makeTables();
        $dashboard = new BillingReceivablesDashboard();
        $user = $this->user('ALL');

        foreach (['pending' => [1, 4], 'partial' => [2], 'paid' => [3]] as $status => $ids) {
            $actual = $dashboard->query($user, ['status' => $status])->orderBy('teves_receivable_table.receivable_id')->pluck('teves_receivable_table.receivable_id')->all();
            $this->assertSame($ids, $actual);
        }

        $filters = $dashboard->filters(Request::create('/', 'GET', [
            'branch' => '1', 'period' => 'today', 'filter_search' => 'Client A', 'status' => 'paid',
        ]));
        $this->assertSame([3], $dashboard->query($user, $filters)->pluck('teves_receivable_table.receivable_id')->all());
        $this->assertSame(400.0, $dashboard->summary($user, $filters)['total_due']);
    }

    public function test_datatable_endpoint_returns_filtered_rows_and_matching_summary(): void
    {
        $this->makeTables();

        $response = $this->withoutMiddleware()
            ->withSession(['loginID' => 42, 'UserType' => 'SUAdmin'])
            ->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->getJson('/receivables/list_billing?' . http_build_query([
                'draw' => 1, 'start' => 0, 'length' => 10, 'status' => 'partial',
                'columns' => [
                    ['data' => 'billing_date', 'name' => 'teves_receivable_table.billing_date', 'searchable' => 'true', 'orderable' => 'true'],
                    ['data' => 'control_number', 'name' => 'teves_receivable_table.control_number', 'searchable' => 'true', 'orderable' => 'true'],
                    ['data' => 'client_name', 'name' => 'teves_client_table.client_name', 'searchable' => 'true', 'orderable' => 'true'],
                    ['data' => 'receivable_amount', 'name' => 'teves_receivable_table.receivable_amount', 'searchable' => 'true', 'orderable' => 'true'],
                    ['data' => 'receivable_remaining_balance', 'name' => 'teves_receivable_table.receivable_remaining_balance', 'searchable' => 'true', 'orderable' => 'true'],
                    ['data' => 'status', 'searchable' => 'false', 'orderable' => 'false'],
                    ['data' => 'action_print', 'searchable' => 'false', 'orderable' => 'false'],
                    ['data' => 'action', 'searchable' => 'false', 'orderable' => 'false'],
                ],
                'order' => [['column' => 0, 'dir' => 'desc']],
            ]));

        $response->assertOk()
            ->assertJsonPath('recordsFiltered', 1)
            ->assertJsonPath('data.0.receivable_id', 2)
            ->assertJsonPath('summary.total_due', 500)
            ->assertJsonPath('summary.collected', 300)
            ->assertJsonPath('summary.outstanding', 200);
        $this->assertStringContainsString('LockReceivable', $response->json('data.0.action'));
        $this->assertStringContainsString('js-delete-receivable', $response->json('data.0.action'));
    }
}
