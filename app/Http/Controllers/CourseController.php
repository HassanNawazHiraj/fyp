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
    public function index()
    {
        $current_session = Session::where("active", 1)->first();
        return response()->json([
            "items" => Course::where('session_id', $current_session->id)->get()
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

        $item = new Course();
        $item->code = $request->code;
        $item->title = $request->title;
        $item->credit_hours = $request->credit_hours;
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
}
