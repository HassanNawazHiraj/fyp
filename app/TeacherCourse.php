<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherCourse extends Model
{
    public function teacher() {
        return $this->hasOne("App\User", "id", "teacher_id");
    }
}
