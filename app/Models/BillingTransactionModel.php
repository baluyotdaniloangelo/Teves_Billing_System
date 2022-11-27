<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingTransactionModel extends Model
{
    //use HasFactory;
	protected $table = 'teves_billing_table';

	protected $fillable = [
        'product_idx',
		'drivers_name',
        'product_price',
        'client_idx',
		'order_quantity',
		'order_total_amount',
        'order_date',
        'order_time',
        'order_po_number',
        'created_at',
		'updated_at'
    ];
    protected $primaryKey = 'billing_id';
}
