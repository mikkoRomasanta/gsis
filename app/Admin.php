<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin_logs';

    protected $fillable = [
        'action', 'user',
    ];

    public static function insertLog($user, $action){
        $admin = new Admin;

        $admin->action = $action;
        $admin->user= $user;
        $admin->save();
    }

    public static function getAll(){
        $data = Admin::all();

        return $data;
    }
}
