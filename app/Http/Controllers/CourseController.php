<?php

namespace App\Http\Controllers;

use App\Course;
use App\Session;
use Illuminate\Http\Request;

class CourseController extends Controller
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
            "items" => Course::where('session_id', $current_session)->get()
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
        $formData = $request->validate([
            "code" => "required",
            "title" => "required",
            "credit_hours" => "required|numeric"
        ]);
        $current_session = Session::where("active", 1)->first()->id;
        $item = new Course();
        $item->code = $request->code;
        $item->title = $request->title;
        $item->credit_hours = $request->credit_hours;
        $item->session_id = $current_session;
        $item->save();

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
        $formData = $request->validate([
            "code" => "required",
            "title" => "required",
            "credit_hours" => "required|numeric"
        ]);


        $item = Course::find($id);
        $item->code = $request->code;
        $item->title = $request->title;
        $item->credit_hours = $request->credit_hours;
        $item->save();

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
        Course::where('id', $id)->delete();

        return response()->json([], 200);
    }

    public function importPreviousCourses(Request $request)
    {
        $validatedData = $request->validate([
            'session' => 'required'
        ]);

        $current_session = Session::where("id", $request->session)->first();

        $session = Session::where('created_at', '<', $current_session->created_at)->first();

        // dd(['previous_session' => $session]);

        if(!$session)
        {
            return response(['message' => 'This is the first session in the system.'], 400);
        }

        $courses = Course::where('session_id', $session->id)->get();

        foreach ($courses as $course) {
            $newCourse = $course->replicate();
            $newCourse->session_id = $current_session->id;

            $newCourse->save();
        }
        return response(["message" => "Courses imported from previous session."]);
    }
}
