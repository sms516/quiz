<div id='calendar'></div>
<script>
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
			hiddenDays:[{{implode(',',$excludeDays)}}],
			@if(isset($events) && count($events)>0)
			events:[
			@foreach($events as $e)
			{
				title: '{{$e['title']}}',
				start: '{{$e['start']}}',
				end: '{{$e['end']}}',
				color:'#ccc'
			},
			@endforeach
			],
			@endif
			slotLabelFormat:'HH:mm',
			timeFormat:'HH:mm'
		});
		</script>