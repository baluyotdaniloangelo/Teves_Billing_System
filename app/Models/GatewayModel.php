<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewayModel extends Model
{
    //use HasFactory;
	protected $table = 'meter_rtu';
	
	/*`id` INT(11) NOT NULL AUTO_INCREMENT,
	`rtu_sn_number` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`rtu_physical_location` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`connection_type` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`phone_no_or_ip_address` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`ip_netmask` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`ip_gateway` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`mac_addr` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`rtu_server_ip` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`gateway_description` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`update_rtu` INT(11) NOT NULL DEFAULT '0',
	`update_rtu_location` INT(11) NOT NULL,
	`update_rtu_ssh` INT(11) NOT NULL,
	`update_rtu_force_lp` INT(11) NOT NULL,
	`rtu_site_id` INT(11) NOT NULL,
	`idf_number` TEXT NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`switch_name` TEXT NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`idf_port` TEXT NULL DEFAULT NULL COLLATE 'latin1_swedish_ci',
	`rtu_site_name` VARCHAR(200) NOT NULL COLLATE 'latin1_swedish_ci',
	`created_by` VARCHAR(300) NOT NULL COLLATE 'latin1_swedish_ci',
	`date_created` VARCHAR(200) NOT NULL COLLATE 'latin1_swedish_ci',
	`modified_by` VARCHAR(200) NOT NULL COLLATE 'latin1_swedish_ci',
	`date_modified` TIMESTAMP NULL DEFAULT NULL,
	`added_by` INT(11) NOT NULL,
	`last_log_update` DATETIME NOT NULL,
	`soft_rev` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	PRIMARY KEY (`id`) USING BTREE*/
	
	protected $fillable = [
        'rtu_sn_number',
        'mac_addr',
        'phone_no_or_ip_address',
		'rtu_physical_location',
		'update_rtu',
        'update_rtu_location',
		'update_rtu_ssh',
		'update_rtu_force_lp',
        'idf_number',
        'switch_name',
        'idf_port',
        'last_log_update',
        'soft_rev'
    ];
    
}
