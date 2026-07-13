<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\SalesAgentModel;

use Session;

class ClientModel extends Model
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

	public function referrer()
	{
		return $this->belongsTo(SalesAgentModel::class, 'sales_agent_idx', 'sales_agent_id');
	}

	public function referrals()
	{
		return $this->hasMany(ClientModel::class, 'sales_agent_idx', 'client_id');
	}


	protected $table = 'teves_client_table';
	
	protected $fillable = [
        'client_name',
        'customer_type',
		'client_account_number',
		'client_address',
		'client_tin',
		'client_email_address',
		'client_contact_number',
		'client_age',
		'default_net_percentage',
		'default_less_percentage',
		'default_vat_percentage',
		'default_withholding_tax_percentage',
		'default_payment_terms',
		'sales_agent_idx',
		'created_at',
		'created_by_user_idx',
		'updated_at',
		'updated_by_user_idx',
		'deleted_at',
		'deleted_by_user_id'
    ];
	
	protected $primaryKey = 'client_id';
     
	protected static $logName = 'Client Information';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'client_name',
        'customer_type',
		'client_account_number',
		'client_address',
		'client_tin',
		'client_email_address',
		'client_contact_number',
		'client_age',		
		'default_net_percentage',
		'default_less_percentage',
		'default_vat_percentage',
		'default_withholding_tax_percentage',
		'default_payment_terms',
		'sales_agent_idx',
		'created_at',
		'created_by_user_idx',
		'updated_at',
		'updated_by_user_idx',
		'deleted_at',
		'deleted_by_user_id'
    ];
       
}
