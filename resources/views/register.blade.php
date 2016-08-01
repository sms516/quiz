@extends('layouts.public')
@section('title') - Register
@endsection
@section('content')
<div class="row">
            <div class="large-8 large-offset-2 columns">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Register</h3>
                    </div>
                    <br />
					<form class="form-horizontal" role="form" method="POST" data-abide>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="name-field">
                        <label>First Name <small>required</small>
                          <input type="text" class="form-control" name="f_name" value="{{ old('f_name') }}" required pattern="alpha">
                        </label>
                        <small class="error">First Name is required and must be a string.</small>
                      </div>
                      <div class="name-field">
                        <label>Surname <small>required</small>
                          <input type="text" class="form-control" name="l_name" value="{{ old('l_name') }}" required pattern="alpha">
                        </label>
                        <small class="error">Surname is required and must be a string.</small>
                      </div>
                      <div class="name-field">
                        <label>Username <small>required</small>
                          <input type="text" class="form-control" name="username" value="{{ old('username') }}" required pattern="alpha_numeric">
                        </label>
                        <small class="error">Username is required.</small>
                      </div>
                      <div class="name-field">
                        <label>Gender <small>required</small>
                          <select class="form-control" name="gender" required>
                          			<option value="">Select Gender</option>
                                	<option value="Male" {{old('gender')=='Male'?'selected="selected"':''}}>Male</option>
                                    <option value="Female" {{old('gender')=='Female'?'selected="selected"':''}}>Female</option>
                                </select>
                        </label>
                        <small class="error">Gender is required.</small>
                      </div>
                      <div class="name-field">
                      
                        <label>Date of Birth <small>required</small></label>
                            <div class="row">
                        <div class="large-3 column">
                        <select class="form-control" name="dobDay" required pattern="number">
                            <option value="">Day</option>
                        @for ($i = 1; $i <= 31; $i++)
                            <option value="{{$i}}" {{old('dobDay')==$i?'selected="selected"':''}}>{{$i}}</option>
                        @endfor
                        </select>
                        </label>
                        <small class="error">Day is required.</small>
                        </div>
                        <div class="large-5 columns">
                        <select class="form-control" name="dobMonth" required pattern="number">
                            <option value="">Month</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{$i}}" {{old('dobMonth')==$i?'selected="selected"':''}}>{{DateTime::createFromFormat('!m', $i)->format('F')}}</option>
                        @endfor
                        </select>
                        </label>
                        <small class="error">Month is required.</small>
                        </div>
                        <div class="large-4 columns">
                        <select class="form-control" name="dobYear" required pattern="number">
                            <option value="">Year</option>
                        @for ($i = date("Y")-4; $i >=date("Y")-104; $i--)
                            <option value="{{$i}}" {{old('dobYear')==$i?'selected="selected"':''}}>{{$i}}</option>
                        @endfor
                        </select>
                        </label>
                        <small class="error">Year is required.</small>
                        </div>
                        </div>
                      </div>
                      <div class="name-field">
                        <label>Email <small>required</small>
                          <input type="email" class="form-control" name="email" value="{{ old('email') }}" required pattern="email">
                        </label>
                        <small class="error">Valid Email address is required.</small>
                      </div>
                      <div class="name-field">
                        <label>Password <small>required</small>
                          <input type="password" class="form-control" name="password" id="password" required>
                        </label>
                        <small class="error">Password is required</small>
                      </div>
                      <div class="name-field">
                        <label>Confirm Password <small>required</small>
                          <input type="password" class="form-control" name="password_confirmation" required data-equalto="password">
                        </label>
                        <small class="error">Password confirmation required.</small>
                      </div>
					
                    <button type="submit">
                        Register
                    </button>
					</form>
                </div>
            </div>
        </div>
@endsection