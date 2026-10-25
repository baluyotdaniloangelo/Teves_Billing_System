<?php

namespace App\Console\Commands;

use App\Services\SmsBirthdayService;
use App\Services\SmsCampaignSender;
use App\Services\SmsTemplateService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendBirthdaySms extends Command
{
    protected $signature = 'sms:birthday {--send : Send today birthday greetings; requires SMS_BIRTHDAY_ENABLED=true}';
    protected $description = 'Preview today birthday recipient count; send only when explicitly enabled';

    public function handle(SmsBirthdayService $birthdays, SmsCampaignSender $sender)
    {
        $date = Carbon::now(config('sms.timezone'));
        $this->info($date->toDateString().': '.$birthdays->recipients($date)->count().' unique valid birthday recipients.');
        if (!$this->option('send')) {
            $this->info('Preview only. No campaigns created and no SMS sent.');
            return 0;
        }
        if (!config('sms.birthday_enabled')) {
            $this->error('Birthday sending is disabled. Review recipients and enable SMS_BIRTHDAY_ENABLED first.');
            return 1;
        }
        $template = app(SmsTemplateService::class)->all()['birthday'] ?? null;
        if (!$template || $template['scope'] !== 'birthday') {
            $this->error('The daily birthday template is unavailable. No SMS sent.');
            return 1;
        }
        $campaign = $birthdays->prepare($date, $template['message']);
        if (!$campaign) { $this->info('No new birthday greetings to send.'); return 0; }
        $sender->process($campaign->campaign_id);
        $campaign->refresh();
        $this->info('Campaign #'.$campaign->campaign_id.': '.$campaign->total_sent.' accepted; '.$campaign->total_failed.' failed. See Message History.');
        return $campaign->total_failed ? 1 : 0;
    }
}
