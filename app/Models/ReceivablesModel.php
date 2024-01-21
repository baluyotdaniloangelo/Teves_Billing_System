<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ReceivablesModel extends Model
{
	
	use LogsActivity;

	protected $table = 'teves_receivable_table';
	
	protected $fillable = [
        'client_idx',
		'billing_date',
		'control_number',
		'or_number',
		'payment_term',
		'receivable_description',
		'receivable_amount',
		'receivable_remaining_balance',
		'created_at',
		'updated_at'
    ];
	
	protected $primaryKey = 'receivable_id';
	
	protected static $logName = 'ReceivableInfo';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'client_idx',
		'billing_date',
		'control_number',
		'or_number',
		'payment_term',
		'receivable_description',
		'receivable_amount',
		'receivable_remaining_balance',
		'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id'		
    ];
}
