<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClassModel;
use App\Program;
use App\Batch;
use Auth;
class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!in_array("user_view",Auth::user()->types[0]["permissions"])) {
            return response('Unauthenticated.', 401);
        }
        return response()->json([
            "items" => ClassModel::with(['batch', 'program'])->get(),
            'programs' => Program::get(),
            'batches' => Batch::get()
            //"user" => Auth::user()->types[0]["permissions"]
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
            "batch_id" => "required",
            "program_id" => "required",
            "section" => "required"
        ]);

        $c = new ClassModel();
        $c->batch_id = $request->batch_id;
        $c->program_id = $request->program_id;
        $c->section = $request->section;
        $c->save();
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
        $request->validate([
            "batch_id" => "required",
            "program_id" => "required",
            "section" => "required"
        ]);

        $c = ClassModel::find($id);
        $c->batch_id = $request->batch_id;
        $c->program_id = $request->program_id;
        $c->section = $request->section;
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
        ClassModel::where('id', $id)->delete();
    }
}
