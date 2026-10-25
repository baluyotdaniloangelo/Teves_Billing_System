<?php

namespace Tests\Feature;

use App\Mail\ResetPassword;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'user_test',
            'database.connections.user_test' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true],
            'activitylog.enabled' => false, 'session.driver' => 'array', 'cache.default' => 'array',
        ]);
        Schema::create('user_tb', function (Blueprint $table) {
            $table->increments('user_id');
            foreach (['user_real_name', 'user_job_title', 'user_name', 'user_email_address', 'user_password', 'user_type', 'user_status', 'user_branch_access_type'] as $field) $table->string($field)->nullable();
            $table->integer('created_by_user_idx')->nullable(); $table->integer('updated_by_user_idx')->nullable();
            $table->integer('deleted_by_user_id')->nullable(); $table->timestamps(); $table->softDeletes();
        });
        Schema::create('teves_branch_table', function (Blueprint $table) {
            $table->increments('branch_id'); $table->string('branch_code'); $table->string('branch_name'); $table->softDeletes();
        });
        Schema::create('teves_product_category', function (Blueprint $table) {
            $table->increments('category_id'); $table->string('category_name'); $table->softDeletes();
        });
        foreach (['teves_user_branch_access' => 'branch_idx', 'teves_user_product_category_access' => 'category_idx'] as $name => $column) {
            Schema::create($name, function (Blueprint $table) use ($column) {
                $table->increments('id'); $table->integer('user_idx'); $table->integer($column);
                $table->integer('created_by_user_idx')->nullable(); $table->timestamps();
            });
        }
        $this->user(1, 'SUAdmin'); $this->user(2, 'Admin'); $this->user(3, 'Encoder'); $this->user(4, 'Supervisor');
        DB::table('teves_branch_table')->insert([
            ['branch_id' => 1, 'branch_code' => 'MAIN', 'branch_name' => 'Main branch', 'deleted_at' => null],
            ['branch_id' => 2, 'branch_code' => 'NORTH', 'branch_name' => 'North branch', 'deleted_at' => null],
            ['branch_id' => 3, 'branch_code' => 'OLD', 'branch_name' => 'Deleted branch', 'deleted_at' => now()],
        ]);
        DB::table('teves_product_category')->insert([['category_id' => 1, 'category_name' => 'Fuel'], ['category_id' => 2, 'category_name' => 'LPG']]);
        Mail::fake();
    }

    private function user(int $id, string $role): void
    {
        DB::table('user_tb')->insert([
            'user_id' => $id, 'user_real_name' => 'Team member ' . $id, 'user_name' => 'member' . $id,
            'user_email_address' => 'member' . $id . '@example.test', 'user_password' => Hash::make('secret123'),
            'user_type' => $role, 'user_status' => 'Active', 'user_branch_access_type' => 'BYBRANCH',
            'created_at' => '2026-10-01 12:00:00', 'updated_at' => '2026-10-25 08:30:00',
        ]);
    }

    private function payload(array $values = []): array
    {
        return array_merge([
            'user_real_name' => 'New team member', 'user_job_title' => 'Accountant', 'user_name' => 'new.member',
            'user_email_address' => 'new.member@example.test', 'user_password' => 'secret456',
            'user_type' => 'Encoder', 'user_status' => 'Active', 'user_access' => 'BYBRANCH',
        ], $values);
    }

    public function test_guests_and_non_managers_cannot_manage_accounts_or_permissions()
    {
        $this->get('/user')->assertRedirect('/');
        foreach (['/user', '/user_branch_access?UserID=2', '/user_product_access?UserID=2'] as $url) $this->withSession(['loginID' => 3])->getJson($url)->assertForbidden();
        foreach (['/user_list' => [], '/create_user_post' => $this->payload(), '/update_user_post' => $this->payload(['userID' => 4]), '/delete_user_confirmed' => ['userID' => 4], '/add_user_access_post' => ['userID' => 4, 'site_items' => [1]], '/add_user_product_access_post' => ['userID' => 4, 'category_items' => [1]]] as $url => $body) {
            $this->withSession(['loginID' => 3])->postJson($url, $body)->assertForbidden();
        }
        $this->assertSame(4, DB::table('user_tb')->count());
    }

    public function test_database_role_wins_over_stale_or_forged_session_role()
    {
        $this->withSession(['loginID' => 3, 'UserType' => 'SUAdmin'])->postJson('/user_list')->assertForbidden();
        DB::table('user_tb')->where('user_id', 2)->update(['user_status' => 'Inactive']);
        $this->withSession(['loginID' => 2, 'UserType' => 'Admin'])->postJson('/user_list')->assertForbidden();
    }

    public function test_admin_list_hides_superadmins_and_filters_roles_and_status()
    {
        $response = $this->withSession(['loginID' => 2])->postJson('/user_list', ['draw' => 1, 'start' => 0, 'length' => 10]);
        $response->assertOk()->assertJsonPath('recordsTotal', 3)->assertJsonPath('summary.total', 3);
        $this->assertNotContains('SUAdmin', array_column($response->json('data'), 'user_type'));
        $this->withSession(['loginID' => 1])->postJson('/user_list', ['role' => 'Encoder'])->assertOk()->assertJsonPath('recordsFiltered', 1)->assertJsonPath('data.0.user_id', 3);
        $this->withSession(['loginID' => 1])->postJson('/user_list', ['status' => 'Inactive'])->assertOk()->assertJsonPath('recordsFiltered', 0);
    }

    public function test_admin_cannot_target_superadmin_or_create_that_role()
    {
        $this->withSession(['loginID' => 2])->postJson('/user_info', ['UserID' => 1])->assertNotFound();
        $this->withSession(['loginID' => 2])->postJson('/update_user_post', $this->payload(['userID' => 1]))->assertNotFound();
        $this->withSession(['loginID' => 2])->postJson('/delete_user_confirmed', ['userID' => 1])->assertNotFound();
        $this->withSession(['loginID' => 2])->postJson('/create_user_post', $this->payload(['user_type' => 'SUAdmin']))->assertJsonValidationErrors('user_type');
        $this->withSession(['loginID' => 2])->postJson('/add_user_access_post', ['userID' => 1, 'site_items' => [1]])->assertNotFound();
    }

    public function test_creation_hashes_password_and_records_actual_creator()
    {
        $response = $this->withSession(['loginID' => 2])->postJson('/create_user_post', $this->payload());
        $response->assertCreated();
        $user = DB::table('user_tb')->where('user_id', $response->json('user_id'))->first();
        $this->assertTrue(Hash::check('secret456', $user->user_password));
        $this->assertSame(2, $user->created_by_user_idx);
        Mail::assertSent(ResetPassword::class, 1);
    }

    public function test_validation_rejects_duplicate_invalid_email_role_status_and_access()
    {
        $this->withSession(['loginID' => 1])->postJson('/create_user_post', $this->payload([
            'user_name' => 'member3', 'user_email_address' => 'invalid', 'user_type' => 'Unknown', 'user_status' => 'Unknown', 'user_access' => 'Unknown', 'user_password' => 'short',
        ]))->assertJsonValidationErrors(['user_name', 'user_email_address', 'user_type', 'user_status', 'user_access', 'user_password']);
        $this->assertSame(4, DB::table('user_tb')->count());
        Mail::assertNothingSent();
    }

    public function test_blank_password_update_preserves_hash_and_sets_audit_id()
    {
        $hash = DB::table('user_tb')->where('user_id', 3)->value('user_password');
        $this->withSession(['loginID' => 2])->postJson('/update_user_post', $this->payload(['userID' => 3, 'user_password' => '']))->assertOk();
        $this->assertSame($hash, DB::table('user_tb')->where('user_id', 3)->value('user_password'));
        $this->assertSame(2, DB::table('user_tb')->where('user_id', 3)->value('updated_by_user_idx'));
        Mail::assertNothingSent();
    }

    public function test_password_change_hashes_once_and_sends_existing_notification()
    {
        $this->withSession(['loginID' => 1])->postJson('/update_user_post', $this->payload(['userID' => 3]))->assertOk();
        $this->assertTrue(Hash::check('secret456', DB::table('user_tb')->where('user_id', 3)->value('user_password')));
        Mail::assertSent(ResetPassword::class, 1);
    }

    public function test_missing_or_malformed_target_is_handled_without_server_error()
    {
        $this->withSession(['loginID' => 1])->postJson('/update_user_post', $this->payload(['userID' => ['1']]))->assertJsonValidationErrors('userID');
        $this->withSession(['loginID' => 1])->postJson('/user_info', ['UserID' => 999])->assertNotFound();
        $this->withSession(['loginID' => 1])->postJson('/delete_user_confirmed', ['userID' => 999])->assertNotFound();
    }

    public function test_self_delete_demotion_and_deactivation_are_blocked()
    {
        $this->withSession(['loginID' => 2])->postJson('/delete_user_confirmed', ['userID' => 2])->assertStatus(422);
        $this->withSession(['loginID' => 2])->postJson('/update_user_post', $this->payload(['userID' => 2, 'user_type' => 'Encoder']))->assertJsonValidationErrors('user_type');
        $this->withSession(['loginID' => 2])->postJson('/update_user_post', $this->payload(['userID' => 2, 'user_type' => 'Admin', 'user_status' => 'Inactive']))->assertJsonValidationErrors('user_type');
    }

    public function test_deletion_is_soft_and_has_audit_id()
    {
        $this->withSession(['loginID' => 2])->postJson('/delete_user_confirmed', ['userID' => 3])->assertOk();
        $user = DB::table('user_tb')->where('user_id', 3)->first();
        $this->assertNotNull($user->deleted_at); $this->assertSame(2, $user->deleted_by_user_id);
        $this->withSession(['loginID' => 2])->postJson('/user_info', ['UserID' => 3])->assertNotFound();
    }

    public function test_self_profile_remains_available_but_cannot_update_another_user()
    {
        $response = $this->withSession(['loginID' => 3])->postJson('/user_info', ['UserID' => 3])->assertOk();
        $this->assertArrayNotHasKey('user_password', $response->json());
        $this->withSession(['loginID' => 3])->postJson('/user_account_post', $this->payload(['userID' => 4]))->assertForbidden();
        $this->withSession(['loginID' => 3])->postJson('/user_account_post', $this->payload(['userID' => 3, 'user_password' => '']))->assertOk();
        $this->assertSame('Encoder', DB::table('user_tb')->where('user_id', 3)->value('user_type'));
    }

    public function test_branch_access_replacement_and_empty_selection()
    {
        $this->withSession(['loginID' => 2])->postJson('/add_user_access_post', ['userID' => 3, 'site_items' => [1, 2]])->assertOk();
        $this->assertSame(2, DB::table('teves_user_branch_access')->count());
        $response = $this->withSession(['loginID' => 2])->getJson('/user_branch_access?UserID=3');
        $response->assertOk()->assertJsonCount(2, 'data')->assertJsonPath('data.0.selected', true);
        $this->withSession(['loginID' => 2])->postJson('/add_user_access_post', ['userID' => 3, 'site_items' => []])->assertOk();
        $this->assertSame(0, DB::table('teves_user_branch_access')->count());
    }

    public function test_invalid_deleted_or_duplicate_access_ids_preserve_existing_selection()
    {
        DB::table('teves_user_branch_access')->insert(['user_idx' => 3, 'branch_idx' => 1]);
        foreach ([[2, 999], [3], [1, 1], ['bad']] as $items) {
            $this->withSession(['loginID' => 2])->postJson('/add_user_access_post', ['userID' => 3, 'site_items' => $items])->assertStatus(422);
            $this->assertSame([1], DB::table('teves_user_branch_access')->pluck('branch_idx')->all());
        }
    }

    public function test_category_access_accepts_legacy_csv_and_array_payloads()
    {
        $this->withSession(['loginID' => 2])->postJson('/add_user_product_access_post', ['userID' => 3, 'category_items' => '1,2'])->assertOk();
        $this->withSession(['loginID' => 2])->getJson('/user_product_access?UserID=3')->assertOk()->assertJsonCount(2, 'data')->assertJsonPath('data.0.selected', true);
        $this->withSession(['loginID' => 2])->postJson('/add_user_product_access_post', ['userID' => 3, 'category_items' => [2]])->assertOk();
        $this->assertSame([2], DB::table('teves_user_product_category_access')->pluck('category_idx')->all());
        $this->withSession(['loginID' => 2])->postJson('/add_user_product_access_post', ['userID' => 3, 'category_items' => ''])->assertOk();
        $this->assertSame(0, DB::table('teves_user_product_category_access')->count());
    }

    public function test_missing_access_payload_does_not_clear_assignments()
    {
        DB::table('teves_user_branch_access')->insert(['user_idx' => 3, 'branch_idx' => 1]);
        $this->withSession(['loginID' => 2])->postJson('/add_user_access_post', ['userID' => 3])->assertJsonValidationErrors('site_items');
        $this->assertSame([1], DB::table('teves_user_branch_access')->pluck('branch_idx')->all());
    }

    public function test_email_length_matches_existing_database_column()
    {
        $this->withSession(['loginID' => 1])->postJson('/create_user_post', $this->payload(['user_email_address' => str_repeat('a', 60) . '@' . str_repeat('b', 45) . '.test']))->assertJsonValidationErrors('user_email_address');
        $this->assertSame(4, DB::table('user_tb')->count());
    }

    public function test_page_renders_separated_forms_and_role_options()
    {
        $this->withSession(['loginID' => 1])->get('/user')->assertOk()->assertSee('user-management.js')->assertSee('user-management.css')->assertSee('CreateUserform')->assertSee('UpdateUserform')->assertSee('branch-access-modal')->assertSee('category-access-modal');
        $response = $this->withSession(['loginID' => 2])->get('/user');
        $response->assertOk()->assertDontSee('<option value="SUAdmin">', false);
    }

    public function test_dates_and_action_labels_are_rendered_safely()
    {
        DB::table('user_tb')->where('user_id', 3)->update(['user_real_name' => '"><img src=x onerror=alert(1)>']);
        $response = $this->withSession(['loginID' => 1])->postJson('/user_list', ['role' => 'Encoder']);
        $response->assertOk()->assertJsonPath('data.0.updated_at_dt_format', '2026-10-25 08:30:00');
        $this->assertStringNotContainsString('<img', $response->json('data.0.action'));
        $this->assertStringContainsString('&lt;img', $response->json('data.0.action'));
    }
}
