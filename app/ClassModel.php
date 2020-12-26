<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    use SoftDeletes;

    protected $table = "classes";
    public function batch() {
        return $this->hasOne('App\Batch', 'id', 'batch_id');
    }

    public function program() {
        return $this->hasOne('App\Program', 'id', 'program_id');
    }
}
