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
    'user' => 'API\UserController'
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

// Route::apiResources([
//     'categories' => 'API\CategoryController'
// ]);
Route::get('categories', 'API\CategoryController@index');
Route::post('store-department', 'API\CategoryController@store');
Route::post('edit-department/{id}', 'API\CategoryController@edit');
Route::post('update-department/{id}', 'API\CategoryController@update');
Route::delete('delete-department/{id}', 'API\CategoryController@delete');