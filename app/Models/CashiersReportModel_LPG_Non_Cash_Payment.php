<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class CashiersReportModel_LPG_Non_Cash_Payment extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'teves_cashiers_report_lpg_non_cash_payments';

    protected $primaryKey = 'cashiers_report_lpg_non_cash_payment_id';

    protected $dates = ['check_expiry_date', 'deleted_at'];

    protected $fillable = [
        'cashiers_report_id',
        'mode_of_payment',
        'payer_name',
        'payer_number',
        'reference_no',
        'check_expiry_date',
        'amount',
        'created_at',
        'created_by_user_idx',
        'updated_at',
        'updated_by_user_idx',
        'deleted_at',
        'deleted_by_user_id',
    ];

    protected static $logName = 'Cashiers Report LPG Non-Cash Payments';

    protected static $logOnlyDirty = true;

    protected static $logAttributes = [
        'cashiers_report_id',
        'mode_of_payment',
        'payer_name',
        'payer_number',
        'reference_no',
        'check_expiry_date',
        'amount',
        'created_at',
        'created_by_user_idx',
        'updated_at',
        'updated_by_user_idx',
        'deleted_at',
        'deleted_by_user_id',
    ];

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->causer_id = Session::get('loginID');
    }

    public function delete()
    {
        $this->deleted_by_user_id = Session::get('loginID');
        $this->save();

        return parent::delete();
    }
}
