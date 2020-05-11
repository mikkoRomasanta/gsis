<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Rules\ConfirmOldPassword;
use App\User;
use App\UserRoles;
use App\Admin;
use Validator;
use Auth;
use DB;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if($user->can('view', User::class)){
            $users = UserRoles::with('emp')->get();

            return view('admin.accounts')->with('data',$users);
        }
        else{
            return redirect('/error01');
        }
    }

    public function adminLogs(){
        $user = Auth::user();
        if($user->can('viewLogs', User::class)){

            return view('admin.adminlogs');
        }
        else{
            return redirect('/error01');
        }

    }

    public function getAdminLogs(){
        $user = Auth::user();
        if($user->can('viewLogs', User::class)){
            $adminLogs = Admin::get();

            return $adminLogs->toJson();
        }
        else{
            return redirect('/error01');
        }
    }

    public function getAll(){
        $userDt = User::get();
        
        return $userDt->toJson();
    }

    public function update(Request $request){
        $user = Auth::user();
        if($user->can('update', User::class)){

            $id = $request->id;
            $emp = UserRoles::find($id);

            $emp->role = $request->role;
            $emp->save();

            //save admin logs
            $user = Auth::user()->emp_id;
            $action1 = 'Edited';
            $action2 = 'User';
            $action3 = '[User Id: '.$emp->user_id.'] ';
            $remarks = null;
            Admin::insertLog($user, $action1, $action2, $action3, $remarks);

            return redirect('/admin/accounts')->with('success', 'Item Updated');
        }
        else{
            return redirect('/error01');
        }
    }

}
