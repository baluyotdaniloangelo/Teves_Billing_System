<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Session;

class ProductPricePerSellerModel extends Model
{
	
	use LogsActivity;
		
	protected $table = 'teves_product_seller_price_table';
	
	protected $fillable = [
		'product_idx',
		'supplier_idx',
        'seller_price',
        'created_at',
		'created_by_user_idx',
		'updated_at',
		'modified_by_user_idx'
    ];
	
	protected $primaryKey = 'seller_price_id';
    
	protected static $logName = 'Product Price per Seller';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'product_idx',
		'supplier_idx',
        'seller_price',
        'created_at',
		'created_by_user_idx',
		'updated_at',
		'modified_by_user_idx'
    ];	
	
}
