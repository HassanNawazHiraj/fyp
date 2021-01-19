<?php

namespace App\Http\Controllers;

use App\ClassCourseChat;
use App\ClassCourses;
use Illuminate\Http\Request;

class ClassCourseChatController extends Controller
{

    public function get($id)
    {
        $lab = false;
        if (substr($id, -2) == "-l") {
            $id = str_replace("-l", "", $id);
            $lab = true;
        }
        $class = ClassCourses::where("folder_name", $id)->first();
        //get teacher

        $chat = ClassCourseChat::where("class_course_id", $class->id)->with(["user", "class_course.teacherCourse.teacher", "class_course.course.tai"]);
        $chat->where("lab_chat", $lab);

        return $chat->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $lab = false;
        if (substr($id, -2) == "-l") {
            $id = str_replace("-l", "", $id);
            $lab = true;
        }

        $class = ClassCourses::where("folder_name", $id)->first();
        $class_course_id = $class->id;

        $chat = new ClassCourseChat();
        $chat->user_id = $request->user_id;
        $chat->class_course_id = $class_course_id;
        $chat->user_type = $request->user_type;
        $chat->message = $request->message;
        $chat->lab_chat = $lab;

        $chat->save();
    }
}
