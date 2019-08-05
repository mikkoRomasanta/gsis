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
                    <h4 class="card-title text-center" style="margin-bottom: 0;">Register</h4>
                </div>
                <div class="card-body color-bg-secondary">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label"><strong>Name</strong></label>
                            <div class="col-md-9">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-md-3 col-form-label"><strong>Username</strong></label>
                            <div class="col-md-9">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="role" class="col-md-3 col-form-label"><strong>Role</strong></label>
                            <div class="col-md-9">
                                <select class="form-control" name="role">
                                    <option value="USER">USER</option>
                                    <option value="GSADMIN">GS-ADMIN</option>
                                    <option value="ADMIN">ADMIN</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-3 col-form-label"><strong>Password</strong></label>
                            <div class="col-md-9">
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-3 col-form-label"><strong>Confirm Pass</strong></label>
                            <div class="col-md-9">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-9"></div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                        Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
