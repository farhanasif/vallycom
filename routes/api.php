<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'user' => 'API\UserController',
    'category' => 'API\CategoryController',
    'subcategory' => 'API\SubCategoryController'
]);

//-------------profile route----------------//
Route::get('profile', 'API\UserController@profile');
Route::put('profile', 'API\UserController@updateProfile');
Route::get('findUser', 'API\UserController@search');

//-------------products route----------------//
Route::get('products','API\ProductController@index');
Route::get('products/{id}','API\ProductController@show');


//------------- Departments route ----------------//
Route::get('department', 'API\DepartmentController@index');
Route::post('store-department', 'API\DepartmentController@storeDepartment');
Route::put('update-department/{id}', 'API\DepartmentController@updateDepartment');
Route::get('findDepartment', 'API\DepartmentController@search');
Route::delete('delete-department/{id}', 'API\DepartmentController@deleteDepartment');

//------------- Category route ----------------//
Route::get('findCategory', 'API\CategoryController@search');
Route::get('getDepartment', 'API\CategoryController@getDepartment');

//-------------SubCategory route ----------------//
Route::get('findsubcategory', 'API\SubCategoryController@search');
Route::get('getDepartment', 'API\SubCategoryController@getDepartment');
Route::get('getCategory/{id}', 'API\SubCategoryController@getCategory');
