@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1>{{$title}}</h1>
        <p>Bienvenue à GSIS, comment ça va? C'est une propriété de Nanox Philippines Inc.</p><hr>
        @if (Auth::guest())
            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                <div class="form-group form-row">
                    <div class="col-md-1"></div>
                    <div class="col-md-4{{ $errors->has('username') ? ' has-error' : '' }}">
                        <label for="username"">Username</label>
                        <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
                        @if ($errors->has('username'))
                            <span class="help-block">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="col-md-4"{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 control-label">Password</label>
                        <input id="password" type="password" class="form-control" name="password" required>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    {{-- <div class="col-md-1">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                            </label>
                        </div>
                    </div> --}}

                    <div class="col-md-2">
                        <button type="submit" class="btn form-control color-btn-link" style="margin-top: 2rem;">
                            Login
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
@endsection
