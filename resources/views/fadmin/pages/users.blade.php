@extends('fadmin.master')

<?php
    $roles = [];
    foreach($_roles as $role) {
        $roles[$role->id] = $role->name;
    }
?>

@section('content')
    <div id="main-content" class="users-page">

        @if(Session::has("errors"))
            <div class="alert alert-danger">{{$errors->first()}}</div>
        @endif

        @if(Session::has("success_msg"))
            <div class="alert alert-success">{{Session::get("success_msg")}}</div>
        @endif

        <div class="section1 row">
            <h3>إضافة مستخدم جديد</h3>

            <form action="{{url("/fadmn/users/$token/add")}}" method="POST">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label for="name">إسم المستخدم</label>
                    <input type="text" name="name" id="name" placeholder="إسم المستخدم" class="form-control">
                </div>

                <div class="form-group">
                    <label for="password">كلمة السر</label>
                    <input type="text" name="password" id="password" placeholder="كلمة السر" class="form-control">
                </div>

                <div class="form-group">
                    <label for="role">الرتبة</label>
                    <select name="role" class="form-control">
                        @foreach($roles as $key => $vaule)
                            <option value="{{$key}}">{{$vaule}}</option>
                        @endforeach
                    </select>
                </div>

                <input type="submit" value="أضف" class="btn btn-success" style="width: 100px;">
            </form>
        </div>

        <div class="row">
            <form id="users-search">
                <input type="saerch" name="search" value="{{$keyword}}" placeholder="إبحث عن: الجهاز / الدولة / الأيبي"/>
                <button class="btn btn-info">إبحث</button>
            </form>
            <form id="users-filter">
                <select name="filter" id="users-filter-select">
                    <option selected value="0">كل السوابر</option>
                    @foreach($roles as $key => $vaule)
                        <?php $selected = $filter == "$key" ? "selected" : ""; ?>
                        <option {{$selected}} value="{{$key}}">{{$vaule}}</option>
                    @endforeach
                </select>
                <label dir="rtl" for="users-filter-select">فلتر عبر: </label>
            </form>
        </div>

        <div class="row">
            <table class="table table-striped table-responsive">
                <thead>
                <tr>
                    <th>الرتبة</th><th>العضو</th><th>الزخرفه</th><th>الأيبي</th><th>الدوله</th><th>الجهاز</th><th>آخر تواجد</th><th>التسجيل</th><th>تعديل</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr data-role="{{$user->id}}">
                        <td>
                            <select class="change_user_role">
                                @foreach($roles as $key => $vaule)
                                    <?php $selected = $user->role_id == "$key" ? "selected" : ""; ?>
                                    <option {{$selected}} value="{{$key}}">{{$roles[$key]}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>{{$user->name ?: "/"}}</td>
                        <td>{{$user->decoration ?: "/"}}</td>
                        <td>{{$user->ip ?: "/"}}</td>
                        <td>{!!$user->country ? '<img src="/images/flags/'.$user->country.'.png" />' : "/"!!}</td>
                        <td>{{$user->device ?: "/"}}</td>
                        <td>{{$user->updated_at ?: "/"}}</td>
                        <td>{{$user->created_at ?: "/"}}</td>
                        <td style="max-width: 260px; text-align: center;">
                            <button style="margin-bottom: 5px" data-action="change-password" class="btn btn-warning fa fa-key"></button>
                            <button data-action="remove" class="btn btn-danger fa fa-times"></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/socket.io.js?v=1') }}"></script>
    <script>
        $(document).ready(function() {

            var token = '{{$token}}';
            var socket = io(socketHost, {transports: ['websocket', 'xhr-polling', 'polling', 'htmlfile', 'flashsocket'],
                secure: true,
                rejectUnauthorized: false,
                query: "token="+token}
            );

            $("#users-filter-select").on("change", function() {
                $(this).closest("form").submit();
            });

            $("table tr select.change_user_role").on("change", function() {
                updateRole($(this).val(), $(this).closest("tr"));
            });

            $("table tr button").on("click", function() {
                var action = $(this).data("action");
                if(action == "change-password") {
                    var pass = prompt("الرجاء إدخال كلمة المرور الجديدة");
                    if(pass) {
                        if(pass.length < 6) {
                            return alert("كلمة المرور لا يجب أن تقل عن 6 أحرف");
                        } else {
                            changePassword(pass, $(this).closest("tr"));
                        }
                    }
                } else if(action == "remove") {
                    removeUser($(this).closest("tr"));
                }
            });

            function changePassword(pass, row) {
                if(!pass || !row) return;
                $.ajax({
                    url: window.location.pathname+"/change-password",
                    type: "post",
                    data: {password: pass, id: row.data("role")},
                    success: function(data) {
                        try {
                            if(data.error === false) {
                                row.addClass("succeeded");
                                setTimeout(function() {
                                    row.removeClass("succeeded");
                                }, 4000);
                            } else {
                                if(data.msg == "not permitted") {
                                    alert("لا تلمك الصلاحيات للقيام بهذه العملية");
                                } else {
                                    alert(data.msg);
                                }
                            }
                        } catch(e) {console.log(e);}
                    }
                });
            }

            function removeUser(row) {
                if(!row) return;
                $.ajax({
                    url: window.location.pathname+"/delete",
                    type: "post",
                    data: {id: row.data("role")},
                    success: function(data) {
                        try {

                            if(data.error === false) {
                                row.remove();
                            } else {
                                if(data.msg == "not permitted") {
                                    alert("لا تلمك الصلاحيات للقيام بهذه العملية");
                                } else {
                                    alert("حدث خطأ أثناء القيام بالعملية, الرجاء إعادة المحاولة");
                                }
                            }

                        } catch(e) {}
                    }
                });
            }

            function updateRole(roleId, row) {
                if(!roleId || !row) return;
                $.ajax({
                    url: window.location.pathname+"/update-role",
                    type: "post",
                    data: {roleId: roleId, userId: row.data("role")},
                    success: function(data) {
                        try {
                            var res = JSON.parse(data);
                            if(res.error === false) {
                                row.addClass("succeeded");
                                setTimeout(function() {
                                    row.removeClass("succeeded");
                                }, 4000);

                                socket.emit("req", {
                                    type: "ADMIN_USERS_UPDATE_ROLE",
                                    data: { role: res.role, user_id: res.user_id, token: token }
                                });

                            } else {
                                if(res.msg == "not permitted") {
                                    alert("لا تلمك الصلاحيات للقيام بهذه العملية");
                                } else if(res.msg == "no super") {
                                    alert("لم يتم التعرف على هذا السوبر");
                                } else {
                                    alert("حدث خطأ أثناء القيام بالعملية, الرجاء إعادة المحاولة");
                                }
                            }
                        } catch(e) {console.log(e);}
                    }
                });
            }

        });
    </script>
@endsection