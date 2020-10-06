<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClassCourses;
use App\ClassModel;
use App\Course;
use App\Session;
use Illuminate\Support\Facades\File;

class ClassCoursesController extends Controller
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
        return response()->json([
            "items" => ClassCourses::with(['class', 'class.batch', 'class.program', 'course'])->where('session_id', $current_session)->get(),
            'classes' => ClassModel::with(['batch', 'program'])->get(),
            'courses' => Course::get()
        ]);
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
        $request->validate([
            "class_id" => "required",
            "course_id" => "required",
            "has_lab" => "required"
        ]);
        if($request->session_id) {
            $current_session = $request->session_id;
        } else {
            $current_session = Session::where("active", 1)->first()->id;
        }
        $folder_name = "s" . $current_session . "-class" . $request->class_id . "-course" . $request->course_id . "-" .sha1(time());

        File::copyDirectory("../storage/app/theory", "../storage/app/data/" . $folder_name);
        if($request->has_lab) {
            File::copyDirectory("../storage/app/lab", "../storage/app/data/" . $folder_name . "-l");
        }


        $c = new ClassCourses();
        $c->class_id = $request->class_id;
        $c->course_id = $request->course_id;
        $c->has_lab = $request->has_lab;
        $c->session_id = $current_session;
        $c->folder_name = $folder_name;
        $c->save();
        return response()->json($request, 200);
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
        $request->validate([
            "class_id" => "required",
            "course_id" => "required",
            "has_lab" => "required"
        ]);

        $c = ClassCourses::find($id);
        $c->class_id = $request->class_id;
        $c->course_id = $request->course_id;
        $c->has_lab = $request->has_lab;
        $c->save();
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
        ClassCourses::where('id', $id)->delete();
    }
}
