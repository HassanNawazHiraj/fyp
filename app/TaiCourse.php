<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ClassCourses;
class TaiCourse extends Model
{

    public function tai() {
        return $this->hasOne("App\User", "id", "tai_id");
    }

    public function course() {
        return $this->hasOne("App\Course", "id", "course_id");
    }

    public function classes() {
        //$class = ClassCourses::where("course_id", $this->course_id)->get();
        return $this->hasMany("App\ClassCourses", "course_id", "course_id");
    }
}
