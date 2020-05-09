<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Category;
use DB;

class CategoryController extends Controller
{
    public function index()
    {
        // return Category::latest()->paginate(8);

        // return  DB::select("SELECT categories.*, departments.* FROM categories
        // LEFT JOIN departments ON categories.department_id = departments.id
        // WHERE categories.department_id = departments.id")->paginate(8);

        $results = DB::table('categories')
        -> join('departments', 'categories.department_id', '=', 'departments.id')
        ->paginate(8);

        return $results;
        // dd($results);


    }
}
