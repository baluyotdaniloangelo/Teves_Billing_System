<?php
namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashiersReportModel_P1 extends Model
{
	
	protected $table = 'teves_cashiers_report_p1';

	protected $fillable = [
        'user_idx',
		'cashiers_report_id',
        'product_idx',
        'beginning_reading',
        'closing_reading',
        'calibration',
		'order_quantity',
		'product_price',
		'order_total_amount',
		'created_at',
		'updated_at'
    ];
    protected $primaryKey = 'cashiers_report_p1_id';
}
