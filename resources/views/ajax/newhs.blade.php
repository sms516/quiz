@if(isset($topTen) && $topTen>0)
<div class="alert-box success">You have placed {{$topTen}} overall for {{ucFirst($testType)}} tests</div>
@endif
@if(isset($topTenRank) && $topTenRank>0)
<div class="alert-box success">You have placed {{$topTen}} overall for {{ucFirst($testType)}} tests with an average level of {{$rank->rank_name}}</div>
@endif
@if((isset($topTen) && $topTen>0) || (isset($topTenRank) && $topTenRank>0))
<div class="name-field">
    <label>Name
    <input type="text" class="form-control" name="hs_name" id="hs_name" value="{{ isset($user)?$user->getDisplayName():'' }}">
    </label>
</div>	
<div class="button success" onclick="saveHS()">Save High Score</div>
@endif
@include('partials.quiz.highscores')