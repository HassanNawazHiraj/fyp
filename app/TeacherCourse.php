<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherCourse extends Model
{
    use SoftDeletes;

    public function teacher() {
        return $this->hasOne("App\User", "id", "teacher_id");
    }

    public function class_courses() {
        return $this->hasOne("App\ClassCourses", "id", "class_course_id");
    }
}
