@extends('layouts.quiz')
@section('includes')
<link rel='stylesheet' href='{{URL::to('/')}}/plugins/jquery-ui-1.11.4/jquery-ui.min.css' />
<link rel='stylesheet' href='{{URL::to('/')}}/plugins/fullcalendar-2.9.0/fullcalendar.css' />
<script src='{{URL::to('/')}}/plugins/jquery-ui-1.11.4/jquery-ui.min.js'></script>
<script src='{{URL::to('/')}}/plugins/fullcalendar-2.9.0/lib/moment.min.js'></script>
<script src='{{URL::to('/')}}/plugins/fullcalendar-2.9.0/fullcalendar.min.js'></script>
@endsection
@section('title') - {{$title}}
@endsection
@section('content')
<div id="contentWrapper">
    <h2>{{$title}}</h2>
    <div class="row">
        @if(count($streams)>0)
        <div class="small-12 columns">
         <div class="name-field">
         	<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <label>Stream <small>required</small>
              <select class="form-control" name="club_stream" id="club_stream" onchange="changeStream(this);">
                        <option value="">All Streams</option>
                        @foreach($streams as $s)
                        <option value="{{$s->club_stream_id}}" >{{$s->stream_name}}</option>
                        @endforeach
                    </select>
            </label>
          </div>
        </div>
        <div class="small-12 columns">
        	<div id="streamCalendar">
            <div id='calendar'></div>
			<script>
            var token=jQuery("#token").val();
			$('#calendar').fullCalendar({
				allDaySlot:false,
				slotEventOverlap:false,
				minTime:'{{$minTime}}',
				maxTime:'{{$maxTime}}',
				scrollTime:'{{$minTime}}',
				slotDuration:'00:05:00',
				selectable: true,
				editable: false,
				height:600,
				eventSources: [
				
					// your event source
					{
						url: '{{URL::to('/').'/ajax/'.strtolower($club->club_short_name).'/training'}}', // use the `url` property
						type: 'POST',
						data: function() { // a function that returns an object
						return {
							_token:token
						};
						},
						
					}
				
					// any other sources...
				
				],
				slotLabelFormat:'HH:mm',
				timeFormat:'HH:mm'
			});
			 $(function(){
			  // bind change event to select
			  $('#club_stream').on('change', function () {
				  var streamID = $(this).val(); // get selected value
				  if (streamID) { // require a URL
					  window.location = '{{URL::to('/').'/'.strtolower($club->club_short_name).'/training/'}}'+streamID; // redirect
				  }
				  return false;
			  });
			});
			</script>
        </div>
        </div>
        @else
        <div data-alert class="alert-box alert round">
        No club streams were found. club streams must be created before a training plan can be created.
        </div>
        @endif  
    </div>
</div>
@endsection