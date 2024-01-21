<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SOBillingTransactionModel extends Model
{

    //use HasFactory;
	use LogsActivity;
	
	protected $table = 'teves_billing_so_table';

	protected $fillable = [
		'drivers_name',
		'plate_no',
        'client_idx',
        'order_date',
        'order_time',
        'so_number',
        'created_at',
		'updated_at'
    ];
	
    protected $primaryKey = 'so_id';
	
	protected static $logName = 'SO';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'order_date',
        'order_time',
		'so_number',
		'client_idx',
		'drivers_name',
		'plate_no',
		'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id'
		
    ];
	
}
