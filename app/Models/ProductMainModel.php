<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class ProductMainModel extends Model
{
    use SoftDeletes;

    protected $table = 'teves_product_main_table';
    protected $primaryKey = 'product_main_id';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'product_name',
        'category_idx',
        'created_by_user_idx',
        'updated_by_user_idx',
        'deleted_by_user_id',
    ];

    public function variants()
    {
        return $this->hasMany(ProductModel::class, 'product_main_idx', 'product_main_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategoryModel::class, 'category_idx', 'category_id');
    }

    public function delete()
    {
        $this->deleted_by_user_id = Session::get('loginID');
        $this->save();

        return parent::delete();
    }
}
