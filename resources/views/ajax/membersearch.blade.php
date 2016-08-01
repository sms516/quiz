@if(count($members)==0)
<div data-alert class="alert-box alert radius">
                                  Search for '{{$search}}' returned no results
                                </div>
@elseif(count($members)==1)
<div class="row">
<div class="small-6 medium-3 columns">
<img src="{{$members[0]->getImgSrc()}}" />
</div>
<div class="small-6 medium-9 columns">
<input type="hidden" name="user_id[]" value="{{$members[0]->user_id}}" />
<input id="checkin{{$members[0]->user_id}}" type="hidden" name="checkin[]" value="Yes" /> 
<h3>{{$members[0]->getDisplayName()}}</h3>
<h4>{{$members[0]->rank_name}}</h4>
</div>
</div>
@if(isset($familyMembers) && sizeof($familyMembers)>0)
@include('ajax.familylist')
@endif
<button type="submit" id="signIn" class="">Sign In</button>
@else
<div data-alert class="alert-box info radius">
Search Returned multiple results. Click on the correct person from the table below to sign in
</div>
<table id="searchTable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
	        <th class="desktop">&nbsp;</th>
            <th class="all">First Name</th>
            <th class="all">Surname</th>
            <th class="desktop">Rank</th>
        </tr>
    </thead>
    <tbody>
@foreach($members as $m)
<tr class="pointer" onclick="selectStudent('{{strtolower($club->club_short_name)}}','{{$m->username}}')">
	<td class="userImgCell">
    	<img src="{{$m->getImgSrc()}}" style="height:50px;" />
    </td>
	<td>{{$m->f_name}}</td>
    <td>{{$m->l_name}}</td>
    <td data-order="{{$m->rank_num}}" data-search="{{$m->rank_name}} {{$m->rank_alias}}">
    @if($m->rank_id!=NULL)
    	@if($m->rank_image_url!='')
		    <img src="{{URL::to('/')}}/{{$m->rank_image_url}}" />
        @else
        	{{$m->rank_name}}
    	@endif
    @endif
    </td>   
</tr>
@endforeach      
    </tbody>
</table>
<style>
.userImgCell
{
width:60px;	
}
</style>
<script>
$(document).ready(function() {
    $('#searchTable').dataTable({
        responsive: true,
		paging:false,
		"dom": ''
    });
} );
</script>
@endif

