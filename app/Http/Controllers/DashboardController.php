<?php

namespace App\Http\Controllers;

use App\Session;
use App\TaiCourse;
use App\TeacherCourse;
use App\UserTypeRelation;
use Illuminate\Http\Request;
use Auth;
use stdClass;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        //get current session
        if ($request->session) {
            $current_session = $request->session;
        } else {
            $current_session = Session::where("active", 1)->first()->id;
        }

        //get roles
        $roles = UserTypeRelation::where("user_id", $user->id)->with("UserType")->get();

        //check if teacher, tai or both
        $teacher = false;
        $tai = false;
        foreach ($roles as $role) {

            if ($role->UserType->name == "Teacher") {
                $teacher = true;
            }

            if ($role->UserType->name == "Teaching area in-charge") {
                $tai = true;
            }
        }

        $result = new stdClass();
        //teacher
        if ($teacher) {
            $number_of_completed_courses = 0;
            $courses = TeacherCourse::where("teacher_id", $user->id)->where("session_id", $current_session)->with(["class_courses.course"])->get();
            // dd(["teacher courses" => count($courses)]);
            if (count($courses)) {
                $checklist_total = 0;
                $checklist_checked = 0;
                foreach ($courses as $course) {
                    $folder_name = $course->class_courses->folder_name;
                    if ($course->course_type == "lab") $folder_name .= "-l";
                    $json = file_get_contents(base_path("storage/app/data/" . $folder_name . ".json"));
                    $checklist = json_decode($json);
                    $total = count($checklist);
                    $checklist_total += $total;
                    $checked = 0;
                    foreach ($checklist as $item) {
                        if ($item->value) $checked++;
                    }
                    $checklist_checked += $checked;
                    if ($checklist_total == $checked) {
                        $number_of_completed_courses++;
                    }
                }
                if ($checklist_total == 0) {
                    $completed_percentage = 0;
                } else {
                    $completed_percentage = $checklist_checked / $checklist_total * 100;
                }
                $result->teacher = [
                    "checklist_total" => $checklist_total,
                    "checklist_checked" => $checklist_checked,
                    "completed_courses" => $number_of_completed_courses,
                    "checklist_percentage" => round($completed_percentage)
                ];
            }
        }

        //tai
        if ($tai) {
            $number_of_completed_courses = 0;
            $courses = TaiCourse::where("tai_id", $request->user()->id)->with(['classes', 'classes.course', 'classes.class', 'classes.class.batch', 'classes.class.program', 'classes.teacherCourse.teacher',])
        ->where("session_id", $current_session)->get();

            if (count($courses)) {
                $checklist_total = 0;
                $checklist_checked = 0;

                $new_courses = [];

                foreach($courses as $class_course)
                {
                    foreach($class_course->classes as $class)
                    {
                        $teacherCourses = $class->teacherCourse;
                        foreach($teacherCourses as $t)
                        {
                            $obj = [
                                "id" => $class->id,
                                "folder_name" => $class->folder_name,
                                "class" => $class->class,
                                "course" => $class->course,
                                "created_at" => $class->created_at,
                                "teacher" => $t->teacher,
                                "course_type" => $t->course_type
                            ];
                            array_push($new_courses, $obj);
                        }
                    }
                }

                foreach ($new_courses as $course) {
                    $folder_name = $course['folder_name'];
                    if ($course['course_type'] == "lab") $folder_name .= "-l";
                    $json = file_get_contents(base_path("storage/app/data/" . $folder_name . ".json"));
                    $checklist = json_decode($json);
                    $total = count($checklist);
                    $checklist_total += $total;
                    $checked = 0;
                    foreach ($checklist as $item) {
                        if ($item->value) $checked++;
                    }
                    $checklist_checked += $checked;
                    if ($checklist_total == $checked) {
                        $number_of_completed_courses++;
                    }
                }
                if ($checklist_total == 0) {
                    $completed_percentage = 0;
                } else {
                    $completed_percentage = $checklist_checked / $checklist_total * 100;
                }
                $result->tai = [
                    "checklist_total" => $checklist_total,
                    "checklist_checked" => $checklist_checked,
                    "completed_courses" => $number_of_completed_courses,
                    "checklist_percentage" => round($completed_percentage)
                ];
            }
        }

        return ["stats" => $result];
    }
}
