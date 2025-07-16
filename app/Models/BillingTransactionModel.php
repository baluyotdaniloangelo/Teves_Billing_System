<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Illuminate\Database\Eloquent\SoftDeletes;

use Session;

class BillingTransactionModel extends Model
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
	
	protected $table = 'teves_billing_table';

	protected $fillable = [
		'so_idx',
		'cashiers_report_idx',
		'branch_idx',
		'receivable_idx',
		'lock_billing_item',
        'product_idx',
		'drivers_name',
		'plate_no',
        'product_price',
        'client_idx',
		'order_quantity',
		'order_total_amount',
        'order_date',
        'order_time',
        'order_po_number',
		'sales_order_idx',
		'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id',
		'deleted_at',
		'deleted_by_user_id'
    ];
	
    protected $primaryKey = 'billing_id';
	
	protected static $logName = 'Billing Transaction';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'so_idx',
		'cashiers_report_idx',
		'branch_idx',
		'receivable_idx',
		'lock_billing_item',
        'product_idx',
		'drivers_name',
		'plate_no',
        'product_price',
        'client_idx',
		'order_quantity',
		'order_total_amount',
        'order_date',
        'order_time',
        'order_po_number',
		'sales_order_idx',
		'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id',
		'deleted_at',
		'deleted_by_user_id'
    ];
	
}
