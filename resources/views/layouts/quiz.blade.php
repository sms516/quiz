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
<div class="large-8 large-offset-2 medium-12 small-12 columns">
   @yield('content') 
</div>
<script>
  $(document).foundation();
  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
</body>

</html>
