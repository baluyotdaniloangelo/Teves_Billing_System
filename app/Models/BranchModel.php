<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Illuminate\Database\Eloquent\SoftDeletes;

use Session;

class BranchModel extends Model
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
	
	protected $table = 'teves_branch_table';
	
	protected $fillable = [
        'branch_code',
		'branch_name',
		'branch_initial',
		'branch_tin',
		'branch_address',
		'branch_contact_number',
		'branch_owner',
		'branch_owner_title',
		'created_at',
		'created_by_user_idx',
		'updated_at',
		'updated_by_user_idx',
		'deleted_at',
		'deleted_by_user_id'
    ];
	
	protected $primaryKey = 'branch_id';
    
	protected static $logName = 'Branch Information';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'branch_code',
		'branch_name',
		'branch_initial',
		'branch_tin',
		'branch_address',
		'branch_contact_number',
		'branch_owner',
		'branch_owner_title',
		'created_at',
		'created_by_user_idx',
		'updated_at',
		'updated_by_user_idx',
		'deleted_at',
		'deleted_by_user_id'
    ];
}
