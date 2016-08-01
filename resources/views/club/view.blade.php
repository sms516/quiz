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
@if(count($clubs)>0)
<div class="small-12 columns">
<table id="clubTable" class="display responsive nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Name</th>
            <th>Short Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Location</th>
        </tr>
    </thead>
    <tbody>
@foreach($clubs as $c)
<tr>
	<td>{{$c->club_name}}</td>
    <td>{{$c->club_short_name}}</td>
    <td>{{$c->club_email}}</td>
    <td>{{$c->club_phone}}</td>
    <td>{{$c->club_location}}</td>
</tr>
@endforeach      
    </tbody>
</table>
</div>
<script>
$(document).ready(function() {
    $('#clubTable').dataTable({
        responsive: true,
		"dom": 'f<t>p'
    });
} );
</script>
@else
<div data-alert class="alert-box alert round">
No Clubs were found
</div>
@endif            
</div>
</div>
@endsection