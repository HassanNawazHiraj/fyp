<?php

namespace App\Http\Controllers;

use App\User;
use App\UserType;
use App\UserTypeRelation;
use App\ClassCourses;
use App\Session;
use App\TeacherCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!in_array("user_view", Auth::user()->types[0]["permissions"])) {
            return response('Unauthenticated.', 401);
        }


        $users = User::with(['types' => function ($q) {
            $q->where('name', 'not like', 'Super admin');
        }, 'types'])->get();

        return response()->json([
            "items" => $users,
            //"user" => Auth::user()->types[0]["permissions"]
        ]);
    }

    public function getUser(Request $request)
    {
        $types = $request->user()->types;
        $permissions = [];
        foreach ($types as $type) {
            foreach ($type->permissions as $permission) {
                if (!in_array($permission, $permissions))
                    array_push($permissions, $permission);
            }
        }
        return [
            "types" => json_encode($request->user()->types),
            "permissions" => $permissions,
            "user" => $request->user()
        ];
    }

    public function getTeachers(Request $request)
    {
        if ($request->session) {
            $current_session = $request->session;
        } else {
            $current_session = Session::where("active", 1)->first()->id;
        }
        // $u = User::class_courses()->where("class_courses.session_id", $current_session)
        // ->get();
        // return response()->json($u);
        // die();
        $users = User::whereHas('types', function ($q) {
            $q->where('name', 'like', 'teacher');
        })->with(['types', 'course_type'])->get();
        foreach ($users as $user) {
            $courses = TeacherCourse::where("session_id", $current_session)->where("teacher_id", $user->id)
                ->with(['class_courses.course', 'class_courses.class.batch', 'class_courses.class.program'])
                ->get();
            $user->class_courses = $courses;
        }

        // ->with(['types', 'class_courses.course', 'class_courses.class.batch', 'class_courses.class.program', "course_type"])
        // ->get();

        return response()->json([
            "items" => $users,
            "courses" => ClassCourses::with(['class', 'class.batch', 'class.program', 'course'])->where('session_id', $current_session)->get()
        ]);
    }

    public function getTAIs()
    {
        $users = User::whereHas('types', function ($q) {
            $q->where('name', 'like', 'Teaching area in-charge');
        })->get();

        return response()->json([
            "items" => $users,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $formData = $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required",
            "user_types" => "required"
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $types = explode(",", $request->user_types);
        foreach ($types as $type) {
            // you will get id in $type now
            $userTypeRelation = new UserTypeRelation();
            $userTypeRelation->user_id = $user->id;
            $userTypeRelation->user_type_id = $type;
            $userTypeRelation->save();
        }
        return response()->json($request, 200);
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
        $formData = $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "user_types" => "required"
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password != null || $request->password != "") {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $types = explode(",", $request->user_types);
        UserTypeRelation::where('user_id', $id)->forceDelete();
        foreach ($types as $type) {
            if((UserTypeRelation::where('user_id', $id)->where('user_type_id', $type)->get())->count() < 1) {
                $userTypeRelation = new UserTypeRelation();
                $userTypeRelation->user_id = $id;
                $userTypeRelation->user_type_id = $type;
                $userTypeRelation->save();
            }
        }

        return response()->json($request, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();
        UserTypeRelation::where('user_id', $id)->delete();
    }

    public function getUserTypes()
    {
        return response()->json([
            "items" => UserType::get()
        ]);
    }

    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        $oldPassword = $request->user()->password;

        if (!Hash::check($request->old_password, $oldPassword)) {
            return response(['message' => 'The given data was invalid.', 'errors' => ["old_password" => ["The old password does not match."]]], 400);
        } else if ($request->old_password == $request->new_password) {
            return response(['message' => 'The given data was invalid.', 'errors' => ["new_password" => ["New password cannot be same as old password."]]], 400);
        }

        $user = User::find($request->user()->id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response(['message' => 'Password Changed'], 200);
    }

    public function changeName(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $oldName = $request->user()->name;

        if($oldName == $request->name){
            return response(['message' => 'The given data was invalid.', 'errors' => ["name" => ["New name cannot be same as old name."]]], 400);
        }

        $user = User::find($request->user()->id);
        $user->name = $request->name;
        $user->save();

        return response(['message' => 'Name Changed'], 200);
    }
}
