<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>{{env("CHAT_NAME")}} | Login</title>
    <link href="{{url('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('css/admin.css')}}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4 main">
            <div id="admin-login">
                <h3>تسجيل الدخول</h3>

                <form action="{{ url(env("ADMIN_HASH").'/admin') }}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="form-group">
                        <label for="username" style="color: #777; font-weight: normal;">إسم المستخدم</label>
                        <input type="text" name="username" id="username" placeholder="إسم المستخدم"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password" style="color: #777; font-weight: normal;">كلمة المرور</label>
                        <input AUTOCOMPLETE='OFF' type="password" name="password" id="password" placeholder="كلمة المرور" class="form-control">
                    </div>
                    <input type="submit" value="دخول" class="btn btn-success" style="width: 100px;">
                </form>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>

</body>
</html>

