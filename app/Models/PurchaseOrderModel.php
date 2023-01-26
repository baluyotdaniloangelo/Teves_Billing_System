<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderModel extends Model
{

	protected $table = 'teves_purchase_order_table';
	
	protected $fillable = [
		'purchase_order_date',
		'purchase_order_control_number',
		'purchase_supplier_name',
		'purchase_order_or_number',
		'purchase_order_total_due'
    ];
	
	protected $primaryKey = 'sales_order_id';
    
}
