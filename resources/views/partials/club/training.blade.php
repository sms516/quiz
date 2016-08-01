<h3>Training Times</h3>
@if(isset($trainings) && sizeof($trainings)>0)
@foreach($trainings as $t)
<div class="medium-12 large-4 columns">
<div class="classStream">
<h3>{{$t->stream_name}}</h3>
<div>{{$t->minRankName}} to {{$t->maxRankName}}</div>
<div data-alert class="alert-box info radius">
<div data-countdown="{{$t->getNextTrainingTime()}}"></div>
<!--  <div>{{$t->getNextTrainingTime()}}</div>-->
  <a href="#" class="close">&times;</a>
</div>
@foreach($t->trainingTimes()->get() as $tt)
<div>
<strong>{{date('l', strtotime("Sunday +{$tt->training_day} days"))}}:</strong> {{date('g:i a',strtotime($tt->start_time))}} - {{date('g:i a',strtotime($tt->end_time))}}
</div>
@endforeach
</div>
</div>
@endforeach
<script>
$('[data-countdown]').each(function() {
  var $this = $(this), finalDate = $(this).data('countdown');
  $this.countdown(finalDate, function(event) {
    $this.html(event.strftime('Next Class: %-D Day%!D %-H:%M'));
  });
});
</script>
@endif