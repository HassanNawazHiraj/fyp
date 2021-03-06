<?php

namespace App\Http\Controllers;

use App\TeacherCourse;
use App\Session;
use Illuminate\Http\Request;
use Auth;

class TeacherCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->session) {
            $current_session = $request->session;
        } else {
            $current_session = Session::where("active", 1)->first()->id;
        }
        //show all courses of the teacher
        $id = Auth::user()->id;
        $courses = TeacherCourse::with(["teacher","class_courses.course", "class_courses.folder", "class_courses.class.batch", "class_courses.class.program"])
        ->where("teacher_id", $id)->where("session_id", $current_session)->get();
        return response(["items" => $courses]);

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        if ($request->session_id) {
            $current_session = $request->session_id;
        } else {
            $current_session = Session::where("active", 1)->first()->id;
        }
        //id = teacher_id in users
        $courses = json_decode($request->selected_courses);


        //get all existing items and delete them
        TeacherCourse::where([
            "teacher_id" => $id,
            "session_id" => $current_session
        ])->delete();

        //add new items
        $insert_array = [];
        foreach ($courses as $course) {
            if ($course->type == 'lab') {
                //remove "l" from end
                $course->id = substr($course->id, 0, strlen($course->id) - 1);
            }
            array_push($insert_array, [
                "teacher_id" => $id,
                "class_course_id" => $course->id,
                "course_type" => $course->type,
                "session_id" => $current_session
            ]);
        }
        TeacherCourse::insert($insert_array);

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
        //
    }
}
