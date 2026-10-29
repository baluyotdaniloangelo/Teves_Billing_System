<?php

namespace Tests\Feature;

use App\Services\SmsBirthdayService;
use App\Services\SmsTemplateService;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class SmsTemplatesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // All database writes are isolated from the configured application database.
        config(['database.default' => 'sms_test', 'database.connections.sms_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
        ], 'cache.default' => 'array', 'session.driver' => 'array', 'sms.birthday_enabled' => false]);
        Schema::create('user_tb', function (Blueprint $table) {
            $table->integer('user_id'); $table->string('user_type'); $table->string('user_real_name'); $table->string('user_name');
        });
        Schema::create('teves_client_table', function (Blueprint $table) {
            $table->integer('client_id'); $table->string('client_name'); $table->string('client_first_name')->nullable();
            $table->string('client_gender')->nullable(); $table->string('client_contact_number')->nullable();
            $table->string('customer_type'); $table->date('client_birthday')->nullable(); $table->timestamp('deleted_at')->nullable();
        });
        (require base_path('database/migrations/2026_07_15_062947_create_teves_sms_campaign_table.php'))->up();
        (require base_path('database/migrations/2026_07_15_063045_create_teves_sms_logs_table.php'))->up();
        (require base_path('database/migrations/2026_10_25_000000_create_sms_birthday_events_table.php'))->up();
        (require base_path('database/migrations/2026_10_25_000001_create_sms_templates_table.php'))->up();
        DB::table('user_tb')->insert(['user_id' => 1, 'user_type' => 'SUAdmin', 'user_real_name' => 'Test Admin', 'user_name' => 'test']);
        Carbon::setTestNow(Carbon::parse('2026-10-25 08:00:00', 'Asia/Manila'));
        Http::fake(['*' => Http::response(['messages' => [['messageId' => 'fake-message']]], 200)]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function client(int $id, array $overrides = []): void
    {
        DB::table('teves_client_table')->insert(array_merge([
            'client_id' => $id, 'client_name' => 'Juan Dela Cruz', 'client_first_name' => 'Juan',
            'client_gender' => 'Male', 'client_contact_number' => '09171234567',
            'customer_type' => 'Household', 'client_birthday' => '1990-10-25', 'deleted_at' => null,
        ], $overrides));
    }

    private function birthdayPayload(): array
    {
        return ['audience' => 'birthday', 'template_key' => 'birthday', 'sms_content' => config('sms.templates.birthday.message'), 'request_id' => (string) Str::uuid()];
    }

    public function test_birthday_template_uses_first_name_exact_signature_and_valid_emoji()
    {
        $text = app(SmsTemplateService::class)->render(config('sms.templates.birthday.message'), ['client_name' => 'Company account', 'client_first_name' => 'Maria']);
        $this->assertStringContainsString('Wishing a very Happy Birthday, Maria,', $text);
        $this->assertStringContainsString('Phoenix Super LPG as your kitchen buddy!', $text);
        $this->assertStringContainsString('🎉🎊🎁', $text);
        $this->assertStringContainsString("Greetings from;\nTeves Gas\nNational Highway, Brgy. San Juan, Madrid Surigao Del Sur", $text);
        $this->assertStringNotContainsString('{', $text);
    }

    public function test_birthday_audience_excludes_other_dates_invalid_deleted_and_duplicate_mobiles()
    {
        $this->client(1);
        $this->client(2, ['client_contact_number' => '+63 917 123 4567']);
        $this->client(3, ['client_birthday' => '1990-10-26', 'client_contact_number' => '09181234567']);
        $this->client(4, ['client_contact_number' => '12345']);
        $this->client(5, ['deleted_at' => now(), 'client_contact_number' => '09191234567']);
        $this->withSession(['loginID' => 1])->getJson('/sms_blast/recipient-count?audience=birthday')->assertOk()->assertJson(['count' => 1]);
        Http::assertNothingSent();
    }

    public function test_birthday_sends_personalized_message_and_never_repeats_same_year()
    {
        $this->client(1);
        $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', $this->birthdayPayload())->assertOk()->assertJson(['total_sent' => 1]);
        $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', $this->birthdayPayload())->assertStatus(422);
        $this->assertSame(1, DB::table('teves_sms_birthday_events')->count());
        $this->assertSame(1, DB::table('teves_sms_campaign_table')->count());
        $this->assertSame(1, DB::table('teves_sms_campaign_table')->value('created_by'));
        $this->assertStringContainsString('Happy Birthday, Juan,', DB::table('teves_sms_logs_table')->value('sms_message'));
        Http::assertSentCount(1);
    }

    public function test_birthday_preview_is_read_only_and_scheduled_send_is_disabled_by_default()
    {
        $this->client(1);
        $this->artisan('sms:birthday')->expectsOutput('2026-10-25: 1 unique valid birthday recipients.')->assertExitCode(0);
        $this->artisan('sms:birthday --send')->assertExitCode(1);
        $this->assertSame(0, DB::table('teves_sms_campaign_table')->count());
        Http::assertNothingSent();
    }

    public function test_enabled_command_sends_once_and_can_send_again_next_year()
    {
        config(['sms.birthday_enabled' => true]);
        $this->client(1);
        $this->artisan('sms:birthday --send')->assertExitCode(0);
        $this->artisan('sms:birthday --send')->assertExitCode(0);
        Carbon::setTestNow(Carbon::parse('2027-10-25 08:00:00', 'Asia/Manila'));
        $this->artisan('sms:birthday --send')->assertExitCode(0);
        Http::assertSentCount(2);
    }

    public function test_reserved_birthday_remains_reserved_after_provider_failure()
    {
        Http::swap(new \Illuminate\Http\Client\Factory());
        Http::fake(['*' => Http::response(['error' => 'Unavailable'], 500)]);
        $this->client(1);
        $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', $this->birthdayPayload())->assertOk()->assertJson(['total_failed' => 1]);
        $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', $this->birthdayPayload())->assertStatus(422);
        Http::assertSentCount(1);
    }

    public function test_birthday_cannot_be_blasted_to_entire_customer_category()
    {
        $this->client(1);
        $payload = $this->birthdayPayload(); $payload['audience'] = 'customer_type'; $payload['customer_type'] = 'Household';
        $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', $payload)->assertStatus(422);
        Http::assertNothingSent();
    }

    public function test_unknown_placeholders_unedited_advisories_and_order_templates_are_rejected()
    {
        $this->client(1);
        foreach ([['', 'Hello {unknown}'], ['service_notice', config('sms.templates.service_notice.message')], ['lpg_refill', config('sms.templates.lpg_refill.message')]] as $case) {
            $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', ['customer_type' => 'Household', 'template_key' => $case[0], 'sms_content' => $case[1], 'request_id' => (string) Str::uuid()])->assertStatus(422);
        }
        Http::assertNothingSent();
    }

    public function test_broadcast_stores_a_different_personalized_message_per_client()
    {
        $this->client(1);
        $this->client(2, ['client_first_name' => 'Maria', 'client_contact_number' => '09181234567']);
        $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', ['customer_type' => 'Household', 'sms_content' => 'Hello {client_first_name}! - {business_name}', 'request_id' => (string) Str::uuid()])->assertOk()->assertJson(['total_sent' => 2]);
        $this->assertSame(['Hello Juan! - Teves Gas', 'Hello Maria! - Teves Gas'], DB::table('teves_sms_logs_table')->orderBy('sms_log_id')->pluck('sms_message')->all());
    }

    public function test_templates_compose_and_history_views_render()
    {
        $this->withSession(['loginID' => 1])->get('/sms_blast?tab=templates')->assertOk()->assertSee('SMS template library')->assertSee('LPG refill reminder');
        $this->withSession(['loginID' => 1])->get('/sms_blast?template=birthday')->assertOk()->assertSee('Birthday today only');
        $this->client(1);
        $campaign = app(SmsBirthdayService::class)->prepare(now(), config('sms.templates.birthday.message'), 1);
        $this->withSession(['loginID' => 1])->get('/sms_blast?tab=history&customer_type=Birthday&campaign_id='.$campaign->campaign_id)->assertOk()->assertSee('Personalized message')->assertSee('Happy Birthday, Juan,');
    }

    public function test_user_can_create_a_template_and_select_it_from_compose()
    {
        $this->withSession(['loginID' => 1])->post('/sms_blast/templates', [
            'name' => 'Delivery advisory', 'scope' => 'broadcast', 'situation' => 'Delivery announcement',
            'message' => 'Hello {client_first_name}! Delivery is available today. - {business_name}',
        ])->assertRedirect('/sms_blast?tab=templates');
        $saved = DB::table('teves_sms_templates')->where('name', 'Delivery advisory')->first();
        $this->assertNotNull($saved);
        $this->assertSame(1, $saved->created_by);
        $this->assertArrayHasKey($saved->template_key, app(SmsTemplateService::class)->all());
        $this->withSession(['loginID' => 1])->get('/sms_blast?template='.$saved->template_key)->assertOk()->assertSee('Delivery advisory');
        Http::assertNothingSent();
    }

    public function test_template_edits_are_persisted_without_changing_existing_message_history()
    {
        $this->client(1);
        $campaign = app(SmsBirthdayService::class)->prepare(now(), config('sms.templates.birthday.message'), 1);
        $oldMessage = DB::table('teves_sms_logs_table')->value('sms_message');
        $this->withSession(['loginID' => 1])->put('/sms_blast/templates/birthday', [
            'name' => 'Personal birthday greeting', 'scope' => 'birthday', 'situation' => 'Birthday today',
            'message' => 'Happy Birthday, {client_first_name}! From Teves Gas.', 'version' => 1,
        ])->assertRedirect('/sms_blast?tab=templates');
        $this->assertSame('Happy Birthday, {client_first_name}! From Teves Gas.', app(SmsTemplateService::class)->all()['birthday']['message']);
        $this->assertSame($oldMessage, DB::table('teves_sms_logs_table')->where('campaign_id', $campaign->campaign_id)->value('sms_message'));
        Http::assertNothingSent();
    }

    public function test_daily_sender_uses_the_latest_saved_birthday_message()
    {
        $this->client(1);
        config(['sms.birthday_enabled' => true]);
        $this->withSession(['loginID' => 1])->put('/sms_blast/templates/birthday', [
            'name' => 'Birthday', 'scope' => 'birthday', 'message' => 'Enjoy your birthday, {client_first_name}! - Teves Gas', 'version' => 1,
        ])->assertRedirect();
        $this->artisan('sms:birthday --send')->assertExitCode(0);
        $this->assertSame('Enjoy your birthday, Juan! - Teves Gas', DB::table('teves_sms_logs_table')->value('sms_message'));
        Http::assertSentCount(1);
    }

    public function test_archiving_hides_template_from_compose_and_restoring_brings_it_back()
    {
        $this->withSession(['loginID' => 1])->patch('/sms_blast/templates/service_notice/status', ['version' => 1, 'is_active' => 0])->assertRedirect();
        $this->assertArrayNotHasKey('service_notice', app(SmsTemplateService::class)->all());
        $this->withSession(['loginID' => 1])->get('/sms_blast?tab=templates')->assertOk()->assertSee('Restore');
        $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', ['template_key' => 'service_notice', 'customer_type' => 'Household', 'sms_content' => 'Hello', 'request_id' => (string) Str::uuid()])->assertStatus(422);
        $this->withSession(['loginID' => 1])->patch('/sms_blast/templates/service_notice/status', ['version' => 2, 'is_active' => 1])->assertRedirect();
        $this->assertArrayHasKey('service_notice', app(SmsTemplateService::class)->all());
        Http::assertNothingSent();
    }

    public function test_stale_edits_do_not_overwrite_newer_saved_changes()
    {
        $payload = ['name' => 'Birthday', 'scope' => 'birthday', 'message' => 'First saved message', 'version' => 1];
        $this->withSession(['loginID' => 1])->putJson('/sms_blast/templates/birthday', $payload)->assertRedirect();
        $payload['message'] = 'Stale message';
        $this->withSession(['loginID' => 1])->putJson('/sms_blast/templates/birthday', $payload)->assertStatus(422)->assertJsonValidationErrors('template');
        $this->assertSame('First saved message', app(SmsTemplateService::class)->all()['birthday']['message']);
    }

    public function test_unknown_fields_empty_messages_and_birthday_category_changes_are_rejected()
    {
        foreach ([['name' => 'Bad', 'scope' => 'broadcast', 'message' => 'Hello {unsupported}'], ['name' => 'Blank', 'scope' => 'broadcast', 'message' => '   ']] as $payload) {
            $this->withSession(['loginID' => 1])->postJson('/sms_blast/templates', $payload)->assertStatus(422);
        }
        $this->withSession(['loginID' => 1])->putJson('/sms_blast/templates/birthday', ['name' => 'Birthday', 'scope' => 'broadcast', 'message' => 'Hello', 'version' => 1])->assertStatus(422);
        $this->withSession(['loginID' => 1])->patchJson('/sms_blast/templates/birthday/status', ['is_active' => 0, 'version' => 1])->assertStatus(422);
        $this->assertSame(6, DB::table('teves_sms_templates')->count());
        Http::assertNothingSent();
    }

    public function test_editor_renders_first_name_buttons_and_existing_emoji_message()
    {
        $this->withSession(['loginID' => 1])->get('/sms_blast?tab=templates&new_template=1')->assertOk()->assertSee('Save template')->assertSee('Sample preview')->assertSee('data-sms-token="client_first_name"', false);
        $this->withSession(['loginID' => 1])->get('/sms_blast?tab=templates&edit_template=birthday')->assertOk()->assertSee('Save changes')->assertSee('🎉🎊🎁')->assertSee('National Highway, Brgy. San Juan, Madrid Surigao Del Sur');
    }

    public function test_template_management_requires_login()
    {
        $this->post('/sms_blast/templates', [])->assertRedirect('/');
        $this->put('/sms_blast/templates/birthday', [])->assertRedirect('/');
        $this->patch('/sms_blast/templates/birthday/status', [])->assertRedirect('/');
    }

    public function test_individual_sending_targets_only_selected_client_with_its_own_saved_number()
    {
        $this->client(1);
        $this->client(2, ['client_name' => 'Maria Santos', 'client_first_name' => 'Maria', 'client_contact_number' => '+63 918 123 4567']);
        $payload = ['audience' => 'individual', 'client_id' => 2, 'confirmed_mobile' => '639181234567',
            'customer_type' => 'Household', 'sms_content' => 'Hello {client_first_name}! - {business_name}', 'request_id' => (string) Str::uuid(),
            'mobile_number' => '639171234567']; // Forged number must never override the client record.
        $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', $payload)->assertOk()->assertJson(['total_sent' => 1]);
        $this->assertSame(1, DB::table('teves_sms_logs_table')->count());
        $log = DB::table('teves_sms_logs_table')->first();
        $this->assertSame(2, $log->client_id);
        $this->assertSame('639181234567', $log->mobile_number);
        $this->assertSame('Hello Maria! - Teves Gas', $log->sms_message);
        $this->assertStringContainsString('Individual SMS - Maria Santos', DB::table('teves_sms_campaign_table')->value('campaign_title'));
        $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', $payload)->assertStatus(409);
        Http::assertSentCount(1);
    }

    public function test_client_search_is_bounded_excludes_deleted_and_flags_invalid_numbers()
    {
        $this->client(1, ['client_name' => 'Maria Santos']);
        $this->client(2, ['client_name' => 'Maria Deleted', 'deleted_at' => now()]);
        $this->client(3, ['client_name' => 'Maria Invalid', 'client_contact_number' => '123']);
        for ($id = 4; $id <= 26; $id++) $this->client($id, ['client_name' => 'Maria '.$id]);
        $response = $this->withSession(['loginID' => 1])->getJson('/sms_blast/clients?q=Maria')->assertOk();
        $clients = collect($response->json('clients'));
        $this->assertSame(20, $clients->count());
        $this->assertFalse($clients->contains('client_id', 2));
        $this->withSession(['loginID' => 1])->getJson('/sms_blast/clients?q=Invalid')->assertOk()->assertJsonPath('clients.0.mobile_number', null);
        Http::assertNothingSent();
    }

    public function test_individual_count_returns_actual_client_preview_fields_without_sending()
    {
        $this->client(1, ['client_name' => 'Sample Business', 'client_first_name' => 'Maria', 'client_gender' => 'Female']);
        $this->withSession(['loginID' => 1])->getJson('/sms_blast/recipient-count?audience=individual&client_id=1')->assertOk()
            ->assertJson(['count' => 1, 'recipient' => ['client_id' => 1, 'client_first_name' => 'Maria', 'client_gender' => 'Female', 'mobile_number' => '639171234567']]);
        Http::assertNothingSent();
    }

    public function test_invalid_deleted_missing_or_changed_individual_recipients_cannot_send()
    {
        $this->client(1, ['client_contact_number' => '123']);
        $this->client(2, ['deleted_at' => now()]);
        $this->client(3, ['client_contact_number' => '09181234567']);
        foreach ([null, 1, 2, 3, 999] as $id) {
            $payload = ['audience' => 'individual', 'client_id' => $id, 'confirmed_mobile' => '639171234567', 'sms_content' => 'Hello', 'request_id' => (string) Str::uuid()];
            $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', $payload)->assertStatus(422);
        }
        $this->assertSame(0, DB::table('teves_sms_campaign_table')->count());
        Http::assertNothingSent();
    }

    public function test_individual_birthday_only_sends_selected_client_and_preserves_yearly_reservation()
    {
        $this->client(1);
        $this->client(2, ['client_contact_number' => '09181234567', 'client_first_name' => 'Maria']);
        $payload = $this->birthdayPayload();
        $payload['audience'] = 'individual'; $payload['client_id'] = 2; $payload['confirmed_mobile'] = '639181234567';
        $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', $payload)->assertOk()->assertJson(['total_sent' => 1]);
        $this->assertSame([2], DB::table('teves_sms_logs_table')->pluck('client_id')->all());
        $payload['request_id'] = (string) Str::uuid();
        $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', $payload)->assertStatus(422);
        Http::assertSentCount(1);
    }

    public function test_individual_birthday_template_cannot_send_to_client_with_other_birthday()
    {
        $this->client(1, ['client_birthday' => '1990-10-26']);
        $payload = $this->birthdayPayload();
        $payload['audience'] = 'individual'; $payload['client_id'] = 1; $payload['confirmed_mobile'] = '639171234567';
        $this->withSession(['loginID' => 1])->postJson('/send_sms_blast', $payload)->assertStatus(422);
        $this->assertSame(0, DB::table('teves_sms_logs_table')->count());
        Http::assertNothingSent();
    }

    public function test_individual_controls_render_and_client_search_requires_login()
    {
        $this->get('/sms_blast/clients?q=Maria')->assertRedirect('/');
        $this->withSession(['loginID' => 1])->get('/sms_blast')->assertOk()->assertSee('Individual client')->assertSee('sms-client-search', false)->assertSee('sms-client-results', false);
    }

    public function test_history_view_opens_details_in_a_dialog_and_supports_existing_campaign_links()
    {
        $this->client(1);
        $campaign = app(SmsBirthdayService::class)->prepare(now(), config('sms.templates.birthday.message'), 1);
        $this->withSession(['loginID' => 1])->get('/sms_blast?tab=history&status=Pending')
            ->assertOk()->assertSee('id="sms-history-dialog"', false)->assertSee('aria-haspopup="dialog"', false)
            ->assertSee('data-sms-campaign-url=', false)->assertSee('data-auto-open="0"', false);
        $this->withSession(['loginID' => 1])->get('/sms_blast?tab=history&campaign_id='.$campaign->campaign_id)
            ->assertOk()->assertSee('data-auto-open="1"', false)->assertSee('Happy Birthday, Juan,');
        Http::assertNothingSent();
    }

    public function test_campaign_popup_endpoint_is_authenticated_paginated_and_read_only()
    {
        $this->get('/sms_blast/campaigns/1')->assertRedirect('/');
        for ($id = 1; $id <= 26; $id++) $this->client($id, ['client_contact_number' => '0917'.str_pad((string) $id, 7, '0', STR_PAD_LEFT), 'client_first_name' => 'Client'.$id]);
        $campaign = app(SmsBirthdayService::class)->prepare(now(), config('sms.templates.birthday.message'), 1);
        $this->withSession(['loginID' => 1])->get('/sms_blast/campaigns/'.$campaign->campaign_id)
            ->assertOk()->assertSee('Personalized message')->assertSee('Happy Birthday, Client1,')
            ->assertDontSee('Happy Birthday, Client26,')->assertSee('recipients_page=2', false)->assertDontSee('<main', false);
        $this->withSession(['loginID' => 1])->get('/sms_blast/campaigns/'.$campaign->campaign_id.'?recipients_page=2')
            ->assertOk()->assertSee('Happy Birthday, Client26,')->assertDontSee('Happy Birthday, Client1,');
        $this->withSession(['loginID' => 1])->get('/sms_blast/campaigns/999999')->assertNotFound();
        $this->assertSame(26, DB::table('teves_sms_logs_table')->where('sms_status', 'Pending')->count());
        Http::assertNothingSent();
    }
}
