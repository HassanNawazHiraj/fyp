<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherCourse extends Model
{
    public function teacher() {
        return $this->hasOne("App\User", "id", "teacher_id");
    }

    public function class_courses() {
        return $this->hasMany("App\ClassCourses", "id", "class_course_id");
    }
}
