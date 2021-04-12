<!doctype html>
<html lang="ar">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta property="og:title" content='{{settings('chat_title')}}' />
    <meta property="og:description" content="{{settings('chat_desc')}}" />
    <meta property="og:image" content="" />
    <meta name="google" content="notranslate" />
    {{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes" />

    <link rel="icon" type="image/x-icon" href="/favicon.png" />

    <title>{{settings("chat_title")}}</title>
    <meta name="description" content="{{settings('chat_desc')}}" />
    <meta name="keywords"    content="{{settings('chat_keywords')}}" />

    {{--Chrome, Firefox OS and Opera --}}
    <meta name="theme-color" content="{{settings('window_color')}}" />
    {{--Windows Phone --}}
    <meta name="msapplication-navbutton-color" content="{{settings('window_color')}}" />
    {{--iOS Safari--}}
    <meta name="apple-mobile-web-app-status-bar-style" content="{{settings('window_color')}}" />
    <script src="{{url('js/socket.io.js')}}"></script>

    <link rel="stylesheet" href="{{ asset("font-awesome-4.7.0/css/font-awesome.min.css") }}" />
    <link rel="stylesheet" href="{{ asset('/css/app.css?v=1') }}" />
    <style>{{settings("css")}}</style>

</head>
<body>

    <div id="app"></div>
    <script >
        var socketHost = '{{env("SOCKET_IO_HOST")}}';
        var settings = {!! json_encode(\App\Settings::parse()) !!};
    </script>
<script>
    // var socket = io('http://localhost:8000');
    // socket.on('connect', function(){alert("asddsa")});
    // socket.on('event', function(data){});
    // socket.on('disconnect', function(){});
  </script> 
    <script src="{{ asset("/js/simplewebrtc.js") }}"></script>
    <script src="{{ asset("/js/clientjs.js") }}"></script>
    <script src="{{ asset("/js/app.js?v=1")  }}"></script>

</body>
</html>