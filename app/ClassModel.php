<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    protected $table = "classes";
    public function batch() {
        return $this->hasOne('App\Batch', 'id', 'batch_id');
    }

    public function program() {
        return $this->hasOne('App\Program', 'id', 'program_id');
    }
}
