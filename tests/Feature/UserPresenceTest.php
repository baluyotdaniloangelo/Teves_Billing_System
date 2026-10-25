<?php

namespace Tests\Feature;

use App\Services\UserPresenceService;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserPresenceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'presence_test',
            'database.connections.presence_test' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true],
            'session.driver' => 'array', 'session.lifetime' => 120, 'activitylog.enabled' => false, 'cache.default' => 'array',
        ]);
        Schema::create('user_tb', function (Blueprint $table) {
            $table->increments('user_id');
            foreach (['user_real_name', 'user_job_title', 'user_name', 'user_email_address', 'user_password', 'user_type', 'user_status', 'user_branch_access_type'] as $field) $table->string($field)->nullable();
            $table->timestamps(); $table->softDeletes();
        });
        (require base_path('database/migrations/2026_10_25_000002_create_teves_user_presence_table.php'))->up();
        foreach ([1 => 'SUAdmin', 2 => 'Admin', 3 => 'Encoder', 4 => 'Supervisor'] as $id => $role) {
            DB::table('user_tb')->insert([
                'user_id' => $id, 'user_name' => 'member' . $id, 'user_real_name' => 'Team member ' . $id,
                'user_type' => $role, 'user_status' => 'Active', 'user_password' => Hash::make('secret123'),
                'user_branch_access_type' => 'ALL',
            ]);
        }
        Carbon::setTestNow(Carbon::parse('2026-10-25 10:00:00', 'Asia/Manila'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function browser(int $userId, string $key): Request
    {
        $session = new Store('test', new ArraySessionHandler(120));
        $session->setId(hash('sha1', $key)); $session->start(); $session->put('loginID', $userId);
        $request = Request::create('/user/presence/heartbeat', 'POST'); $request->setLaravelSession($session);

        return $request;
    }

    private function seedPresence(int $userId, string $key, int $minutesAgo = 0, bool $loggedOut = false): void
    {
        DB::table('teves_user_presence')->insert([
            'session_key' => hash('sha256', $key), 'user_idx' => $userId,
            'last_seen_at' => now('UTC')->subMinutes($minutesAgo)->toDateTimeString(),
            'logged_out_at' => $loggedOut ? now('UTC')->toDateTimeString() : null,
        ]);
    }

    public function test_heartbeat_records_only_the_authenticated_user_and_hides_session_metadata()
    {
        $this->postJson('/user/presence/heartbeat')->assertRedirect('/');
        $this->withSession(['loginID' => 3])->postJson('/user/presence/heartbeat', ['userID' => 1])->assertOk()->assertJson(['tracking_available' => true, 'online_window_seconds' => 300]);
        $this->assertSame([3], DB::table('teves_user_presence')->pluck('user_idx')->all());
        $key = DB::table('teves_user_presence')->value('session_key');
        $this->assertSame(64, strlen($key)); $this->assertNotSame(session()->getId(), $key);
        $this->withSession(['loginID' => 3])->postJson('/user_list')->assertForbidden();
        $response = $this->withSession(['loginID' => 1])->postJson('/user_list', ['role' => 'Encoder'])->assertOk()->assertJsonPath('data.0.is_online', true);
        $this->assertStringNotContainsString($key, $response->getContent());
    }

    public function test_normal_authenticated_requests_record_presence_without_a_heartbeat()
    {
        $this->withSession(['loginID' => 3])->postJson('/user_info', ['UserID' => 3])->assertOk();
        $this->assertSame(3, DB::table('teves_user_presence')->value('user_idx'));
    }

    public function test_presence_writes_are_throttled_and_store_utc_timestamps()
    {
        $service = new UserPresenceService(); $browser = $this->browser(3, 'browser-one');
        $service->touch($browser);
        $this->assertSame('2026-10-25 02:00:00', DB::table('teves_user_presence')->value('last_seen_at'));
        Carbon::setTestNow(now()->addSeconds(30)); $service->touch($browser);
        $this->assertSame('2026-10-25 02:00:00', DB::table('teves_user_presence')->value('last_seen_at'));
        Carbon::setTestNow(now()->addSeconds(31)); $service->touch($browser);
        $this->assertSame(1, DB::table('teves_user_presence')->count());
        $this->assertSame('2026-10-25 02:01:01', DB::table('teves_user_presence')->value('last_seen_at'));
    }

    public function test_online_count_counts_users_once_across_multiple_browser_sessions()
    {
        $this->seedPresence(3, 'desktop'); $this->seedPresence(3, 'mobile'); $this->seedPresence(4, 'stale', 6);
        $this->withSession(['loginID' => 1])->postJson('/user_list', ['presence' => 'online'])->assertOk()->assertJsonPath('recordsFiltered', 2)->assertJsonPath('summary.online', 2);
        $this->withSession(['loginID' => 1])->postJson('/user_list', ['presence' => 'offline'])->assertOk()->assertJsonPath('recordsFiltered', 2);
    }

    public function test_closing_the_browser_expires_presence_without_ending_its_login()
    {
        $browser = $this->browser(3, 'quiet-browser'); (new UserPresenceService())->touch($browser);
        Carbon::setTestNow(now()->addMinutes(6));
        $this->withSession(['loginID' => 1])->postJson('/user_list', ['role' => 'Encoder'])->assertOk()->assertJsonPath('data.0.is_online', false)->assertJsonPath('data.0.last_seen_at', '2026-10-25T02:00:00+00:00');
        $this->assertSame(3, $browser->session()->get('loginID'));
    }

    public function test_logging_out_one_browser_keeps_another_active_browser_online()
    {
        $service = new UserPresenceService(); $desktop = $this->browser(3, 'desktop'); $mobile = $this->browser(3, 'mobile');
        $service->touch($desktop); $service->touch($mobile); $service->logout($desktop);
        $this->assertSame(1, DB::table('teves_user_presence')->whereNull('logged_out_at')->count());
        $this->withSession(['loginID' => 1])->postJson('/user_list', ['role' => 'Encoder'])->assertOk()->assertJsonPath('data.0.is_online', true);
        $service->logout($mobile);
        $this->withSession(['loginID' => 1])->postJson('/user_list', ['role' => 'Encoder'])->assertOk()->assertJsonPath('data.0.is_online', false);
    }

    public function test_logout_marks_the_current_browser_offline_and_invalidates_its_login()
    {
        $this->withSession(['loginID' => 3])->postJson('/user/presence/heartbeat')->assertOk();
        $this->withCookie(config('session.cookie'), session()->getId());
        $this->get('/logout')->assertRedirect('/')->assertSessionMissing('loginID');
        $this->assertSame(0, DB::table('teves_user_presence')->whereNull('logged_out_at')->count());
        $this->assertNotNull(DB::table('teves_user_presence')->value('logged_out_at'));
    }

    public function test_delayed_request_cannot_reopen_a_logged_out_session()
    {
        $service = new UserPresenceService(); $browser = $this->browser(3, 'late-request');
        $service->touch($browser); $service->logout($browser);
        Carbon::setTestNow(now()->addMinutes(2)); $service->touch($browser);
        $this->assertNotNull(DB::table('teves_user_presence')->value('logged_out_at'));
        $this->withSession(['loginID' => 1])->postJson('/user_list', ['role' => 'Encoder'])->assertOk()->assertJsonPath('data.0.is_online', false);
    }

    public function test_successful_login_regenerates_the_session_and_records_presence()
    {
        $this->withSession([]); $oldId = session()->getId();
        $this->post('/login-user', ['user_name' => 'member3', 'InputPassword' => 'secret123'])->assertRedirect('billing')->assertSessionHas('loginID', 3);
        $this->assertNotSame($oldId, session()->getId());
        $this->assertSame(3, DB::table('teves_user_presence')->value('user_idx'));
    }

    public function test_inactive_and_deleted_accounts_do_not_inflate_superadmin_counts()
    {
        $this->seedPresence(1, 'super-admin'); $this->seedPresence(3, 'inactive'); $this->seedPresence(4, 'deleted');
        DB::table('user_tb')->where('user_id', 3)->update(['user_status' => 'Inactive']);
        DB::table('user_tb')->where('user_id', 4)->update(['deleted_at' => now()]);
        $this->withSession(['loginID' => 1])->postJson('/user_list', ['presence' => 'online'])->assertOk()->assertJsonPath('recordsFiltered', 1)->assertJsonPath('summary.online', 1)->assertJsonPath('data.0.user_id', 1);
        $this->withSession(['loginID' => 1])->postJson('/user_list', ['role' => 'Encoder'])->assertOk()->assertJsonPath('data.0.is_online', false);
    }

    public function test_heartbeat_rejects_deactivated_accounts()
    {
        DB::table('user_tb')->where('user_id', 3)->update(['user_status' => 'Inactive']);
        $this->withSession(['loginID' => 3])->postJson('/user/presence/heartbeat')->assertForbidden();
        $this->assertSame(0, DB::table('teves_user_presence')->count());
    }

    public function test_missing_migration_is_reported_as_unavailable_instead_of_offline()
    {
        Schema::drop('teves_user_presence');
        $this->withSession(['loginID' => 1])->postJson('/user_list')->assertOk()->assertJsonPath('presence.available', false)->assertJsonPath('summary.online', null)->assertJsonPath('data.0.is_online', null);
        $this->withSession(['loginID' => 1])->postJson('/user_list', ['presence' => 'offline'])->assertOk()->assertJsonPath('recordsFiltered', 0);
    }

    public function test_invalid_presence_filter_is_rejected()
    {
        $this->withSession(['loginID' => 1])->postJson('/user_list', ['presence' => 'unknown'])->assertJsonValidationErrors('presence');
    }

    public function test_online_window_does_not_exceed_the_configured_session_lifetime()
    {
        config(['session.lifetime' => 2]);
        $this->seedPresence(3, 'short-session', 3);
        $this->withSession(['loginID' => 1])->postJson('/user_list', ['role' => 'Encoder'])->assertOk()->assertJsonPath('data.0.is_online', false)->assertJsonPath('presence.window_seconds', 120);
    }

    public function test_header_online_endpoint_is_superadmin_only_even_with_forged_session_role()
    {
        $this->getJson('/user/presence/online')->assertRedirect('/');
        foreach ([2, 3, 4] as $id) {
            $this->withSession(['loginID' => $id, 'UserType' => 'SUAdmin'])->getJson('/user/presence/online')->assertForbidden();
        }
    }

    public function test_header_list_aggregates_users_and_excludes_offline_logged_out_and_inactive_accounts()
    {
        $this->seedPresence(3, 'desktop'); $this->seedPresence(3, 'mobile');
        $this->seedPresence(4, 'quiet', 6); $this->seedPresence(2, 'signed-out', 0, true);
        $response = $this->withSession(['loginID' => 1])->getJson('/user/presence/online')->assertOk()->assertJsonPath('total', 2)->assertJsonCount(2, 'users');
        $this->assertSame([1, 3], array_column($response->json('users'), 'id'));
        $this->assertSame(['id', 'name', 'role', 'last_seen_at'], array_keys($response->json('users.0')));
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
    }

    public function test_header_list_is_bounded_but_total_includes_every_online_user()
    {
        foreach (range(5, 17) as $id) {
            DB::table('user_tb')->insert(['user_id' => $id, 'user_name' => 'user' . $id, 'user_real_name' => 'Member ' . $id, 'user_type' => 'Encoder', 'user_status' => 'Active']);
            $this->seedPresence($id, 'browser-' . $id);
        }
        $this->withSession(['loginID' => 1])->getJson('/user/presence/online')->assertOk()->assertJsonPath('total', 14)->assertJsonCount(10, 'users');
    }

    public function test_admin_cannot_receive_presence_data_through_the_directory()
    {
        $this->seedPresence(3, 'encoder');
        $response = $this->withSession(['loginID' => 2])->postJson('/user_list')->assertOk();
        $this->assertArrayNotHasKey('presence', $response->json());
        $this->assertArrayNotHasKey('online', $response->json('summary'));
        foreach ($response->json('data') as $row) {
            $this->assertArrayNotHasKey('is_online', $row);
            $this->assertArrayNotHasKey('last_seen_at', $row);
        }
        $this->withSession(['loginID' => 2])->postJson('/user_list', ['presence' => 'online'])->assertForbidden();
    }

    public function test_shortcut_assets_and_presence_fields_render_only_for_superadmin()
    {
        $this->withSession(['loginID' => 1])->get('/user?presence=online')->assertOk()
            ->assertSee('id="online-users-shortcut"', false)->assertSee('online-users-shortcut.js')
            ->assertSee('View all online')->assertSee('value="online"  selected', false);
        $this->withSession(['loginID' => 2])->get('/user')->assertOk()
            ->assertDontSee('id="online-users-shortcut"', false)->assertDontSee('online-users-shortcut.js')
            ->assertDontSee('id="user-presence-filter"', false)->assertSee('Active accounts');
    }

    public function test_header_endpoint_handles_missing_migration_without_disclosing_data()
    {
        Schema::drop('teves_user_presence');
        $this->withSession(['loginID' => 1])->getJson('/user/presence/online')->assertOk()->assertJson(['available' => false, 'total' => null, 'users' => []]);
    }
}
