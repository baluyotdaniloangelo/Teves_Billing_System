<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Session;

class ProductPricePerBranchHistoryModel extends Model
{
	
	use LogsActivity;
		
	protected $table = 'teves_product_branch_price_table_history';
	
	protected $fillable = [
		'product_idx',
		'branch_idx',
		'date_of_changes',
		'time_of_changes',
        'buying_price',
        'profit_margin_type',
		'profit_margin',
		'profit_margin_in_peso',
		'branch_price',
        'created_at',
		'created_by_user_idx',
		'updated_at',
		'modified_by_user_idx'
    ];
	
	protected $primaryKey = 'branch_price_id_history';
    
	protected static $logName = 'Product Price per Branch';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'product_idx',
		'branch_idx',
		'date_of_changes',
		'time_of_changes',
        'buying_price',
        'profit_margin_type',
		'profit_margin',
		'profit_margin_in_peso',
		'branch_price',
        'created_at',
		'created_by_user_idx',
		'updated_at',
		'modified_by_user_idx'
    ];	
	
}
