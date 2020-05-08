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

    public function changePass(){
        return view('auth.changepassword');
    }

    public function changePassword(Request $request){
        $userAuth = Auth::user();
        $oldPassword = $userAuth->password;

        $this->validate($request, [
            'cur_password' => ['required', new ConfirmOldPassword($oldPassword)],
            'new_password' => 'required|min:6|confirmed'
        ]);

        $curPassword = $request->input("cur_password");
        $newPassword = $request->input('new_password');


        $userId = $userAuth->id;
        $user = User::find($userId);
        $user->password = Hash::make($newPassword);
        $user->save();

        // return response()->json(['result' => $newPassword.' '.$userId]);

        Auth::logout();

        return redirect('/');
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

    // public function getAll(){
    //     $user = Auth::user();
    //     if($user->can('view', User::class)){
    //         $userDt = User::with('userRole')->select('id','emp_id', DB::raw("CONCAT(last_name,' ',first_name) as name"),'last_name','role','updated_at','created_at')
    //         ->get();

    //         return $userDt->toJson();
    //     }
    //     else{
    //         return redirect('/error01');
    //     }
    // }

    public function update(Request $request){
        $user = Auth::user();
        if($user->can('update', User::class)){

            $id = $request->id;
            $user = UserRoles::find($id);

            $user->status = $request->status;
            $user->role = $request->role;
            $user->save();

            //save admin logs
            $user = Auth::user()->emp_id;
            $action1 = 'Edited';
            $action2 = 'User';
            $action3 = '['.$id.'] ';
            $remarks = null;
            Admin::insertLog($user, $action1, $action2, $action3, $remarks);

            return redirect('/admin/accounts')->with('success', 'Item Updated');
        }
        else{
            return redirect('/error01');
        }
    }

}
