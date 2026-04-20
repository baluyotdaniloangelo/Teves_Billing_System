<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Mail\MyTestMail;

use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\ReminderModel;
use App\Mail\ReminderMail;

use Illuminate\Support\Facades\DB;
use App\Mail\UnbilledReportMail;
//use Illuminate\Support\Facades\Mail;


class EmailController extends Controller
{
	
	 public function sendTemporaryPasswordtoEmail(Request $request)
    {
		
		$request->validate(
						['user_email_address'    		=> 'required',], 
						['user_email_address.required' 	=> 'Email Address is Required']
					);
		
		$user = User::where('user_email_address', '=', $request->user_email_address)->first();
		
		if ($user){
			
			if($request->user_email_address == $user->user_email_address){
				
						$title = 'Reset Password';
						$body = 'Your password has been changed successfully. Please use below password to log in.';
						$user_id = $user->user_id;
						$name = $user->user_real_name;
						$user_name = $user->user_name;
						$user_password = '';

						Mail::to($user->user_email_address)->send(new ResetPassword($title, $body, $name, $user_id, $user_name, $user_password));

						return response()->json(['success'=>'Email sent successfully!']);
							
			}else{
				
				//return 'Incorrect Email';
				return response()->json(['success'=>'Incorrect Email!']);
			
			}
			
		}else{
			
				//return 'Email Not Found';
				return response()->json(['success'=>'Email Not Found!']);
		
		}
		
    }
	
    // ============================================
    // ✅ SEND UNBILLED REPORT
    // ============================================
    public function sendUnbilledReport()
    {
        // ✅ STEP 1: Get report data
        $data = DB::select("
            SELECT 
                c.client_id,
                c.client_name,

                COUNT(b.billing_id) AS unbilled_count,
                SUM(b.order_total_amount) AS total_unbilled_amount,

                MIN(STR_TO_DATE(b.order_date, '%Y-%m-%d')) AS first_transaction_date,
                MAX(STR_TO_DATE(b.order_date, '%Y-%m-%d')) AS last_transaction_date

            FROM teves_client_table c
            INNER JOIN teves_billing_table b 
                ON b.client_idx = c.client_id
                AND (b.receivable_idx = 0 OR b.receivable_idx IS NULL)
                AND b.deleted_at IS NULL

            WHERE c.deleted_at IS NULL

            GROUP BY c.client_id, c.client_name

            ORDER BY total_unbilled_amount DESC
        ");

        // ✅ STEP 2: Get valid recipient emails
        $emails = DB::table('user_tb')
            ->whereIn('user_type', ['SUAdmin'])
            ->where('user_status', 'Active')
            ->whereNull('deleted_at')
            ->whereNotNull('user_email_address')
            ->where('user_email_address', 'like', '%@%') // basic DB filter
            ->pluck('user_email_address')
            ->filter(function ($email) {
                return !empty(trim($email)) && filter_var($email, FILTER_VALIDATE_EMAIL);
            })
            ->unique()
            ->values()
            ->toArray();

        if (empty($emails)) {
            return response()->json(['error' => 'No valid recipients found']);
        }

        // ✅ STEP 3: Send Email (BCC for privacy)
        Mail::to('main@email.com') // optional main email
            ->bcc($emails)
            ->send(new UnbilledReportMail($data));

        return response()->json([
            'success' => 'Unbilled report sent successfully!',
            'total_recipients' => count($emails)
        ]);
    }

	public function sendReminderEmails_old()
	{
		// ✅ STEP 1: Get due reminders
		$reminders = ReminderModel::where('email_sent', false)
			->where('reminder_date', '<=', now())
			->whereNull('deleted_at')
			->get();

		if ($reminders->isEmpty()) {
			return response()->json(['message' => 'No reminders to send']);
		}

		// ✅ STEP 2: Loop reminders
		foreach ($reminders as $reminder) {

			// 👉 get recipient (you can customize this)
			$user = DB::table('user_tb')
				//->where('user_id', 1249)
				->where('user_status', 'Active')
				->whereNull('deleted_at')
				->first();

			if (!$user || empty($user->user_email_address)) {
				continue; // skip if no valid email
			}

			// ✅ validate email
			if (!filter_var($user->user_email_address, FILTER_VALIDATE_EMAIL)) {
				continue;
			}

			// ✅ STEP 3: Send Email
			Mail::to($user->user_email_address)
				->send(new ReminderMail($reminder));

			// ✅ STEP 4: Mark as sent
			$reminder->email_sent = true;
			$reminder->save();
		}

		return response()->json([
			'success' => 'Reminder emails sent successfully!',
			'total_sent' => $reminders->count()
		]);
	}	

	public function sendReminderEmails()
	{
		// ✅ STEP 1: due reminders
		$reminders = ReminderModel::where('email_sent', false)
			->where('reminder_date', '<=', now())
			->whereNull('deleted_at') // if using soft deletes
			->get();

		if ($reminders->isEmpty()) {
			return;
		}

		// ✅ STEP 2: get ALL valid users
		$emails = DB::table('user_tb')
			->where('user_status', 'Active')
			->whereNull('deleted_at')
			->whereNotNull('user_email_address')
			->pluck('user_email_address')
			->filter(function ($email) {
				return !empty(trim($email)) && filter_var($email, FILTER_VALIDATE_EMAIL);
			})
			->unique()
			->values()
			->toArray();

		if (empty($emails)) {
			return;
		}

		// ✅ STEP 3: send per reminder
		foreach ($reminders as $reminder) {

			foreach ($emails as $email) {

				try {
					Mail::to($email)
						->send(new ReminderMail($reminder));

				} catch (\Exception $e) {
					\Log::error('Mail error: '.$e->getMessage());
				}
			}

			// mark as sent AFTER all users received
			$reminder->email_sent = true;
			$reminder->save();
		}
	}
	
}