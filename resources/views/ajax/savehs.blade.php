@if(isset($message))
<div class="alert-box success">{{$message}}</div>
@endif
@include('partials.quiz.highscores')