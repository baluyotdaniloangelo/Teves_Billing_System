<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Session;

class ProductPricePerSellerHistoryModel extends Model
{
		
	protected $table = 'teves_product_seller_price_table_history';
	
	protected $fillable = [
		'seller_price_idx',
		'product_idx',
		'supplier_idx',
		'branch_idx',
        'seller_price',
        'created_at',
		'created_by_user_idx',
		'updated_at',
		'modified_by_user_idx'
    ];
	
	protected $primaryKey = 'seller_price_id_history';
    
	protected static $logName = 'Product Price per Seller History';

}
