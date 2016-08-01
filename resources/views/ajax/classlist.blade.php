<table id="membersTable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
	        <th class="desktop">&nbsp;</th>
            <th class="all">First Name</th>
            <th class="all">Surname</th>
            <th class="desktop">Rank</th>
            <th class="all">Present</th>
            <th class="desktop">Status</th>
        </tr>
    </thead>
    <tbody>
@foreach($members as $m)
<tr>
	<td class="userImgCell">
    	<img src="{{$m->getImgSrc()}}" style="height:50px;" />
    </td>
	<td>{{$m->f_name}}<input type="hidden" name="user_id[]" value="{{$m->user_id}}" /></td>
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
    <td>
    <div class="switch">
      <input id="checkbox{{$m->user_id}}" type="checkbox" name="checkbox[]" onchange="setAttend(this,{{$m->user_id}})" 
      {{$attendance[$m->user_id]!=null && $attendance[$m->user_id]->checkin_status!=NULL && $attendance[$m->user_id]->checkin_status!='absent_explained'?'checked':''}} >
      <label for="checkbox{{$m->user_id}}"></label>
    </div> 
    <input id="checkin{{$m->user_id}}" type="hidden" name="checkin[]" value="{{$attendance[$m->user_id]!=null && $attendance[$m->user_id]->checkin_status!=NULL && $attendance[$m->user_id]->checkin_status!='absent_explained'?'Yes':'No'}}" /> 
    </td>
    <td>
      <select class="form-control checkinStatus" name="checkin_status[]">
                <option value="ontime" {{$attendance[$m->user_id]!=null && $attendance[$m->user_id]->checkin_status=='ontime'?'selected="selected"':''}} >On Time</option>
                <option value="late" {{$attendance[$m->user_id]!=null && $attendance[$m->user_id]->checkin_status=='late'?'selected="selected"':''}} >Late</option>
                <option value="late_explained" {{$attendance[$m->user_id]!=null && $attendance[$m->user_id]->checkin_status=='late_explained'?'selected="selected"':''}}>Late - Explained</option>
                <option value="absent_explained" {{$attendance[$m->user_id]!=null && $attendance[$m->user_id]->checkin_status=='absent_explained'?'selected="selected"':''}}>Absent - Explained</option>
            </select>
    </td>
</tr>
@endforeach      
    </tbody>
</table>
<style>
.checkinStatus
{
width:80%;	
}
.userImgCell
{
width:60px;	
}
</style>
<script>
$(document).ready(function() {
    $('#membersTable').dataTable({
        responsive: true,
		paging:false,
		"dom": 'f<t>p'
    });
} );
function setAttend(el,user_id)
{
	if($(el).is(':checked'))
	{
		$("#checkin"+user_id).val("Yes");
	}
	else
	{
		$("#checkin"+user_id).val("No");
	}
}
</script>