<?php

namespace App\Http\Controllers;

use App\Folder;
use Illuminate\Http\Request;
use Storage;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($name)
    {

        $files = Storage::disk('local')->files("data/" . $name);
        $folders = Storage::disk('local')->directories("data/" . $name);

        //get rid of other paths in both files and folder
        for($f =0; $f < count($folders); $f++) {
            $split = explode("/", $folders[$f]);
            $folders[$f] = $split[count($split)-1];
        }
        for($f =0; $f < count($files); $f++) {
            $split = explode("/", $files[$f]);
            $files[$f] = $split[count($split)-1];
        }

        return response([
            "files" => $files,
            "folders" => $folders
        ], 200);
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
     * @param  \App\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function show(Folder $folder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Folder $folder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folder $folder)
    {
        //
    }
}
