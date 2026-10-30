<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class CashiersReportModel_LPG_EmptyInventory extends Model
{
    use LogsActivity, SoftDeletes;

    public const ADDITIONS = [
        'return_quantity' => 'Return (On-Loan)',
        'swap_in_quantity' => 'Swap In',
        'advance_pull_out_quantity' => 'Advance Pull-Out',
    ];

    public const DEDUCTIONS = [
        'sold_quantity' => 'Cylinder Sold',
        'borrow_quantity' => 'Borrow (On-Loan)',
        'delivery_from_advance_pull_out_quantity' => 'Delivery from Advance Pull-Out',
        'pull_out_truck_quantity' => 'Delivery Truck',
    ];

    public const QUANTITY_FIELDS = ['beginning_quantity' => 'Beginning Inventory'] + self::ADDITIONS + self::DEDUCTIONS;

    protected $table = 'teves_cashiers_report_lpg_empty_inventory';
    protected $primaryKey = 'cashiers_report_lpg_empty_inventory_id';
    protected $guarded = ['cashiers_report_lpg_empty_inventory_id'];
    protected $dates = ['deleted_at'];
    protected $casts = ['auto_fields' => 'array'];
    protected $appends = ['ending_quantity', 'capacity_group'];
    protected static $logName = 'Cashiers Report LPG Empty Cylinders / Canisters';
    protected static $logOnlyDirty = true;
    protected static $logAttributes = [
        'cashiers_report_id', 'product_idx', 'beginning_quantity', 'refill_sell_out_quantity',
        'return_quantity', 'swap_in_quantity', 'from_other_branch_quantity', 'advance_pull_out_quantity', 'sold_quantity',
        'swap_out_quantity', 'borrow_quantity', 'pull_out_branch_quantity', 'delivery_from_advance_pull_out_quantity', 'pull_out_truck_quantity',
        'remarks', 'created_by_user_idx', 'updated_by_user_idx', 'deleted_by_user_id', 'deleted_at',
        'auto_fields',
    ];

    public function getEndingQuantityAttribute(): int
    {
        $ending = (int) $this->beginning_quantity;
        foreach (self::ADDITIONS as $field => $label) $ending += (int) $this->{$field};
        foreach (self::DEDUCTIONS as $field => $label) $ending -= (int) $this->{$field};
        return $ending;
    }

    public function getCapacityGroupAttribute(): string
    {
        if (!$this->product_capacity || !in_array($this->product_capacity_unit, ['kg', 'g'], true)) {
            return 'Capacity not set';
        }
        return rtrim(rtrim(number_format((float) $this->product_capacity, 3, '.', ''), '0'), '.') . ' ' . $this->product_capacity_unit;
    }

    public function scopeForReport($query, int $reportId)
    {
        return $query->leftJoin('teves_product_table as empty_product', 'empty_product.product_id', '=', $this->table . '.product_idx')
            ->where($this->table . '.cashiers_report_id', $reportId)
            ->select([$this->table . '.*', 'empty_product.product_name', 'empty_product.product_capacity', 'empty_product.product_capacity_unit'])
            ->orderByRaw("CASE empty_product.product_capacity_unit WHEN 'kg' THEN 0 WHEN 'g' THEN 1 ELSE 2 END")
            ->orderBy('empty_product.product_capacity')
            ->orderBy('empty_product.product_name');
    }

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
