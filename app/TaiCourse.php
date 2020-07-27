<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaiCourse extends Model
{

    public function tai() {
        return $this->hasOne("App\User", "id", "tai_id");
    }

    public function course() {
        return $this->hasOne("App\Course", "id", "course_id");
    }

    public function teachers() {
        //return $this->hasOne("App\ClassCourses", "course_id", "course_id");
        return $this->belongsToMany("App\User", "tai_courses", "course_id", "tai_id");
        //dd($classes);
    }
}
