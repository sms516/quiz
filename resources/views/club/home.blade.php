@extends('layouts.club')
@section('includes')
<link href="{{URL::to('/')}}/plugins/DataTables-1.10.7/media/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="{{URL::to('/')}}/plugins/DataTables-1.10.7/extensions/Responsive/css/dataTables.responsive.css" rel="stylesheet">
<script src="{{URL::to('/')}}/plugins/DataTables-1.10.7/media/js/jquery.dataTables.min.js"></script>
<script src="{{URL::to('/')}}/plugins/DataTables-1.10.7/extensions/Responsive/js/dataTables.responsive.js"></script>
<script src="{{URL::to('/')}}/plugins/jquery.countdown-2.1.0/jquery.countdown.min.js"></script>
@endsection
@section('title') - {{$title}}
@endsection
@section('content')
<div id="contentWrapper">
<h2>{{$title}}</h2>
@if(isset($progress))
<div class="row">
<label>Club Setup Progress: {{$progress}}%</label>
<div class="progress round">
  <span class="meter" style="width: {{$progress}}%"></span>
</div>
@if(isset($progressMessages))
<ul>
@foreach($progressMessages as $m)
<li><a href="{{$m['path']}}">{{$m['message']}}</a></li>
@endforeach
</ul>
@endif
</div>
@endif
<div class="row">
@include('partials.club.training')
</div>
</div>
@endsection