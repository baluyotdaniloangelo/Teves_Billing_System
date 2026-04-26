<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ReminderModel;
use Session;
use DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    $userType = Session::get('UserType');

    $html = '<div align="center" class="action_table_menu_client">';

    // 👁️ VIEW (always visible)
    $html .= '
        <a href="#" data-id="'.$row->reminder_id.'"
           class="btn-warning btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_edit"
           id="viewReminder_tb"></a>
    ';

    // ✏️ EDIT + 🗑 DELETE (SUAdmin only)
    if($userType == "SUAdmin"){
        $html .= '
            <a href="#" data-id="'.$row->reminder_id.'"
               class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit"
               id="editReminder"></a>

            <a href="#" data-id="'.$row->reminder_id.'"
               class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete"
               id="deleteReminder"></a>
        ';
    }

    $html .= '</div>';

    return $html;
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
            'reminder_date'     => 'required',
			'recurrence_type' => 'required_if:is_recurring,1',
        ]);

        $reminder = new ReminderModel();
        $reminder->reminders_title = $request->reminders_title;
        $reminder->reminders_content = $request->reminders_content;
        $reminder->reminder_date = $request->reminder_date;
		$reminder->is_recurring = $request->is_recurring ?? 0;
		$reminder->recurrence_type = $request->recurrence_type;
		$reminder->recurrence_end_date = $request->recurrence_end_date;
		$reminder->next_run_at = $request->reminder_date;
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
		
		if(!$request->is_recurring){
			$reminder->recurrence_type = null;
			$reminder->recurrence_end_date = null;
		}

		// ✅ RECURRING
		$reminder->is_recurring = $request->is_recurring ?? 0;
		$reminder->recurrence_type = $request->recurrence_type;
		$reminder->recurrence_end_date = $request->recurrence_end_date;
		
		// 🔥 IMPORTANT: reset email if changed
		$reminder->email_sent = 0;

        // ✅ FIXED
        $reminder->updated_by_user_idx = Session::get('loginID');

        $reminder->update();

        return response()->json(['success'=>'Reminder Updated']);
    }

/*	public function getTopReminders()
	{
		$reminders = ReminderModel::whereNull('deleted_at')
			->where('is_done', 0)
			->orderBy('reminder_date', 'asc')
			->limit(10)
			->get();

		return response()->json($reminders);
	}
*/

	public function getTopReminders()
	{
		$userId = Session::get('loginID');

		$reminders = DB::table('reminders')
			->leftJoin('reminder_user_status as rus', function($join) use ($userId){
				$join->on('rus.reminder_id','=','reminders.reminder_id')
					 ->where('rus.user_id','=',$userId);
			})
			->whereNull('reminders.deleted_at')
			->select(
				'reminders.*',
				DB::raw('COALESCE(rus.is_read,0) as is_read')
			)
			->orderBy('reminders.reminder_date','asc')
			->limit(10)
			->get();
			
			
		$unreadCount = DB::table('reminders')
			->leftJoin('reminder_user_status as rus', function($join) use ($userId){
				$join->on('rus.reminder_id','=','reminders.reminder_id')
					 ->where('rus.user_id','=',$userId);
			})
			->whereNull('reminders.deleted_at')
			->whereRaw('COALESCE(rus.is_read,0)=0')
			->count();	

		return response()->json([
			'reminders' => $reminders,
			'unread' => $unreadCount
		]);

	}
	
	public function markAsRead(Request $request){

		$userId = Session::get('loginID');

		DB::table('reminder_user_status')->updateOrInsert(
			[
				'reminder_id' => $request->reminder_id,
				'user_id' => $userId
			],
			[
				'is_read' => 1,
				'read_at' => now(),
				'updated_at' => now()
			]
		);

		return response()->json(['success'=>true]);
	}	
	
	
}