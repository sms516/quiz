<div class="row">
    <div class="small-12 large-2 columns show-for-large-up">
    <img src="{{$user->getImgSrc()}}" />
    @if($user->currentRank()!=NULL && $user->currentRank()->rank_image_url!='')
    <img src="{{URL::to('/')}}/{{$user->currentRank()->rank_image_url}}" />
    @endif
    </div>
    <div class="small-12 large-10 columns">
        <div class="row">
            <div class="small-6 medium-3 large-2 columns">
                <h3>Name:</h3>
            </div>
            <div class="small-6 medium-9 large-10 columns">
                <h3>{{$user->getDisplayName()}}</h3>
            </div>
        </div>
        <div class="row">
            <div class="small-6 medium-3 large-2 columns">
                <h3>Rank:</h3>
            </div>
            <div class="small-6 medium-9 large-10 columns">
                <h3>{{$user->currentRank()!=NULL ?$user->currentRank()->rank_name: 'No Rank'}}</h3>
            </div>
        </div>
        <div class="row">
            <div class="small-6 medium-3 large-2 columns">
                <h3>Last Grading:</h3>
            </div>
            <div class="small-6 medium-9 large-10 columns">
              @if($user->currentRank()==NULL)
	            <h3 class="headerSuccess">User has no Rank</h3>
              @elseif($user->hasMetTimeRequirement($club->club_id)===true)
                <h3 class="headerSuccess">{{$user->gradingDate()}}</h3>
              @elseif($user->hasMetTimeRequirement($club->club_id)===false)
                <h3>{{$user->gradingDate()}}</h3>
              @else
                <h3 class="headerFalse">{{$user->gradingDate()}}
                <span>({{$user->hasMetTimeRequirement($club->club_id)}})</span></h3>
              @endif
            </div>
        </div>
        <div class="row">
            <div class="small-6 medium-3 large-2 columns">
                <h3>Trainings:</h3>
            </div>
            <div class="small-6 medium-9 large-10 columns">

                 @if($user->currentRank()==NULL)
	              <h3 class="headerSuccess">No Previous Gradings</h3>
              	@elseif($user->hasMetAttendanceRequirement($club->club_id)===true)
                  <h3 class="headerSuccess">{{$user->getAttendanceCount()}}</h3>
                @elseif($user->hasMetAttendanceRequirement($club->club_id)===false)
                  <h3>{{$user->getAttendanceCount()}}</h3>
                @else
                  <h3 class="headerFalse">{{$user->getAttendanceCount()}}
                  <span>({{$user->hasMetAttendanceRequirement($club->club_id)}})</span></h3>
                @endif
            </div>
        </div>

    </div>
</div>
<style>
.headerSuccess{
color:#43AC6A;
}
.headerFalse{
  color:#f04124;
}
.headerFalse span{
font-size:0.5em;
}
</style>
