@extends('layouts.app')

@section('content')
<div class="container h-100">
        <a href="{{URL::previous()}}" class="btn btn-secondary">Back</a>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header color-bg-main">
                    <h4 class="card-title text-center" style="margin-bottom: 0;">Change Password</h4>
                </div>
                <div class="card-body color-bg-secondary">
                    {{Form::open(['action' => ['UsersController@changePassword'], 'method' => 'POST', 'class' => 'text-center'])}}
                        {{ csrf_field() }}
                        <div class="form-group form-row">
                            <div class="col-2"></div>
                            <div class="col-8">
                                {{Form::label('cur_password', 'Current Password')}}
                                {{Form::password('cur_password',['class' => 'form-control text-center', 'placeholder' => 'enter your old password'])}}
                            </div>
                        </div>
                        <div class="form-group form-row">
                            <div class="col-2"></div>
                            <div class="col-8">
                                {{Form::label('new_password', 'New Password')}}
                                {{Form::password('new_password',['class' => 'form-control text-center', 'placeholder' => 'enter your new password'])}}
                            </div>
                        </div>
                        <div class="form-group form-row">
                                <div class="col-2"></div>
                                <div class="col-8">
                                    {{Form::label('new_password_confirmation', 'Confirm Password')}}
                                    {{Form::password('new_password_confirmation',['class' => 'form-control text-center', 'placeholder' => 'confirm your new password'])}}
                                </div>
                            </div>
                        {{Form::submit('Submit',['class' => 'btn btn-primary'])}}
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>

@endsection
