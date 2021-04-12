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
                    <h3>إضافة إختصار جديد</h3>

                    <form action="{{url("/fadmn/shortcuts/$token/add")}}" method="POST">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="name">الإختصار</label>
                            <input type="text" name="key" id="key" placeholder="س١" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">الرسالة</label>
                            <input type="text" name="value" id="value" placeholder=".@.السلام.عليكم.ورحمة.الله.وبركاته.@." class="form-control">
                        </div>
                        <input type="submit" value="أضف" class="btn btn-success" style="width: 100px;">
                    </form>
                </div>

                <div class="section2">
                    <h3>الإختصارات</h3>

                    <div class="section-table-header">
                        <div class="left-section">

                            <div class="table-header">
                                <ul>
                                    <li id="select-all" class="blue select-all"><a href="#">تحديد الكل</a></li>
                                    <li id="unselect-all" style="display: none;" class="blue deselect-all"><a href="#">إلغاء تحديد الكل</a></li>
                                    <li id="rooms-delete" class="red delete"><a class="red" href="#">حذف</a></li>
                                    <li id="shortcuts-save-changes" class="green save-changes"><a class="green" href="#">حفظ التعديلات</a></li>
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
                                <th>الإختصار</th><th>الرسالة</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($shortcuts as $shortcut) {
                                echo '<tr row_id="' . $shortcut->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $shortcut->id . '"></td>';
                                echo '<td width="10"><input style="width: 100%" name="key" type="text" value="' . $shortcut->key . '" /></td>';
                                echo '<td><input style="width: 100%" name="value" type="text" value="' . $shortcut->value . '" /></td>';
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
    @if(Session::has("success_msg") && Session::get("success_msg") == "تم إنشاء الإختصار بنجاح")
        <script>
            var newShortcut = {
                id: '{{json_decode(Session::get("shortcut"))->id}}',
                key: '{{json_decode(Session::get("shortcut"))->key}}',
                value: '{{json_decode(Session::get("shortcut"))->value}}'
            };
        </script>
    @else
        <script>var newShortcut = null;</script>
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

            if(newShortcut) {
                socket.emit("req", {
                    type: "ADMIN_SHORTCUTS_ADD",
                    data: { shortcut: newShortcut, token: token }
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
                    var confirmation = confirm("هل أنت متأكد من حذف الإختصارات؟");
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
                                        if(Array.isArray(json.deleteds) && json.deleteds.length) {
                                            for(var key in json.deleteds) {
                                                $('#rooms-section .section2 .table tbody tr[row_id="'+json.deleteds[key]+'"]').remove();
                                            }
                                            socket.emit("req", {
                                                type: "ADMIN_SHORTCUTS_REMOVE",
                                                data: { ids: json.deleteds, token: token }
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
                    alert("الرجاء إختيار على الأقل إختصار واحد؟");
                }
            });

            $("#shortcuts-save-changes").on('click', function(e) {
                e.preventDefault();
                var ids         = [];
                var keys        = [];
                var values      = [];

                $.each($('#rooms-section .section2 .table tbody tr'), function(index, value) {
                    var checkbox = $(this).find("td input[type='checkbox']");
                    if(checkbox.prop("checked")){
                        ids.push(checkbox.attr("check_id"));
                        keys.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='key']").val());
                        values.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='value']").val());
                    }
                });

                if(ids.length > 0) {

                    $.ajax({
                        url: location.pathname+'/edit',
                        type: 'POST',
                        data: { ids: ids, keys: keys, values: values },
                        success: function(result) {
                            try {
                                if(result.error === false) {
                                    socket.emit("req", {
                                        type: "ADMIN_SHORTCUTS_UPDATE",
                                        data: { shortcuts: result.shortcuts, token: token }
                                    }, function() {
                                        location.reload();
                                    });
                                }
                            } catch(e) {
                                //console.log(e);
                            }
                        }
                    });
                } else {
                    alert("الرجاء إختيار على الأقل إختصار واحد");
                }

            });

        });

    </script>
@stop