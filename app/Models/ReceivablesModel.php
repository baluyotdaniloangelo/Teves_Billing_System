<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use Illuminate\Database\Eloquent\SoftDeletes;

use Session;

class ReceivablesModel extends Model
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
	
	protected $table = 'teves_receivable_table';
	
	protected $fillable = [
		'receivable_id',
		'sales_order_idx',
		'receivable_name',
        'client_idx',
        'ar_reference',
		'control_number',
		'billing_date',
		'billing_time',
		'billing_period_start',
		'billing_period_end',
		'or_number',
		'payment_term',
		'all_branches',
		'receivable_description',
		'receivable_gross_amount',
		'receivable_vatable_sales',
		'receivable_vat_amount',
		'receivable_net_value_percentage',
		'receivable_vat_value_percentage',
		'receivable_withholding_tax_percentage',
		'receivable_withholding_tax',
		'receivable_amount',
		'receivable_remaining_balance',
		'receivable_status',
		'receivable_lock_status',
		'receivable_lock_by_id',
		'receivable_unlock_expiration',
		'less_per_liter',
		'company_header',
		'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id',
		'deleted_at',
		'deleted_by_user_id'
    ];
	
	protected $primaryKey = 'receivable_id';
	
	protected static $logName = 'ReceivableInfo';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'receivable_id',
		'sales_order_idx',
		'receivable_name',
        'client_idx',
        'ar_reference',
		'control_number',
		'billing_date',
		'billing_time',
		'billing_period_start',
		'billing_period_end',
		'or_number',
		'payment_term',
		'all_branches',
		'receivable_description',
		'receivable_gross_amount',
		'receivable_vatable_sales',
		'receivable_vat_amount',
		'receivable_net_value_percentage',
		'receivable_vat_value_percentage',
		'receivable_withholding_tax_percentage',
		'receivable_withholding_tax',
		'receivable_amount',
		'receivable_remaining_balance',
		'receivable_status',
		'receivable_lock_status',
		'receivable_lock_by_id',
		'receivable_unlock_expiration',
		'less_per_liter',
		'company_header',
		'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id',
		'deleted_at',
		'deleted_by_user_id'		
    ];
}
