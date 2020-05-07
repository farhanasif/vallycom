<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        return Department::paginate(8);
    //    dd($department);
    }

    public function storeDepartment(Request $request)
    {
        $this->validate($request,[
            'department_name' => 'required|string|max:191',
        ]);

        return Department::create([
            'department_name' => $request['department_name'],
            'photo' => $request['photo'],
        ]);
    }

    public function deleteDepartment($id)
    {
        $this->authorize('isAdmin');

        $user = Department::findOrFail($id);
        // delete the department

        $user->delete();

        return ['message' => 'Department Deleted'];
    }

}
