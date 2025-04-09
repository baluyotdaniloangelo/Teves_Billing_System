<?php
namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;
use Illuminate\Database\Eloquent\Model;

use Session;

class PurchaseOrderModel extends Model
{
	//use HasFactory;
	use LogsActivity;
	
	public function tapActivity(Activity $activity, string $eventName)
	{
    $activity->causer_id = Session::get('loginID');
	}
	
	protected $table = 'teves_purchase_order_table';
	
	protected $fillable = [
		'purchase_order_control_number',
		'purchase_order_date',
		'purchase_order_sales_order_number',
		'purchase_order_collection_receipt_no',
		'purchase_order_official_receipt_no',
		'purchase_order_delivery_receipt_no',
		'purchase_order_bank',
		'purchase_order_date_of_payment',
		'purchase_order_reference_no',
		'purchase_order_payment_amount',
		'purchase_order_delivery_method',
		'purchase_loading_terminal',
		'purchase_order_date_of_pickup',
		'purchase_order_date_of_arrival',
		'purchase_order_gross_amount',
		'purchase_order_total_liters',
		'purchase_order_net_percentage', 
		'purchase_order_net_amount',
		'purchase_order_less_percentage',
		'purchase_order_total_payable',
		'hauler_operator',
		'lorry_driver',
		'plate_number',
		'purchase_destination',
		'purchase_order_instructions',
		'purchase_order_note',
		'company_header',
		'purchase_order_invoice',
		'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id'    
	];
	
	protected $primaryKey = 'purchase_order_id';
	
	protected static $logName = 'Purchase Order';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'purchase_order_control_number',
		'purchase_order_date',
		'purchase_order_sales_order_number',
		'purchase_order_collection_receipt_no',
		'purchase_order_official_receipt_no',
		'purchase_order_delivery_receipt_no',
		'purchase_order_bank',
		'purchase_order_date_of_payment',
		'purchase_order_reference_no',
		'purchase_order_payment_amount',
		'purchase_order_delivery_method',
		'purchase_loading_terminal',
		'purchase_order_date_of_pickup',
		'purchase_order_date_of_arrival',
		'purchase_order_gross_amount',
		'purchase_order_total_liters',
		'purchase_order_net_percentage', 
		'purchase_order_net_amount',
		'purchase_order_less_percentage',
		'purchase_order_total_payable',
		'hauler_operator',
		'lorry_driver',
		'plate_number',
		'purchase_destination',
		'purchase_order_instructions',
		'purchase_order_note',
		'company_header',
		'purchase_order_invoice',
		'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id'
    ];
	
}