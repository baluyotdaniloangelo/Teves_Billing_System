<?php

namespace App\Http\Controllers;

use App\Models\SmsCampaign;
use App\Models\SmsLog;
use App\Models\User;
use App\Services\SmsService;
use App\Services\SmsTemplateService;
use App\Services\SmsBirthdayService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class SMSBlastController extends Controller
{
    public const CUSTOMER_TYPES = ['Commercial Account', 'Household', 'Outlet', 'Fuel Retail Bulk', 'Industrial – Fuel', 'Industrial – LPG'];
    public const STATUSES = ['Pending', 'Processing', 'Completed', 'Completed With Errors', 'Cancelled'];

    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sms_blast(Request $request)
    {
        $filters = $request->validate([
            'customer_type' => ['nullable', Rule::in(array_merge(self::CUSTOMER_TYPES, ['Birthday']))],
            'status' => ['nullable', Rule::in(self::STATUSES)],
            'from' => 'nullable|date_format:Y-m-d',
            'to' => array_filter(['nullable', 'date_format:Y-m-d', $request->filled('from') ? 'after_or_equal:from' : null]),
            'search' => 'nullable|string|max:100',
            'campaign_id' => 'nullable|integer|min:1',
        ]);
        $data = User::where('user_id', Session::get('loginID'))->firstOrFail();
        $title = 'SMS';
        $types = self::CUSTOMER_TYPES;
        $statuses = self::STATUSES;
        $templates = app(SmsTemplateService::class)->all();
        $templatesReady = Schema::hasTable('teves_sms_templates');
        $templateLibrary = $templatesReady ? app(SmsTemplateService::class)->library() : collect();
        $editingTemplate = null;
        if ($request->filled('edit_template')) {
            $editingTemplate = $templateLibrary->firstWhere('template_key', $request->input('edit_template'));
            abort_unless($editingTemplate, 404);
        }
        $birthdayReady = Schema::hasTable('teves_sms_birthday_events');
        $birthdayEnabled = (bool) config('sms.birthday_enabled');
        $query = SmsCampaign::query();
        if (!empty($filters['customer_type'])) $query->where('customer_type', $filters['customer_type']);
        if (!empty($filters['status'])) $query->where('campaign_status', $filters['status']);
        if (!empty($filters['from'])) $query->where('created_at', '>=', $filters['from'].' 00:00:00');
        if (!empty($filters['to'])) $query->where('created_at', '<', \Carbon\Carbon::parse($filters['to'])->addDay()->startOfDay());
        if (!empty($filters['search'])) {
            $term = $filters['search'];
            $query->where(function ($q) use ($term) {
                $q->where('campaign_title', 'like', '%'.$term.'%')->orWhere('sms_message', 'like', '%'.$term.'%');
            });
        }
        $summary = (clone $query)->selectRaw('COUNT(*) as campaigns, COALESCE(SUM(total_recipients),0) as recipients, COALESCE(SUM(total_sent),0) as sent, COALESCE(SUM(total_failed),0) as failed')->first();
        $campaigns = $query->orderByDesc('campaign_id')->paginate(15)->withQueryString();
        $selected = !empty($filters['campaign_id']) ? SmsCampaign::findOrFail($filters['campaign_id']) : null;
        $logs = $selected ? SmsLog::where('campaign_id', $selected->campaign_id)->orderBy('sms_log_id')->paginate(25, ['client_name', 'mobile_number', 'sms_message', 'sms_status', 'sent_at', 'delivered_at'], 'recipients_page')->withQueryString() : null;
        return view('pages.sms_blast_for_client.index', compact('data', 'title', 'types', 'statuses', 'summary', 'campaigns', 'selected', 'logs', 'templates', 'birthdayReady', 'birthdayEnabled', 'templatesReady', 'templateLibrary', 'editingTemplate'));
    }

    private function recipients(string $type)
    {
        return DB::table('teves_client_table')->select('client_id', 'client_name', 'client_first_name', 'client_gender', 'client_contact_number', 'customer_type')
            ->whereNull('deleted_at')->where('customer_type', $type)->whereNotNull('client_contact_number')->orderBy('client_id')->get()
            ->map(function ($client) {
                $recipient = (array) $client;
                $recipient['mobile_number'] = SmsTemplateService::normalizeMobile($client->client_contact_number);
                return $recipient['mobile_number'] ? $recipient : null;
            })->filter()->unique('mobile_number')->values();
    }

    public function recipient_count(Request $request)
    {
        $input = $request->validate([
            'audience' => 'nullable|in:customer_type,birthday',
            'customer_type' => ['required_unless:audience,birthday', 'nullable', Rule::in(self::CUSTOMER_TYPES)],
        ]);
        $birthday = ($input['audience'] ?? '') === 'birthday';
        $recipients = $birthday ? app(SmsBirthdayService::class)->recipients(now(config('sms.timezone'))) : $this->recipients($input['customer_type']);
        return response()->json(['count' => $recipients->count()]);
    }

    public function send_sms_blast(Request $request)
    {
        $request->merge(['sms_content' => trim((string) $request->input('sms_content'))]);
        $input = $request->validate([
            'audience' => 'nullable|in:customer_type,birthday',
            'template_key' => ['nullable', Rule::in(array_keys(app(SmsTemplateService::class)->all()))],
            'customer_type' => ['required_unless:audience,birthday', 'nullable', Rule::in(self::CUSTOMER_TYPES)],
            'sms_content' => 'required|string|max:1000',
            'request_id' => 'required|uuid',
        ]);
        $birthday = ($input['audience'] ?? '') === 'birthday';
        $template = app(SmsTemplateService::class)->all()[$input['template_key'] ?? ''] ?? null;
        if ($template && $template['scope'] === 'order') {
            return response()->json(['message' => 'This template needs an LPG order and follow-up timing. Use the template library to preview it.'], 422);
        }
        if (($template && $template['scope'] === 'birthday') !== $birthday) {
            return response()->json(['message' => 'Select the birthday template together with the Birthday today audience.'], 422);
        }
        if ($birthday && !Schema::hasTable('teves_sms_birthday_events')) {
            return response()->json(['message' => 'Birthday tracking is not installed yet. Run the birthday events migration first.'], 422);
        }
        app(SmsTemplateService::class)->validateMessage($input['sms_content'], $birthday ? 'birthday' : 'broadcast');
        // Never repeat a provider call for the same browser submission, even after a timeout.
        $key = 'sms-broadcast:'.Session::get('loginID').':'.$input['request_id'];
        if (!Cache::add($key, true, now()->addDay())) {
            return response()->json(['message' => 'This submission has already started. Check Message History before sending again.'], 409);
        }
        try {
            if ($birthday) {
                $campaign = app(SmsBirthdayService::class)->prepare(now(config('sms.timezone')), $input['sms_content'], Session::get('loginID'));
                if (!$campaign) {
                    Cache::forget($key);
                    return response()->json(['message' => 'No new birthday recipients. Today clients may already have a reserved or sent greeting this year.'], 422);
                }
            } else {
                $recipients = $this->recipients($input['customer_type'])->map(function ($recipient) use ($input) {
                    $recipient['sms_message'] = app(SmsTemplateService::class)->render($input['sms_content'], $recipient);
                    return $recipient;
                });
                if ($recipients->isEmpty()) {
                    Cache::forget($key);
                    return response()->json(['message' => 'No valid mobile numbers found for this customer type.'], 422);
                }
                $campaign = DB::transaction(function () use ($input, $recipients, $template) {
                    $campaign = SmsCampaign::create([
                        'campaign_title' => ($template['name'] ?? 'SMS Broadcast').' ('.$input['customer_type'].') - '.now()->format('Y-m-d H:i'),
                        'customer_type' => $input['customer_type'], 'sms_message' => $input['sms_content'],
                        'total_recipients' => $recipients->count(), 'total_sent' => 0, 'total_failed' => 0,
                        'provider' => 'ITEXMO', 'campaign_status' => 'Pending', 'created_by' => Session::get('loginID'),
                    ]);
                    $now = now();
                    foreach ($recipients->chunk(250) as $chunk) {
                        SmsLog::insert($chunk->map(function ($recipient) use ($campaign, $now) {
                            return [
                                'client_id' => $recipient['client_id'], 'client_name' => $recipient['client_name'],
                                'customer_type' => $recipient['customer_type'], 'mobile_number' => $recipient['mobile_number'],
                                'sms_message' => $recipient['sms_message'], 'campaign_id' => $campaign->campaign_id,
                                'provider' => 'ITEXMO', 'sms_status' => 'Pending', 'created_at' => $now, 'updated_at' => $now,
                            ];
                        })->all());
                    }
                    return $campaign;
                });
            }
            $this->process_sms_campaign($campaign->campaign_id);
            $campaign->refresh();
            return response()->json([
                'success' => true, 'campaign_id' => $campaign->campaign_id,
                'message' => $campaign->total_sent.' accepted by provider; '.$campaign->total_failed.' failed. Delivery is not yet confirmed.',
                'total_sent' => $campaign->total_sent, 'total_failed' => $campaign->total_failed,
                'history_url' => route('sms_blast', ['tab' => 'history', 'campaign_id' => $campaign->campaign_id]),
            ]);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            Cache::forget($key);
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return response()->json(['message' => 'The broadcast could not finish. Check Message History before sending again.'], 500);
        }
    }

    public function process_sms_campaign($campaign_id)
    {
        app(\App\Services\SmsCampaignSender::class)->process($campaign_id);
    }
}
