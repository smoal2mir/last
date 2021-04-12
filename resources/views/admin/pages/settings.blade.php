@extends('admin.master')

@section('content')
    <script src="{{url('js/socket.io.js')}}"></script>
    <script>
        var socket = io(socketHost, {transports: ['websocket', 'xhr-polling', 'polling', 'htmlfile', 'flashsocket'],
            secure: true,
            rejectUnauthorized: false,
            query: "admin_hash="+'{{env('ADMIN_HASH')}}'}
        );
    </script>
    <?php $activeLI = 4; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        @if(Session::has("errors"))
            <div class="alert alert-danger">{{$errors->first()}}</div>
        @endif

        @if(Session::has("success_msg"))
            <div class="alert alert-success">{{Session::get("success_msg")}}</div>
        @endif

        @if(Session::has("reload_settings"))
            <script> socket.emit("req", { type: "ADMIN_RESET_SETTINGS", data: { admin_hash: '{{env("ADMIN_HASH")}}' } }); </script>
        @endif

        <div id="settings-section">
            <div class="section">
                <form action="{{ url(env('ADMIN_HASH').'/admin/settingsCredintials') }}" method="POST">
                    {{csrf_field()}}
                    <h3>بيانات المدير</h3>
                    <div class="form-group">
                        <label for="username">الإسم</label>
                        <input type="text" name="username" id="username" placeholder="إسم المستخدم" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="password">كلمة المرور</label>
                        <input AUTOCOMPLETE='OFF' type="password" name="password" id="password" placeholder="كلمة المرور" class="form-control" />
                    </div>
                    <input type="submit" value="تغيير" class="btn btn-success" />
                </form>
            </div>
            <div class="section">
                <h3>إعدادات الحائط</h3>
                <p><a href="#" id="clear-wall" class="btn btn-danger" {{count(App\Wall::all()) ? "" : "disabled"}}>تفريغ الحائط</a></p>
                <form action="{{ url(env('ADMIN_HASH').'/admin/wallSettings') }}" method="POST">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="wall_msg_count">عدد الرسائل الظاهرة على الحائط</label>
                        <input type="number" name="wall_msg_count" id="wall_msg_count" min="5" max="50" value="{{ $options->wall_msg_count }}" class="form-control" />
                    </div>
                    <input type="submit" value="حفظ الإعدادات" class="btn btn-success"/>
                </form>
            </div>
            <div class="section">

                <div style="margin-bottom: 15px; padding-bottom: 15px;">
                    <button id="start-chat" style="margin-left: 5px;" class="btn btn-success btn-start-chat pull-left">تشغيل الشات </button>
                    <button id="restart-chat" style="margin-left: 5px;" class="btn btn-warning btn-restart-chat pull-left">إعادة تشغيل الشات </button>
                    <button id="stop-chat" style="margin-left: 5px;" class="btn btn-danger btn-stop-chat pull-left">إيقاف الشات </button>
                </div>

            </div>
            <div class="section">
                <h3>الإعدادات العامة</h3>
                <form action="{{ url(env('ADMIN_HASH').'/admin/settings') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="required_likes_wall">لايكات الحائط</label>
                        <input type="number" name="required_likes_wall" id="required_likes_wall" value="{{ $options->required_likes_wall }}" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="required_likes_notify">لايكات التنبيهات</label>
                        <input type="number" name="required_likes_notify" id="required_likes_notify" value="{{ $options->required_likes_notify }}" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="required_likes_private">لايكات الخاص</label>
                        <input type="number" name="required_likes_private" id="required_likes_private" value="{{ $options->required_likes_private}}" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="public_msg_length">طول الرسالة العامة</label>
                        <input type="number" name="public_msg_length" id="public_msg_length" value="{{ $options->public_msg_length }}" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="private_message_length">طول الرسالة الخاصة</label>
                        <input type="number" name="private_message_length" id="private_message_length" value="{{ $options->private_msg_length }}" class="form-control" />
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="allow_multi_sessions" id="allow_multi_sessions" <?php if($options->allow_multi_sessions) echo 'checked="checked"'; ?> />
                        <label for="allow_multi_sessions">السماح للمستخدمين بالدخول بأكثر من إسم على نفس المتصفح</label>
                    </div>
                    <div class="form-group">
                        <label for="time_between_messages">المدة الزمنية (بالثواني) بين الرسائل</label>
                        <input type="number" name="time_between_messages" id="time_between_messages" value="{{ $options->time_between_messages }}" class="form-control" />
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="disable_private_messages" id="disable_private_messages" <?php if($options->disable_private_messages) echo 'checked="checked"'; ?>/>
                        <label dir="rtl" for="disable_private_messages">إلغاء المحادثات الخاصة (على الجميع)</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="disable_guest_private_messages" id="disable_guest_private_messages" <?php if($options->disable_guest_private_messages) echo 'checked="checked"'; ?>/>
                        <label dir="rtl" for="disable_guest_private_messages">إلغاء المحادثات الخاصة (على الزوار)</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="disable_foreing_contries" id="disable_foreing_contries" <?php if($options->disable_foreing_contries) echo 'checked="checked"'; ?> />
                        <label for="disable_foreing_contries">منع دخول الدول الأجنبية</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="disable_guest_login" id="disable_guest_login" <?php if($options->disable_guest_login) echo 'checked="checked"'; ?> />
                        <label for="disable_guest_login">منع دخول الزوار الغير مسجلين</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="disable_signup" id="disable_signup" <?php if($options->disable_signup) echo 'checked="checked"'; ?> />
                        <label for="disable_signup">منع تسجيل عضويات جديدة</label>
                    </div>
                    <div class="form-group">
                        <label for="bolcked_countries">قائمة الدول المحظورة</label>
                        <textarea rows="15" name="bolcked_countries" id="bolcked_countries" class="form-control">{{ $options->bolcked_countries }}</textarea>
                    </div>
                    <input type="submit" value="حفظ التعديلات" class="btn btn-success" />
                </form>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    @include("scripts.settings")
@stop


















