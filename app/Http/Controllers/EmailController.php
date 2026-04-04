<?php

namespace App\Http\Controllers;

use App\Mail\ResetPassword;
use App\Mail\MyTestMail;

use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Http\Request;



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
	
public function sendUnbilledReport()
{
    // ✅ Get report data
    $data = \DB::select("
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

    // ✅ Get recipients
    $emails = \DB::table('user_tb')
        ->whereIn('user_type', ['SUAdmin', 'Supervisor', 'Admin'])
        ->where('user_status', 'Active')
        ->whereNull('deleted_at')
        ->whereNotNull('user_email_address')
        ->pluck('user_email_address')
        ->toArray();

    if (empty($emails)) {
        return response()->json(['error' => 'No recipients found']);
    }

    // ✅ Send email to multiple users
    \Mail::to($emails)->send(new \App\Mail\UnbilledReportMail($data));

    return response()->json([
        'success' => 'Unbilled report sent!',
        'recipients' => $emails
    ]);
}
		
}