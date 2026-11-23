<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class CashDeposit extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'teves_cash_deposits';
    protected $primaryKey = 'cash_deposit_id';

    protected $fillable = [
        'bank_idx', 'branch_idx', 'cash_deposit_bank', 'cash_deposit_date', 'cash_deposit_amount',
        'cash_deposit_reference', 'cash_deposit_remarks', 'cash_deposit_photo',
        'created_by_user_id', 'updated_by_user_id', 'deleted_by_user_id',
    ];

    protected $dates = ['cash_deposit_date', 'deleted_at'];
    protected static $logName = 'Cash Deposits';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = [
        'bank_idx', 'branch_idx', 'cash_deposit_bank', 'cash_deposit_date', 'cash_deposit_amount',
        'cash_deposit_reference', 'cash_deposit_remarks', 'cash_deposit_photo',
        'created_by_user_id', 'updated_by_user_id', 'deleted_by_user_id',
    ];

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->causer_id = session('loginID');
    }
}
