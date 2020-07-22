<?php

namespace App\Http\Controllers;

use App\TeacherCourse;
use Illuminate\Http\Request;

class TeacherCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //id = teacher_id in users
        $courses = json_decode($request->selected_courses);


        //get all existing items and delete them
        TeacherCourse::where("teacher_id", $id)->delete();

        //add new items
        $insert_array = [];
        foreach($courses as $course) {
            if($course->type == 'lab') {
                //remove "l" from end
                $course->id = substr($course->id, 0, strlen($course->id)-1);
            }
            array_push($insert_array,[
                "teacher_id" => $id,
                "class_course_id" => $course->id,
                "course_type" => $course->type
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
