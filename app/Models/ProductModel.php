<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Illuminate\Database\Eloquent\SoftDeletes;

use Session;

class ProductModel extends Model
{
	use LogsActivity;
	
	public function tapActivity(Activity $activity, string $eventName)
	{
    $activity->causer_id = Session::get('loginID');
	}

	use SoftDeletes;
    protected $dates = ['deleted_at'];
	
	/*Delete*/
	public function delete()
	{
		$this->deleted_by_user_id = Session::get('loginID'); // or session()->get('user_id')
		$this->save();

		parent::delete();
	}
	
	protected $table = 'teves_product_table';
	
	protected $fillable = [
        'product_main_idx',
        'product_code',
        'product_name',
        'product_price',
		'product_unit_measurement',
		'product_capacity',
		'product_capacity_unit',
		'category_idx',
		'created_at',
		'created_by_user_idx',
		'updated_at',
		'updated_by_user_idx',
		'deleted_at',
		'deleted_by_user_id'
    ];
	
	protected $primaryKey = 'product_id';

	protected $casts = [
		'product_capacity' => 'decimal:3',
	];
    
	protected static $logName = 'Product Information';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'product_main_idx',
		'product_code',
		'product_name',
        'product_price',
		'product_unit_measurement',
		'product_capacity',
		'product_capacity_unit',
		'category_idx',
		'created_at',
		'created_by_user_idx',
		'updated_at',
		'updated_by_user_idx',
		'deleted_at',
		'deleted_by_user_id'
    ];

	public function mainProduct()
	{
		return $this->belongsTo(ProductMainModel::class, 'product_main_idx', 'product_main_id');
	}
    
}
