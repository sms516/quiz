<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Dojang @yield('title')</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{URL::to('/')}}/foundation-5.5.2/css/foundation.min.css" rel="stylesheet">
    <script src="{{URL::to('/')}}/foundation-5.5.2/js/vendor/modernizr.js"></script>
    <script src="{{URL::to('/')}}/js/jquery/jquery.js"></script>
	  <script src="{{URL::to('/')}}/js/functions.js"></script>
    <script src="{{URL::to('/')}}/foundation-5.5.2/js/vendor/fastclick.js"></script>
    <script src="{{URL::to('/')}}/foundation-5.5.2/js/foundation.min.js"></script>
    @yield('includes')
    <link href="{{URL::to('/')}}/css/dashboard.css" rel="stylesheet">
<style>
#behind, #background {
    position: fixed;
	top:0;
	left:0;
    width: 100%;
    height: 100%;
	<?php
    $bg=$club->getBG();
    if ($bg!=null) {
        if ($bg['type']=='color') {
            echo 'background-color:#'.$bg['val'].';';
        } elseif ($bg['type']=='img') {
            echo 'background-image: url('.URL::to('/').'/'.$bg['val'].');';
        }
    } else {
        echo 'background-image: url('.URL::to('images').'/dojangBG.jpg'.');';
    }

    ?>
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
@if(Auth::user()->hasPermission('edit_club', $club->club_id))
@include('partials.club.menu.admin')
@else
@include('partials.club.menu.student')
@endif
<nav class="top-bar" data-topbar role="navigation">
<ul class="title-area">
<li class="name">
  <h1><a href="{{URL::to('/')}}/{{strtolower($club->club_short_name)}}">{{$club->club_short_name}} Dojang.nz</a></h1>
</li>
 <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
</ul>
@yield('topNav')

</nav>
<div class="row mainContent small-uncollapse">
<div class="large-2 columns navWrap fullHeight show-for-xlarge-up">
              <div class="hide-for-medium">
                <div class="sidebar">
  <nav>
  	<?php
    $logo=$club->getLogo();
    if ($logo!=null) {
        echo '<img src="'.URL::to('/').'/'.$logo.'" height="100px" />';
    }
    ?>
    <ul class="side-nav">
    	@yield('sideNav')
    </ul>
  </nav>
</div>
</div>
</div>
<div class="small-12 large-10 columns">
@if (session('status'))
<div class="alert-box alert info ">
{{ session('status') }}
</div>
@endif
   @yield('content')
</div>
<script>
  $(document).foundation();
</script>
</body>

</html>
