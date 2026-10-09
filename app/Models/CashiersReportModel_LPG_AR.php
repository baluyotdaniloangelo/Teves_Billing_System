<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class CashiersReportModel_LPG_AR extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'teves_cashiers_report_lpg_ar';

    protected $primaryKey = 'cashiers_report_lpg_ar_id';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'cashiers_report_id',
        'ar_date',
        'client_idx',
        'dr_number',
        'remarks',
        'amount_received',
        'created_at',
        'created_by_user_idx',
        'updated_at',
        'updated_by_user_idx',
        'deleted_at',
        'deleted_by_user_id',
    ];

    protected static $logName = 'Cashiers Report LPG AR';

    protected static $logOnlyDirty = true;

    protected static $logAttributes = [
        'cashiers_report_id',
        'ar_date',
        'client_idx',
        'dr_number',
        'remarks',
        'amount_received',
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
