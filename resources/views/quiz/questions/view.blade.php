@extends('layouts.master')
@section('includes')
<link href="{{URL::to('/')}}/plugins/DataTables-1.10.7/media/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{URL::to('/')}}/plugins/DataTables-1.10.7/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<script src="{{URL::to('/')}}/plugins/DataTables-1.10.7/media/js/jquery.dataTables.min.js"></script>
<script src="{{URL::to('/')}}/plugins/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>

@endsection
@section('title') - {{$title}}
@endsection
@section('content')
<div id="contentWrapper">
<h2>{{$title}}</h2>
<div class="row">
@if(count($questions)>0)
<div class="small-12 columns">
<table id="questionTable" class="display responsive" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="all">Rank</th>
            <th class="all">Question</th>
            <th class="all">Answer</th>
            <th class="none">Option 1</th>
            <th class="none">Option 2</th>
            <th class="none">Option 3</th>
            <th class="none">Option 4</th>
            <th>Compulsary</th>
            <th>Image</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
@foreach($questions as $q)
<tr>
	<td>{{$q->rank_name}}</td>
    <td>{{$q->question}}</td>
    <td>{{$q->answer}}</td>
    <td>{{$q->option1}}</td>
    <td>{{$q->option2}}</td>
    <td>{{$q->option3}}</td>
    <td>{{$q->option4}}</td>
    <td>{{$q->compulsary==0?'No':'Yes'}}</td>
    <td>{{$q->image==NULL?'No':'Yes'}}</td>
    <td><a href="{{URL::to('/admin/quiz/question/')}}/{{$q->quiz_question_id}}" class="button expand">Edit</a></td>
</tr>
@endforeach      
    </tbody>
</table>
</div>
<script>
$(document).ready(function() {
    $('#questionTable').dataTable({
        responsive: true,
		"dom": 'f<t>p',
		"columnDefs": [
			{ "width": "10%", "targets": 9 },
			{ "width": "7%", "targets": 0 }
		  ]
    });
} );
</script>
@else
<div data-alert class="alert-box alert round">
No Questions were found
</div>
@endif            
</div>
<a href="{{URL::to('admin/quiz/question/add')}}" class="button expand">Add Question</a>
</div>
@endsection