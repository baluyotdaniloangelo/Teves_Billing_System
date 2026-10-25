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
use App\Mail\SalesCollectionsReportMail;
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

    /** Send the rolling 30-day sales and collections snapshot to active SUAdmins. */
    public function sendSalesCollectionsReport()
    {
        $endDate = now()->toDateString();
        $startDate = now()->subDays(29)->toDateString();

        $orders = DB::table('teves_sales_order_table as so')
            ->leftJoin('teves_branch_table as b', 'b.branch_id', '=', 'so.company_header')
            ->whereNull('so.deleted_at')->where('so.sales_order_quotation', 0)
            ->whereBetween('so.sales_order_date', [$startDate, $endDate])
            ->selectRaw('DATE(so.sales_order_date) as report_day, so.company_header as branch_id, b.branch_code, b.branch_name, SUM(so.sales_order_total_due) as amount, COUNT(so.sales_order_id) as record_count')
            ->groupBy('report_day', 'so.company_header', 'b.branch_code', 'b.branch_name')->get();

        $directBilling = DB::table('teves_billing_table as bill')
            ->leftJoin('teves_branch_table as b', 'b.branch_id', '=', 'bill.branch_idx')
            ->whereNull('bill.deleted_at')->whereBetween('bill.order_date', [$startDate, $endDate])
            ->where(function ($query) { $query->whereNull('bill.sales_order_idx')->orWhere('bill.sales_order_idx', 0); })
            ->selectRaw('DATE(bill.order_date) as report_day, bill.branch_idx as branch_id, b.branch_code, b.branch_name, SUM(bill.order_total_amount) as amount, COUNT(DISTINCT bill.billing_id) as record_count')
            ->groupBy('report_day', 'bill.branch_idx', 'b.branch_code', 'b.branch_name')->get();

        $collections = DB::table('teves_receivable_payment as p')
            ->join('teves_receivable_table as ar', 'ar.receivable_id', '=', 'p.receivable_idx')
            ->leftJoin('teves_branch_table as b', 'b.branch_id', '=', 'ar.company_header')
            ->whereNull('ar.deleted_at')->whereNull('p.deleted_at')
            ->whereBetween('p.receivable_date_of_payment', [$startDate, $endDate])
            ->selectRaw('DATE(p.receivable_date_of_payment) as report_day, ar.company_header as branch_id, b.branch_code, b.branch_name, SUM(p.receivable_payment_amount) as amount, COUNT(p.receivable_payment_id) as record_count')
            ->groupBy('report_day', 'ar.company_header', 'b.branch_code', 'b.branch_name')->get();

        $trendByDay = [];
        $branchTotals = [];
        $addRows = function ($rows, $metric) use (&$trendByDay, &$branchTotals) {
            foreach ($rows as $row) {
                $day = (string) $row->report_day;
                $branch = (string) ($row->branch_id ?? 'unassigned');
                $amount = (float) $row->amount;
                $count = (int) $row->record_count;
                $trendByDay[$day][$metric] = ($trendByDay[$day][$metric] ?? 0) + $amount;
                if (!isset($branchTotals[$branch])) {
                    $branchTotals[$branch] = [
                        'branch_code' => $row->branch_code ?: 'Unassigned',
                        'branch_name' => $row->branch_name ?: 'Branch not specified',
                        'orders' => 0.0, 'billing' => 0.0, 'collections' => 0.0,
                        'order_count' => 0, 'billing_count' => 0, 'collection_count' => 0,
                    ];
                }
                $branchTotals[$branch][$metric] += $amount;
                $countField = $metric === 'orders' ? 'order_count' : ($metric === 'billing' ? 'billing_count' : 'collection_count');
                $branchTotals[$branch][$countField] += $count;
            }
        };
        $addRows($orders, 'orders');
        $addRows($directBilling, 'billing');
        $addRows($collections, 'collections');

        $trend = [];
        foreach (\Carbon\CarbonPeriod::create($startDate, $endDate) as $date) {
            $key = $date->toDateString();
            $trend[] = [
                'date' => $key,
                'orders' => (float) ($trendByDay[$key]['orders'] ?? 0),
                'billing' => (float) ($trendByDay[$key]['billing'] ?? 0),
                'collections' => (float) ($trendByDay[$key]['collections'] ?? 0),
            ];
        }

        $branches = collect($branchTotals)->sortByDesc('orders')->values();
        $report = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'generated_at' => now(),
            'order_sales' => (float) $orders->sum('amount'),
            'order_count' => (int) $orders->sum('record_count'),
            'direct_billing_amount' => (float) $directBilling->sum('amount'),
            'direct_billing_count' => (int) $directBilling->sum('record_count'),
            'collections' => (float) $collections->sum('amount'),
            'collection_count' => (int) $collections->sum('record_count'),
            'open_ar_amount' => (float) DB::table('teves_receivable_table')->whereNull('deleted_at')->where('receivable_remaining_balance', '>', 0)->sum('receivable_remaining_balance'),
            'open_ar_count' => (int) DB::table('teves_receivable_table')->whereNull('deleted_at')->where('receivable_remaining_balance', '>', 0)->count('receivable_id'),
            'unbilled_amount' => (float) DB::table('teves_billing_table')->whereNull('deleted_at')->where(function ($query) { $query->whereNull('receivable_idx')->orWhere('receivable_idx', 0); })->sum('order_total_amount'),
            'unbilled_count' => (int) DB::table('teves_billing_table')->whereNull('deleted_at')->where(function ($query) { $query->whereNull('receivable_idx')->orWhere('receivable_idx', 0); })->count('billing_id'),
            'trend' => $trend,
            'branches' => $branches,
        ];

        $emails = DB::table('user_tb')
            ->where('user_type', 'SUAdmin')->where('user_status', 'Active')->whereNull('deleted_at')
            ->whereNotNull('user_email_address')->where('user_email_address', 'like', '%@%')
            ->pluck('user_email_address')
            ->filter(fn ($email) => !empty(trim($email)) && filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()->values();

        if ($emails->isEmpty()) {
            \Illuminate\Support\Facades\Log::warning('Sales and collections report skipped: no active SUAdmin email recipients.');
            return response()->json(['error' => 'No valid SUAdmin recipients found']);
        }

        // Send individually so only SUAdmin accounts receive the report and recipient addresses stay private.
        foreach ($emails as $email) {
            Mail::to($email)->send(new SalesCollectionsReportMail($report));
        }

        return response()->json(['success' => 'Sales and collections report sent.', 'total_recipients' => $emails->count()]);
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
			->where(function($q){
				$q->where('reminder_date', '<=', now())
				  ->orWhere('next_run_at', '<=', now());
			})
			->whereNull('deleted_at')
			->get();

		if ($reminders->isEmpty()) {
			return;
		}

		// ✅ STEP 2: get ALL valid users
		$userIds = [1249, 1274]; // 👈 your target users
		$emails = DB::table('user_tb')
			->where('user_status', 'Active')
			//->whereIn('user_id', $userIds) // ✅ MULTIPLE IDs
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
		
		/*NEW*/
		foreach ($reminders as $reminder) {

			// send email (your existing logic)
			foreach ($emails as $email) {

				try {
					Mail::to($email)
						->send(new ReminderMail($reminder));

				} catch (\Exception $e) {
					\Log::error('Mail error: '.$e->getMessage());
				}
			}
			
			// mark sent
			$reminder->email_sent = true;
			$reminder->save();

			// ✅ HANDLE RECURRING
			if ($reminder->is_recurring) {

				$nextDate = null;

				switch ($reminder->recurrence_type) {
					case 'daily':
						$nextDate = now()->addDay();
						break;

					case 'weekly':
						$nextDate = now()->addWeek();
						break;

					case 'monthly':
						$nextDate = now()->addMonth();
						break;
				}

				// stop if beyond end date
				if ($reminder->recurrence_end_date && $nextDate > $reminder->recurrence_end_date) {
					continue;
				}

				// OPTION A: update same record
				$reminder->email_sent = true;
				$reminder->reminder_date = $nextDate;
				$reminder->next_run_at = $nextDate;
				$reminder->save();

				// OPTION B (better): create new record
				/**/
				ReminderModel::create([
					'reminders_title' => $reminder->reminders_title,
					'reminders_content' => $reminder->reminders_content,
					'reminder_date' => $nextDate,
					'next_run_at' => $nextDate,
					'is_recurring' => true,
					'recurrence_type' => $reminder->recurrence_type,
					'parent_reminder_id' => $reminder->reminder_id
				]);
				
			}
		}
	}
	
}
