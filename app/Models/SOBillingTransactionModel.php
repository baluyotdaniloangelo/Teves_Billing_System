<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SOBillingTransactionModel extends Model
{
	/*
	CREATE TABLE `teves_billing_so_table` (
	`so_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
	`so_number` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`order_date` VARCHAR(20) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`order_time` VARCHAR(20) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`client_idx` BIGINT(20) NULL DEFAULT NULL,
	`drivers_name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`plate_no` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`created_at` DATETIME NULL DEFAULT NULL,
	`created_by_user_id` INT(11) NULL DEFAULT NULL,
	`updated_at` DATETIME NULL DEFAULT NULL,
	`updated_by_user_id` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`so_id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=4
;
*/
    //use HasFactory;
	use LogsActivity;
	
	protected $table = 'teves_billing_so_table';

	protected $fillable = [
		'drivers_name',
		'plate_no',
        'client_idx',
        'order_date',
        'order_time',
        'so_number',
        'created_at',
		'updated_at'
    ];
	
    protected $primaryKey = 'so_id';
	
	protected static $logName = 'SO';
	
	protected static $logOnlyDirty = true;
	
	protected static $logAttributes = [
		'order_date',
        'order_time',
		'so_number',
		'client_idx',
		'drivers_name',
		'plate_no',
		'created_at',
		'created_by_user_id',
		'updated_at',
		'updated_by_user_id'
		
    ];
	
}
