<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassCourseChat extends Model
{
    use SoftDeletes;

    public function user(){
        return $this->hasOne("App\User", "id", "user_id");
    }

    public function class_course() {
        return $this->hasOne("App\ClassCourses", "id", "class_course_id");
    }
}
