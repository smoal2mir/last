@extends('fadmin.master')

@section('content')
    <div id="main-content" class="rooms-page">

        @if(Session::has("errors"))
            <div class="alert alert-danger">{{$errors->first()}}</div>
        @endif

        @if(Session::has("success_msg"))
            <div class="alert alert-success">{{Session::get("success_msg")}}</div>
        @endif

        <div>
            <div id="rooms-section">
                <div class="section1">
                    <h3>إضافة رسالة جديدة</h3>

                    <form action="{{url("/fadmn/self-messages/$token/add")}}" method="POST">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="title">عنوان الرسالة</label>
                            <input type="text" name="title" id="title" placeholder="عنوان الرسالة" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="message">محتوى الرسالة</label>
                            <input type="text" name="message" id="message" placeholder="محتوى الرسالة" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="interval">الفاصل الزمني</label>
                            <input type="number" name="interval" id="interval" value="30" placeholder="الفاصل الزمني" class="form-control">
                        </div>
                        <input type="submit" value="أضف" class="btn btn-success" style="width: 100px;">
                    </form>
                </div>

                <div class="section" style="margin-bottom: 15px">
                    <h3>الإعدادات</h3>

                    <div class="form-group">
                        <label for="admin-settings-general-default-room-flag">أيقونة الرسائل</label>
                        <img id="admin-settings-general-default-room-flag_img" src="{{settings("self_messages_icon") ? "/uploads/".settings("self_messages_icon") : "/images/default_room.png"}}" alt=""/>
                        <img src="/images/loader.gif" id="admin-settings-general-default-room-flag_loader" alt="" style="display: none"/>
                        <input type="file" id="admin-settings-general-default-room-flag" style="display: none; position: absolute; left: -99999px;"/>
                    </div>

                    <input accept="image/*" type="file" id="admin-room-flag-input" data-id="0" style="display: none; position: absolute; left: -99999px;"/>

                    <form action="{{$token}}/self-messages-colors" method="POST">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <input type="color" name="self_message_bg_color" id="self_message_bg_color" value="{{ $settings->self_message_bg_color }}" />
                            <label for="self_message_bg_color">لون الخلفية</label>
                        </div>

                        <div class="form-group">
                            <input type="color" name="self_message_title_color" id="self_message_title_color" value="{{ $settings->self_message_title_color }}" />
                            <label for="self_message_title_color">لون العنوان</label>
                        </div>

                        <div class="form-group">
                            <input type="color" name="self_message_content_color" id="self_message_content_color" value="{{ $settings->self_message_content_color }}" />
                            <label for="self_message_content_color">لون المحتوى</label>
                        </div>

                        <input type="submit" value="حفظ" class="btn btn-success"/>
                    </form>

                </div>

                <div class="section2">
                    <h3>الرسائل التلقائية</h3>

                    <div class="section-table-header">
                        <div class="left-section">

                            <div class="table-header">
                                <ul>
                                    <li id="select-all" class="blue select-all"><a href="#">تحديد الكل</a></li>
                                    <li id="unselect-all" style="display: none;" class="blue deselect-all"><a href="#">إلغاء تحديد الكل</a></li>
                                    <li id="rooms-delete" class="red delete"><a class="red" href="#">حذف</a></li>
                                    <li id="rooms-save-changes" class="green save-changes"><a class="green" href="#">حفظ التعديلات</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="clear"></div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th><input id="checkbox-select-all" type="checkbox"style="width: 17px; height: 17px;"></th>
                                <th>العنوان</th><th>الرسالة</th><th>الفاصل الزمني</th><th>الحالة</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $status = ['running' => 'مشغل', 'paused' => 'موقف'];
                            foreach ($messages as $msg) {
                                echo '<tr row_id="' . $msg->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $msg->id . '"></td>';
                                echo '<td width="20%"><input style="width: 100%" name="title" type="text" value="' . $msg->title . '" /></td>';
                                echo '<td><input style="width: 100%" name="message" type="text" value="' . $msg->msg . '" /></td>';
                                echo '<td width="5%"><input style="width: 100%" name="interval" type="number" value="' . $msg->interval . '" /></td>';
                                echo '<td><select>';
                                foreach($status as $key => $value) {
                                    echo ($key == $msg->status) ? '<option selected="selected" value="'.$key.'">'.$value.'</option>' : '<option value="'.$key.'">'.$value.'</option>';
                                }
                                echo '</select></td>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section("scripts")
    <script src="{{ asset('js/socket.io.js?v=1') }}"></script>
    @if(Session::has("success_msg") && Session::get("success_msg") == "تم إضافة الرسالة بنجاح")
        <script>
            var selfMessage = {
                id: '{{json_decode(Session::get("message"))->id}}',
                title: '{{json_decode(Session::get("message"))->title}}',
                msg: '{{json_decode(Session::get("message"))->msg}}',
                interval: '{{json_decode(Session::get("message"))->interval}}',
                status: '{{json_decode(Session::get("message"))->status}}',
            };
        </script>
    @else
        <script>var selfMessage = null;</script>
    @endif

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
            socket.emit("req", { type: "ADMIN_RESET_SETTINGS", data: { token: token } });
        }

        setTimeout(function() {
            $(".alert").remove();
        }, 5000);

        $(document).ready(function() {

            if(selfMessage) {
                socket.emit("req", {
                    type: "ADMIN_SELF_MESSAGES_ADD",
                    data: { message: selfMessage, token: token }
                });
            }

            $("#select-all").on("click", function(e) {
                e.preventDefault();
                $(this).hide();
                $("#unselect-all").show();
                $(".table-check-row, #checkbox-select-all").prop("checked", true);
            });
            $("#unselect-all").on("click", function(e) {
                e.preventDefault();
                $(this).hide();
                $("#select-all").show();
                $(".table-check-row, #checkbox-select-all").prop("checked", false);
            });
            $("#checkbox-select-all").on("click", function() {
                $(".table-check-row").prop("checked", $(this).prop("checked"));
                $("#select-all, #unselect-all").toggle();
            });
            $("#rooms-section .section2 table tbody tr td *").on('change', function() {
                $("input[check_id='"+$(this).parent().parent().attr('row_id')+"']").prop('checked', true);
            });
            $("#rooms-delete").on('click', function(e) {
                e.preventDefault();
                var ids = [];
                $.each($('#rooms-section .section2 .table tbody tr'), function(index, value) {
                    var checkbox = $(this).find("td input[type='checkbox']");
                    if(checkbox.prop("checked")){
                        ids.push(checkbox.attr("check_id"));
                    }
                });
                if(ids.length > 0) {
                    var confirmation = confirm("هل أنت متأكد من حذف الرسائل؟");
                    if(confirmation) {
                        $.ajax({
                            url: location.pathname+"/remove",
                            type: "POST",
                            data: {
                                ids: ids
                            },
                            success: function(result) {
                                try {
                                    var json = JSON.parse(result);
                                    if(!json.error) {
                                        for(var key in json.deleteds) {
                                            $('#rooms-section .section2 .table tbody tr[row_id="'+json.deleteds[key]+'"]').remove();
                                            socket.emit("req", {
                                                type: "ADMIN_SELF_MESSAGES_DELETE",
                                                data: { deleteds: json.deleteds, token: token }
                                            });
                                        }
                                    }
                                } catch(e) {
                                    //console.log(e);
                                }
                            }
                        });
                    }
                } else {
                    alert("الرجاء إختيار على الأقل رسالة واحدة؟");
                }
            });
            $("#rooms-save-changes").on('click', function(e) {
                e.preventDefault();
                var ids         = [];
                var titles      = [];
                var messages    = [];
                var intervals   = [];
                var status      = [];

                $.each($('#rooms-section .section2 .table tbody tr'), function(index, value) {
                    var checkbox = $(this).find("td input[type='checkbox']");
                    if(checkbox.prop("checked")){
                        ids.push(checkbox.attr("check_id"));
                        titles.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='title']").val());
                        messages.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='message']").val());
                        intervals.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='interval']").val());
                        status.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td select").val());
                    }
                });

                if(ids.length > 0) {

                    $.ajax({
                        url: location.pathname+'/edit',
                        type: 'POST',
                        data: {
                            ids: ids,
                            titles: titles,
                            messages: messages,
                            intervals: intervals,
                            status: status,
                        },
                        success: function(result) {
                            try {
                                if(result.error === false) {
                                    socket.emit("req", {
                                        type: "ADMIN_SELF_MESSAGES_UPDATE",
                                        data: { messages: result.messages, token: token }
                                    });
                                    location.reload();
                                }
                            } catch(e) {
                                //console.log(e);
                            }
                        }
                    });
                } else {
                    alert("الرجاء إختيار على الأقل غرفة واحدة");
                }

            });

            $("#search-rooms-submit").on("click", function(e) {
                $(this).closest("form").submit();
            });

            $("#admin-settings-general-default-room-flag_img").on("click", function () {
                $("#admin-settings-general-default-room-flag").trigger("click");
            });

            $("#admin-settings-general-default-room-flag").on("change", function() {
                changeSelfMessagesIcon($(this));
            });

            function changeSelfMessagesIcon(el) {

                $("#admin-settings-general-default-room-flag_img").hide();
                $("#admin-settings-general-default-room-flag_loader").show();
                var formData = new FormData();
                formData.append("flag", el.prop("files")[0]);

                $.ajax({
                    xhr: function () {
                        return new window.XMLHttpRequest();
                    },
                    url: window.location.pathname+"/change-icon",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        try {
                            if (response.error === false) {
                                $("#admin-settings-general-default-room-flag_loader").hide();
                                $("#admin-settings-general-default-room-flag_img").show().attr("src", "/uploads/" + response.icon);
                                socket.emit("req", { type: "ADMIN_RESET_SETTINGS", data: { token: token } });
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

        });

    </script>
@stop