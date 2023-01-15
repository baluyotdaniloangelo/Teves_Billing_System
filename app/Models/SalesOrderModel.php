<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SalesOrderModel extends Model
{

	protected $table = 'teves_sales_order_table';
	
	protected $fillable = [
        'sales_order_client_idx',
		'sales_order_date',
		'sales_order_control_number',
		'sales_order_dr_number',
		'sales_order_or_number',
		'sales_order_payment_term',
		'sales_order_total_due',
    ];
	
	protected $primaryKey = 'sales_order_id';
    
}
