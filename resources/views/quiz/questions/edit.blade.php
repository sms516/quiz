@extends('layouts.master')
@section('title')
@endsection
@section('content')
<div class="row">
            <div class="large-8 large-offset-2 columns">
                <div class="add-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{$title}}</h3>
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
                        <div class="name-field">
                        <label>Question Rank <small>required</small>
                          <select class="form-control" name="rank_id" required>
                          			<option value="">Select Rank</option>
                                	@foreach($ranks as $r)
                                    <option value="{{$r->rank_id}}" {{$question->rank_id==$r->rank_id?'selected="selected"':''}}>{{$r->rank_name}}</option>
                                    @endforeach
                                </select>
                        </label>
                      </div>
						<div class="name-field">
                        <label>Question <small>required</small>
                          <input type="text" class="form-control" name="question" value="{{ $question->question }}" required>
                        </label>
                        <small class="error">Question is required.</small>
                      </div>
                      <div class="name-field">
                        <label>Answer <small>required</small>
                          <input type="text" class="form-control" name="answer" value="{{ $question->answer }}" required>
                        </label>
                        <small class="error">Answer is required.</small>
                      </div>
                      <div class="name-field">
                        <label>Option 1 <small>required</small>
                          <input type="text" class="form-control" name="option1" value="{{ $question->option1 }}" required>
                        </label>
                         <small class="error">Option 1 is required.</small>
                      </div>
                       <div class="name-field">
                        <label>Option 2
                          <input type="text" class="form-control" name="option2" value="{{ $question->option2 }}">
                        </label>
                      </div>
                       <div class="name-field">
                        <label>Option 3
                          <input type="text" class="form-control" name="option3" value="{{ $question->option3 }}">
                        </label>
                      </div>
                       <div class="name-field">
                        <label>Option 4
                          <input type="text" class="form-control" name="option4" value="{{ $question->option4 }}">
                        </label>
                      </div>
                      <div class="name-field">
                        <label>Compulsary Question <small>required</small>
                          <select class="form-control" name="compulsary">
                          			<option value="0"></option>
                                	<option value="0" {{$question->compulsary=='0'?'selected="selected"':''}}>No</option>
                                    <option value="1" {{$question->compulsary=='1'?'selected="selected"':''}}>Yes</option>
                                </select>
                        </label>
                      </div>
                      <div class="name-field">
                        <label>Image
                          <input type="file" name="image" accept="image/*">
                        </label>
                      </div>
                    <button type="submit">
                        Update Question
                    </button>
					</form>
                </div>
            </div>
        </div>
@endsection