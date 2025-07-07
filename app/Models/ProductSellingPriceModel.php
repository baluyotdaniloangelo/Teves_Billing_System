<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Session;

class ProductSellingPriceModel extends Model
{
	
	use LogsActivity;
		
	protected $table = 'teves_product_selling_price_table';
	
	protected $fillable = [
		'product_idx',
		'supplier_idx',
		'branch_idx',
        'selling_price',
        'created_at',
		'created_by_user_idx',
		'updated_at',
		'modified_by_user_idx'
    ];
	
	protected $primaryKey = 'selling_price_id';
    
	protected static $logName = 'Product Price per selling';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'product_idx',
		'supplier_idx',
		'branch_idx',
        'selling_price',
        'created_at',
		'created_by_user_idx',
		'updated_at',
		'modified_by_user_idx'
    ];	
	
}
