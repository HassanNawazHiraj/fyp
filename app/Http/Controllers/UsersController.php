<?php

namespace App\Http\Controllers;

use App\User;
use App\UserType;
use App\UserTypeRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            "items" => User::with(['types'])->get()
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

        $types = explode(",",$request->user_types);
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
            "name" => "required",
        ]);

        $item = FormField::find($id);
        $item->name = $request->name;
        $item->field_type = $request->field_type;
        $item->save();

        return response()->json($request, 200);

        $formData = $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required",
            "user_types" => "required"
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        // $user->save();

        $types = explode(",",$request->user_types);
        foreach ($types as $type) {
            // $userTypeRelation = new UserTypeRelation::where("user_id", $id);
            // $userTypeRelation->user_id = $user->id;
            // $userTypeRelation->user_type_id = $type;
            // $userTypeRelation->save();
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
        //
    }

    public function getUserTypes()
    {
        return response()->json([
            "items" => UserType::get()
        ]);
    }
}
