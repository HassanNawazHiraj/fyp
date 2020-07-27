<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
use App\TaiCourse;
use Auth;
use Illuminate\Http\Request;

class TAICourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            "items" => Course::with(['tai'])->get()
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

    public function assigned() {
        $tai_id = Auth::user()->id;
        $items = TaiCourse::where("tai_id",$tai_id)->with(['teachers'])->get();
        return response()->json([
            "items" => $items
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
        $request->validate([
            "course_id" => "required",
            "tai_id" => "required"
        ]);

        $taiCourse = new TaiCourse();
        $taiCourse->course_id = $request->course_id;
        $taiCourse->tai_id = $request->tai_id;
        $taiCourse->save();
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
            "course_id" => "required",
            "tai_id" => "required"
        ]);

        $taiCourse = TaiCourse::where('course_id', $id)->first();
        $taiCourse->course_id = $request->course_id;
        $taiCourse->tai_id = $request->tai_id;
        $taiCourse->save();
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
