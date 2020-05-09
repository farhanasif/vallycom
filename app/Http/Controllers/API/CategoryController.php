<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Support\Facades\Hash;

class CategoryController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::latest()->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'departmentId' => 'required|string|max:11',
            'categoryName' => 'required|string|max:191|unique:categories',
        ]);

        return Category::create([
            'departmentId' => $request['departmentId'],
            'categoryName' => $request['categoryName'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $this->validate($request,[
            'departmentId' => 'required|string|max:11',
            'categoryName' => 'required|string|max:191|unique:categories',
        ]);

        $category->update($request->all());
        return ['message' => 'Updated the category info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $category = Category::findOrFail($id);
        // delete the category

        $category->delete();

        return ['message' => 'Category is Deleted'];
    }

  

    public function search(){

        if ($search = \Request::get('q')) {
            $categories = Category::where(function($query) use ($search){
                $query->where('categoryName','LIKE',"%$search%")
                        ->orWhere('departmentId','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $categories = Category::latest()->paginate(5);
        }

        return $categories;

    }
}
