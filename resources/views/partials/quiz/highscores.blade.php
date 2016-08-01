@if(isset($topTenTable) || isset($topTenTableRank))
<ul class="tabs" data-tab role="tablist">
	@if(isset($topTenTable) && count($topTenTable)>0)
  <li class="tab-title active" role="presentation">
  	<a href="#panel2-1" role="tab" tabindex="0" aria-selected="true" aria-controls="panel2-1">Overall</a>
  </li>
  @endif
  @if(isset($topTenTableRank) && count($topTenTableRank)>0)
  <li class="tab-title" role="presentation">
  	<a href="#panel2-2" role="tab" tabindex="0" aria-selected="false" aria-controls="panel2-2">Rank Top Ten</a>
  </li>
  @endif
</ul>
<div class="tabs-content">
@if(isset($topTenTable) && count($topTenTable)>0)
  <section role="tabpanel" aria-hidden="false" class="content active" id="panel2-1">
    <h2>Top Ten scores overall</h2>
    <table id="topTenTable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
	        <th class="all">Name</th>
            <th class="all">Average Rank</th>
            <th class="all">Score</th>
            <th class="desktop">% Correct</th>
        </tr>
    </thead>
    <tbody>
@foreach($topTenTable as $t)
<tr>
	<td>{{$t->name}}</td>
	<td data-order="{{$t->rank_num}}" data-search="{{$t->rank_name}} {{$t->rank_alias}}">
    @if($t->rank_id!=NULL)
    	@if($t->rank_image_url!='')
		    <img src="{{URL::to('/')}}/{{$t->rank_image_url}}" />
        @else
        	{{$t->rank_name}}
    	@endif
    @endif
    </td>
    <td>{{$t->score}}</td>
    <td>{{round(($t->number_correct/$t->test_total)*100)}}</td>
</tr>
@endforeach      
    </tbody>
</table>
  </section>
@endif
@if(isset($topTenTableRank) && count($topTenTable)>0)
  <section role="tabpanel" aria-hidden="true" class="content" id="panel2-2">
    <h2>Top Ten scores for {{$rank->rank_name}}</h2>
    <table id="topTenRankTable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
	        <th class="all">Name</th>
            <th class="all">Average Rank</th>
            <th class="all">Score</th>
            <th class="desktop">% Correct</th>
        </tr>
    </thead>
    <tbody>
@foreach($topTenTableRank as $t)
<tr>
	<td>{{$t->name}}</td>
	<td data-order="{{$t->rank_num}}" data-search="{{$t->rank_name}} {{$t->rank_alias}}">
    @if($t->rank_id!=NULL)
    	@if($t->rank_image_url!='')
		    <img src="{{URL::to('/')}}/{{$t->rank_image_url}}" />
        @else
        	{{$t->rank_name}}
    	@endif
    @endif
    </td>
    <td>{{$t->score}}</td>
    <td>{{round(($t->number_correct/$t->test_total)*100)}}</td>
</tr>
@endforeach      
    </tbody>
</table>
  </section>
@endif
</div>
<script>
$(document).ready(function() {
    $('#topTenRankTable').dataTable({
        responsive: true,
		paging:false,
		"order": [[ 2, "desc" ]],
		"dom": 'f<t>p'
    });
	$('#topTenTable').dataTable({
        responsive: true,
		paging:false,
		"order": [[ 2, "desc" ]],
		"dom": 'f<t>p'
    });
} );
$(document).foundation('tab', 'reflow');
</script>
@endif