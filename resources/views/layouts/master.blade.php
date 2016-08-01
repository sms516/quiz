<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dojang @yield('title')</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{URL::to('/')}}/foundation-5.5.2/css/foundation.min.css" rel="stylesheet">
    
    <link href="{{URL::to('/')}}/css/dashboard.css" rel="stylesheet">
    <script src="{{URL::to('/')}}/foundation-5.5.2/js/vendor/modernizr.js"></script>
    <script src="{{URL::to('/')}}/js/jquery/jquery.js"></script>
    <script src="{{URL::to('/')}}/js/functions.js"></script>
    <script src="{{URL::to('/')}}/foundation-5.5.2/js/vendor/fastclick.js"></script>
    <script src="{{URL::to('/')}}/foundation-5.5.2/js/foundation.min.js"></script>
    @yield('includes')
<style>
#behind, #background {
    position: fixed;
	top:0;
	left:0;
    width: 100%;
    height: 100%;
    background: url('{{URL::to('images')}}/dojangBG.jpg');
	-webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
#background {
    filter: blur(7px) brightness(0.75);
    -webkit-filter: blur(7px) brightness(0.75);
    -moz-filter: blur(7px) brightness(0.75);
    -ms-filter: blur(7px) brightness(0.75);
    -o-filter: blur(7px) brightness(0.75);
}
.row{max-width:100%;}
.padTop{height:100px;}
</style>
</head>

<body>
<div id="behind"></div>
<div id="background"></div>
<nav class="top-bar" data-topbar role="navigation">
<ul class="title-area">
<li class="name">
  <h1><a href="{{URL::to('/')}}">Dojang.nz</a></h1>
</li>
 <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
</ul>

  <section class="top-bar-section">
    <!-- Right Nav Section -->
    <ul class="right">
      <li class="has-dropdown">
        <a href="#">User</a>
        <ul class="dropdown">
          <li><a href="#">Profile</a></li>
          <li class="active"><a href="{{URL::to('/logout')}}">Logout</a></li>
        </ul>
      </li>
    </ul>
    <ul class="left">
      <li class="active has-dropdown"><a href="{{URL::to('/admin')}}">Admin</a>
      <ul class="dropdown">
          <li><a href="{{URL::to('/admin/user')}}">Users</a></li>
          <li><a href="{{URL::to('/admin/role')}}">Roles</a></li>
          <li><a href="{{URL::to('/admin/permission')}}">Permissions</a></li>
          <li><a href="{{URL::to('/admin/rank/group')}}">Ranks</a></li>
          <li><a href="{{URL::to('/admin/membership')}}">Membership Types</a></li>
        </ul>
      </li>
      <li class="active has-dropdown"><a href="{{URL::to('/admin/club')}}">Club</a>
      <ul class="dropdown">
          <li><a href="{{URL::to('/admin/club')}}">View</a></li>
          <li><a href="{{URL::to('/admin/club/add')}}">Add</a></li>
        </ul>
      </li>
    </ul>
  </section>
</nav>
<div class="row fullHeight mainContent small-uncollapse">
<div class="large-2 medium-3 columns navWrap fullHeight show-for-medium-up">
              <div class="hide-for-small">
                <div class="sidebar">
  <nav>
    <ul class="side-nav">
      <li class="heading">User</li>
	  <li class="divider"></li>
      <li class="heading">Admin</li>
      <li><a href="{{URL::to('/admin')}}">Menu</a></li>
      <li><a href="{{URL::to('/admin/user')}}">Users</a></li>
      <li><a href="{{URL::to('/admin/role')}}">Roles</a></li>
      <li><a href="{{URL::to('/admin/permission')}}">Permissions</a></li>
      <li><a href="{{URL::to('/admin/rank/group')}}">Ranks</a></li>
      <li><a href="{{URL::to('/admin/membership')}}">Membership Types</a></li>
      <li class="divider"></li>
      <li class="heading">Club</li>
      <li><a href="{{URL::to('/admin/club')}}">View Clubs</a></li>
      <li><a href="{{URL::to('/admin/club/add')}}">Add Club</a></li>

    </ul>
  </nav>
</div>
</div>
</div>
<div class="large-10 medium-9 small-12 columns">
   @yield('content') 
</div>
<script>
  $(document).foundation();
</script>
</body>

</html>
