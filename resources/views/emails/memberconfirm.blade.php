<h3>{{$club->club_name}} Registration</h3>
<p>Hi {{$user->getDisplayName()}},</p>
<p>You have been registered as a member of {{$club->club_name}}</p>
<p>You can log into <a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/login">dojang.nz</a> using the following username and temporary password.</p>
<p><strong>Username:</strong> {{$user->username}}</p>
<p><strong>Password:</strong> {{$password}}</p>