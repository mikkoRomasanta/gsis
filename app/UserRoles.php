<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    protected $connection = 'mysql';
    protected $table = 'user_roles';

    public function emp(){
        return $this->hasOne('App\User','id','user_id');
    }
}
