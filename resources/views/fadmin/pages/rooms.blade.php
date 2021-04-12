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
                    <h3>إضافة غرفة جديدة</h3>

                    <form action="{{url("/fadmn/rooms/$token/add")}}" method="POST">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="name">إسم الغرفة</label>
                            <input type="text" name="name" id="name" placeholder="إسم الغرفة"
                                   class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">كلمة السر</label>
                            <input type="text" name="password" id="password" placeholder="كلمة السر" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="welcome">الرسالة الترحيبية</label>
                            <input type="text" name="welcome" id="welcome" placeholder="الرسالة الترحيبية" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="max">عدد الزوار</label>
                            <input type="number" name="capacity" id="max" value="100" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="target">نوع الزوار</label>
                            <select name="target" class="form-control">
                                <option value="all">الجميع</option>
                                <option value="supers">المراقبين</option>
                            </select>
                        </div>
                        <input type="submit" value="أضف" class="btn btn-success" style="width: 100px;">
                    </form>
                </div>

                <div class="section2">
                    <h3>الغرف المسجلة</h3>

                    <div class="section-table-header">
                        <div class="left-section">

                            <div class="form-group">
                                <label for="admin-settings-general-default-room-flag">الإيقونة الإفتراضية للغرف</label>
                                <img id="admin-settings-general-default-room-flag_img" src="{{settings("default_rooms_flag") ? "/uploads/rooms/".settings("default_rooms_flag") : "/images/default_room.png"}}" alt=""/>
                                <img src="/images/loader.gif" id="admin-settings-general-default-room-flag_loader" alt="" style="display: none"/>
                                <input type="file" id="admin-settings-general-default-room-flag" style="display: none; position: absolute; left: -99999px;"/>
                            </div>

                            <input accept="image/*" type="file" id="admin-room-flag-input" data-id="0" style="display: none; position: absolute; left: -99999px;"/>

                            <form id="admin-search-rooms">
                                <input type="text" name="query" placeholder="إبحث عن غرفة..."/>
                                <button id="search-rooms-submit" class="btn btn-info">إبحث</button>
                            </form>

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
                                <th>إسم الغرفة</th><th>كلمة المرور</th><th>الرسالة الترحيبية</th><th>الوصف</th>
                                <th>عدد الزوار</th><th>الأيقونة</th><th>من أجل</th><th>تاريخ الإنشاء</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $roomsFor = ['all' => 'الجميع', 'supers' => 'المراقبين'];
                            foreach ($rooms as $room) {

                                $roomFlag = $room->flag ? "/uploads/rooms/$room->flag" : null;
                                if(!$roomFlag) {
                                    $roomFlag = settings("default_rooms_flag") ? "/uploads/rooms/".settings("default_rooms_flag") : "/images/default_room.png";
                                }

                                echo '<tr row_id="' . $room->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $room->id . '"></td>';
                                echo '<td><input style="width: 100%" name="name" type="text" value="' . $room->name . '" /></td>';
                                echo '<td width="80"><input style="width: 100%" name="password" type="text" value="' . $room->password . '" /></td>';
                                echo '<td><input style="width: 100%" name="welcome" type="text" value="' . $room->welcome . '" /></td>';
                                echo '<td><input style="width: 100%" name="description" type="text" value="' . $room->description . '" /></td>';
                                echo '<td width="65"><input style="width: 100%" name="capacity" type="number" value="' . $room->capacity . '" /></td>';
                                echo '<td width="40" class="center"><img data-id="'.$room->id.'" class="room-flag" src="'.$roomFlag.'" alt="room flag"/></td>';
                                echo '<td width="80"><select>';
                                foreach($roomsFor as $key => $value) {
                                    echo ($key == $room->target) ? '<option selected="selected" value="'.$key.'">'.$value.'</option>' : '<option value="'.$key.'">'.$value.'</option>';
                                }
                                echo '</select></td>';
                                echo '<td>'.$room->created_at.'</td>';
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
    @if(Session::has("success_msg") && Session::get("success_msg") == "تم إنشاء الغرفة بنجاح")
        <script>
            var publishRoom = {
                id: '{{json_decode(Session::get("room"))->id}}',
                name: '{{json_decode(Session::get("room"))->name}}',
                welcome: '{{json_decode(Session::get("room"))->welcome}}',
                description: '{{json_decode(Session::get("room"))->description}}',
                password: '{{json_decode(Session::get("room"))->password}}',
                capacity: '{{json_decode(Session::get("room"))->capacity}}',
                target: '{{json_decode(Session::get("room"))->target}}'
            };
        </script>
    @else
        <script>var publishRoom = null;</script>
    @endif
    <script>
        var token = '{{$token}}';
        var socket = io(socketHost, {transports: ['websocket', 'xhr-polling', 'polling', 'htmlfile', 'flashsocket'],
            secure: true,
            rejectUnauthorized: false,
            query: "token="+token}
        );

        setTimeout(function() {
            $(".alert").remove();
        }, 5000);

        $(document).ready(function() {

            if(publishRoom) {
                socket.emit("req", {
                    type: "ADMIN_ROOM_CREATE",
                    data: { room: publishRoom, token: token }
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
                    var confirmation = confirm("هل أنت متأكد من حذف الغرف؟");
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
                                                type: "ADMIN_ROOM_DELETE",
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
                    alert("الرجاء إختيار على الأقل غرفة واحدة؟");
                }
            });
            $("#rooms-save-changes").on('click', function(e) {
                e.preventDefault();
                var ids         = [];
                var names       = [];
                var passwords   = [];
                var welcomes    = [];
                var descs       = [];
                var capacities  = [];
                var targets     = [];

                $.each($('#rooms-section .section2 .table tbody tr'), function(index, value) {
                    var checkbox = $(this).find("td input[type='checkbox']");
                    if(checkbox.prop("checked")){
                        ids.push(checkbox.attr("check_id"));
                        names.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='name']").val());
                        passwords.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='password']").val());
                        welcomes.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='welcome']").val());
                        descs.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='description']").val());
                        capacities.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='capacity']").val());
                        targets.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td select").val());
                    }
                });

                if(ids.length > 0) {

                    $.ajax({
                        url: location.pathname+'/edit',
                        type: 'POST',
                        data: {
                            ids: ids,
                            names: names,
                            passwords: passwords,
                            welcomes: welcomes,
                            descs: descs,
                            capacities: capacities,
                            targets: targets,
                        },
                        success: function(result) {
                            try {
                                if(result.error === false) {
                                    socket.emit("req", {
                                        type: "ADMIN_ROOM_UPDATE",
                                        data: { rooms: result.rooms, token: token }
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

            $(".rooms-page tbody tr .room-flag").unbind("click").bind("click", function() {
                $("#admin-room-flag-input").data("id", $(this).data("id"));
                $("#admin-room-flag-input").data("old-src", $(this).attr("src"));
                $("#admin-room-flag-input").trigger("click");
            });

            $("#admin-settings-general-default-room-flag_img").on("click", function () {
                $("#admin-settings-general-default-room-flag").trigger("click");
            });

            $("#admin-settings-general-default-room-flag").on("change", function() {
                changeDefaultRoomFlag($(this));
            });

            $("#admin-room-flag-input").unbind("change").bind("change", function() {

                var formData = new FormData();
                formData.append("flag", $(this).prop("files")[0]);
                formData.append("room_id", $(this).data("id"));

                $(".rooms-page tbody tr[row_id='"+$(this).data("id")+"'] .room-flag").attr("src", "/images/loader.gif");

                $.ajax({
                    xhr: function() {
                        return new window.XMLHttpRequest();
                    },
                    url: location.pathname+'/edit-flag',
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
                                socket.emit("req", {
                                    type: "ADMIN_ROOM_UPDATE_FLAG",
                                    data: { id: response.id, flag: response.flag, token: token }
                                });
                                $(".rooms-page tbody tr[row_id='"+response.id+"'] .room-flag").attr("src", "/uploads/rooms/"+response.flag);
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

            function changeDefaultRoomFlag(el) {

                $("#admin-settings-general-default-room-flag_img").hide();
                $("#admin-settings-general-default-room-flag_loader").show();
                var formData = new FormData();
                formData.append("flag", el.prop("files")[0]);

                $.ajax({
                    xhr: function () {
                        return new window.XMLHttpRequest();
                    },
                    url: window.location.pathname+"/change-default-room-flag",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        try {
                            if (response.error === false) {
                                $("#admin-settings-general-default-room-flag_loader").hide();
                                $("#admin-settings-general-default-room-flag_img").show().attr("src", "/uploads/rooms/" + response.flag);
                                socket.emit("req", {
                                    type: "ADMIN_ROOMS_SET_DEFAULT_FLAG",
                                    data: { flag: response.flag, token: token }
                                }, function() {
                                    location.reload();
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

        });

    </script>
@stop