<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Illuminate\Database\Eloquent\SoftDeletes;

use Session;

class SalesAgentModel extends Model
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

	/*
	public function referrer()
	{
		return $this->belongsTo(ClientModel::class, 'referred_by_idx', 'client_id');
	}

	public function referrals()
	{
		return $this->hasMany(ClientModel::class, 'referred_by_idx', 'client_id');
	}
	*/

	protected $table = 'teves_sales_agent_table';
	
	protected $fillable = [
        'sales_agent_name',
		'sales_agent_contact_number',
		'sales_agent_email_address',
		'sales_agent_address',
		'sales_agent_status',
		'created_at',
		'created_by_user_idx',
		'updated_at',
		'updated_by_user_idx',
		'deleted_at',
		'deleted_by_user_id'
    ];
	
	protected $primaryKey = 'sales_agent_id';
     
	protected static $logName = 'Sales Agent Information';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'sales_agent_name',
		'sales_agent_contact_number',
		'sales_agent_email_address',
		'sales_agent_address',
		'sales_agent_status',
		'created_at',
		'created_by_user_idx',
		'updated_at',
		'updated_by_user_idx',
		'deleted_at',
		'deleted_by_user_id'
    ];
       
}
