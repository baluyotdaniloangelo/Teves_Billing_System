<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientModel extends Model
{
    //use HasFactory;
	
	/*
	
	`client_id` INT(11) NOT NULL AUTO_INCREMENT,
	`client_name` TEXT NOT NULL COLLATE 'latin1_swedish_ci',
	`created_at` DATETIME NULL DEFAULT NULL,
	`updated_at` DATETIME NULL DEFAULT NULL,
	
	*/
	
	protected $table = 'teves_client_table';
	
	protected $fillable = [
        'client_name',
        'created_at',
		'updated_at'
    ];
    
}
