<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        return Department::latest()->paginate(8);
    //    dd($department);
    }

    public function storeDepartment(Request $request)
    {

            $exploded = explode(',',$request->photo);

            $decoded = base64_decode($exploded[1]);

            if (str_contains($exploded[0],'jpeg')) {
               $extention = 'jpg';
            }else{
                $extention = 'png';
            }

            $fileName = str_random().'.'.$extention;

            $path = public_path().'/'.$fileName;
            file_put_contents($path, $decoded);


        return Department::create([
            'department_name' => $request['department_name'],
            'photo' => $fileName,
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
