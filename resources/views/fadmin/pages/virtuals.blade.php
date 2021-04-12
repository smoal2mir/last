@extends('fadmin.master')

<?php
    $roles = [];
    foreach($_roles as $role) {
        $roles[$role->id] = $role->name;
    }
?>

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
                    <h3>إضافة عضو جديد</h3>

                    <form action="{{url("/fadmn/virtuals/$token/add")}}" method="POST">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label for="name">إسم المستخدم</label>
                            <input type="text" name="name" id="name" placeholder="إسم المستخدم" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="country">الدولة</label>
                            <select name="country" class="form-control">
                                @foreach($countries as $code => $country)
                                    <option value="{{$code}}">{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="role">الرتبة</label>
                            <select name="role" class="form-control">
                                @foreach($roles as $key => $vaule)
                                    <option value="{{$key}}">{{$vaule}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="room">الغرفة</label>
                            <select name="room" class="form-control">
                                @foreach($rooms as $room)
                                    <option value="{{$room->id}}">{{$room->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="submit" value="أضف" class="btn btn-success" style="width: 100px;">
                    </form>
                </div>

                <div class="section" style="margin-bottom: 15px">
                    <h3>الإعدادات</h3>

                    <form action="{{$token}}/settings" method="POST">
                        {!! csrf_field() !!}

                        <div class="form-group">
                            <label for="virtuals_count">عدد المستخدمين الوهميين</label>
                            <input type="number" name="virtuals_count" id="virtuals_count" min="1" max="100" value="{{ $settings->virtuals_count }}" class="form-control" />
                        </div>

                        <div class="form-group">
                            <input type="checkbox" name="disable_virtuals" id="disable_virtuals" <?php if($settings->disable_virtuals) echo 'checked="checked"'; ?>/>
                            <label for="disable_virtuals">إلغاء خاصية الوهميين</label>
                        </div>

                        <input type="submit" value="حفظ" class="btn btn-success"/>
                    </form>

                </div>

                <div class="section2">
                    <h3>الأعضاء الوهميين</h3>

                    <div class="section-table-header">
                        <div class="left-section">

                            <form id="admin-search-rooms">
                                <input type="text" name="search" value="{{$keyword}}" placeholder="إبحث عن عضو..."/>
                                <button id="search-rooms-submit" class="btn btn-info">إبحث</button>
                            </form>

                            <div class="table-header">
                                <ul>
                                    <li id="select-all" class="blue select-all"><a href="#">تحديد الكل</a></li>
                                    <li id="unselect-all" style="display: none;" class="blue deselect-all"><a href="#">إلغاء تحديد الكل</a></li>
                                    <li id="rooms-delete" class="red delete"><a class="red" href="#">حذف</a></li>
                                    <li id="rooms-save-changes" class="green save-changes"><a class="green" href="#">حفظ التعديلات</a></li>
                                    <li id="logout-virtuals" class="red logout-virtuals"><a class="red" href="#">إخراج</a></li>
                                </ul>
                            </div>

                            <input accept="image/*" type="file" id="admin-room-flag-input" data-id="0" style="display: none; position: absolute; left: -99999px;"/>

                        </div>
                    </div>
                    <div class="clear"></div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th><input id="checkbox-select-all" type="checkbox"style="width: 17px; height: 17px;"></th>
                                <th>الرتبة</th><th>الإسم</th><th>الحالة</th><th>الغرفة</th><th>الأيبي</th><th>الصورة</th><th>الدوله</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr row_id="{{$user->id}}">
                                        <td><input class="table-check-row" type="checkbox" check_id="{{$user->id}}"></td>
                                        <td>
                                            <select name="role" class="change_user_role">
                                                @foreach($roles as $key => $vaule)
                                                    <?php $selected = $user->role_id == "$key" ? "selected" : ""; ?>
                                                    <option {{$selected}} value="{{$key}}">{{$roles[$key]}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="name" width="100%" value="{{$user->name}}"></td>
                                        <td><input type="text" name="status" width="100%" value="{{$user->status}}"></td>
                                        <td>
                                            <select name="room" class="change_user_room">
                                                @foreach($rooms as $room)
                                                    <?php $selected = $user->default_room == $room->id ? "selected" : ""; ?>
                                                    <option {{$selected}} value="{{$room->id}}">{{$room->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="ip" width="100%" value="{{$user->ip}}"></td>
                                        <td><img data-id="{{$user->id}}" class="room-flag" src="{{$user->avatar ? "/uploads/avatars/$user->avatar" : "/images/none.png"}}"/></td>
                                        <td>
                                            <select name="country" class="change_user_country">
                                                @foreach($countries as $code => $country)
                                                    <?php $selected = $user->country == $code ? "selected" : ""; ?>
                                                    <option {{$selected}} value="{{$code}}">{{$country}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
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
    @if(Session::has("success_msg") && Session::get("success_msg") == "تم إنشاء العضو بنجاح")
        <script>
            var user = {
                id: '{{json_decode(Session::get("user"))->id}}',
                name: '{{json_decode(Session::get("user"))->name}}',
                device: '{{json_decode(Session::get("user"))->device}}',
                ip: '{{json_decode(Session::get("user"))->ip}}',
                country: '{{json_decode(Session::get("user"))->country}}'
            };
        </script>
    @else
        <script>var user = null;</script>
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

            if(user) {
                socket.emit("req", {
                    type: "ADMIN_VIRTUAL_USERS_ADD",
                    data: { user: user, token: token }
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
                    var confirmation = confirm("هل أنت متأكد من حذف الأعضاء؟");
                    if(confirmation) {
                        $.ajax({
                            url: location.pathname+"/delete",
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
                                                type: "ADMIN_VIRTUAL_USERS_DELETE",
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
                    alert("الرجاء إختيار على الأقل عضو واحد");
                }
            });

            $("#logout-virtuals").on('click', function(e) {
                var ids = [];

                $.each($('#rooms-section .section2 .table tbody tr'), function(index, value) {
                    var checkbox = $(this).find("td input[type='checkbox']");
                    if(checkbox.prop("checked")){
                        ids.push(checkbox.attr("check_id"));
                    }
                });

                if(ids.length > 0) {
                    socket.emit("req", {
                        type: "ADMIN_VIRTUAL_USERS_LOGOUT",
                        data: { ids: ids, token: token }
                    }, function() {
                        location.reload();
                    });
                } else {
                    alert("الرجاء تحديد على الأقل عضو واحد");
                }

            });

            $("#rooms-save-changes").on('click', function(e) {
                e.preventDefault();
                var ids         = [];
                var names       = [];
                var roles       = [];
                var status      = [];
                var rooms       = [];
                var ips         = [];
                var countries   = [];

                $.each($('#rooms-section .section2 .table tbody tr'), function(index, value) {
                    var checkbox = $(this).find("td input[type='checkbox']");
                    if(checkbox.prop("checked")){
                        ids.push(checkbox.attr("check_id"));
                        names.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='name']").val());
                        roles.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td select[name='role']").val());
                        status.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='status']").val());
                        rooms.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td select[name='room']").val());
                        ips.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='ip']").val());
                        countries.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td select[name='country']").val());
                    }
                });

                if(ids.length > 0) {

                    $.ajax({
                        url: location.pathname+'/edit',
                        type: 'POST',
                        data: {
                            ids: ids,
                            names: names,
                            roles: roles,
                            status: status,
                            rooms: rooms,
                            ips: ips,
                            countries: countries,
                        },
                        success: function(result) {
                            try {
                                if(result.error === false) {
//                                    socket.emit("req", {
//                                        type: "ADMIN_VIRTUAL_USERS_UPDATE",
//                                        data: { users: result.users, token: token }
//                                    });
                                    location.reload();
                                }
                            } catch(e) {
                                //console.log(e);
                            }
                        }
                    });
                } else {
                    alert("الرجاء إختيار على الأقل عضو واحد");
                }

            });
            $("#search-rooms-submit").on("click", function(e) {
                $(this).closest("form").submit();
            });

            $(".rooms-page tbody tr .room-flag").unbind("click").bind("click", function() {
                $("#admin-room-flag-input").data("id", $(this).data("id"));
                $("#admin-room-flag-input").data("old-src", $(this).attr("src"));
                $("#admin-room-flag-input").trigger("click");
            });

            $("#admin-room-flag-input").unbind("change").bind("change", function() {

                var formData = new FormData();
                formData.append("flag", $(this).prop("files")[0]);
                formData.append("user_id", $(this).data("id"));

                $(".rooms-page tbody tr[row_id='"+$(this).data("id")+"'] .room-flag").attr("src", "/images/loader.gif");

                $.ajax({
                    xhr: function() {
                        return new window.XMLHttpRequest();
                    },
                    url: location.pathname+'/change-avatar',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(typeof response == "string") {
                            response = JSON.parse(response);
                        }
                        try {
                            if(response.error === false) {
//                                socket.emit("req", {
//                                    type: "ADMIN_VIRTUAL_USERS_CHANGE_AVATAR",
//                                    data: { id: response.id, avatars: response.avatar, token: token }
//                                });
                                $(".rooms-page tbody tr[row_id='"+response.id+"'] .room-flag").attr("src", "/uploads/avatars/"+response.avatar);
                            } else {
                                if(response.msg == "not permitted") {
                                    alert("لا تملك الصلاحيات للقيام بهذه العملية");
                                } else {
                                    alert("حدث خطأ أثناء رفع الصورة");
                                }
                            }
                        } catch(e) {console.log(e.stack);}
                    }
                });

            });

        });

    </script>
@stop