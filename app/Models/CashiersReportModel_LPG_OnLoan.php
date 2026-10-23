<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class CashiersReportModel_LPG_OnLoan extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $table = 'teves_cashiers_report_lpg_on_loan';
    protected $primaryKey = 'cashiers_report_lpg_on_loan_id';
    protected $dates = ['deleted_at'];
    protected $casts = ['borrow_stock_state' => 'string', 'return_stock_state' => 'string'];

    protected $fillable = [
        'cashiers_report_id', 'client_idx', 'dr_number', 'borrow_product_idx',
        'borrow_quantity', 'borrow_alum_quantity', 'return_product_idx',
        'return_quantity', 'return_alum_quantity', 'borrow_stock_state', 'return_stock_state', 'remarks', 'created_at',
        'created_by_user_idx', 'updated_at', 'updated_by_user_idx', 'deleted_at',
        'deleted_by_user_id',
    ];

    protected static $logName = 'Cashiers Report LPG On Loan';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = [
        'cashiers_report_id', 'client_idx', 'dr_number', 'borrow_product_idx',
        'borrow_quantity', 'borrow_alum_quantity', 'return_product_idx',
        'return_quantity', 'return_alum_quantity', 'borrow_stock_state', 'return_stock_state', 'remarks', 'created_at',
        'created_by_user_idx', 'updated_at', 'updated_by_user_idx', 'deleted_at',
        'deleted_by_user_id',
    ];

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
