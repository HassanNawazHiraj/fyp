<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassCourses extends Model
{
    protected $table = "class_courses";
    public function class() {
        return $this->hasOne('App\ClassModel', 'id', 'class_id');
    }

    public function course() {
        return $this->hasOne('App\Course', 'id', 'course_id');
    }
}
