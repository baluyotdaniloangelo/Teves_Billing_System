<?php
namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Session;

class ReceivablesPaymentModel extends Model
{
	
	use SoftDeletes;
    protected $dates = ['deleted_at'];
	
	use LogsActivity;

	public function tapActivity(Activity $activity, string $eventName)
	{
    $activity->causer_id = Session::get('loginID');
	}
	
	protected $table = 'teves_receivable_payment';

	protected $fillable = [
        'receivable_payment_id',
		'receivable_idx',
        'client_idx',
		'receivable_date_of_payment',
		'receivable_time_of_payment',
		'receivable_mode_of_payment',
        'receivable_reference',
		'receivable_payment_amount',
		'receivable_payment_remarks',
        'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id'
    ];
	
    protected $primaryKey = 'receivable_payment_id';
		
	protected static $logName = 'ReceivablePayment';
	
	protected static $logOnlyDirty = true;

	protected static $logAttributes = [
        'receivable_payment_id',
		'receivable_idx',
        'client_idx',
		'receivable_date_of_payment',
		'receivable_time_of_payment',
		'receivable_mode_of_payment',
        'receivable_reference',
		'receivable_payment_amount',
		'receivable_payment_remarks',
        'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id'
    ];
}
