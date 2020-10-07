<?php

namespace App\Http\Controllers;

use App\Folder;
use Auth;
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
        for ($f = 0; $f < count($folders); $f++) {
            $split = explode("/", $folders[$f]);
            $folders[$f] = $split[count($split) - 1];
        }
        for ($f = 0; $f < count($files); $f++) {
            $split = explode("/", $files[$f]);
            $files[$f] = $split[count($split) - 1];
        }

        return response([
            "files" => $files,
            "folders" => $folders
        ], 200);
    }

    public function rename($name, Request $request)
    {
        $path = $request->path;
        $old = $request->old_name;
        $new = $request->new_name;

        $full_path = $name;
        if (isset($path) && is_array($path) && count($path) > 0) {
            $full_path = "";
            foreach ($path as $p) {
                $full_path .= $p . "/";
            }
            $full_path .= $name;
        }

        Storage::disk("local")->rename("data/" . $full_path . "/" . $old, "data/" . $full_path . "/" . $new);
    }

    public function delete($name, Request $request)
    {
        $path = $request->path;
        $file_name = $request->file_name;

        $full_path = $name;

        if (isset($path) && is_array($path) && count($path) > 0) {
            $full_path = "";
            foreach ($path as $p) {
                $full_path .= $p . "/";
            }
            $full_path .= $name;
        }

        $to_delete = storage_path("app\\") . "data\\" . $full_path . "\\" . $file_name;
        if (is_dir($to_delete)) {

            //folder
            $files = array_diff(scandir($to_delete), array('.', '..'));

            foreach ($files as $file) {
                (is_dir("$to_delete/$file")) ? delTree("$to_delete/$file") : unlink("$to_delete/$file");
            }

            return rmdir($to_delete);
        } else {
            //files
            return unlink($to_delete);
        }
    }

    public function download($name, $path, $file_name, Request $r)
    {
        $full_path = $name;

        if (isset($path) && is_array($path) && count($path) > 0) {

            foreach ($path as $p) {
                $full_path .= $p . "/";
            }

        }

        return Storage::download("data/" . $full_path . "/" . $file_name);


    }
}
