@extends('layouts.quiz')
@section('includes')
<link href="{{URL::to('/')}}/plugins/DataTables-1.10.8/media/css/dataTables.foundation.min.css" rel="stylesheet">
<link href="{{URL::to('/')}}/plugins/DataTables-1.10.8/extensions/Responsive/css/responsive.foundation.min.css" rel="stylesheet">
<script src="{{URL::to('/')}}/plugins/DataTables-1.10.8/media/js/jquery.dataTables.min.js"></script>
<script src="{{URL::to('/')}}/plugins/DataTables-1.10.8/media/js/dataTables.foundation.min.js"></script>
<script src="{{URL::to('/')}}/plugins/DataTables-1.10.8/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<link href="{{URL::to('/')}}/plugins/flipclock/flipclock.css" rel="stylesheet">
<script src="{{URL::to('/')}}/plugins/flipclock/flipclock.min.js"></script>
@endsection
@section('title') - {{$title}}
@endsection
@section('content')
<div id="contentWrapper">
<h2>{{$title}}</h2>
<div class="row">
<div class="small-12 columns end" id="quizOptions">
    <div class="row">
    	<div class="small-12 large-4 columns">
        <h4>Minimum Rank</h4>
        </div>
        <div class="small-12 large-8 columns">
          <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">       
          <select class="form-control" name="rank_min_id" id="rank_min_id" required>
                    <option value="">Select Rank</option>
                    @foreach($ranks as $r)
                    <option value="{{$r->rank_id}}">{{$r->rank_name}}</option>
                    @endforeach
                </select>
         </div>
         </div>
     <div class="row">
    	<div class="small-12 large-4 columns">
        <h4>Maximum Rank</h4>
        </div>
        <div class="small-12 large-8 columns">
          <select class="form-control" name="rank_max_id" id="rank_max_id" required>
                    <option value="">Select Rank</option>
                    @foreach($ranks as $r)
                    <option value="{{$r->rank_id}}">{{$r->rank_name}}</option>
                    @endforeach
                </select>
       </div>
      </div>
       <div class="row">
    	<div class="small-12 large-4 columns">
        <h4>Show Question Level</h4>
        </div>
        <div class="small-12 large-8 columns">
          <select class="form-control" id="q_level" name="q_level">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
		</div>      
        </div>
       <div class="row">
    	<div class="small-12 large-4 columns">
        <h4>Compulsary Questions Only</h4>
        </div>
        <div class="small-12 large-8 columns">
          <select class="form-control" id="compulsary" name="compulsary">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
        </div>
      </div>
      <div class="row" id="buttonWrapper">
      	<input type="hidden" id="testType" name="testType" value="endless" />
        <div class="small-12 columns"><h4 style="text-align:center">Test Type</h4></div>
      	<div class="small-12 medium-4 columns">
        <a class="small expand button" onclick="setTestType('endless',this)">Endless Test</a>
        </div>
        <div class="small-12 medium-4 columns">
        <a class="small expand button secondary" onclick="setTestType('full',this)">Full Test</a>
        </div>
        <div class="small-12 medium-4 columns">
        <a class="small expand button secondary" onclick="setTestType('2min',this)">2 Min Test</a>
        </div>
      </div>
      <div>
      <div><a class="expand button success" onclick="startQuiz()">Start Quiz</a></div>
      <div><a class="button hide" id="hideButton" onclick="$('#quizOptions').addClass('hide');$('#optionsButton').removeClass('hide')">Hide Options</a></div>
      </div>
</div>
<div class="row">
<div id="optionsButton" class="hide">
<a class="button radius" onclick="jQuery('#quizOptions').removeClass('hide');jQuery('#optionsButton').addClass('hide');">Quiz Options</a>
</div>
</div>
<div id="quizContent">
	<div class="row">
        <div class="small-12 medium-4 columns">
            <div id="averageLevel" class="show-for-medium-up"></div>
            <div id="qLevel"></div>
        </div>
        <div class="small-12 medium-8 columns end">
        	<div id="clock" class="hide">
            </div>
        </div>
    </div>
    <div id="question"><h3></h3></div>
    <div id="options"></div>
    <div id="prevResult"></div>
</div>
<div class="quizResult">
<div id="scoreSummary"></div>
<div id="questionReview"></div>
</div>
</div>
<div id="hsModal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
  <h2 id="modalTitle">High Score.</h2>
  <div id="hsModalContent"></div>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>
<script>
function setTestType(testType,el)
{
	jQuery('#testType').val(testType);	
	jQuery('#buttonWrapper .button').addClass('secondary');
	jQuery(el).removeClass('secondary');
}
</script>
@endsection