<table id="reviewTable" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
	        <th>&nbsp;</th>
            <th>Question</th>
            <th>Your Answer</th>
            <th>Correct Answer</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
@foreach($quizQuestions as $q)
<tr>
	<td data-order="{{$q->rank_num}}" data-search="{{$q->rank_name}} {{$q->rank_alias}}">
    @if($q->rank_id!=NULL)
    	@if($q->rank_image_url!='')
		    <img src="{{URL::to('/')}}/{{$q->rank_image_url}}" />
        @else
        	{{$q->rank_name}}
    	@endif
    @endif
    </td> 
	<td>{{$q->question}}</td>
    <td class="{{$q->your_answer==$q->answer?'success':'alert'}}" >{{$q->your_answer}}</td>
    <td class="{{$q->your_answer==$q->answer?'success':'alert'}}" >{{$q->answer}}</td>
    <td class="{{$q->your_answer==$q->answer?'success':'alert'}}" >{{$q->your_answer==$q->answer?'Correct':'Incorrect'}}</td>
</tr>
@endforeach      
    </tbody>
</table>
<script>
$(document).ready(function() {
    $('#reviewTable').dataTable({
        responsive: true,
		paging:false,
		"dom": 'f<t>p',
		"aaSorting": []
    });
} );
</script>
<style>
td.alert
{
	background-color:#f04124;	
}
td.success
{
	background-color:#43AC6A;
}
</style>