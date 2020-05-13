<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Department;
use DB;
use Illuminate\Support\Facades\Hash;

class CategoryController extends Controller
{

    public function index()
    {
        $category = DB::table('categories')
                    ->select('categories.id as category_id','categories.department_id','categories.category_name','categories.photo','categories.created_at')
                    ->addSelect('departments.department_name')
                    ->join('departments','departments.id','=','categories.department_id')
                    ->latest()
                    ->paginate(5);
        return $category;//response()->json(["category"=>$category]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'department_id' => 'required|int',
            'category_name' => 'required|string|max:191',

        ]);

        $exploded = explode(',',$request->photo);

        $decoded = base64_decode($exploded[1]);

        if (str_contains($exploded[0],'jpeg')) {
        $extention = 'jpg';
        }else{
            $extention = 'png';
        }

        $fileName = str_random().'.'.$extention;

        $path = public_path().'/img/master/category/'.$fileName;
        file_put_contents($path, $decoded);
        $file = '/img/master/category/'.$fileName;

        return Category::create([
            'department_id' => $request['department_id'],
            'category_name' => $request['category_name'],
            'photo' => $file,
        ]);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $this->validate($request,[
            'department_id' => 'required|int',
            'category_name' => 'required|string|max:191',
        ]);

        $currentPhoto = $category->photo;

        if($request->photo != null){
            $exploded = explode(',',$request->photo);

            $decoded = base64_decode($exploded[1]);

            if (str_contains($exploded[0],'jpeg')) {
                $extention = 'jpg';
            }else{
                $extention = 'png';
            }

            $name = str_random().'.'.$extention;

            // \Image::make($request->photo)->save(public_path('/').'/img/master/category/'.$name);
            // $request->merge(['photo' => $name]);

            $categoryPhoto = public_path('/').$currentPhoto;
            file_put_contents($categoryPhoto, $decoded);
            $file = '/img/master/category/'.$name;
            \Image::make($request->photo)->save(public_path('/').$file);
            $request->merge(['photo' => $file]);
            if(file_exists($categoryPhoto)){
                @unlink($file);
            }
        }
        $category->update($request->all());
        return ['message' => 'Updated the category info'];
    }


    public function destroy($id)
    {
        // $this->authorize('isAdmin');

        $category = Category::findOrFail($id);
        // delete the category

        $category->delete();

        return ['message' => 'Category is Deleted'];
    }



    public function search(){

        if($search = \Request::get('q')) {
            $categories = Category::where(function($query) use ($search){
                $query->where('category_name','LIKE',"%$search%")
                        ->orWhere('department_id','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $categories = Category::latest()->paginate(5);
        }

        return $categories;

    }

    public function getDepartment()
    {
        $departments = Department::all();
        return $departments;
    }
}
