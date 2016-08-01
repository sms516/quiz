@extends('layouts.public')
@section('title') - Login
@endsection
@section('content')
<div class="row">
            <div class="large-8 large-offset-2 columns">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Login</h3>
                    </div>
                    <br />
                    @if (count($errors) > 0)
                    <div data-alert class="alert-box alert round">
                      {{ $errors->first('username') }}
                      <a href="#" class="close">&times;</a>
                    </div>
                    @endif
					<form class="form-horizontal" role="form" method="POST">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="name-field">
                        <label>Username
                          <input type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                        </label>
                      </div>
                      <div class="name-field">
                        <label>Password
                          <input type="password" class="form-control" name="password" id="password" required>
                        </label>
                      </div>
                    <button type="submit">
                        Login
                    </button>
					</form>
                </div>
            </div>
        </div>
@endsection