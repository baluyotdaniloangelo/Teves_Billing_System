<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Illuminate\Database\Eloquent\SoftDeletes;

use Session;

class ReminderModel extends Model
{
	
	use LogsActivity;
	
	public function tapActivity(Activity $activity, string $eventName)
	{
    $activity->causer_id = Session::get('loginID');
	}

	use SoftDeletes;
    protected $dates = ['deleted_at'];
	
	/*Delete*/
	public function delete()
	{
		$this->deleted_by_user_idx = Session::get('loginID'); // or session()->get('user_id')
		$this->save();

		parent::delete();
	}

	protected $table = 'reminders';
	
	protected $casts = [
    'email_sent' => 'boolean',
	];
	
	protected $fillable = [
        'reminders_title',
		'reminders_content',
		'reminder_date',
		'email_sent',
		'created_by_user_idx',
		'updated_by_user_idx'
    ];
	
	protected $primaryKey = 'reminder_id';
     
	protected static $logName = 'Reminders Information';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
        'reminders_title',
        'reminders_content',
		'reminder_date',
		'is_done',
		'created_at',
		'created_by_user_idx',
		'updated_at',
		'updated_by_user_idx',
		'deleted_at',
		'deleted_by_user_idx'
    ];
          
}
