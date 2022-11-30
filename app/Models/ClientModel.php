<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ClientModel extends Model
{

	
	protected $table = 'teves_client_table';
	
	protected $fillable = [
        'client_name',
		'client_address',
    ];
	
	protected $primaryKey = 'client_id';
    
}
