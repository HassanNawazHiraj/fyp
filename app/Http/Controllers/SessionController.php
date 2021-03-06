<?php

namespace App\Http\Controllers;

use App\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $session = Session::orderBy('active', 'DESC');
        // if($request->session) {
        //     $session->where
        // }
        return response()->json([
            "items" => Session::orderBy('active', 'DESC')->get()
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
            "season" => "required",
            "year" => "required|integer",
            "active" => "required",
            "status" => "required"
        ]);

        if ($request->active) {
            Session::where("active", 1)->update(['active' => 0]);
        }

        $item = new Session();
        $item->season = $request->season;
        $item->year = $request->year;
        $item->active = $request->active;
        $item->status = $request->status;
        $item->save();

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
            "season" => "required",
            "year" => "required",
            "active" => "required",
            "status" => "required"
        ]);

        if ($request->active) {
            Session::where("active", 1)->update(['active' => 0]);
        }


        $item = Session::find($id);

        if ($item->active && !$request->active) {
            return response()->json(
                [
                    "message" => "The given data was invalid.",
                    "errors" => [
                        "Active" => ["Please set any other session active. There needs to be atleast one active session."],
                    ]
                ]
           , 422);
        }

        $item->season = $request->season;
        $item->year = $request->year;
        $item->active = $request->active;
        $item->status = $request->status;
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
        Session::where('id', $id)->delete();

        return response()->json([], 200);
    }
}
