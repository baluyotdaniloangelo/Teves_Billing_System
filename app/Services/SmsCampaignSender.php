<?php

namespace App\Services;

use App\Models\SmsCampaign;
use App\Models\SmsLog;

class SmsCampaignSender
{
    protected $smsService;

    public function __construct(SmsService $smsService) { $this->smsService = $smsService; }

    public function process($campaign_id)
    {
        // Atomically claim the campaign; two requests must never send the same batch.
        $claimed = SmsCampaign::where('campaign_id', $campaign_id)->where('campaign_status', 'Pending')
            ->update(['campaign_status' => 'Processing', 'started_at' => now()]);
        if (!$claimed) return;
        try {
            SmsLog::where('campaign_id', $campaign_id)->where('sms_status', 'Pending')->chunkById(100, function ($logs) {
                foreach ($logs as $sms) {
                    try {
                        $response = $this->smsService->send($sms->mobile_number, $sms->sms_message);
                        $accepted = ($response['status'] ?? null) === 200;
                        $sms->update([
                            'sms_status' => $accepted ? 'Sent' : 'Failed',
                            'provider_message_id' => $response['body']['messages'][0]['messageId'] ?? null,
                            'provider_response' => json_encode($response),
                            'error_message' => $accepted ? null : json_encode($response['body'] ?? []),
                            'sent_at' => $accepted ? now() : null,
                        ]);
                    } catch (\Throwable $exception) {
                        report($exception);
                        $sms->update(['sms_status' => 'Failed', 'error_message' => 'Provider request did not complete. Verify provider records before resending.']);
                    }
                    $this->updateTotals($sms->campaign_id);
                }
            }, 'sms_log_id');
        } finally {
            $this->updateTotals($campaign_id);
            $campaign = SmsCampaign::findOrFail($campaign_id);
            $pending = SmsLog::where('campaign_id', $campaign_id)->where('sms_status', 'Pending')->exists();
            $campaign->update(['campaign_status' => ($pending || $campaign->total_failed > 0) ? 'Completed With Errors' : 'Completed', 'completed_at' => now()]);
        }
    }

    private function updateTotals($campaignId): void
    {
        $counts = SmsLog::where('campaign_id', $campaignId)->selectRaw("SUM(CASE WHEN sms_status IN ('Sent','Delivered') THEN 1 ELSE 0 END) as sent, SUM(CASE WHEN sms_status = 'Failed' THEN 1 ELSE 0 END) as failed")->first();
        SmsCampaign::where('campaign_id', $campaignId)->update(['total_sent' => $counts->sent ?? 0, 'total_failed' => $counts->failed ?? 0]);
    }

}
