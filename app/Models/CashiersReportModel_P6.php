<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashiersReportModel_P6 extends Model
{
	
	protected $table = 'teves_cashiers_report_p6';

	protected $fillable = [
        'user_idx',
		'cashiers_report_id',
		'product_idx',
		'tank_idx',
		'beginning_inventory',
		'sales_in_liters',
		'delivery',
		'ending_inventory',
		'book_stock',
		'variance',
		'created_at',
		'updated_at'
    ];
    protected $primaryKey = 'cashiers_report_p6_id';
	
}
