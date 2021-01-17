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
    public function index()
    {
        $user = Auth::user();
        //get active session
        $active_session = Session::where("active", 1)->first();
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
            $courses = TeacherCourse::where("teacher_id", $user->id)->where("session_id", $active_session->id)->with(["class_courses.course"])->get();
            $checklist_total = 0;
            $checklist_checked = 0;
            foreach ($courses as $course) {
                $folder_name = $course->class_courses->folder_name;
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
            $completed_percentage = $checklist_checked / $checklist_total * 100;
            $result->teacher = [
                "checklist_total" => $checklist_total,
                "checklist_checked" => $checklist_checked,
                "completed_courses" => $number_of_completed_courses,
                "checklist_percentage" => round($completed_percentage)
            ];
        }

        //tai
        if ($tai) {
            $number_of_completed_courses = 0;
            $courses = TaiCourse::where("tai_id", $user->id)->where("session_id", $active_session->id)->with([
                'teacher_courses', 'teacher_courses.teacher', 'teacher_courses.class_courses',
                'teacher_courses.class_courses.class.program', 'teacher_courses.class_courses.class.batch',  'teacher_courses.class_courses.course'
            ])->get();

            $checklist_total = 0;
            $checklist_checked = 0;
            foreach ($courses as $class_course) {
                foreach ($class_course->teacher_courses as $course) {
                    $folder_name = $course->class_courses->folder_name;
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
                $completed_percentage = $checklist_checked / $checklist_total * 100;
                $result->tai = [
                    "checklist_total" => $checklist_total,
                    "checklist_checked" => $checklist_checked,
                    "completed_courses" => $number_of_completed_courses,
                    "checklist_percentage" => round($completed_percentage)
                ];
            }
            return [
                "stats" => $result
            ];
        }
    }
}
