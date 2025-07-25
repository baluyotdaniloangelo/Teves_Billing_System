<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use Illuminate\Database\Eloquent\SoftDeletes;

use Session;

class CashiersReportModel_P3 extends Model
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

	protected $table = 'teves_cashiers_report_p3';

	protected $fillable = [
		'cashiers_report_p3_id',
		'cashiers_report_id',
		'client_idx',
		'user_idx',
		'billing_idx',
		'miscellaneous_items_type',
		'so_idx',
		'reference_no',
        'product_idx',
		'item_description',
		'order_quantity',
		'cashiers_report_p1_id',
		'pump_price',
		'unit_price',
		'discounted_price',
		'order_total_amount',
		'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id',
		'deleted_at',
		'deleted_by_user_id'
    ];
	
    protected $primaryKey = 'cashiers_report_p3_id';
	
	protected static $logName = 'Cashiers Report Table Part 3';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'cashiers_report_p3_id',
		'cashiers_report_id',
		'client_idx',
		'user_idx',
		'billing_idx',
		'miscellaneous_items_type',
		'so_idx',
		'reference_no',
        'product_idx',
		'item_description',
		'order_quantity',
		'cashiers_report_p1_id',
		'pump_price',
		'unit_price',
		'discounted_price',
		'order_total_amount',
		'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id',
		'deleted_at',
		'deleted_by_user_id'
    ];
}
