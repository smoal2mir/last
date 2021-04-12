@extends('fadmin.master')

@section('content')
    <div id="main-content" class="settings-page">

        <div class="row">
            <div id="settings-section">

                @if(Session::has("success_mesg"))
                    <div class="alert alert-success">{{Session::get("success_mesg")}}</div>
                    @endif

                    <!-- SEO Settings -->
                    <div class="section">
                        <h1><span dir="rtl">إعدادات محرك البحث</span></h1>

                        <form action="{{$token}}/seoSettings" method="POST">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label class="form-label" for="settings-chat-name">إسم الشات</label>
                                <textarea class="form-control" name="chat_name" id="settings-chat-name" placeholder="شات فلان الكتابي">{{$settings->chat_name}}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="settings-chat-title">عنوان الصفخة</label>
                                <textarea class="form-control" name="chat_title" id="settings-chat-title" placeholder="شات  فلان الكتابي للجوال - شات خليجي عربي">{{$settings->chat_title}}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="settings-chat-description">وصف الشات</label>
                                <textarea class="form-control" name="chat_desc" id="settings-chat-description" placeholder="شات فلان الكتابي للجوال, دردشة, مسابقات, ألعاب, تعارف, بدون تسجيل أو تحميل">{{$settings->chat_desc}}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="settings-chat-keywords">الكلمات الدلالية</label>
                                <textarea class="form-control" name="chat_keywords" id="settings-chat-keywords" placeholder="شات فلان, شات فلان الكتابي, شات خليجي, شات بدون تسجيل, شات بدون تحميل">{{$settings->chat_keywords}}</textarea>
                            </div>

                            <input type="submit" value="حفظ" class="btn btn-success"/>
                        </form>
                    </div>

                    <!-- Wall Settings -->
                    <div class="section">
                        <h3>إعدادات الحائط</h3>
                        <button id="clear-wall" class="btn btn-danger" >تفريغ الحائط</button>
                        <form action="{{$token}}/wallSettings" method="POST">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label for="wall_msg_count">عدد الرسائل الظاهرة على الحائط</label>
                                <input type="number" name="wall_msg_count" id="wall_msg_count" min="5" max="50" value="{{ $settings->wall_msg_count }}" class="form-control" />
                            </div>

                            <div class="form-group">
                                <input type="checkbox" name="show_wall_banner" id="show_wall_banner" <?php if($settings->show_wall_banner) echo 'checked="checked"'; ?>/>
                                <label for="show_wall_banner">إظهار البنر</label>
                            </div>

                            <div class="form-group">
                                <label for="wall_banner_msg">رسالة البنر</label>
                                <textarea rows="5" name="wall_banner_msg" id="wall_banner_msg" class="form-control">{{ $settings->wall_banner_msg }}</textarea>
                            </div>

                            <div class="form-group">
                                <input class="jscolor form-input" type="text" name="wall_banner_background_color" id="wall_banner_background_color" value="{{ $settings->wall_banner_background_color }}" />
                                <label for="wall_banner_background_color">لون خلفية البنر</label>
                            </div>

                            <div class="form-group">
                                <input class="jscolor form-input" type="text" name="wall_banner_font_color" id="wall_banner_font_color" value="{{ $settings->wall_banner_font_color }}" />
                                <label for="wall_banner_font_color">لون الكتابة على البنر</label>
                            </div>

                            <input type="submit" value="حفظ" class="btn btn-success"/>
                        </form>
                    </div>

                    <!-- Interface Settings -->
                    <div class="section">
                        <h3>إعدادات الواجهة</h3>
                        <form action="{{$token}}/interfaceSettings" method="POST">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <input class="jscolor form-input" type="text" name="background_color" id="background_color" value="{{ $settings->background_color }}" />
                                <label for="background_color">لون الخلفية</label>
                            </div>

                            <div class="form-group">
                                <input class="jscolor form-input" type="text" name="btn_color" id="btn_color" value="{{ $settings->btn_color }}" />
                                <label for="btn_color">لون الأزرار</label>
                            </div>

                            <div class="form-group">
                                <input class="jscolor form-input" type="text" name="window_color" id="window_color" value="{{ $settings->window_color }}" />
                                <label for="window_color">لون النوافذ</label>
                            </div>

                            <div class="form-group">
                                <input class="jscolor form-input" type="text" name="border_color" id="border_color" value="{{ $settings->border_color }}" />
                                <label for="border_color">لون الحواف</label>
                            </div>

                            <div class="form-group">
                                <input type="checkbox" name="show_banner" id="show_banner" <?php if($settings->show_banner) echo 'checked="checked"'; ?>/>
                                <label for="show_banner">إظهار المساحة الإعلانية</label>
                            </div>

                            <div class="form-group">
                                {{--<label for="banner_img">المساحة الإعلانية</label><br />--}}
                                <img id="banner_img" src="{{$settings->banner_img ? "/uploads/".$settings->banner_img : "/images/banner.png"}}" alt=""/>
                                <img src="/images/loader.gif" id="banner_loader" alt="" style="display: none"/>
                                <input type="file" id="banner_input" style="display: none; position: absolute; left: -99999px;"/>
                            </div>

                            <div class="form-group">
                                <input type="checkbox" class="input" id="show_chat_background" name="show_chat_background" <?php if($settings->show_chat_background) echo 'checked="checked"'; ?>/>
                                <label for="show_chat_background">تفعيل خلفية الشات</label>
                            </div>

                            <div class="form-group">
                                {{--<label for="background">خلفية الشات</label><br />--}}
                                <img id="chat_background_img" src="{{settings("chat_background") ? "/uploads/".$settings->chat_background : getDefaultChatBackground()}}" alt=""/>
                                <img src="/images/loader.gif" id="chat_background_loader" alt="" style="display: none"/>
                                <input type="file" id="chat_background_input" style="display: none; position: absolute; left: -99999px;"/>
                            </div>

                            <div class="form-group">
                                <input type="checkbox" class="input" id="show_chat_background_on_windows" name="show_chat_background_on_windows" <?php if($settings->show_chat_background_on_windows) echo 'checked="checked"'; ?>/>
                                <label for="show_chat_background_on_windows">تفعيل خلفية الشات على النوافذ</label>
                            </div>

                            <input type="submit" value="حفظ" class="btn btn-success"/>
                        </form>
                    </div>

                    <!-- Marquee Settings -->
                    <div class="section">
                        <h3>إعدادات الشريط المتحرك</h3>
                        <form action="{{$token}}/marqueeSettings" method="POST">
                            {!! csrf_field() !!}

                            <div class="form-group">
                                <input type="checkbox" class="input" id="disable_marquee" name="disable_marquee" <?php if($settings->disable_marquee) echo 'checked="checked"'; ?>/>
                                <label for="disable_marquee">تفعيل خلفية الشات على النوافذ</label>
                            </div>

                            <div class="form-group">
                                <input class="jscolor form-input" type="text" name="marquee_bg_color" id="marquee_bg_color" value="{{ $settings->marquee_bg_color }}" />
                                <label for="marquee_bg_color">لون الخلفية</label>
                            </div>

                            <div class="form-group">
                                <input class="jscolor form-input" type="text" name="marquee_text_color" id="marquee_text_color" value="{{ $settings->marquee_text_color }}" />
                                <label for="marquee_text_color">لون الكتابة</label>
                            </div>

                            <div class="form-group">
                                <label for="marquee_content">محتوى الشريط</label>
                                <textarea rows="5" name="marquee_content" id="marquee_content" class="form-control">{{ $settings->marquee_content }}</textarea>
                            </div>

                            <input type="submit" value="حفظ" class="btn btn-success"/>
                        </form>
                    </div>

                    <!-- General Settings -->
                    <div class="section">
                        <h3>الإعدادات العامة</h3>

                        <form id="restart-chat-form" action="{{$token}}/restart" method="post">
                            {!! csrf_field() !!}
                            <button style="margin-left: 5px;" class="btn btn-warning btn-restart-chat">إعادة تشغيل الشات </button>
                        </form>

                        <div class="form-group">
                            <label for="admin-settings-general-default-avatar">الإيقونة الإفتراضية للزوار</label>
                            <img id="admin-settings-general-default-avatar_img" src="{{$settings->default_avatar ? "/uploads/avatars/".$settings->default_avatar : "/images/none.png"}}" alt=""/>
                            <img src="/images/loader.gif" id="admin-settings-general-default-avatar_loader" alt="" style="display: none"/>
                            <input type="file" id="admin-settings-general-default-avatar" style="display: none; position: absolute; left: -99999px;"/>
                        </div>

                        <form action="{{$token}}/general" method="POST">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label for="required_likes_wall">لايكات الحائط</label>
                                <input type="number" name="required_likes_wall" id="required_likes_wall" value="{{ $settings->required_likes_wall }}" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="required_likes_notify">لايكات التنبيهات</label>
                                <input type="number" name="required_likes_notify" id="required_likes_notify" value="{{ $settings->required_likes_notify }}" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="required_likes_private">لايكات الخاص</label>
                                <input type="number" name="required_likes_private" id="required_likes_private" value="{{ $settings->required_likes_private}}" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="public_msg_length">طول الرسالة العامة</label>
                                <input type="number" name="public_msg_length" id="public_msg_length" value="{{ $settings->public_msg_length }}" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label for="private_message_length">طول الرسالة الخاصة</label>
                                <input type="number" name="private_message_length" id="private_message_length" value="{{ $settings->private_msg_length }}" class="form-control" />
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="allow_multi_sessions" id="allow_multi_sessions" <?php if($settings->allow_multi_sessions) echo 'checked="checked"'; ?> />
                                <label for="allow_multi_sessions">السماح للمستخدمين بالدخول بأكثر من إسم على نفس المتصفح</label>
                            </div>
                            <div class="form-group">
                                <label for="time_between_messages">المدة الزمنية (بالثواني) بين الرسائل</label>
                                <input type="number" name="time_between_messages" id="time_between_messages" value="{{ $settings->time_between_messages }}" class="form-control" />
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="disable_private_messages" id="disable_private_messages" <?php if($settings->disable_private_messages) echo 'checked="checked"'; ?>/>
                                <label dir="rtl" for="disable_private_messages">إلغاء المحادثات الخاصة (على الجميع)</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="disable_guest_private_messages" id="disable_guest_private_messages" <?php if($settings->disable_guest_private_messages) echo 'checked="checked"'; ?>/>
                                <label dir="rtl" for="disable_guest_private_messages">إلغاء المحادثات الخاصة (على الزوار)</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="disable_foreing_contries" id="disable_foreing_contries" <?php if($settings->disable_foreing_contries) echo 'checked="checked"'; ?> />
                                <label for="disable_foreing_contries">منع دخول الدول الأجنبية</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="disable_guest_login" id="disable_guest_login" <?php if($settings->disable_guest_login) echo 'checked="checked"'; ?> />
                                <label for="disable_guest_login">منع دخول الزوار الغير مسجلين</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="disable_signup" id="disable_signup" <?php if($settings->disable_signup) echo 'checked="checked"'; ?> />
                                <label for="disable_signup">منع تسجيل عضويات جديدة</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="disable_floating_particles" id="disable_floating_particles" <?php if($settings->disable_floating_particles) echo 'checked="checked"'; ?> />
                                <label for="disable_floating_particles">إلغاء الورود و القلوب المتحركة</label>
                            </div>
                            <div class="form-group">
                                <label for="bolcked_countries">قائمة الدول المحظورة</label>
                                <textarea rows="15" name="bolcked_countries" id="bolcked_countries" class="form-control">{{ $settings->bolcked_countries }}</textarea>
                            </div>
                            <input type="submit" value="حفظ" class="btn btn-success" />
                        </form>
                    </div>

            </div>
        </div>

    </div>
@endsection

@section("scripts")
    <script src="{{url('js/socket.io.js')}}"></script>
    @if(Session::has("reset_settings"))
        <script>var resetSettings = true;</script>
    @else
        <script>var resetSettings = false;</script>
    @endif
    <script>
        var token = '{{$token}}';
        var socket = io(socketHost, {transports: ['websocket', 'xhr-polling', 'polling', 'htmlfile', 'flashsocket'],
            secure: true,
            rejectUnauthorized: false,
            query: "token="+token}
        );

        if(resetSettings) {
            socket.emit("req", {
                type: "ADMIN_RESET_SETTINGS",
                data: { token: token }
            });
        }

        setTimeout(function() {
            $(".alert").remove();
        }, 5000);

        $(document).ready(function() {

            $("#clear-wall").click(function() {
                if(confirm("هل أنت متأكد من تفريغ الحائط؟")) {
                    clearWall();
                }
            });

            $(".btn-restart-chat").click(function(e) {
                if(!confirm("هل أنت متأكد من إعادة تشغيل الشات؟"))
                    e.preventDefault();
            });

            $(".logout-virtuals").on("click", function(e) {
                e.preventDefault();
                socket.emit("logoutVirtuals", $(this).data("flag"), token);
            });

            $("#admin-settings-general-default-avatar_img").on("click", function () {
                $("#admin-settings-general-default-avatar").trigger("click");
            });
            $("#admin-settings-general-btn-bg_img").on("click", function () {
                $("#admin-settings-general-btn-bg").trigger("click");
            });

            $("#admin-settings-general-default-avatar").on("change", function() {
                changeDefaultAvatar($(this));
            });

            $("#banner_img").on("click", function () {
                $("#banner_input").trigger("click");
            });

            $("#banner_input").on("change", function() {
                changeBannerImage($(this));
            });

            $("#chat_background_img").on("click", function () {
                $("#chat_background_input").trigger("click");
            });

            $("#chat_background_input").on("change", function() {
                changeChatBackground($(this));
            });


        });

        function clearWall() {
            $.ajax({
                url: window.location.pathname+"/clear-wall",
                type: "post",
                success: function(res) {
                    try {
                        var json = JSON.parse(res);
                        if(json.error === false) {
                            alert("تم تفريغ الحائط بنجاج");
                        } else {
                            if(json.msg) alert(json.msg);
                        }
                    } catch(e) {}
                }
            });
        }

        function changeDefaultAvatar(el) {

            $("#admin-settings-general-default-avatar_img").hide();
            $("#admin-settings-general-default-avatar_loader").show();
            var formData = new FormData();
            formData.append("avatar", el.prop("files")[0]);

            $.ajax({
                xhr: function () {
                    return new window.XMLHttpRequest();
                },
                url: window.location.pathname+"/change-default-avatar",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    try {
                        if (response.error === false) {
                            $("#admin-settings-general-default-avatar_loader").hide();
                            $("#admin-settings-general-default-avatar_img").show().attr("src", "/uploads/avatars/" + response.avatar);
                            socket.emit("req", {
                                type: "ADMIN_USERS_SET_DEFAULT_AVATAR",
                                data: { avatar: response.avatar, token: token }
                            });
                        } else {
                            alert("حدث خطأ أثناء رفع الصورة");
                        }
                    } catch (e) {
                    }
                },
                error: function () {
                    alert("حدث خطأ أثناء رفع الصورة");
                }
            });

        }

        function changeBannerImage(el) {

            $("#banner_img").hide();
            $("#banner_loader").show();
            var formData = new FormData();
            formData.append("banner", el.prop("files")[0]);

            $.ajax({
                xhr: function () {
                    return new window.XMLHttpRequest();
                },
                url: window.location.pathname+"/change-banner",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    try {
                        if (response.error === false) {
                            $("#banner_loader").hide();
                            $("#banner_img").show().attr("src", "/uploads/" + response.banner);

                            socket.emit("req", {
                                type: "ADMIN_RESET_SETTINGS",
                                data: { token: token }
                            });

                        } else {
                            alert("حدث خطأ أثناء رفع الصورة");
                        }
                    } catch (e) {
                    }
                },
                error: function () {
                    alert("حدث خطأ أثناء رفع الصورة");
                }
            });

        }

        function changeChatBackground(el) {

            $("#chat_background_img").hide();
            $("#chat_background_loader").show();
            var formData = new FormData();
            formData.append("background", el.prop("files")[0]);

            $.ajax({
                xhr: function () {
                    return new window.XMLHttpRequest();
                },
                url: window.location.pathname+"/change-chat-background",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    try {
                        if (response.error === false) {
                            $("#chat_background_loader").hide();
                            $("#chat_background_img").show().attr("src", "/uploads/" + response.background);
                            socket.emit("req", {
                                type: "ADMIN_RESET_SETTINGS",
                                data: { token: token }
                            });
                        } else {
                            alert("حدث خطأ أثناء رفع الصورة");
                        }
                    } catch (e) {
                    }
                },
                error: function () {
                    alert("حدث خطأ أثناء رفع الصورة");
                }
            });

        }

    </script>
@stop