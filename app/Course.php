<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    // use SoftDeletes;

    public function tai() {
        return $this->belongsToMany('App\User', 'tai_courses', 'course_id', 'tai_id');

    }
}
