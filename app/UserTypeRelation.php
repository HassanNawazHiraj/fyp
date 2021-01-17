<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTypeRelation extends Model
{
    use SoftDeletes;

    public function UserType() {
        return $this->hasOne("App\UserType", "id", "user_type_id");
    }
}
