<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TevesBranchModel extends Model
{

	/*
	CREATE TABLE `teves_branch_table` (
	`branch_id` INT(11) NOT NULL AUTO_INCREMENT,
	`branch_code` VARCHAR(50) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`branch_name` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`branch_tin` VARCHAR(50) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`branch_address` VARCHAR(255) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`branch_contact_number` VARCHAR(50) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`branch_owner` VARCHAR(50) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`branch_owner_title` VARCHAR(50) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`branch_logo` VARCHAR(50) NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`created_at` DATETIME NULL DEFAULT NULL,
	`updated_at` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`branch_id`) USING BTREE
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=4
;

	*/
	protected $table = 'teves_branch_table';
	
	protected $fillable = [
        'branch_code',
		'branch_name',
		'branch_tin',
		'branch_address',
		'branch_contact_number',
		'branch_owner',
		'branch_owner_title',
		'branch_logo',
    ];
	
	protected $primaryKey = 'branch_id';
    
}
