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

        $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

        \Image::make($request->photo)->save(public_path('img/master/').$name);
        $request->merge(['photo' => $name]);


        return Department::create([
            'department_name' => $request['department_name'],
            'photo' => $name,
        ]);
    }

    public function editDepartment($id)
    {
        // return Department::findOrFail($id);

        dd(Department::findOrFail($id));
    }

    public function updateDepartment(Request $request,$id)
    {
        $user = Department::findOrFail($id);

        $this->validate($request,[
            'department_name' => 'required|string|max:191',
            'photo' => 'required|mimes:jpeg,jpg,png'
        ]);

        $user->update($request->all());
        return ['message' => 'Updated the department info'];
    }

    public function deleteDepartment($id)
    {
        // $this->authorize('isAdmin');

        $user = Department::findOrFail($id);
        // delete the department
            // dd($user);
            // exit;
        $user->delete();

        return ['message' => 'Department Deleted'];
    }

}
