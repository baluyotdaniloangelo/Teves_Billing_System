<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
//use App\Models\ProductModel;
//use App\Models\ProductCategoryModel;
use App\Models\ClientModel;
use App\Models\TevesBranchModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Models\SmsCampaign;
use App\Models\SmsLog;

use App\Services\SmsService;

class SMSBlastController extends Controller
{
	
	
	protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
	
	/*Load SMS Blast Interface - Send Per Client Category*/
	public function sms_blast(){
		
		$title = 'SMS';
		$data = array();
		
		if(Session::has('loginID')){
			
			$client_data = ClientModel::all();
			//$product_data = ProductModel::all();
			//$product_category_data = ProductCategoryModel::all();
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
			return view("pages.sms_blast_for_client.index", compact('data','title'));
			
		}

		
		
	}   




public function send_sms_blast(Request $request)
{
    //DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'customer_type' => 'required|string',
            'sms_content'   => 'required|string|max:1000',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Customers
        |--------------------------------------------------------------------------
        */

        $customers = DB::table('teves_client_table')
            ->select(
                'client_id',
                'client_name',
                'client_contact_number',
                'customer_type'
            )
            ->where('customer_type', $request->customer_type)
            ->whereNotNull('client_contact_number')
            ->where('client_contact_number', '<>', '')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Prepare Recipients
        |--------------------------------------------------------------------------
        */

        $recipients = [];

        foreach ($customers as $customer) {

            $number = preg_replace('/\D/', '', $customer->client_contact_number);

            // Convert 09XXXXXXXXX to 639XXXXXXXXX
            if (substr($number, 0, 1) == '0') {

                $number = '63' . substr($number, 1);

            }

            // Skip invalid mobile numbers
            if (strlen($number) != 12) {

                continue;

            }

            $recipients[] = [

                'client_id'      => $customer->client_id,

                'client_name'    => $customer->client_name,

                'customer_type'  => $customer->customer_type,

                'mobile_number'  => $number,

            ];

        }
dd($customers->count());
        /*
        |--------------------------------------------------------------------------
        | Remove Duplicate Numbers
        |--------------------------------------------------------------------------
        */

        $recipients = collect($recipients)
            ->unique('mobile_number')
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | No Recipients
        |--------------------------------------------------------------------------
        */

        if (empty($recipients)) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'No valid mobile numbers found.'
            ], 404);

        }

        /*
        |--------------------------------------------------------------------------
        | SMS Details
        |--------------------------------------------------------------------------
        */

        $smsLength = strlen($request->sms_content);

        $smsParts = max(1, ceil($smsLength / 160));

        /*
        |--------------------------------------------------------------------------
        | Create Campaign
        |--------------------------------------------------------------------------
        */

        $campaign = SmsCampaign::create([

            'campaign_title' => 'SMS Broadcast (' .
                $request->customer_type .
                ') - ' .
                now()->format('Y-m-d H:i'),

            'customer_type'    => $request->customer_type,

            'sms_message'      => $request->sms_content,

            'total_recipients' => count($recipients),

            'total_sent'       => 0,

            'total_failed'     => 0,

            'provider'         => 'ITEXMO',

            'campaign_status'  => 'Pending',

            'started_at'       => now(),

            'created_by'       => auth()->id(),

        ]);

        /*
        |--------------------------------------------------------------------------
        | Prepare SMS Logs
        |--------------------------------------------------------------------------
        */

        $logs = [];

        $now = now();

        foreach ($recipients as $recipient) {

            $logs[] = [

                'campaign_id'   => $campaign->campaign_id,

                'client_id'     => $recipient['client_id'],

                'client_name'   => $recipient['client_name'],

                'customer_type' => $recipient['customer_type'],

                'mobile_number' => $recipient['mobile_number'],

                'sms_message'   => $request->sms_content,

                'provider'      => 'ITEXMO',

                'sms_status'    => 'Pending',

                'created_at'    => $now,

                'updated_at'    => $now,

            ];

        }

        /*
        |--------------------------------------------------------------------------
        | Bulk Insert Logs
        |--------------------------------------------------------------------------
        */

        SmsLog::insert($logs);

       // DB::commit();

        /*
        |--------------------------------------------------------------------------
        | Process SMS Campaign
        |--------------------------------------------------------------------------
        */

        $this->process_sms_campaign($campaign->campaign_id);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'message' => 'SMS Broadcast completed successfully.',

            'campaign_id' => $campaign->campaign_id,

            'campaign_title' => $campaign->campaign_title,

            'customer_type' => $campaign->customer_type,

            'total_recipients' => count($recipients),

            'sms_length' => $smsLength,

            'sms_parts' => $smsParts,

        ]);

    }

    catch (\Illuminate\Validation\ValidationException $e) {

        //DB::rollBack();

        return response()->json([

            'errors' => $e->errors()

        ], 422);

    }

    catch (\Exception $e) {

        DB::rollBack();

        return response()->json([

            'success' => false,

            'message' => $e->getMessage()

        ], 500);

    }

}



public function process_sms_campaign($campaign_id)
{
    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | Get Campaign
        |--------------------------------------------------------------------------
        */

        $campaign = SmsCampaign::findOrFail($campaign_id);

        /*
        |--------------------------------------------------------------------------
        | Update Campaign Status
        |--------------------------------------------------------------------------
        */

        $campaign->update([
            'campaign_status' => 'Processing'
        ]);

        DB::commit();

        /*
        |--------------------------------------------------------------------------
        | Get Pending SMS
        |--------------------------------------------------------------------------
        */

        $pendingSms = SmsLog::where('campaign_id', $campaign_id)
            ->where('sms_status', 'Pending')
            ->get();

        $sent = 0;
        $failed = 0;

        /*
        |--------------------------------------------------------------------------
        | Process SMS
        |--------------------------------------------------------------------------
        */

        foreach ($pendingSms as $sms) {

            try {

                $response = $this->smsService->send(
                    $sms->mobile_number,
                    $sms->sms_message
                );
				dd($response);

                /*
                |--------------------------------------------------------------------------
                | Success
                |--------------------------------------------------------------------------
                */

                if ($response['status'] == 200) {

                    $messageId = $response['body']['messages'][0]['messageId'] ?? null;

                    $sms->update([

                        'sms_status' => 'Sent',

                        'provider_message_id' => $messageId,

                        'provider_response' => json_encode($response),

                        'sent_at' => now(),

                    ]);

                    $sent++;

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | Failed Response
                    |--------------------------------------------------------------------------
                    */

                    $sms->update([

                        'sms_status' => 'Failed',

                        'provider_response' => json_encode($response),

                        'error_message' => json_encode($response['body']),

                    ]);

                    $failed++;

                }

            } catch (\Exception $ex) {

                /*
                |--------------------------------------------------------------------------
                | Exception
                |--------------------------------------------------------------------------
                */

                $sms->update([

                    'sms_status' => 'Failed',

                    'error_message' => $ex->getMessage(),

                ]);

                $failed++;

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Update Campaign
        |--------------------------------------------------------------------------
        */

        $campaign->update([

            'campaign_status' => ($failed > 0)
                ? 'Completed With Errors'
                : 'Completed',

            'total_sent' => $sent,

            'total_failed' => $failed,

            'completed_at' => now(),

        ]);

        /*
        |--------------------------------------------------------------------------
        | Return
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'campaign_id' => $campaign->campaign_id,

            'total_recipients' => $campaign->total_recipients,

            'total_sent' => $sent,

            'total_failed' => $failed,

            'message' => 'SMS Campaign completed successfully.'

        ]);

    } catch (\Exception $ex) {

        DB::rollBack();

        if (isset($campaign)) {

            $campaign->update([

                'campaign_status' => 'Completed With Errors',

                'completed_at' => now(),

            ]);

        }

        return response()->json([

            'success' => false,

            'message' => $ex->getMessage()

        ], 500);

    }

}
}
