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
    
    <link href="{{URL::to('/')}}/css/public.css" rel="stylesheet">
    <script src="{{URL::to('/')}}/foundation-5.5.2/js/vendor/modernizr.js"></script>
    <script src="{{URL::to('/')}}/js/jquery/jquery.js"></script>
    <script src="{{URL::to('/')}}/foundation-5.5.2/js/vendor/fastclick.js"></script>

    <script src="{{URL::to('/')}}/foundation-5.5.2/js/foundation.min.js"></script>
<style>
#behind, #background {
    position: absolute;
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
</style>
</head>

<body>
<div id="behind"></div>
<div id="background"></div>
<div class="publicTop">
<h1>Martial Arts Club Management @yield('title')</h1>
</div>
<div class="container">
   @yield('content') 
</div>
<script>
  $(document).foundation();
</script>
</body>

</html>
