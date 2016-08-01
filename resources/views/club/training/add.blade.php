@extends('layouts.club')
@section('title') - {{$title}}
@endsection
@section('includes')
<link rel='stylesheet' href='{{URL::to('/')}}/plugins/fullcalendar-2.9.0/fullcalendar.css' />
<script src='{{URL::to('/')}}/plugins/fullcalendar-2.9.0/lib/moment.min.js'></script>
<script src='{{URL::to('/')}}/plugins/fullcalendar-2.9.0/fullcalendar.min.js'></script>
@endsection
@section('content')
<div class="row">
            <div class="large-12 columns">
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
					<form class="form-horizontal" role="form" method="POST" data-abide>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="name-field">
                        <label>Stream <small>required</small>
                          <select class="form-control" name="club_stream" required>
                          			<option value="">Select Stream</option>
                                	@foreach($streams as $s)
                                    <option value="{{$s->club_stream_id}}" {{old('club_stream_id')==$s->club_stream_id?'selected="selected"':''}}>{{$s->stream_name}}</option>
                                    @endforeach
                                </select>
                        </label>
                        <small class="error">Club Stream is required.</small>

                      </div>
						<div class="name-field">
                        <label>Training Name <small>required</small>
                          <input type="text" class="form-control" name="training_name" value="{{ old('f_name')!=NULL?old('f_name'):$defaultName }}" required pattern="alpha">
                        </label>
                        <small class="error">Training Name is required and must be a string.</small>
                      </div>
                    <button type="submit">
                        Add Training
                    </button>
					</form>
                    <div class="row">
                    <div class="small-12 columns">
                    <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
        <script>
		 $('#calendar').fullCalendar({
			allDaySlot:false,
			slotEventOverlap:false,
			defaultView:'agendaDay',
			minTime:'00:00:00',
			scrollTime:'00:00:00',
			slotDuration:'00:05:00',
			selectable: true,
			editable: true,
			hiddenDays:[{{implode(',',$excludeDays)}}],
			events: [
				{
					title: 'Warmup - Whole Class - Mr Sutherland',
					start: '2016-07-20T00:00:00',
					end: '2016-07-20T00:15:00',
					color:'#ccc'
				},
				{
					title: 'Patterns - 2nd-1st Gup - Mr Blackwell',
					start: '2016-07-20T00:15:00',
					end: '2016-07-20T00:30:00',
					color:'red'
				},
				{
					title: 'Patterns - 4th-3rd Gup - Mr Eccles',
					start: '2016-07-20T00:15:00',
					end: '2016-07-20T00:30:00',
					color:'blue'
				},
				{
					title: 'Patterns - 1st-3rd Dan - Mr Ward',
					start: '2016-07-20T00:15:00',
					end: '2016-07-20T00:30:00',
					color:'black'
				},
			],
			slotLabelFormat:'HH:mm',
			timeFormat:'HH:mm'
		});
		</script>
@endsection