@section('topNav')
  <section class="top-bar-section">
    <!-- Right Nav Section -->
    <ul class="right">
      <li class="has-dropdown">
        <a href="#">User</a>
        <ul class="dropdown">
          <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/profile">Profile</a></li>
          <li class="active"><a href="{{URL::to('/logout')}}">Logout</a></li>
        </ul>
      </li>
    </ul>
    <ul class="left">
    <li class="has-dropdown"><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}">Admin</a>
      <ul class="dropdown">
          <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/customize">Customize</a></li>
          <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/import">Import Students</a></li>
          <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/stream">Streams</a></li>
          <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/class">Classes</a></li>
          <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/fees">Fees</a></li>
          <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/family">Families</a></li>
          <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/payment">Payments</a></li>
          <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/syllabus">Syllabus</a></li>
          <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/training">Training Plan</a></li>
        </ul>
      </li>
      <li class="has-dropdown"><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/reports/attendance">Reports</a>
      <ul class="dropdown">
      	<li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/reports/attendance">Attendance</a></li>
        <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/reports/fees">Fees</a></li>
        <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/reports/syllabus">Syllabus</a></li>
        </ul>
      </li>
      <li class="has-dropdown"><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/members">Members</a>
      	<ul class="dropdown">
	     <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/members">View</a></li>
	     <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/alumni">Alumni</a></li>
         <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/memberships">Memberships</a></li>
         </ul>
       </li>
       <li class="has-dropdown"><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/attendance">Attendance</a>
       <ul class="dropdown">
	     <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/attendance">Mark</a></li>
         <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/attendance/self">Check In</a></li>
         </ul>
       </li>
      </li>
    </ul>
  </section>
@endsection
@section('sideNav')
  <li class="heading">Admin</li>
      <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/customize">Customize</a></li>
      <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/import">Import Students</a></li>
      <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/stream">Streams</a></li>
      <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/class">Classes</a></li>
      <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/fees">Fees</a></li>
      <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/family">Families</a></li>
      <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/payment">Payments</a></li>
      <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/syllabus">Syllabus</a></li>
      <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/training">Training plan</a></li>
    <li class="heading">Reports</li>
      <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/reports/attendance">Attendance</a></li>
      <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/reports/fees">Fees</a></li>
      <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/reports/syllabus">Syllabus</a></li>
    <li class="heading">Members</li>
      <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/members">View</a></li>
        <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/alumni">Alumni</a></li>
        <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/admin/memberships">Memberships</a></li>
    <li class="heading">Attendance</li>
   <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/attendance">Mark</a></li>
     <li><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}/attendance/self">Check In</a></li>
   @endsection
