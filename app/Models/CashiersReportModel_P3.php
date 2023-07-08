<?php
namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashiersReportModel_P3 extends Model
{
	
	protected $table = 'teves_cashiers_report_p3';

	protected $fillable = [
        'user_idx',
		'cashiers_report_id',
        'product_idx',
		'order_quantity',
		'product_price',
		'order_total_amount',
		'created_at',
		'updated_at'
    ];
    protected $primaryKey = 'cashiers_report_p3_id';
}
