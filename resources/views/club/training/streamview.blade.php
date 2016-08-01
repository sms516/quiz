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
                        <option value="{{$s->club_stream_id}}" {{$s->club_stream_id==$stream->club_stream_id?'selected="selected"':''}} >{{$s->stream_name}}</option>
                        @endforeach
                    </select>
            </label>
          </div>
        </div>
        <div class="small-12 columns">
        <div class="alert-box info">Click on the syllabus option and fill out the details on the form. Once a session has been aded to the planner, you can resize and move the session. Click on a session for more details.</div>
        <ul class="button-group">
        <li><div data-alert class="button small draggable" onclick="addSession(0)" data-event='{"title":"Warm Up","duration":"00:05","syllabus_id":"0","training_notes":"Warm Up"}'>Warm Up</div></li>
        @foreach($club->Syllabus as $s)
        <li><div data-alert class="button small draggable" onclick="addSession({{$s->syllabus_id}});" data-event='{"title":"{{$s->syllabus_name}}","duration":"00:05","syllabus_id":"{{$s->syllabus_id}}"}'>{{$s->syllabus_name}}</div></li>
        @endforeach
        </ul>
        </div>
        <div class="small-12 columns">
        	<div id="streamCalendar">
            <div id='calendar'></div>
			<script>
            $('.draggable').draggable({
                revert: true,      // immediately snap back to original position
                revertDuration: 0  //
            });
            var token=jQuery("#token").val();
			$('#calendar').fullCalendar({
				allDaySlot:false,
				slotEventOverlap:false,
				defaultView:'agendaDay',
				minTime:'{{$minTime}}',
				maxTime:'{{$maxTime}}',
				scrollTime:'00:00:00',
				slotDuration:'00:05:00',
				selectable: true,
				editable: true,
				height:600,
				droppable: true,
				hiddenDays:[{{implode(',',$excludeDays)}}],
				 eventClick: function(calEvent, jsEvent, view) {
					 $("#modalTitle").html('Edit Session');
					 $("#deleteButton").removeClass('hide');
					 $("#updateButton").removeClass('hide');
					 $("#saveButton").addClass('hide');
					$("#instructor_id option[value='"+calEvent.instructor_id+"']").prop('selected', true);
					if(calEvent.min_rank_id==null)removeSelection('min_rank_id');
					else $("#min_rank_id option[value='"+calEvent.min_rank_id+"']").prop('selected', true);
					if(calEvent.max_rank_id==null)removeSelection('max_rank_id');
					else $("#max_rank_id option[value='"+calEvent.max_rank_id+"']").prop('selected', true);
					if(calEvent.min_age==null)removeSelection('min_age');
					else $("#min_age option[value='"+calEvent.min_age+"']").prop('selected', true);
					if(calEvent.max_age==null)removeSelection('max_age');
					else $("#max_age option[value='"+calEvent.max_age+"']").prop('selected', true);
					$("#start_hour option[value='"+calEvent.start.format('HH')+"']").prop('selected', true);
					$("#start_min option[value='"+calEvent.start.format('mm')+"']").prop('selected', true);
					$("#end_hour option[value='"+calEvent.end.format('HH')+"']").prop('selected', true);
					$("#end_min option[value='"+calEvent.end.format('mm')+"']").prop('selected', true);
					$("#training_notes").val(calEvent.training_notes);
					$("#syllabus_id").val(calEvent.syllabus_id);
					$("#training_id").val(calEvent.training_id);	
					$('#calendarModal').foundation('reveal', 'open');			
				},
				eventSources: [
				
					// your event source
					{
						url: '{{URL::to('/').'/ajax/'.strtolower($club->club_short_name).'/training'}}', // use the `url` property
						type: 'POST',
						data: function() { // a function that returns an object
						return {
							club_stream: jQuery('#club_stream').val(),
							_token:token
						};
						},
						
					}
				
					// any other sources...
				
				],
				 eventRender: function(event, element) {
					 console.log(event.training_id+" "+event.min_color+", "+event.max_color);
					$(element).css("background","linear-gradient("+event.min_color+", "+event.max_color+")");
				},
				eventDrop: function(event, delta, revertFunc) {

					updateTimes(event,'{{strtolower($club->club_short_name)}}');
				},
				eventResize: function(event, delta, revertFunc) {

					updateTimes(event,'{{strtolower($club->club_short_name)}}');
				},
				eventReceive: function(event) {

					saveDroppedEvent(event,'{{strtolower($club->club_short_name)}}',{{$club->club_id}});
				},
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
				  else
				  {
					  window.location = '{{URL::to('/').'/'.strtolower($club->club_short_name).'/training'}}'; // redirect
				  }
				  return false;
			  });
			});

			</script>
<style>
.fc-time-grid-event .fc-time, .fc-time-grid-event .fc-title {
font-size: 1.3em;
}
</style>
        </div>
        </div>
        <div id="calendarModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
            <h2 id="modalTitle">Add Session</h2>
            <a class="close-reveal-modal" aria-label="Close">&#215;</a>
            <input type="hidden" name="syllabus_id" id="syllabus_id" value="">
            <input type="hidden" name="training_id" id="training_id" value="">
            <div class="row">
            <div class="small-12 columns">
             <div class="name-field">
                <label>Instructor
                  <select class="form-control" name="instructor_id" id="instructor_id">
                            <option value="">Select Instructor</option>
                            @foreach($members as $m)
                            <option value="{{$m->user_id}}" >{{$m->getDisplayName().' ('.$m->currentRank()->rank_name.')'}}</option>
                            @endforeach
                        </select>
                </label>
              </div>
            </div>
            <div class="small-6 columns">
             <div class="name-field">
                <label>Min Rank
                  <select class="form-control" name="min_rank_id" id="min_rank_id">
                            <option value="">No Minimum Rank</option>
                            @foreach($ranks as $r)
                            @if($r->rank_num<=$minRank && $r->rank_num>=$maxRank)
                            <option value="{{$r->rank_id}}" >{{$r->rank_name}}</option>
                            @endif
                            @endforeach
                        </select>
                </label>
              </div>
            </div>
            <div class="small-6 columns">
             <div class="name-field">
                <label>Max Rank
                  <select class="form-control" name="max_rank_id" id="max_rank_id">
                            <option value="">No Maximum Rank</option>
                            @foreach($ranks as $r)
                            @if($r->rank_num<=$minRank && $r->rank_num>=$maxRank)
                            <option value="{{$r->rank_id}}" >{{$r->rank_name}}</option>
                            @endif
                            @endforeach
                        </select>
                </label>
              </div>
            </div>
            </div>
            <div class="row">
            <div class="small-6 columns">
             <div class="name-field">
                <label>Min Age
                  <select class="form-control" name="min_age" id="min_age">
                            <option value="">No Minimum Age</option>
                            @for($i=$minAge;$i<=$maxAge;$i++)
                            <option value="{{$i}}" >{{$i}}</option>
                            @endfor
                        </select>
                </label>
              </div>
            </div>
             <div class="small-6 columns">
             <div class="name-field">
                <label>Max Age
                  <select class="form-control" name="max_age" id="max_age">
                            <option value="">No Maximum Age</option>
                            @for($i=$minAge;$i<=$maxAge;$i++)
                            <option value="{{$i}}" >{{$i}}</option>
                            @endfor
                        </select>
                </label>
              </div>
            </div>
            </div>
            
             <div class="row">
            <div class="small-6 columns">
             <div class="name-field">
                <label>Start Time
                <div class="row">
                <div class="small-4 columns">
                  <select class="form-control" name="start_hour" id="start_hour">

                            @for($i=$minHour;$i<=$maxHour;$i++)
                            <option value="{{$i}}" >{{$i}}</option>
                            @endfor
                        </select>
                </div>
                <div class="small-4 columns end">
                        <select class="form-control" name="start_min" id="start_min">

                            @for($i=0;$i<60;$i+=5)
                            <option value="{{str_pad($i,2,0,STR_PAD_LEFT)}}" >{{str_pad($i,2,0,STR_PAD_LEFT)}}</option>
                            @endfor
                        </select>
                 </div>
                 </div>
                </label>
              </div>
            </div>
             <div class="small-6 columns">
             <div class="name-field">
                <label>End Time
                <div class="row">
                <div class="small-4 columns">
                  <select class="form-control" name="end_hour" id="end_hour">

                            @for($i=$minHour;$i<=$maxHour;$i++)
                            <option value="{{$i}}" >{{$i}}</option>
                            @endfor
                        </select>
                </div>
                <div class="small-4 columns end">
                        <select class="form-control" name="end_min" id="end_min">

                            @for($i=0;$i<60;$i+=5)
                            <option value="{{str_pad($i,2,0,STR_PAD_LEFT)}}" >{{str_pad($i,2,0,STR_PAD_LEFT)}}</option>
                            @endfor
                        </select>
                 </div>
                 </div>
                </label>
              </div>
            </div>
            </div>
            
            <div class="row">
            <div class="small-12 columns">
             <div class="name-field">
                <label>Notes
                 <textarea id="training_notes" name="training_notes"></textarea>
                </label>
              </div>
            </div>
            <div class="button small success" id="saveButton" onclick="saveSession('{{strtolower($club->club_short_name)}}',{{$club->club_id}})">Save</div>
            <div class="button small hide success" id="updateButton" onclick="updateSession('{{strtolower($club->club_short_name)}}',{{$club->club_id}})">Update</div>
            <div id="deleteButton" class="button small alert" onclick="deleteSession('{{strtolower($club->club_short_name)}}',{{$club->club_id}})">Delete</div>
            <div class="button small" onclick="$('#calendarModal').foundation('reveal', 'close');">Cancel</div>
        </div>
        @else
        <div data-alert class="alert-box alert round">
        No club streams were found. club streams must be created before a training plan can be created.
        </div>
        @endif  
    </div>
</div>
@endsection