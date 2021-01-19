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

Route::post("login", "LoginController@login");

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource("formFields", "FormFieldController");
    Route::post("formFields/order", "FormFieldController@order");

    Route::resource("users", "UsersController");
    Route::post("user/change-password", "UsersController@changePassword");
    Route::post("user/change-name", "UsersController@changeName");
    Route::get("user/types", "UsersController@getUserTypes");
    Route::get("user/teachers", "UsersController@getTeachers");
    Route::get("user/tais", "UsersController@getTAIs");


    Route::resource("roles", "UserRolesController");

    Route::resource("/role/check/permission", "UserRolesController@checkPermission");

    Route::resource("batch", "BatchController");

    Route::resource("program", "ProgramController");

    Route::resource("course", "CourseController");
    Route::get("course-import-previous", "CourseController@importPreviousCourses");

    Route::resource("class", "ClassController");

    Route::resource("class_courses", "ClassCoursesController");

    Route::resource("teacher_courses", "TeacherCourseController");

    Route::resource("tai_courses", "TAICourseController");
    Route::get("assigned_courses", "TAICourseController@assigned");
    Route::get("tai_course_folders", "TAICourseController@getCourses");

    Route::resource("session", "SessionController");


    //show links to folders for courses of teacher
    Route::get("teacher/courses", "TeacherCourseController@index");

    //files & folders
    Route::post("/folder/{name}/rename", "FolderController@rename");
    Route::post("/folder/{name}/delete", "FolderController@delete");
    Route::post("/folder/{name}/new", "FolderController@folder");
    Route::post("/folder/upload", "FolderController@upload");
    Route::post("/folder/file/move", "FolderController@moveFile");
    Route::post("/folder/move", "FolderController@moveFolder");
    Route::get("/folder/{name}/checklist", "FolderController@getChecklist");
    Route::post("/folder/{name}/checklist", "FolderController@updateChecklist");
    Route::get("/folder/{name}/{path}", "FolderController@index");
    Route::get("/folder/checklist_template", "FolderController@getChecklistTemplate");
    Route::post("/folder/checklist_template", "FolderController@updateChecklistTemplate");
    Route::get("/folder/update_checklist_from_template", "FolderController@updateChecklistFromTemplate");

    //chat
    Route::get("/chat/get/{id}", "ClassCourseChatController@get");
    Route::post("/chat/store/{id}", "ClassCourseChatController@store");

    //dashboard
    Route::get("/dashboard", "DashboardController@index");


});
Route::get("/folder/{name}/{path}/{file_name}/download", "FolderController@download");
Route::get("/folder/{name}/{path}/{folder}/zip", "FolderController@zip");
