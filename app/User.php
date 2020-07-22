<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function types() {
        return $this->belongsToMany('App\UserType', 'user_type_relations', 'user_id', 'user_type_id');
    }

    public function class_courses() {
        return $this->belongsToMany('App\ClassCourses', 'teacher_courses', 'teacher_id', 'class_course_id')->withPivot("course_type");
    }

    public function course_type() {
        return $this->hasMany("App\TeacherCourse", "teacher_id");
    }
}
