<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'department_id', 'category_id', 'subcategory_id','sku', 'title', 'short_description','long_description', 'current_stock', 'current_price', 'discount_price', 'is_discount', 'specification','delivery_description'
    ];
}
