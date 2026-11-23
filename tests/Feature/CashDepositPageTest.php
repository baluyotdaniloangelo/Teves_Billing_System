<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CashDepositPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'cash_deposit_test',
            'database.connections.cash_deposit_test' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => ''],
            'session.driver' => 'array', 'activitylog.enabled' => false,
        ]);
        Carbon::setTestNow('2026-11-24 12:00:00');
        Storage::fake('public');

        Schema::create('user_tb', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->string('user_type');
            $table->string('user_branch_access_type');
            $table->string('user_real_name');
        });
        Schema::create('teves_bank_table', function (Blueprint $table) {
            $table->integer('bank_id')->primary();
            $table->string('bank_name');
            $table->string('bank_account_number');
            $table->softDeletes();
        });
        Schema::create('teves_cash_deposits', function (Blueprint $table) {
            $table->increments('cash_deposit_id');
            $table->integer('bank_idx');
            $table->string('cash_deposit_bank')->nullable();
            $table->dateTime('cash_deposit_date');
            $table->decimal('cash_deposit_amount', 15, 2);
            $table->string('cash_deposit_reference');
            $table->text('cash_deposit_remarks')->nullable();
            $table->string('cash_deposit_photo')->nullable();
            $table->integer('created_by_user_id');
            $table->integer('updated_by_user_id')->nullable();
            $table->integer('deleted_by_user_id')->default(0);
            $table->timestamps(); $table->softDeletes();
        });

        DB::table('user_tb')->insert([
            ['user_id'=>1, 'user_type'=>'SUAdmin', 'user_branch_access_type'=>'ALL', 'user_real_name'=>'Super Admin'],
            ['user_id'=>2, 'user_type'=>'Admin', 'user_branch_access_type'=>'ALL', 'user_real_name'=>'Admin'],
            ['user_id'=>3, 'user_type'=>'Accounting_Staff', 'user_branch_access_type'=>'BYBRANCH', 'user_real_name'=>'Accountant'],
            ['user_id'=>4, 'user_type'=>'Encoder', 'user_branch_access_type'=>'ALL', 'user_real_name'=>'Encoder'],
            ['user_id'=>5, 'user_type'=>'Guest', 'user_branch_access_type'=>'ALL', 'user_real_name'=>'Guest'],
            ['user_id'=>6, 'user_type'=>'Encoder', 'user_branch_access_type'=>'ALL', 'user_real_name'=>'Other Cashier'],
        ]);
        DB::table('teves_bank_table')->insert(['bank_id'=>5, 'bank_name'=>'Sample Bank', 'bank_account_number'=>'1234', 'deleted_at'=>null]);
        $this->deposit(1, 1, 47);
        $this->deposit(2, 1, 49);
        $this->deposit(3, 2, 1);
        $this->deposit(4, 4, 47);
        $this->deposit(5, 4, 49);
        $this->deposit(6, 6, 1);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function deposit(int $id, int $owner, int $hoursAgo): void
    {
        DB::table('teves_cash_deposits')->insert([
            'cash_deposit_id'=>$id, 'bank_idx'=>5, 'cash_deposit_bank'=>null,
            'cash_deposit_date'=>'2026-11-24', 'cash_deposit_amount'=>100,
            'cash_deposit_reference'=>'REF-'.$id, 'cash_deposit_photo'=>'cash_deposits/existing.jpg',
            'created_by_user_id'=>$owner, 'created_at'=>now()->subHours($hoursAgo),
            'updated_at'=>now()->subHours($hoursAgo), 'deleted_at'=>null,
        ]);
    }

    private function asUser(int $id): self
    {
        return $this->withSession(['loginID'=>$id, 'UserType'=>['', 'SUAdmin', 'Admin', 'Accounting_Staff', 'Encoder', 'Guest', 'Encoder'][$id]]);
    }

    private function values(string $reference): array
    {
        return ['bank_idx'=>5, 'cash_deposit_date'=>'2026-11-24',
            'cash_deposit_amount'=>125.50, 'cash_deposit_reference'=>$reference,
            'cash_deposit_remarks'=>'Deposit slip verified'];
    }

    public function test_allowed_roles_can_create_with_photo_and_encoder_is_recorded(): void
    {
        $this->asUser(1)->postJson(route('cash_deposits.store'), $this->values('NO-PHOTO'))
            ->assertUnprocessable()->assertJsonValidationErrors('cash_deposit_photo');
        foreach ([1, 2, 3, 4] as $user) {
            $this->asUser($user)->postJson(route('cash_deposits.store'),
                $this->values('NEW-'.$user) + ['cash_deposit_photo'=>UploadedFile::fake()->image('slip.jpg')])
                ->assertCreated();
            $this->assertDatabaseHas('teves_cash_deposits', [
                'cash_deposit_reference'=>'NEW-'.$user, 'created_by_user_id'=>$user,
            ]);
        }
    }

    public function test_only_original_encoder_can_edit_or_delete_within_48_hours(): void
    {
        $update = $this->values('CHANGED');
        $this->asUser(2)->putJson(route('cash_deposits.update', 1), $update)->assertForbidden();
        $this->asUser(1)->putJson(route('cash_deposits.update', 2), $update)->assertForbidden();
        $this->asUser(1)->deleteJson(route('cash_deposits.destroy', 2))->assertForbidden();
        $this->asUser(1)->putJson(route('cash_deposits.update', 1), $update)->assertOk();
        $this->assertDatabaseHas('teves_cash_deposits', ['cash_deposit_id'=>1, 'cash_deposit_reference'=>'CHANGED']);
        $this->asUser(1)->deleteJson(route('cash_deposits.destroy', 1))->assertOk();
        $this->assertDatabaseHas('teves_cash_deposits', ['cash_deposit_id'=>1, 'deleted_by_user_id'=>1]);
        $this->assertNotNull(DB::table('teves_cash_deposits')->where('cash_deposit_id', 1)->value('deleted_at'));
    }

    public function test_page_is_standalone_and_role_restrictions_still_apply(): void
    {
        $page = $this->asUser(1)->get(route('cash_deposits.index'));
        $page->assertOk()->assertSee('Cash Deposits')->assertSee('REF-1')->assertSee('REF-3');
        $page->assertDontSee('Back to report')->assertDontSee('For this cashier report');
        $this->assertFalse(Schema::hasColumn('teves_cash_deposits', 'cashiers_report_idx'));
        $this->assertSame(1, substr_count($page->getContent(), 'class="btn cd-icon cd-edit"'));
        $this->asUser(3)->get(route('cash_deposits.index'))->assertOk();
        $this->asUser(5)->get(route('cash_deposits.index'))->assertForbidden();
        $this->asUser(5)->postJson(route('cash_deposits.store'),
            $this->values('NO-ACCESS') + ['cash_deposit_photo'=>UploadedFile::fake()->image('slip.jpg')])->assertForbidden();
        $this->asUser(1)->get('/cashiers_report_form_fuel/10/cash-deposits')->assertNotFound();
        $this->asUser(1)->postJson('/save_cash_deposit_cashiers_report_p9')->assertNotFound();
    }

    public function test_edit_window_closes_exactly_after_48_hours(): void
    {
        Carbon::setTestNow('2026-11-24 13:00:00');
        $this->asUser(1)->putJson(route('cash_deposits.update', 1), $this->values('TOO-LATE'))
            ->assertForbidden();
    }

    public function test_encoder_sees_only_own_deposits_and_own_summary_even_when_searching(): void
    {
        $page = $this->asUser(4)->get(route('cash_deposits.index'));
        $page->assertOk()->assertSee('href="'.route('cash_deposits.index').'"', false)
            ->assertSee('Your recorded deposits')->assertSee('₱200.00')
            ->assertSee('REF-4')->assertSee('REF-5')
            ->assertDontSee('REF-1')->assertDontSee('REF-2')->assertDontSee('REF-3')->assertDontSee('REF-6');
        $this->assertSame(1, substr_count($page->getContent(), 'class="btn cd-icon cd-edit"'));

        $this->asUser(4)->get(route('cash_deposits.index', ['q' => 'REF-1']))
            ->assertOk()->assertSee('No cash deposits found.')
            ->assertDontSee('<td>REF-1</td>', false)->assertSee('₱200.00');
    }

    public function test_encoder_can_change_own_recent_deposit_only(): void
    {
        $this->asUser(4)->putJson(route('cash_deposits.update', 1), $this->values('OTHER-USER'))
            ->assertForbidden();
        $this->asUser(4)->deleteJson(route('cash_deposits.destroy', 3))->assertForbidden();
        $this->asUser(4)->putJson(route('cash_deposits.update', 6), $this->values('OTHER-CASHIER'))
            ->assertForbidden();
        $this->asUser(4)->deleteJson(route('cash_deposits.destroy', 6))->assertForbidden();
        $this->asUser(4)->putJson(route('cash_deposits.update', 5), $this->values('TOO-OLD'))
            ->assertForbidden();
        $this->asUser(4)->deleteJson(route('cash_deposits.destroy', 5))->assertForbidden();

        $this->asUser(4)->putJson(route('cash_deposits.update', 4), $this->values('CASHIER-UPDATED'))
            ->assertOk();
        $this->assertDatabaseHas('teves_cash_deposits', [
            'cash_deposit_id'=>4, 'cash_deposit_reference'=>'CASHIER-UPDATED', 'updated_by_user_id'=>4,
        ]);
        $this->asUser(4)->deleteJson(route('cash_deposits.destroy', 4))->assertOk();
        $this->assertDatabaseHas('teves_cash_deposits', ['cash_deposit_id'=>4, 'deleted_by_user_id'=>4]);
    }

    public function test_deposit_photo_uses_a_working_route_with_record_access_checks(): void
    {
        UploadedFile::fake()->image('slip.jpg')->storeAs('cash_deposits', 'existing.jpg', 'public');
        $photoUrl = route('cash_deposits.photo', 4, false);

        $this->asUser(4)->get(route('cash_deposits.index'))
            ->assertOk()->assertSee('data-photo="'.$photoUrl.'"', false);
        $this->asUser(4)->get($photoUrl)->assertOk();
        $this->asUser(1)->get($photoUrl)->assertOk();
        $this->asUser(6)->get($photoUrl)->assertForbidden();
        $this->asUser(5)->get($photoUrl)->assertForbidden();
        $this->asUser(4)->get(route('cash_deposits.photo', 999))->assertNotFound();

        Storage::disk('public')->delete('cash_deposits/existing.jpg');
        $this->asUser(4)->get($photoUrl)->assertNotFound();
    }
}
