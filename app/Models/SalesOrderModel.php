<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SalesOrderModel extends Model
{

	protected $table = 'teves_sales_order_table';
	
	protected $fillable = [
        'client_idx',
		'sales_order_date',
		'control_number',
		'dr_number',
		'or_number',
		'payment_term',
		'total_due',
    ];
	
	protected $primaryKey = 'sales_order_id';
    
}
