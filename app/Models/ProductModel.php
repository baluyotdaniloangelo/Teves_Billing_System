<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    //use HasFactory;
	
	/*
	
	`product_id` INT(11) NOT NULL AUTO_INCREMENT,
	`product_name` TEXT NOT NULL COMMENT 'SITE CODE TO Used on Gateway' COLLATE 'latin1_swedish_ci',
	`product_category` INT(11) NOT NULL DEFAULT '0' COMMENT 'COMPANY_CODE',
	`product_price` TEXT NOT NULL COMMENT 'BUSINESS_ENTITY/Location/SITE ed SM SA LAZARO' COLLATE 'latin1_swedish_ci',
	`product_unit_measurement` TEXT NULL DEFAULT NULL COMMENT 'METER_READING_CUTOFF' COLLATE 'latin1_swedish_ci',
	`created_at` DATETIME NOT NULL,
	`updated_at` DATETIME NOT NULL,*/
	
	protected $table = 'teves_product_table';
	
	protected $fillable = [
        'product_name',
        'product_price',
		'product_unit_measurement',
        'created_at',
		'updated_at'
    ];
    
}
