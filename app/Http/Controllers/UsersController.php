<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Rules\ConfirmOldPassword;
use App\User;
use App\Admin;
use Validator;
use Auth;

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
            // $data = User::all()->where('status', '=', 'ACTIVE');

            // return view('admin.accounts')->with('data', $data);

            return view('admin.accounts');
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
        if($user->can('view', User::class)){
            $data = Admin::getAll();

            return view('admin.adminlogs')->with('data', $data);
        }
        else{
            return redirect('/error01');
        }

    }

    public function getAll(){
        $user = Auth::user();
        if($user->can('view', User::class)){
            $userDt = User::select('id','username','name','status','role','updated_at','created_at')->get();

            return $userDt->toJson();
        }
        else{
            return redirect('/error01');
        }
    }

    public function update(Request $request){
        $user = Auth::user();
        if($user->can('update', User::class)){
            $this->validate($request, [
                'name' => 'required|max:30'
            ]);

            $id = $request->id;
            $username = $request->username;
            $user = User::find($id);

            $user->name = $request->name;
            $user->status = $request->status;
            $user->role = $request->role;
            $user->save();

            //save admin logs
            $user = Auth::user()->username;
            $action = 'Edited user['.$id.'] '.$username;
            Admin::insertLog($user, $action);

            return redirect('/admin/accounts')->with('success', 'Item Updated');
        }
        else{
            return redirect('/error01');
        }
    }

}
