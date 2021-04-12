@extends('fadmin.master')

<?php
$roles = [];
foreach($_roles as $role) {
    $roles[$role->id] = $role->name;
}
?>

@section('content')
    <div id="main-content" class="subscriptions-page">
        <div class="row">
            <form id="subscriptions-search">
                <input type="saerch" name="search" value="{{$keyword}}" placeholder="إبحث عن: الجهاز / الدولة / الأيبي"/>
                <button class="btn btn-info">إبحث</button>
            </form>
            <form id="subscriptions-filter">
                <select name="filter" id="subscriptions-filter-select">
                    <option selected value="">كل السوابر</option>
                    @foreach($roles as $key => $vaule)
                        <?php $selected = $filter == "$key" ? "selected" : ""; ?>
                        <option {{$selected}} value="{{$key}}">{{$vaule}}</option>
                    @endforeach
                </select>
                <label dir="rtl" for="subscriptions-filter-select">فلتر عبر: </label>
            </form>
        </div>

        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>الرتبة</th><th>العضو</th><th>الزخرفه</th><th>الأيبي</th><th>الدوله</th><th>الجهاز</th><th>نهاية الإشتراك</th><th>دائم</th><th>آخر تواجد</th><th>التسجيل</th><th>حذف</th>
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
                        <td>{!!$user->subscriptionEnd()!!}<button class="edit_subscription_end fa fa-cog btn btn-info pull-right"></button></td>
                        <td><input type="checkbox" name="permanent_subscription" class="permanent_subscription" {{$user->permanent_subscription ? 'checked="checked"' :  ""}}></td>
                        <td>{{$user->updated_at ?: "/"}}</td>
                        <td>{{$user->created_at ?: "/"}}</td>
                        <td style="max-width: 260px; text-align: center;">
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

            $("#subscriptions-filter-select").on("change", function() {
                $(this).closest("form").submit();
            });

            $("table tr select.change_user_role").on("change", function() {
                updateRole($(this).val(), $(this).closest("tr"));
            });

            $(".edit_subscription_end").on("click", function() {
                var val = prompt("الرجاء إدخال عدد الأيام (إبتداء من اليوم)").trim();
                if(isNaN(val)) return alert("الرجاء إدخال عدد الأيام");
                if(val) updateRoleSubscription(val, $(this).closest("tr"));
            });

            $(".permanent_subscription").on("change", function() {
                updateSubscriptionPermanent($(this)[0].checked, $(this).closest("tr"));
            });

            $("table tr button").on("click", function() {
                var action = $(this).data("action");
                if(action == "remove") {
                   if(confirm("هل أنت متأكد من حذف هذا المشترك؟")) {
                       removeUser($(this).closest("tr"));
                   }
                }
            });

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
                        } catch(e) {}
                    }
                });
            }

            function updateRoleSubscription(val, row) {
                $.ajax({
                    url: window.location.pathname+"/update-subscription",
                    type: "post",
                    data: {userId: row.data("role"), days: val},
                    success: function(data) {
                        try {
                            var res = JSON.parse(data);
                            if(res.error === false) {
                                row.addClass("succeeded");
                                row.find(".finishes").html(res.date);
                                setTimeout(function() {
                                    row.removeClass("succeeded");
                                }, 4000);
                            } else {
                                if(res.msg == "not permitted") {
                                    alert("لا تلمك الصلاحيات للقيام بهذه العملية");
                                } else if(res.msg == "no super") {
                                    alert("لم يتم التعرف على هذا السوبر");
                                } else {
                                    alert("حدث خطأ أثناء القيام بالعملية, الرجاء إعادة المحاولة");
                                }
                            }
                        } catch(e) {}
                    }
                });
            }

            function updateSubscriptionPermanent(val, row) {
                $.ajax({
                    url: window.location.pathname+"/update-subscription-permanent",
                    type: "post",
                    data: {userId: row.data("role"), permanent: val},
                    success: function(data) {
                        try {
                            var res = JSON.parse(data);
                            if(res.error === false) {
                                row.addClass("succeeded");
                                row.find(".finishes").html(res.date);
                                setTimeout(function() {
                                    row.removeClass("succeeded");
                                }, 4000);
                            } else {
                                if(res.msg == "not permitted") {
                                    alert("لا تلمك الصلاحيات للقيام بهذه العملية");
                                } else if(res.msg == "no super") {
                                    alert("لم يتم التعرف على هذا السوبر");
                                } else {
                                    alert("حدث خطأ أثناء القيام بالعملية, الرجاء إعادة المحاولة");
                                }
                            }
                        } catch(e) {}
                    }
                });
            }

        });
    </script>
@endsection