@if(isset($training_times))
@if(sizeof($training_times)>1)
<div data-alert class="alert-box info radius">
Multiple Sessions today for this class stream. Select the session you are marking attendance for below
</div>
<div class="row attendanceTimeWrapper">
@foreach($training_times as $t)
    <div class="classStream small-6 medium-4 columns {{isset($club_training_time_id)&& $club_training_time_id==$t->club_training_time_id?' alert-box success':''}}" onclick="loadClassList(this,{{$t->club_training_time_id}},'{{$date}}','{{strtolower($club->club_short_name)}}')">
    <h4>{{date('g:i a',strtotime($t->start_time))}} - {{date('g:i a',strtotime($t->end_time))}}</h4>
    </div>
@endforeach 
<input type="hidden" name="club_training_time_id" id="club_training_time_id" value="{{isset($club_training_time_id)?$club_training_time_id:''}}" />
</div>
@else
<input type="hidden" name="club_training_time_id" id="club_training_time_id" value="{{$training_times[0]->club_training_time_id}}" />
<script>
loadClassList(null,{{$training_times[0]->club_training_time_id}},'{{$date}}','{{strtolower($club->club_short_name)}}');
</script>
@endif
@endif
