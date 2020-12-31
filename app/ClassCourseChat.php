<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassCourseChat extends Model
{
    public function user(){
        return $this->hasOne("App\User", "id", "user_id");
    }

    public function class_course() {
        return $this->hasOne("App\ClassCourses", "id", "class_course_id");
    }
}
