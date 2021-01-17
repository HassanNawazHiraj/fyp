<?php

namespace App\Http\Controllers;

use App\Folder;
use Auth;
use Illuminate\Http\Request;
use stdClass;
use Storage;
use ZipArchive;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($name, $path)
    {
        $full_path = $name;
        $path = json_decode($path);

        if (isset($path) && is_array($path) && count($path) > 0) {
            foreach ($path as $p) {
                $full_path .= "/" . $p;
            }
        }

        $files = Storage::disk('local')->files("data/" . $full_path);
        $folders = Storage::disk('local')->directories("data/" . $full_path);

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
        $path = json_decode($path);

        if (isset($path) && is_array($path) && count($path) > 0) {
            foreach ($path as $p) {
                $full_path .= "/" . $p;
            }
        }

        Storage::disk("local")->rename("data/" . $full_path . "/" . $old, "data/" . $full_path . "/" . $new);
    }

    public function folder($name, Request $request)
    {
        $path = $request->path;
        $old = $request->name;

        $full_path = $name;
        $path = json_decode($path);

        if (isset($path) && is_array($path) && count($path) > 0) {
            foreach ($path as $p) {
                $full_path .= "/" . $p;
            }
        }

        Storage::disk("local")->makeDirectory("data/" . $full_path . "/" . $old);
    }

    public function delete($name, Request $request)
    {
        $path = $request->path;
        $file_name = $request->file_name;

        $full_path = $name;
        $path = json_decode($path);

        if (isset($path) && is_array($path) && count($path) > 0) {
            foreach ($path as $p) {
                $full_path .= "/" . $p;
            }
        }

        $to_delete = storage_path("app/") . "data/" . $full_path . "/" . $file_name;
        if (is_dir($to_delete)) {

            //folder
            return $this->delTree($to_delete);
        } else {
            //files
            return unlink($to_delete);
        }
    }

    public function delTree($to_delete)
    {
        $files = array_diff(scandir($to_delete), array('.', '..'));

        foreach ($files as $file) {
            (is_dir("$to_delete/$file")) ? $this->delTree("$to_delete/$file") : unlink("$to_delete/$file");
        }

        return rmdir($to_delete);
    }

    public function download($name, $path, $file_name, Request $r)
    {
        $full_path = $name;
        $path = json_decode($path);

        if (isset($path) && is_array($path) && count($path) > 0) {
            foreach ($path as $p) {
                $full_path .= "/" . $p;
            }
        }

        return Storage::download("data/" . $full_path . "/" . $file_name);
    }

    public function upload(Request $request)
    {
        $full_path = "data/" . $request->folder;
        $path = json_decode($request->path);
        if (isset($path) && is_array($path) && count($path) > 0) {
            foreach ($path as $p) {
                $full_path .= "/" . $p;
            }
        }
        //dd($full_path);
        //store file
        $request->file->storeAs($full_path, $request->filename);
        return response("success", 200);
    }

    public function moveFile(Request $request)
    {
        $main_folder = $request->main_folder;
        $to_path = $request->to_path;
        $from_path = $request->from_path;
        $file_name = $request->file;

        $to_full_path = $main_folder;
        $to_path_array = json_decode($to_path);
        if (isset($to_path_array) && is_array($to_path_array) && count($to_path_array) > 0) {
            foreach ($to_path_array as $p) {
                $to_full_path .= "/" . $p;
            }
        }

        $from_full_path = $main_folder;
        $from_path_array = json_decode($from_path);
        if (isset($from_path_array) && is_array($from_path_array) && count($from_path_array) > 0) {
            foreach ($from_path_array as $p) {
                $from_full_path .= "/" . $p;
            }
        }
        Storage::move("data/" . $from_full_path . "/" . $file_name, "data/" . $to_full_path . "/" . $file_name);
        return $request->all();
    }

    public function moveFolder(Request $request) {
        $main_folder = $request->main_folder;
        $to_path = $request->to_path;
        $from_path = $request->from_path;
        $folder_name = $request->folder;

        $to_full_path = $main_folder;
        $to_path_array = json_decode($to_path);
        if (isset($to_path_array) && is_array($to_path_array) && count($to_path_array) > 0) {
            foreach ($to_path_array as $p) {
                $to_full_path .= "/" . $p;
            }
        }

        $from_full_path = $main_folder;
        $from_path_array = json_decode($from_path);
        if (isset($from_path_array) && is_array($from_path_array) && count($from_path_array) > 0) {
            foreach ($from_path_array as $p) {
                $from_full_path .= "/" . $p;
            }
        }
        Storage::move("data/" . $from_full_path . "/" . $folder_name, "data/" . $to_full_path . "/" . $folder_name);
        return $request->all();
    }

    public function zip($name, $path, $folder)
    {

        $full_path = "data/" . $name;
        $path = json_decode($path);

        if (isset($path) && is_array($path) && count($path) > 0) {
            foreach ($path as $p) {
                $full_path .= "/" . $p;
            }
        }
        $full_path .= "/" . $folder;
        $files = Storage::allFiles($full_path);
        if(count($files) < 1) {
            return response()->download("empty.zip", $folder . ".zip");
        }
        $files_full_path = [];
        for ($i = 0; $i < count($files); $i++) {
            $files_full_path[$i] = storage_path() . "/app/" . $files[$i];
            $files[$i] = explode($folder. "/", $files[$i])[1];
        }

        $zip = new ZipArchive;
        $name = rand(1,1000). "zip";
        if ($zip->open($name, (ZipArchive::CREATE | ZipArchive::OVERWRITE)) === TRUE) {
            // Add File in ZipArchive
            for($i=0; $i< count($files);$i++) {
                $zip->addFile($files_full_path[$i], $files[$i]);
            }

            // Close ZipArchive
            $zip->close();
        }


        return response()->download($name, $folder . ".zip");
    }

    public function getChecklist($name, Request $request)
    {
        // Read File
        $jsonString = file_get_contents(base_path('storage/app/data/'. $name .'.json'));
        $data = json_decode($jsonString, true);

        return response($data, 200);
    }

    public function updateChecklist($name, Request $request)
    {
        $request->validate([
            "checklist" => "required"
        ]);

        // Write File
        $newJsonString = json_encode($request->checklist, JSON_PRETTY_PRINT);
        file_put_contents(base_path('storage/app/data/'. $name .'.json'), stripslashes($newJsonString));

        return response()->json($request, 200);
    }

    public function getChecklistTemplate(Request $request)
    {
        // Read File
        $jsonString = file_get_contents(base_path('storage/app/checklist.json'));
        $data = json_decode($jsonString, true);

        return response($data, 200);
    }

    public function updateChecklistTemplate(Request $request)
    {
        $request->validate([
            "checklist" => "required"
        ]);

        $newJsonString = json_encode($request->checklist, JSON_PRETTY_PRINT);
        file_put_contents(base_path('storage/app/checklist.json'), stripslashes($newJsonString));

        return response()->json($request, 200);
    }

    public function updateChecklistFromTemplate() {
        $files = Storage::disk('local')->files("theory/");
        $folders = Storage::disk('local')->directories("theory/");
        $all = array_merge($files, $folders);
        for($i=0; $i<count($all); $i++) {
            $all[$i] = str_replace("theory/", "", $all[$i]);
        }
        sort($all);
        $checklist = [];
        foreach($all as $item) {
            $checklist_item = new stdClass();
            $checklist_item->label = $item;
            $checklist_item->value = false;
            array_push($checklist, $checklist_item);
        }

        $newJsonString = json_encode($checklist, JSON_PRETTY_PRINT);
        file_put_contents(base_path('storage/app/checklist.json'), stripslashes($newJsonString));

        return response()->json(["success"], 200);
    }
}
