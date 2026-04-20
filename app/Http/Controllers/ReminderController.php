<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ReminderModel;
use Session;
use DataTables;

class ReminderController extends Controller
{

    /* Load reminder Interface */
    public function reminder(){
        
        if(Session::has('loginID') && (Session::get('UserType')=="Admin" || Session::get('UserType')=="SUAdmin")){
            
            $title = 'Reminders';
            $data = User::where('user_id', Session::get('loginID'))->first();

            return view("pages.reminder", compact('data','title'));
        }
    }   

    /* Datatable */
    public function getReminderList(Request $request)
    {
        if ($request->ajax()) {

            $data = ReminderModel::select(
                'reminder_id',
                'reminders_title',
                'reminders_content',
                'reminder_date',
                'is_done'
            );

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){

                    return '
                    <div class="text-center">
                        <a href="#" data-id="'.$row->reminder_id.'" 
                        class="btn-warning btn-sm bi bi-pencil-fill" id="editReminder"></a>

                        <a href="#" data-id="'.$row->reminder_id.'" 
                        class="btn-danger btn-sm bi bi-trash3-fill" id="deleteReminder"></a>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /* GET INFO */
    public function reminder_info(Request $request){

        $data = ReminderModel::find($request->reminder_id);

        return response()->json($data);
    }

    /* DELETE */
    public function delete_reminder_confirmed(Request $request){

        ReminderModel::find($request->reminder_id)->delete();

        return response()->json(['success'=>'Deleted']);
    }

    /* CREATE */
    public function create_reminder_post(Request $request){

        $request->validate([
            'reminders_title'   => 'required|unique:reminders,reminders_title',
            'reminders_content' => 'required',
            'reminder_date'     => 'required'
        ]);

        $reminder = new ReminderModel();
        $reminder->reminders_title = $request->reminders_title;
        $reminder->reminders_content = $request->reminders_content;
        $reminder->reminder_date = $request->reminder_date;
		$reminder->email_sent = false;
        // ✅ FIXED
        $reminder->created_by_user_idx = Session::get('loginID');

        $reminder->save();

        return response()->json(['success'=>'Reminder Created']);
    }

    /* UPDATE */
    public function update_reminder_post(Request $request){

        $request->validate([
            'reminders_title' => 'required|unique:reminders,reminders_title,'.$request->reminder_id.',reminder_id',
            'reminders_content' => 'required',
            'reminder_date' => 'required'
        ]);

        $reminder = ReminderModel::find($request->reminder_id);

        $reminder->reminders_title = $request->reminders_title;
        $reminder->reminders_content = $request->reminders_content;
        $reminder->reminder_date = $request->reminder_date;

        // ✅ FIXED
        $reminder->updated_by_user_idx = Session::get('loginID');

        $reminder->update();

        return response()->json(['success'=>'Reminder Updated']);
    }

	public function sendReminderEmails()
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
				->where('user_id', 1249)
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
}