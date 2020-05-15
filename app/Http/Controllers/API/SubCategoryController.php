<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Department;
use DB;
use App\SubCategory;
use Illuminate\Support\Facades\Hash;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategory = DB::table('subcategories')
                    ->select('subcategories.id as subcategory_id','subcategories.department_id','subcategories.category_id','subcategories.subcategory_name','subcategories.photo','subcategories.created_at')
                    ->addSelect('departments.department_name')
                    ->addSelect('categories.category_name')
                    ->join('categories','categories.id','=','subcategories.category_id')
                    ->join('departments','departments.id','=','categories.department_id')
                    ->latest()
                    ->paginate(5);
        return $subcategory;//response()->json(["category"=>$category]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'department_id' => 'required|int',
            'category_id'=> 'required|int',
            'subcategory_name' => 'required|string|max:191',

        ]);

        $exploded = explode(',',$request->photo);

        $decoded = base64_decode($exploded[1]);

        if (str_contains($exploded[0],'jpeg')) {
        $extention = 'jpg';
        }else{
            $extention = 'png';
        }

        $fileName = str_random().'.'.$extention;

        $path = public_path().'/img/master/subcategory/'.$fileName;
        file_put_contents($path, $decoded);
        $file = '/img/master/subcategory/'.$fileName;

        return Subcategory::create([
            'department_id' => $request['department_id'],
            'category_id' => $request['category_id'],
            'subcategory_name' => $request['subcategory_name'],
            'photo' => $file,
        ]);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $subcategory = Subcategory::findOrFail($id);

        $this->validate($request,[
            'department_id' => 'required|int',
            'category_id'=> 'required|int',
            'subcategory_name' => 'required|string|max:191',
        ]);

        $currentPhoto = $subcategory->photo;

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
                $subcategoryPhoto = public_path().'/img/master/subcategory/'.$name;
                file_put_contents($subcategoryPhoto, $decoded);
                $file = '/img/master/subcategory/'.$name;
            }else {
                $subcategoryPhoto = public_path('').$currentPhoto;
                file_put_contents($subcategoryPhoto, $decoded);
                $file = '/img/master/subcategory/'.$name;
            }
            // \Image::make($request->photo)->save(public_path('/').'/img/master/subcategory/'.$name);
            // $request->merge(['photo' => $name]);


            \Image::make($request->photo)->save(public_path('/').$file);
            $request->merge(['photo' => $file]);
            if(file_exists($subcategoryPhoto)){
                @unlink($file);
            }
        }
        $subcategory->update($request->all());
        return ['message' => 'Updated the Subcategory info'];
    }


    public function destroy($id)
    {
        // $this->authorize('isAdmin');

        $subcategory = Subcategory::findOrFail($id);
        // delete the category

        $subcategory->delete();

        return ['message' => 'SubCategory is Deleted'];
    }



    public function search(){

        if ($search = \Request::get('q')) {
            $subcategories = SubCategory::where(function($query) use ($search){
                $query->where('subcategory_name','LIKE',"%$search%")
                        ->orWhere('department_id','LIKE',"%$search%")
                        ->orWhere('category_id','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $subcategories = SubCategory::latest()->paginate(5);
        }

        return $subcategories;

    }

    public function getDepartment()
    {
        $departments = Department::all();
        return $departments;
    }

    public function getCategory($id)
    {
        $categories = DB::table('categories')
                    ->where('department_id','=',$id)
                    ->get();
        return $categories;
    }
}
