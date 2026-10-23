<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class CashiersReportModel_LPG_KitchenMovement extends Model
{
    use LogsActivity, SoftDeletes;

    protected $table = 'teves_cashiers_report_lpg_kitchen_movements';
    protected $primaryKey = 'cashiers_report_lpg_kitchen_movement_id';
    protected $guarded = ['cashiers_report_lpg_kitchen_movement_id'];
    protected $dates = ['deleted_at'];
    protected $casts = ['quantity' => 'decimal:2'];
    protected static $logName = 'LPG Kitchen Stock Movements';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = ['cashiers_report_id', 'movement_type', 'product_idx', 'client_idx', 'reference_no', 'quantity', 'remarks', 'created_by_user_idx', 'updated_by_user_idx', 'deleted_by_user_id'];

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->causer_id = Session::get('loginID');
    }

    public function delete()
    {
        $this->deleted_by_user_id = Session::get('loginID', 0);
        $this->save();
        return parent::delete();
    }
}
