<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    return [
        "type" => $request->user()->types[0],
        "user" => $request->user()
];
});
Route::group(['middleware' => 'auth:api'], function () {
    Route::resource("formFields", "FormFieldController");
    Route::post("formFields/order", "FormFieldController@order");

    Route::resource("users", "UsersController");
    Route::get("user/types", "UsersController@getUserTypes");

    Route::resource("roles", "UserRolesController");
    Route::resource("/role/check/permission", "UserRolesController@checkPermission");

    Route::resource("batch", "BatchController");

    Route::resource("program", "ProgramController");

    Route::resource("course", "CourseController");

    Route::resource("class", "ClassController");

    Route::resource("class_courses", "ClassCoursesController");
});


