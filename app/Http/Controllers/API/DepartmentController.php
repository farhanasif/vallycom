<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        return Department::paginate(12);
    //    $department =  Department::paginate(12);
    //    dd($department);
    }
}
