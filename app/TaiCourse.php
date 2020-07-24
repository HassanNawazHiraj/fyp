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
}
