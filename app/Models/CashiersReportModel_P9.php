<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Session;

class CashiersReportModel_P9 extends Model
{
	use LogsActivity;
	
	public function tapActivity(Activity $activity, string $eventName)
	{
    $activity->causer_id = Session::get('loginID');
	}
	
	protected $table = 'teves_cashiers_report_p9';

	protected $fillable = [
		'cashiers_report_p9_id',
		'user_idx',
		'cashiers_report_idx',

		'cash_deposit_bank',
		'cash_deposit_date',

		'cash_deposit_amount',
		'cash_deposit_reference',
		'cash_deposit_remarks',
		
		'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id'
	];

	protected $primaryKey = 'cashiers_report_p9_id';

	/* ================= ACTIVITY LOG ================= */

	protected static $logName = 'Cashiers Report Table Part 9 - Cash Deposit';

	protected static $logOnlyDirty = true;

	protected static $logAttributes = [
		'cashiers_report_p8_id',
		'user_idx',
		'cashiers_report_idx',

		'cash_deposit_bank',
		'cash_deposit_date',

		'cash_deposit_amount',
		'cash_deposit_reference',
		'cash_deposit_remarks',
		
		'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id'
	];

	
}
