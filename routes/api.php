<?php

use App\Http\Controllers\DepartmentController;
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

Route::get('profile', 'API\UserController@profile');
Route::put('profile', 'API\UserController@updateProfile');
Route::get('findUser', 'API\UserController@search');
//-------------products route----------------//
Route::get('products','API\ProductController@index');


//-------------Departments route----------------//
Route::get('department', 'API\DepartmentController@index');
Route::post('store-department', 'API\DepartmentController@storeDepartment');
Route::post('edit-department/{id}', 'API\DepartmentController@editDepartment');
Route::post('update-department/{id}', 'API\DepartmentController@updateDepartment');
Route::delete('delete-department/{id}', 'API\DepartmentController@deleteDepartment');

/********************* Category route **************************/ 
// Route::apiResources([
    
// ]);

Route::get('getDepartment', 'API\CategoryController@getDepartment');
/**************************Arif Khan ****************************/

/********************* SubCategory route **************************/ 
// Route::apiResources([
    
// ]);

Route::get('getDepartment', 'API\SubCategoryController@getDepartment');
Route::get('getCategory/{id}', 'API\SubCategoryController@getCategory');
/**************************Arif Khan ****************************/