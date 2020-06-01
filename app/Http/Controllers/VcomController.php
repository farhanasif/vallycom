<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use App\Category;
use App\SubCategory;

class VcomController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $departments = Department::get();
        $categories = Category::get();
        $subCategories = SubCategory::get();
        return view('home',compact('departments','categories','subCategories'));
    }
}
