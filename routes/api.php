<?php

use App\Http\Controllers\CourseController;
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

Route::middleware('auth:api')->get('/user', "UsersController@getUser");

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource("formFields", "FormFieldController");
    Route::post("formFields/order", "FormFieldController@order");

    Route::resource("users", "UsersController");
    Route::get("user/types", "UsersController@getUserTypes");
    Route::get("user/teachers", "UsersController@getTeachers");
    Route::get("user/tais", "UsersController@getTAIs");


    Route::resource("roles", "UserRolesController");

    Route::resource("/role/check/permission", "UserRolesController@checkPermission");

    Route::resource("batch", "BatchController");

    Route::resource("program", "ProgramController");

    Route::resource("course", "CourseController");
    // Route::get("tai/course", "CourseController@getTai");

    Route::resource("class", "ClassController");

    Route::resource("class_courses", "ClassCoursesController");

    Route::resource("teacher_courses", "TeacherCourseController");

    Route::resource("tai_courses", "TAICourseController");
    Route::get("assigned_courses", "TAICourseController@assigned");

    Route::resource("session", "SessionController");


    //show links to folders for courses of teacher
    Route::get("teacher/courses", "TeacherCourseController@index");

    //files & folders
    Route::get("/folder/{name}/{path}", "FolderController@index");
    Route::post("/folder/{name}/rename", "FolderController@rename");
    Route::post("/folder/{name}/delete", "FolderController@delete");
    Route::post("/folder/{name}/new", "FolderController@folder");
    Route::post("/folder/upload", "FolderController@upload");
    Route::post("/folder/file/move", "FolderController@moveFile");

});
Route::get("/folder/{name}/{path}/{file_name}/download", "FolderController@download");
