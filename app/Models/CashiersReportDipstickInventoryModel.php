<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Session;

class CashiersReportDipstickInventoryModel extends Model
{
    use LogsActivity;

    protected $table = 'teves_cashiers_report_p6';

    protected $primaryKey = 'cashiers_report_p6_id';

    protected $fillable = [
        'user_idx',
        'cashiers_report_idx',
        'product_idx',
        'tank_idx',
        'beginning_inventory',
        'sales_in_liters',
        'ugt_pumping',
        'delivery',
        'ending_inventory',
        'book_stock',
        'variance',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    protected static $logName = 'Cashiers Report Dipstick Inventory';

    protected static $logOnlyDirty = true;

    protected static $logAttributes = [
        'cashiers_report_p6_id',
        'user_idx',
        'cashiers_report_idx',
        'product_idx',
        'tank_idx',
        'beginning_inventory',
        'sales_in_liters',
        'ugt_pumping',
        'delivery',
        'ending_inventory',
        'book_stock',
        'variance',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->causer_id = Session::get('loginID');
    }
}
