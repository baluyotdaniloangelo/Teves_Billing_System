<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Spatie\Activitylog\Traits\LogsActivity;

class BillingTransactionModel extends Model
{
    //use HasFactory;
	//use LogsActivity;
	
	protected $table = 'teves_billing_table';

	protected $fillable = [
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
        'created_at',
		'updated_at'
    ];
	
    protected $primaryKey = 'billing_id';
	
	protected static $logName = 'Billing Transaction';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'order_date',
        'order_time',
		'order_po_number',
		'client_idx',
		'drivers_name',
		'plate_no',
        'product_idx',
        'product_price',
		'order_quantity',
		'order_total_amount'
    ];
	
}
