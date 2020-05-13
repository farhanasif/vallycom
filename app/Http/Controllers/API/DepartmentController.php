<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        return Department::latest()->paginate(5);
    //    dd($department);
    }

    public function storeDepartment(Request $request)
    {

        $this->validate($request,[
            'department_name' => 'required',
        ]);

            $exploded = explode(',',$request->photo);

            $decoded = base64_decode($exploded[1]);

            if (str_contains($exploded[0],'jpeg')) {
               $extention = 'jpg';
            }else{
                $extention = 'png';
            }

            $fileName = str_random().'.'.$extention;

            $path = public_path().'/img/master/'.$fileName;
            file_put_contents($path, $decoded);
            $file = '/img/master/'.$fileName;


        return Department::create([
            'department_name' => $request['department_name'],
            'photo' => $file,
        ]);
    }

    public function updateDepartment(Request $request,$id)
    {
        $department = Department::findOrFail($id);

        $this->validate($request,[
            'department_name' => 'required',
        ]);

        $currentPhoto = $department->photo;

        if($request->photo != null){
            $exploded = explode(',',$request->photo);

            $decoded = base64_decode($exploded[1]);

            if (str_contains($exploded[0],'jpeg')) {
                $extention = 'jpg';
            }else{
                $extention = 'png';
            }

            $name = str_random().'.'.$extention;
            if($currentPhoto == null) {
                $departmentPhoto = public_path().'/img/master/'.$name;
                file_put_contents($departmentPhoto, $decoded);
                $file = '/img/master/'.$name;
            }else {
                $departmentPhoto = public_path('').$currentPhoto;
                file_put_contents($departmentPhoto, $decoded);
                $file = '/img/master/'.$name;
            }

            \Image::make($request->photo)->save(public_path('/').$file);
            $request->merge(['photo' => $file]);
            if(file_exists($departmentPhoto)){
                @unlink($file);
            }
        }
        $department->update($request->all());
        return ['message' => 'Updated the Department info'];
    }

    public function search(){

        if ($search = \Request::get('q')) {
            $department = Department::where(function($query) use ($search){
                $query->where('department_name','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $department = Department::latest()->paginate(5);
        }

        return $department;
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
