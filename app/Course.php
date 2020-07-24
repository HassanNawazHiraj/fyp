<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function tai() {
        return $this->belongsToMany('App\User', 'tai_courses', 'course_id', 'tai_id');

    }
}
