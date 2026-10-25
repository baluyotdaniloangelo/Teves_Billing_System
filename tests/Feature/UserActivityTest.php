<?php

namespace Tests\Feature;

use App\Models\UserAccountModel;
use App\Services\UserActivityService;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserActivityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'activity_test', 'activitylog.database_connection' => null,
            'database.connections.activity_test' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true],
            'activitylog.enabled' => true, 'activitylog.table_name' => 'activity_log', 'cache.default' => 'array', 'session.driver' => 'array',
        ]);
        Schema::create('user_tb', function (Blueprint $table) {
            $table->increments('user_id');
            foreach (['user_real_name', 'user_job_title', 'user_name', 'user_email_address', 'user_password', 'user_type', 'user_status', 'user_branch_access_type'] as $field) $table->string($field)->nullable();
            $table->integer('created_by_user_idx')->nullable(); $table->integer('updated_by_user_idx')->nullable(); $table->integer('deleted_by_user_id')->nullable();
            $table->timestamps(); $table->softDeletes();
        });
        Schema::create('activity_log', function (Blueprint $table) {
            $table->bigIncrements('id'); $table->string('log_name')->nullable(); $table->text('description');
            $table->string('subject_type')->nullable(); $table->integer('subject_id')->nullable();
            $table->string('causer_type')->nullable(); $table->integer('causer_id')->nullable();
            $table->text('properties')->nullable(); $table->timestamps();
        });
        (require base_path('database/migrations/2026_10_25_000003_add_user_history_indexes_to_activity_log.php'))->up();
        foreach ([1 => 'SUAdmin', 2 => 'Admin', 3 => 'Encoder', 4 => 'Supervisor'] as $id => $role) {
            DB::table('user_tb')->insert([
                'user_id' => $id, 'user_real_name' => 'Team member ' . $id, 'user_name' => 'member' . $id,
                'user_email_address' => 'member' . $id . '@example.test', 'user_job_title' => 'Team',
                'user_type' => $role, 'user_status' => 'Active', 'user_password' => Hash::make('secret123'), 'user_branch_access_type' => 'BYBRANCH',
            ]);
        }
        Mail::fake(); Carbon::setTestNow(Carbon::parse('2026-10-25 10:00:00', 'Asia/Manila'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow(); parent::tearDown();
    }

    private function event(int $userId, string $action = 'updated', array $extra = []): int
    {
        return DB::table('activity_log')->insertGetId(array_merge([
            'log_name' => 'Client Information', 'description' => $action, 'causer_id' => $userId,
            'causer_type' => null, 'subject_type' => 'App\\Models\\ClientModel', 'subject_id' => 99,
            'properties' => json_encode(['old' => ['client_name' => 'Old name'], 'attributes' => ['client_name' => 'New name']]),
            'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString(),
        ], $extra));
    }

    public function test_history_page_list_and_detail_are_superadmin_only()
    {
        $id = $this->event(3);
        foreach (['/user/activity', '/user/activity/list', '/user/activity/' . $id] as $url) {
            $this->getJson($url)->assertRedirect('/');
            foreach ([2, 3, 4] as $actor) $this->withSession(['loginID' => $actor, 'UserType' => 'SUAdmin'])->getJson($url)->assertForbidden();
            $this->flushSession();
        }
        $this->withSession(['loginID' => 1])->getJson('/user/activity/list')->assertOk()->assertJsonPath('data.0.user_id', 3);
    }

    public function test_filters_and_summary_are_scoped_to_the_selected_user_date_module_and_action()
    {
        $this->event(3, 'created'); $this->event(3, 'updated'); $this->event(2, 'created');
        $this->event(3, 'created', ['created_at' => '2026-10-01 10:00:00']);
        $this->withSession(['loginID' => 1])->getJson('/user/activity/list?user_id=3&action=created&module=Client%20Information&start_date=2026-10-25&end_date=2026-10-25')
            ->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('summary.events', 1)->assertJsonPath('summary.users', 1)->assertJsonPath('summary.changes', 1);
    }

    public function test_history_paginates_in_a_stable_latest_first_order()
    {
        foreach (range(1, 23) as $number) $this->event(3, 'updated', ['subject_id' => $number]);
        $this->withSession(['loginID' => 1])->getJson('/user/activity/list')->assertOk()->assertJsonCount(20, 'data')->assertJsonPath('last_page', 2)->assertJsonPath('total', 23)->assertJsonPath('data.0.record', 'Record #23');
        $this->withSession(['loginID' => 1])->getJson('/user/activity/list?page=2')->assertOk()->assertJsonCount(3, 'data')->assertJsonPath('data.0.record', 'Record #3');
    }

    public function test_legacy_actor_ids_resolve_and_snapshots_survive_renamed_or_deleted_accounts()
    {
        $id = $this->event(3, 'updated', ['properties' => json_encode(['_actor' => ['id' => 3, 'name' => 'Original employee', 'role' => 'Encoder']])]);
        DB::table('user_tb')->where('user_id', 3)->update(['user_real_name' => 'Renamed employee', 'deleted_at' => now()]);
        $this->withSession(['loginID' => 1])->getJson('/user/activity/' . $id)->assertOk()->assertJsonPath('user_name', 'Original employee');
        $id = $this->event(2);
        $this->withSession(['loginID' => 1])->getJson('/user/activity/' . $id)->assertOk()->assertJsonPath('user_name', 'Team member 2');
    }

    public function test_non_user_causer_types_are_not_mapped_to_an_unrelated_user()
    {
        $id = $this->event(1, 'updated', ['causer_type' => 'App\\Models\\SomeExternalActor']);
        $this->withSession(['loginID' => 1])->getJson('/user/activity/' . $id)->assertOk()->assertJsonPath('user_id', null)->assertJsonPath('user_name', 'System / unknown actor');
        $this->withSession(['loginID' => 1])->getJson('/user/activity/list?user_id=1')->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_legacy_passwords_tokens_and_binary_values_are_hidden_from_details()
    {
        $id = $this->event(3, 'updated', ['properties' => json_encode([
            'old' => ['user_password' => 'OLD_SECRET_HASH', 'nested' => ['api_token' => 'OLD_TOKEN']],
            'attributes' => ['user_password' => 'NEW_SECRET_HASH', 'nested' => ['api_token' => 'NEW_TOKEN'], 'client_name' => 'Updated client'],
        ])]);
        $response = $this->withSession(['loginID' => 1])->getJson('/user/activity/' . $id)->assertOk();
        foreach (['OLD_SECRET_HASH', 'NEW_SECRET_HASH', 'OLD_TOKEN', 'NEW_TOKEN'] as $secret) $this->assertStringNotContainsString($secret, $response->getContent());
        $response->assertJsonPath('changes.0.before', '[Hidden]')->assertJsonPath('changes.0.after', '[Hidden]');
        $this->assertSame('[Binary data omitted]', app(UserActivityService::class)->sanitize("\xFF\xFE"));
    }

    public function test_new_model_events_record_the_real_actor_and_redact_passwords_before_storage()
    {
        $this->withSession(['loginID' => 2, 'UserType' => 'SUAdmin']);
        $user = UserAccountModel::find(3); $user->user_job_title = 'Senior encoder'; $user->user_password = Hash::make('new-secret'); $user->save();
        $event = DB::table('activity_log')->latest('id')->first();
        $properties = json_decode($event->properties, true);
        $this->assertSame(2, $event->causer_id); $this->assertSame(UserAccountModel::class, $event->causer_type);
        $this->assertSame('Team member 2', $properties['_actor']['name']);
        $this->assertSame('[Hidden]', $properties['attributes']['user_password']);
        $this->assertStringNotContainsString($user->user_password, $event->properties);
    }

    public function test_successful_login_and_logout_are_recorded_with_the_verified_actor()
    {
        $this->post('/login-user', ['user_name' => 'member3', 'InputPassword' => 'secret123'])->assertRedirect('billing');
        $this->assertSame(3, DB::table('activity_log')->where('description', 'login')->value('causer_id'));
        $this->withCookie(config('session.cookie'), session()->getId())->get('/logout')->assertRedirect('/');
        $this->assertSame(3, DB::table('activity_log')->where('description', 'logout')->value('causer_id'));
        $this->assertSame(2, DB::table('activity_log')->where('log_name', 'Authentication')->count());
        $this->assertStringNotContainsString('secret123', DB::table('activity_log')->where('description', 'login')->value('properties'));
    }

    public function test_reauthentication_in_the_same_browser_is_attributed_to_the_new_user()
    {
        $this->withSession(['loginID' => 2])->post('/login-user', ['user_name' => 'member3', 'InputPassword' => 'secret123'])->assertRedirect('billing');
        $this->assertSame(3, DB::table('activity_log')->where('description', 'login')->value('causer_id'));
    }

    public function test_page_views_are_recorded_while_ajax_reads_heartbeats_and_history_reads_are_not()
    {
        $this->withSession(['loginID' => 1])->get('/user')->assertOk();
        $this->assertSame(1, DB::table('activity_log')->where('description', 'viewed')->count());
        $count = DB::table('activity_log')->count();
        $this->withSession(['loginID' => 1])->postJson('/user_list')->assertOk();
        $this->withSession(['loginID' => 1])->postJson('/user_info', ['UserID' => 3])->assertOk();
        $this->withSession(['loginID' => 1])->postJson('/user/presence/heartbeat')->assertOk();
        $this->withSession(['loginID' => 1])->getJson('/user/activity/list')->assertOk();
        $this->withSession(['loginID' => 1])->get('/user/activity')->assertOk();
        $this->assertSame($count, DB::table('activity_log')->count());
    }

    public function test_request_fallback_records_operation_metadata_without_request_payload()
    {
        Route::middleware(['web', 'isLoggedIn'])->post('/activity-test-submit', function () { return response()->json(['ok' => true]); })->name('activity.test.submit');
        $this->withSession(['loginID' => 3])->postJson('/activity-test-submit', ['password' => 'BODY_SECRET', 'content' => 'PRIVATE_BODY'])->assertOk();
        $event = DB::table('activity_log')->where('description', 'submitted')->first();
        $this->assertNotNull($event); $this->assertSame(3, $event->causer_id);
        $this->assertStringNotContainsString('BODY_SECRET', $event->properties); $this->assertStringNotContainsString('PRIVATE_BODY', $event->properties);
    }

    public function test_access_assignment_changes_include_before_and_after_ids()
    {
        Schema::create('teves_branch_table', function (Blueprint $table) {$table->integer('branch_id'); $table->string('branch_name'); $table->string('branch_code'); $table->softDeletes();});
        Schema::create('teves_user_branch_access', function (Blueprint $table) {$table->increments('id');$table->integer('user_idx');$table->integer('branch_idx');$table->integer('created_by_user_idx')->nullable();$table->timestamps();});
        DB::table('teves_branch_table')->insert([['branch_id'=>1,'branch_name'=>'Main','branch_code'=>'M'],['branch_id'=>2,'branch_name'=>'North','branch_code'=>'N']]);
        DB::table('teves_user_branch_access')->insert(['user_idx'=>3,'branch_idx'=>1]);
        $this->withSession(['loginID'=>2])->postJson('/add_user_access_post',['userID'=>3,'site_items'=>[2]])->assertOk();
        $event = DB::table('activity_log')->where('log_name','User branch access')->first();
        $properties=json_decode($event->properties,true);
        $this->assertSame([1],$properties['old']['branch_ids']);$this->assertSame([2],$properties['attributes']['branch_ids']);$this->assertSame(3,$event->subject_id);
        $this->withSession(['loginID'=>2])->postJson('/add_user_access_post',['userID'=>3,'site_items'=>[2]])->assertOk();
        $this->assertSame(1,DB::table('activity_log')->where('log_name','User branch access')->count());
    }

    public function test_model_updates_are_not_duplicated_as_generic_submitted_requests()
    {
        $this->withSession(['loginID'=>2])->postJson('/update_user_post',[
            'userID'=>3,'user_real_name'=>'Team member 3','user_name'=>'member3','user_job_title'=>'Changed title',
            'user_email_address'=>'member3@example.test','user_password'=>'','user_type'=>'Encoder','user_status'=>'Active','user_access'=>'BYBRANCH',
        ])->assertOk();
        $this->assertSame(1,DB::table('activity_log')->where('log_name','User Information')->where('description','updated')->count());
        $this->assertSame(0,DB::table('activity_log')->where('description','submitted')->count());
    }

    public function test_invalid_filters_and_missing_details_have_bounded_error_responses()
    {
        foreach (['start_date=bad','start_date=2026-10-26&end_date=2026-10-25','start_date=2020-01-01&end_date=2026-10-25','user_id[]=1','per_page=10000','action=unknown'] as $query) {
            $this->withSession(['loginID'=>1])->getJson('/user/activity/list?'.$query)->assertStatus(422);
        }
        $this->withSession(['loginID'=>1])->getJson('/user/activity/999')->assertNotFound();
    }

    public function test_html_like_user_names_and_change_values_are_returned_as_data_without_html_templates()
    {
        DB::table('user_tb')->where('user_id',3)->update(['user_real_name'=>'<img src=x onerror=alert(1)>']);
        $id=$this->event(3,'updated',['properties'=>json_encode(['attributes'=>['client_name'=>'<script>alert(1)</script>']])]);
        $response=$this->withSession(['loginID'=>1])->getJson('/user/activity/'.$id)->assertOk();
        $response->assertJsonPath('user_name','<img src=x onerror=alert(1)>')->assertJsonPath('changes.0.after','<script>alert(1)</script>');
        $this->assertArrayNotHasKey('properties',$response->json());
    }

    public function test_history_links_are_visible_only_to_superadmin()
    {
        $this->withSession(['loginID'=>1])->get('/user')->assertOk()->assertSee('Activity history')->assertSee('User Activity');
        $response=$this->withSession(['loginID'=>1])->postJson('/user_list')->assertOk();
        $this->assertStringContainsString('/user/activity?user_id=',$response->json('data.0.action'));
        $response=$this->withSession(['loginID'=>2])->postJson('/user_list')->assertOk();
        $this->assertStringNotContainsString('/user/activity',$response->json('data.0.action'));
    }

    public function test_search_can_find_the_selected_actor_without_searching_raw_properties()
    {
        $this->event(3); $this->event(2, 'updated', ['properties'=>json_encode(['attributes'=>['note'=>'member3']])]);
        $this->withSession(['loginID'=>1])->getJson('/user/activity/list?search=member3')->assertOk()->assertJsonCount(1,'data')->assertJsonPath('data.0.user_id',3);
    }

    public function test_existing_history_remains_readable_when_new_recording_is_disabled()
    {
        $this->event(3); config(['activitylog.enabled'=>false]);
        $this->withSession(['loginID'=>1])->getJson('/user/activity/list')->assertOk()->assertJsonPath('recording_enabled',false)->assertJsonCount(1,'data');
        $this->withSession(['loginID'=>3])->get('/logout')->assertRedirect('/');
        $this->assertSame(1,DB::table('activity_log')->count());
    }

    public function test_missing_history_table_returns_an_unavailable_state()
    {
        Schema::drop('activity_log');
        $this->withSession(['loginID'=>1])->getJson('/user/activity/list')->assertOk()->assertJsonPath('available',false)->assertJsonCount(0,'data');
        $this->withSession(['loginID'=>1])->getJson('/user/activity/1')->assertStatus(503);
    }

    public function test_incomplete_legacy_events_can_be_viewed_without_loading_unknown_models()
    {
        $id=$this->event(999,'legacy custom event',['properties'=>'not valid json','subject_type'=>'Missing\\Legacy\\Model']);
        $this->withSession(['loginID'=>1])->getJson('/user/activity/'.$id)->assertOk()->assertJsonPath('user_name','User #999')->assertJsonPath('action_label','Recorded event')->assertJsonCount(0,'changes');
    }

    public function test_signing_out_an_existing_deactivated_session_retains_its_actor_identity()
    {
        DB::table('user_tb')->where('user_id',3)->update(['user_status'=>'Inactive']);
        $this->withSession(['loginID'=>3])->get('/logout')->assertRedirect('/');
        $this->assertSame(3,DB::table('activity_log')->where('description','logout')->value('causer_id'));
    }
}
