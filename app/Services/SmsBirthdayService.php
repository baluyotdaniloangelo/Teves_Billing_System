<?php

namespace App\Services;

use App\Models\SmsCampaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SmsBirthdayService
{
    public function recipients(Carbon $date, ?int $clientId = null)
    {
        return DB::table('teves_client_table')
            ->select('client_id', 'client_name', 'client_first_name', 'client_gender', 'client_contact_number', 'customer_type')
            ->whereNull('deleted_at')->whereNotNull('client_birthday')
            ->when($clientId !== null, function ($query) use ($clientId) { $query->where('client_id', $clientId); })
            ->whereMonth('client_birthday', $date->month)->whereDay('client_birthday', $date->day)
            ->orderBy('client_id')->get()->map(function ($client) {
                $recipient = (array) $client;
                $recipient['mobile_number'] = SmsTemplateService::normalizeMobile($client->client_contact_number);
                return $recipient['mobile_number'] ? $recipient : null;
            })->filter()->unique('mobile_number')->values();
    }

    public function prepare(Carbon $date, string $message, $createdBy = null, ?int $clientId = null): ?SmsCampaign
    {
        $templates = app(SmsTemplateService::class);
        $templates->validateMessage($message, 'birthday');
        // Pre-render everything before reserving events or creating a campaign.
        $recipients = $this->recipients($date, $clientId)->map(function ($recipient) use ($templates, $message) {
            $recipient['sms_message'] = $templates->render($message, $recipient);
            return $recipient;
        });
        return DB::transaction(function () use ($date, $message, $createdBy, $recipients, $clientId) {
            $campaign = null;
            foreach ($recipients as $recipient) {
                // Two unique keys prevent duplicate client/year AND shared-mobile/year sends.
                $claimed = DB::table('teves_sms_birthday_events')->insertOrIgnore([
                    'client_id' => $recipient['client_id'], 'birthday_year' => $date->year,
                    'mobile_number' => $recipient['mobile_number'], 'event_date' => $date->toDateString(),
                    'created_at' => now(), 'updated_at' => now(),
                ]);
                if (!$claimed) continue;
                if (!$campaign) {
                    $campaign = SmsCampaign::create([
                        'campaign_title' => ($clientId !== null ? 'Birthday greeting (individual) - ' : 'Birthday greetings - ').$date->toDateString(), 'customer_type' => 'Birthday',
                        'sms_message' => $message, 'total_recipients' => 0, 'total_sent' => 0, 'total_failed' => 0,
                        'provider' => 'ITEXMO', 'campaign_status' => 'Pending', 'created_by' => $createdBy,
                    ]);
                }
                DB::table('teves_sms_logs_table')->insert([
                    'campaign_id' => $campaign->campaign_id, 'client_id' => $recipient['client_id'],
                    'client_name' => $recipient['client_name'], 'customer_type' => $recipient['customer_type'],
                    'mobile_number' => $recipient['mobile_number'], 'sms_message' => $recipient['sms_message'],
                    'provider' => 'ITEXMO', 'sms_status' => 'Pending', 'created_at' => now(), 'updated_at' => now(),
                ]);
                DB::table('teves_sms_birthday_events')->where('client_id', $recipient['client_id'])->where('birthday_year', $date->year)->update(['campaign_id' => $campaign->campaign_id]);
                $campaign->increment('total_recipients');
            }
            return $campaign;
        });
    }
}
