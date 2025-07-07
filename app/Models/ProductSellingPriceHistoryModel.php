<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Session;

class ProductSellingPriceHistoryModel extends Model
{
		
	protected $table = 'teves_product_selling_price_table_history';
	
	protected $fillable = [
		'selling_price_idx',
		'product_idx',
		'supplier_idx',
		'branch_idx',
        'selling_price',
        'created_at',
		'created_by_user_idx',
		'updated_at',
		'modified_by_user_idx'
    ];
	
	protected $primaryKey = 'selling_price_id_history';
    
	protected static $logName = 'Product Price per selling History';

}
