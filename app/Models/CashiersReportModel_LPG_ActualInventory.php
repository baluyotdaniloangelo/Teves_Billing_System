<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class CashiersReportModel_LPG_ActualInventory extends Model
{
    use LogsActivity;

    protected $table = 'teves_cashiers_report_lpg_actual_inventory';
    protected $primaryKey = 'cashiers_report_lpg_actual_inventory_id';
    protected $guarded = ['cashiers_report_lpg_actual_inventory_id'];
    protected $casts = ['actual_quantity' => 'decimal:2'];
    protected static $logName = 'LPG Actual Ending Inventory';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = ['cashiers_report_id', 'product_idx', 'actual_quantity', 'remarks', 'created_by_user_idx', 'updated_by_user_idx'];

    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->causer_id = Session::get('loginID');
    }
}
