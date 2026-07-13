<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProductAccessModel extends Model
{
    //use HasFactory;
	protected $table = 'teves_user_product_category_access';
	
	protected $fillable = [
        'user_idx',
        'category_idx',
    ];
    
}
