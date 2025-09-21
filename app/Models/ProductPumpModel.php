<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Contracts\Activity;

use Illuminate\Database\Eloquent\SoftDeletes;

use Session;

class ProductPumpModel extends Model
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
	
	protected $table = 'teves_product_pump_table';
	
	protected $fillable = [
		'product_idx',
		'branch_idx',
        'pump_name',
        'initial_reading',
		'pump_unit_of_measurement',
		'created_at',
		'created_by_user_idx',
		'updated_at',
		'updated_by_user_idx',
		'deleted_at',
		'deleted_by_user_id'
    ];
	
	protected $primaryKey = 'pump_id';
	
 	protected static $logName = 'Pump Information';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'product_idx',
		'branch_idx',
        'pump_name',
        'initial_reading',
		'pump_unit_of_measurement',
		'created_at',
		'created_by_user_idx',
		'updated_at',
		'updated_by_user_idx',
		'deleted_at',
		'deleted_by_user_id'
    ];   
}
