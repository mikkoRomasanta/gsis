<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin_logs';

    protected $fillable = [
        'action', 'user',
    ];

    public static function insertLog($user, $action1, $action2, $action3, $remarks){
        $admin = new Admin;

        $admin->action1 = $action1;
        $admin->action2 = $action2;
        $admin->action3 = $action3;
        $admin->remarks = $remarks;
        $admin->user= $user;
        $admin->save();
    }

    public static function getAll(){
        $data = Admin::all();

        return $data;
    }
}
