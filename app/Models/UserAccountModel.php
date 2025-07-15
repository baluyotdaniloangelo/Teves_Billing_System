<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Illuminate\Database\Eloquent\SoftDeletes;

use Session;

class UserAccountModel extends Model
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
		$this->deleted_by_user_id = Session::get('loginID'); // or session()->get('user_id')
		$this->save();

		parent::delete();
	}

	protected $table = 'user_tb';
	
	protected $fillable = [
        'user_name',
		'user_real_name',
        'user_job_title',
        'user_password',
		'user_type',
		'user_email_address',
		'user_branch_access_type',
		'created_at',
		'created_by_user_idx',
		'updated_at',
		'updated_by_user_idx',
		'deleted_at',
		'deleted_by_user_id'
    ];
    
	protected $primaryKey = 'user_id';
	
	protected static $logName = 'User Information';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
        'user_name',
		'user_real_name',
        'user_job_title',
        'user_password',
		'user_type',
		'user_email_address',
		'user_branch_access_type',
		'created_at',
		'created_by_user_idx',
		'updated_at',
		'updated_by_user_idx',
		'deleted_at',
		'deleted_by_user_id'
    ];
	
}
