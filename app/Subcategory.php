<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $fillable = [
        'department_id', 'category_id', 'subcategory_name','photo',
    ];
}
