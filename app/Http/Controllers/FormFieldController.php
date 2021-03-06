<?php

namespace App\Http\Controllers;

use App\FormField;
use Illuminate\Http\Request;

class FormFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            "items" => FormFIeld::orderBy('sort_order')->get()
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
        ]);


        $item = new FormField();
        $item->name = $request->name;
        $item->field_type = $request->field_type;
        $item->save();

        return response()->json($request, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FormField  $formField
     * @return \Illuminate\Http\Response
     */
    public function show(FormField $formField)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormField  $formField
     * @return \Illuminate\Http\Response
     */
    public function edit(FormField $formField)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormField  $formField
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormField  $formField
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FormField::where('id', $id)->delete();

        return response()->json([], 200);
    }

    public function order(Request $request)
    {
        //return response()->json($request->categories[0]["id"], 200);
        $items = $request->items;
        for($i=0; $i<count($items); $i++) {
            $item = FormField::find($items[$i]["id"]);
            $item->sort_order = $i;
            $item->save();
        }
        return response()->json("success", 200);

    }
}
