<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ReminderModel;
use Session;
use DataTables;
use Illuminate\Support\Facades\DB;
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


}