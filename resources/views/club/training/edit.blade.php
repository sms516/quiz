@extends('layouts.club')
@section('title') - Edit Member
@endsection
@section('includes')
<script src="{{URL::to('/')}}/plugins/slim-js/slim.jquery.js"></script>
<link href="{{URL::to('/')}}/plugins/slim-js/slim.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="row">
            <div class="large-12 columns">
                <div class="add-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Edit Club Member</h3>
                    </div>
                    <br />
                    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
					<form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" data-abide>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="user_id" value="{{ $user->user_id }}">
                        <div class="row">
                        	<div class="medium-3 small-12 columns">
                                  <div class="slim"
                                     data-label="Drop your profile picture here"
                                     data-size="400,400"
                                     data-ratio="1:1">
                                   @if($user->user_image!=NULL)
                                   <img src="{{$user->getImgSrc()}}"/>
                                   @endif
                                    <input type="file" required name="user_image" id="slim" />
                                </div>
                                <script>$('body').slimParse();</script>
                            </div>
                            <div class="medium-9 small-12 columns">
                                <div class="name-field">
                                <label>First Name <small>required</small>
                                  <input type="text" class="form-control" name="f_name" value="{{ $user->f_name }}" required pattern="alpha">
                                </label>
                                <small class="error">First Name is required and must be a string.</small>
                              </div>
                              <div class="name-field">
                                <label>Surname <small>required</small>
                                  <input type="text" class="form-control" name="l_name" value="{{ $user->l_name }}" required pattern="alpha">
                                </label>
                                <small class="error">Surname is required and must be a string.</small>
                              </div>
                              <div class="name-field">
                                <label>Organization ID
                                  <input type="text" class="form-control" name="organization_id" value="{{ $user->organization_id }}">
                                </label>
                              </div>
                              <div class="name-field">
                                <label>Gender <small>required</small>
                                  <select class="form-control" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{$user->gender=='Male'?'selected="selected"':''}}>Male</option>
                                            <option value="Female" {{$user->gender=='Female'?'selected="selected"':''}}>Female</option>
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
                                    <option value="{{$i}}" {{$dobDay==$i?'selected="selected"':''}}>{{$i}}</option>
                                @endfor
                                </select>
                                </label>
                                <small class="error">Day is required.</small>
                                </div>
                                <div class="large-5 columns">
                                <select class="form-control" name="dobMonth" required pattern="number">
                                    <option value="">Month</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{$i}}" {{$dobMonth==$i?'selected="selected"':''}}>{{DateTime::createFromFormat('!m', $i)->format('F')}}</option>
                                @endfor
                                </select>
                                </label>
                                <small class="error">Month is required.</small>
                                </div>
                                <div class="large-4 columns">
                                <select class="form-control" name="dobYear" required pattern="number">
                                    <option value="">Year</option>
                                @for ($i = date("Y")-4; $i >=date("Y")-104; $i--)
                                    <option value="{{$i}}" {{$dobYear==$i?'selected="selected"':''}}>{{$i}}</option>
                                @endfor
                                </select>
                                </label>
                                <small class="error">Year is required.</small>
                                </div>
                                </div>
                              </div>
                              <div class="name-field">
                                <label>Email <small>required</small>
                                  <input type="email" class="form-control" name="email" value="{{ $user->email }}" required pattern="email">
                                </label>
                                <small class="error">Valid Email address is required.</small>
                              </div>
                              <div class="name-field">
                                <label>Phone
                                  <input type="text" class="form-control" name="phone" value="{{ $user->phone }}">
                                </label>
                                <small class="error">Valid Email address is required.</small>
                              </div>
                              <div class="name-field">
                                <label>Role <small>required</small>
                                  <select class="form-control" name="user_role" required>
                                            <option value="">Select Role</option>
                                            @foreach($roles as $r)
                                            <option value="{{$r->role_id}}" {{$user_role->role_id==$r->role_id?'selected="selected"':''}}>{{$r->role_name}}</option>
                                            @endforeach
                                        </select>
                                </label>
                                <small class="error">Role is required.</small>
                              </div>
	                      </div>
                      </div>
                      <div class="name-field">
                    <!--  @if(isset($user->image_url))
                            <img src="{{URL::to('/')}}/{{$user->image_url}}" style="height:100px;"/>
                            @endif
                        <label>Image
                          <input type="file" name="user_image" accept="image/*">
                        </label>
                        -->
                      </div>
                    <button type="submit">
                        Update
                    </button>
					</form>
                </div>
            </div>
        </div>
@endsection