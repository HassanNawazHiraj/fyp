<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassCourses extends Model
{
    use SoftDeletes;

    protected $table = "class_courses";
    public function class() {
        return $this->hasOne('App\ClassModel', 'id', 'class_id');
    }

    public function course() {
        return $this->hasOne('App\Course', 'id', 'course_id');
    }

    public function teacherCourse() {
        return $this->hasMany("App\TeacherCourse", "class_course_id", "id");
    }

    public function folder() {
        return $this->hasOne("App\Folder", "id", "folder_id");
    }
}
