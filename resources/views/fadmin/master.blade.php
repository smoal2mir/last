<?php

$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);

$page = in_array($uri_segments[2], ["home", "reg", "shortcuts", "events", "users", "virtuals", "developer", "ban", "privileges", "filter", "filtered-words", "self-messages", "subscriptions", "settings", "gifts-and-faces", "btn-bgs", "rooms"]) ? $uri_segments[2] : "reg";

?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/x-icon" href="/favicon.png">

    <title>{{settings("chat_name")}} | الإدارة</title>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('font-awesome-4.7.0/css/font-awesome.min.css')}}">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="{{asset('css/ie10-viewport-bug-workaround.css')}}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('/css/fadmin.css?v=3') }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div id="main-container">
    @include('fadmin/partials/sidebar', [$page])
    @yield('content')
</div>

<script src="{{asset("js/jquery-1.11.1.min.js")}}"></script>
<script src="{{asset("js/jscolor.js")}}"></script>
{{--<script src="{{url("js/bootstrap.min.js")}}"></script>--}}
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="{{asset("js/ie10-viewport-bug-workaround.js")}}"></script>
<script >var socketHost = '{{env("SOCKET_IO_HOST")}}';</script>
<script>

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }});

    $(".sidebar li").on("click", function(e) {
        $(this).siblings().removeClass("active");
        $(this).addClass("active");
    });
</script>
@yield('scripts')
</body>
</html>
