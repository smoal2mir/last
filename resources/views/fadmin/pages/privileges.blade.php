@extends('fadmin.master')

@section('content')
    <div id="main-content" class="privileges-page">

        <select id="select-role">
            @foreach($roles as $val)
                <option {{$role->id == $val->id ? "selected" : ""}} value="{{$val->id}}">{{$val->power}} | {{$val->name}}</option>
            @endforeach
        </select>
        <button id="add-role" class="btn btn-success">أضف</button>
        <button id="remove-role" class="btn btn-danger">حذف</button>

        @if(count($roles))

        <form>

            <div class="form-group">
                <label for="role_power">القوة</label>
                <input type="number" id="role_power" name="role_power" value="{{$role->power}}"/>
            </div>

            <div class="form-group">
                <input type="file" id="role_icon_input" style="display: none; position: absolute; left: -99999px;"/>
                <label for="role_icon">الأيقونة</label>{!!$role->getIcon(50)!!}
                <button data-target="{{$role->id}}" id="remove-role-icon" class="btn btn-danger">حذف</button>
            </div>


            <div class="form-group">
                <label for="orders_excpel">الطرد</label>
                <input type="number" id="orders_excpel" name="orders_excpel" value="{{privilegesSplit($privileges->orders_excpel)}}"/>
                <select name="orders_excpel_unit" id="orders_excpel_unit">
                    <option {{privilegesSplit($privileges->orders_excpel, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->orders_excpel, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->orders_excpel, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->orders_excpel, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="orders_ban">الحظر</label>
                <input type="number" id="orders_ban" name="orders_ban" value="{{privilegesSplit($privileges->orders_ban)}}"/>
                <select name="orders_ban_unit" id="orders_ban_unit">
                    <option {{privilegesSplit($privileges->orders_ban, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->orders_ban, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->orders_ban, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->orders_ban, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="orders_notify">النتبيهات</label>
                <input type="number" id="orders_notify" name="orders_notify" value="{{privilegesSplit($privileges->orders_notify)}}"/>
                <select name="orders_notify_unit" id="orders_notify_unit">
                    <option {{privilegesSplit($privileges->orders_notify, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->orders_notify, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->orders_notify, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->orders_notify, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="orders_gift">الهدايا</label>
                <input type="number" id="orders_gift" name="orders_gift" value="{{privilegesSplit($privileges->orders_gift)}}"/>
                <select name="orders_gift_unit" id="orders_gift_unit">
                    <option {{privilegesSplit($privileges->orders_gift, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->orders_gift, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->orders_gift, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->orders_gift, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="orders_remove_avatar">حذف الصور الشخصية</label>
                <input type="number" id="orders_remove_avatar" name="orders_remove_avatar" value="{{privilegesSplit($privileges->orders_remove_avatar)}}"/>
                <select name="orders_remove_avatar_unit" id="orders_remove_avatar_unit">
                    <option {{privilegesSplit($privileges->orders_remove_avatar, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->orders_remove_avatar, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->orders_remove_avatar, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->orders_remove_avatar, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="orders_change_decoartion">الزخرفة</label>
                <input type="number" id="orders_change_decoartion" name="orders_change_decoartion" value="{{privilegesSplit($privileges->orders_change_decoartion)}}"/>
                <select name="orders_change_decoartion_unit" id="orders_change_decoartion_unit">
                    <option {{privilegesSplit($privileges->orders_change_decoartion, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->orders_change_decoartion, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->orders_change_decoartion, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->orders_change_decoartion, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="orders_excpel_from_room">طرد من الغرف</label>
                <input type="number" id="orders_excpel_from_room" name="orders_excpel_from_room" value="{{privilegesSplit($privileges->orders_excpel_from_room)}}"/>
                <select name="orders_excpel_from_room_unit" id="orders_excpel_from_room_unit">
                    <option {{privilegesSplit($privileges->orders_excpel_from_room, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->orders_excpel_from_room, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->orders_excpel_from_room, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->orders_excpel_from_room, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="orders_upgrade">الترقية</label>
                <input type="checkbox" name="orders_upgrade" id="orders_upgrade" {{$privileges->orders_upgrade ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label>ترفية السوابر</label>
                <div id="upgradable_roles">
                    @foreach($roles as $role)
                        <div class="role">
                            <label for="orders_upgrade_roles_{{$role->id}}">{{$role->name}}</label>
                            <input type="checkbox" name="orders_upgrade_roles_{{$role->id}}" id="orders_upgrade_roles_{{$role->id}}" {{isRoleSelected($privileges->orders_upgrade_roles, $role->id) ? "checked" : ""}}/>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label for="room_create">إنشاء الغرف</label>
                <input type="number" id="room_create" name="room_create" value="{{privilegesSplit($privileges->room_create)}}"/>
                <select name="room_create_unit" id="room_create_unit">
                    <option {{privilegesSplit($privileges->room_create, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->room_create, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->room_create, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->room_create, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="room_edit">تعديل الغرف</label>
                <input type="number" id="room_edit" name="room_edit" value="{{privilegesSplit($privileges->room_edit)}}"/>
                <select name="room_edit_unit" id="room_edit_unit">
                    <option {{privilegesSplit($privileges->room_edit, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->room_edit, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->room_edit, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->room_edit, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="room_remove">حذف الغرف</label>
                <input type="number" id="room_remove" name="room_remove" value="{{privilegesSplit($privileges->room_remove)}}"/>
                <select name="room_remove_unit" id="room_remove_unit">
                    <option {{privilegesSplit($privileges->room_remove, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->room_remove, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->room_remove, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->room_remove, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="room_max_pertinents">أقصى عدد للغرف الدائمة</label>
                <input type="number" id="room_max_pertinents" name="room_max_pertinents" value="{{privilegesSplit($privileges->room_max_pertinents)}}"/>
                <select name="room_max_pertinents_unit" id="room_max_pertinents_unit">
                    <option {{privilegesSplit($privileges->room_max_pertinents, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->room_max_pertinents, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->room_max_pertinents, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->room_max_pertinents, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="send_ad">الإعلانات</label>
                <input type="number" id="send_ad" name="send_ad" value="{{privilegesSplit($privileges->send_ad)}}"/>
                <select name="send_ad_unit" id="send_ad_unit">
                    <option {{privilegesSplit($privileges->send_ad, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->send_ad, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->send_ad, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->send_ad, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="wall_remove">الحذف من الحائط</label>
                <input type="number" id="wall_remove" name="wall_remove" value="{{privilegesSplit($privileges->wall_remove)}}"/>
                <select name="wall_remove_unit" id="wall_remove_unit">
                    <option {{privilegesSplit($privileges->wall_remove, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->wall_remove, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->wall_remove, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->wall_remove, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="wall_interval">الفاصل بين رسائل الحائط</label>
                <input type="number" id="wall_interval" name="wall_interval" value="{{privilegesSplit($privileges->wall_interval)}}"/>
                <select name="wall_interval_unit" id="wall_interval_unit">
                    <option {{privilegesSplit($privileges->wall_interval, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->wall_interval, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->wall_interval, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->wall_interval, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="public_msg_remove">حذف رسائل العام</label>
                <input type="number" id="public_msg_remove" name="public_msg_remove" value="{{privilegesSplit($privileges->public_msg_remove)}}"/>
                <select name="public_msg_remove_unit" id="public_msg_remove_unit">
                    <option {{privilegesSplit($privileges->public_msg_remove, true) == "minute" ? "selected" : ""}} value="minute">دقيقة</option>
                    <option {{privilegesSplit($privileges->public_msg_remove, true) == "hour" ? "selected" : ""}} value="hour">ساعة</option>
                    <option {{privilegesSplit($privileges->public_msg_remove, true) == "day" ? "selected" : ""}} value="day">يوم</option>
                    <option {{privilegesSplit($privileges->public_msg_remove, true) == "month" ? "selected" : ""}} value="month">شهر</option>
                </select>
            </div>

            <div class="form-group">
                <label for="wall_banner">البانر على الحائط</label>
                <input type="checkbox" id="wall_banner" name="wall_banner" {{$privileges->wall_banner ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="hidden">المخفي</label>
                <input type="checkbox" id="hidden" name="hidden" {{$privileges->hidden ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label>يرون المخفي</label>
                <div id="can_see_hidden">
                    @foreach($roles as $role)
                        <div class="role">
                            <label for="can_see_hidden_roles_{{$role->id}}">{{$role->name}}</label>
                            <input type="checkbox" name="can_see_hidden_roles_{{$role->id}}" id="can_see_hidden_roles_{{$role->id}}" {{isRoleSelected($privileges->can_see_hidden, $role->id) ? "checked" : ""}}/>
                        </div>
                    @endforeach
                </div>
            </div>

            {{--<div class="form-group">--}}
                {{--<label for="change_btns_bg">تغيير خلفية الازرار</label>--}}
                {{--<input type="checkbox" id="change_btns_bg" name="change_btns_bg" {{$privileges->change_btns_bg ? "checked" : ""}}/>--}}
            {{--</div>--}}
            <div class="form-group">
                <label for="edit_permissions">تعديل الصلاحيات</label>
                <input type="checkbox" id="edit_permissions" name="edit_permissions" {{$privileges->edit_permissions ? "checked" : ""}}/>
            </div>
            <div class="form-group">
                <label for="reveal_names">كشف النكات</label>
                <input type="checkbox" id="reveal_names" name="reveal_names" {{$privileges->reveal_names ? "checked" : ""}}/>
            </div>
            <div class="form-group">
                <label for="open_private">فتح الخاص</label>
                <input type="checkbox" id="open_private" name="open_private" {{$privileges->open_private ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="open_full_rooms">الدخول للغرف الممتلئة</label>
                <input type="checkbox" id="open_full_rooms" name="open_full_rooms" {{$privileges->open_full_rooms ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="open_locked_rooms">الدخول للغرف المغلقة</label>
                <input type="checkbox" id="open_locked_rooms" name="open_locked_rooms" {{$privileges->open_locked_rooms ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="radio_speak_time">مدة التكلم في الراديو</label>
                <input type="number" min="0" max="100" id="radio_speak_time" name="radio_speak_time" value="{{$privileges->radio_speak_time}}"/>
            </div>

            <div class="form-group">
                <label for="stop_radio">سحب المايك</label>
                <input type="checkbox" id="stop_radio" name="stop_radio" {{$privileges->stop_radio ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="like_controls">إعطاء اللايكات</label>
                <input type="checkbox" id="like_controls" name="like_controls" {{$privileges->like_controls ? "checked" : ""}}/>
            </div>

            {{--<div class="form-group">--}}
                {{--<label for="show_real_name">رؤية الإسم الأصلي</label>--}}
                {{--<input type="checkbox" id="show_real_name" name="show_real_name" {{$privileges->show_real_name ? "checked" : ""}}/>--}}
            {{--</div>--}}

            <div class="form-group">
                <label for="orders_change_room">نقل العضو</label>
                <input type="checkbox" id="orders_change_room" name="orders_change_room" {{$privileges->orders_change_room ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="show_ip">رؤية الأيبي</label>
                <input type="checkbox" id="show_ip" name="show_ip" {{$privileges->show_ip ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin">اللوحة</label>
                <input type="checkbox" id="admin" name="admin" {{$privileges->admin ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_reg">اللوحة - السجل</label>
                <input type="checkbox" id="admin_reg" name="admin_reg" {{$privileges->admin_reg ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_events">اللوحة - الأحداث</label>
                <input type="checkbox" id="admin_events" name="admin_events" {{$privileges->admin_events ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_users">اللوحة - المستخدمين</label>
                <input type="checkbox" id="admin_users" name="admin_users" {{$privileges->admin_users ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_users_remove">اللوحة - حذف المستخدمين</label>
                <input type="checkbox" id="admin_users_remove" name="admin_users_remove" {{$privileges->admin_users_remove ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_users_edit">اللوحة - تعديل المستخدمين</label>
                <input type="checkbox" id="admin_users_edit" name="admin_users_edit" {{$privileges->admin_users_edit ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_virtuals">اللوحة - الوهميين</label>
                <input type="checkbox" id="admin_virtuals" name="admin_virtuals" {{$privileges->admin_virtuals ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_virtuals_remove">اللوحة - حذف الوهميين</label>
                <input type="checkbox" id="admin_virtuals_remove" name="admin_virtuals_remove" {{$privileges->admin_virtuals_remove ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_virtuals_edit">اللوحة - تعديل الوهميين</label>
                <input type="checkbox" id="admin_virtuals_edit" name="admin_virtuals_edit" {{$privileges->admin_virtuals_edit ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_banneds">اللوحة - المحظورين</label>
                <input type="checkbox" id="admin_banneds" name="admin_banneds" {{$privileges->admin_banneds ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_banneds_remove">اللوحة - إزالة الحظر</label>
                <input type="checkbox" id="admin_banneds_remove" name="admin_banneds_remove" {{$privileges->admin_banneds_remove ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_rooms">اللوحة - الغرف</label>
                <input type="checkbox" id="admin_rooms" name="admin_rooms" {{$privileges->admin_rooms ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_rooms_edit">اللوحة - نعديل الغرف</label>
                <input type="checkbox" id="admin_rooms_edit" name="admin_rooms_edit" {{$privileges->admin_rooms_edit ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_rooms_remove">اللوحة - حذف الغرف</label>
                <input type="checkbox" id="admin_rooms_remove" name="admin_rooms_remove" {{$privileges->admin_rooms_remove ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_supers">اللوحة - الإشتراكات</label>
                <input type="checkbox" id="admin_supers" name="admin_supers" {{$privileges->admin_supers ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_supers_remove">اللوحة - حذف الإشتراكات</label>
                <input type="checkbox" id="admin_supers_remove" name="admin_supers_remove" {{$privileges->admin_supers_remove ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <label for="admin_settings">اللوحة - الإعدادات</label>
                <input type="checkbox" id="admin_settings" name="admin_settings" {{$privileges->admin_settings ? "checked" : ""}}/>
            </div>

            <div class="form-group">
                <button class="btn btn-success">حفظ التعديلات</button>
            </div>

        </form>

        @endif

    </div>
@endsection

@section("scripts")
    <script src="{{url('js/socket.io.js')}}"></script>
    <script>
        $(document).ready(function() {

            var token = '{{$token}}';
            var socket = io(socketHost, {transports: ['websocket', 'xhr-polling', 'polling', 'htmlfile', 'flashsocket'],
                secure: true,
                rejectUnauthorized: false,
                query: "token="+token}
            );

            $("#role_icon").on("click", function() {
                $("#role_icon_input").trigger("click");
            });

            $("#role_icon_input").on("change", function() {
                var formData = new FormData();
                formData.append("icon", $(this).prop("files")[0]);
                formData.append("role", $("#select-role").val());
                uploadRoleIcon(formData);
            });

            $("#add-role").on("click", function() {
               var roleName = "";
               roleName = prompt("الرجاء إدخال إسم السوبر");
               addRole(roleName);
            });

            $("#remove-role").on("click", function() {
                if(confirm("هل أنت متأكد من حذف السوبر؟")) {
                    removeRole($("#select-role").val());
                }
            });

            $("#remove-role-icon").on("click", function() {
                if(confirm("هل أنت متأكد من حذف الأيقونة؟")) {
                    console.log("target", $(this).data("target"));
                    removeRoleIcon($(this).data("target"));
                }
            });

            $("#select-role").on("change", function() {
                location.href = location.pathname+"?r="+$(this).val();
            });

            $("#main-content > form").on("submit", function(e) {
                e.preventDefault();
                saveAutorizations($(this), $("#select-role").val());
            });

            //***********************************************************************************

            function addRole(roleName) {
                if(!roleName) return;
                $.ajax({
                    url: window.location.pathname,
                    type: "post",
                    data: {roleName: roleName},
                    success: function(data) {
                        try {
                            var res = JSON.parse(data);
                            if(res.error === false) {
                                socket.emit("req", {type: "ADMIN_ROLES_RESET", data: {token: token} });
                                window.location.reload();
                            } else {
                                if(res.msg == "The role name has already been taken.") {
                                    alert("هذا الإسم مستخدم من قبل");
                                } else if(res.msg == "error") {
                                    alert("حدث خطأ أثناء القيام بالعملية, الرجاء إعادة المحاولة");
                                }
                            }
                        } catch(e) {}
                    }
                });
            }

            function removeRole(roleId) {
                if(!roleId) return;
                $.ajax({
                    url: window.location.pathname,
                    type: "post",
                    data: {roleId: roleId},
                    success: function(data) {
                        try {
                            var res = JSON.parse(data);
                            if(res.error === false) {
                                socket.emit("req", {type: "ADMIN_ROLES_RESET", data: {token: token} });
                                window.location.href = location.pathname;
                            } else {
                                if(res.msg == "unremovable role") {
                                    alert("هذا السوبر غير قابل للحذف");
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

            function removeRoleIcon(roleId) {
                if(!roleId) return;
                $.ajax({
                    url: window.location.pathname+"/remove-icon",
                    type: "post",
                    data: {roleId: roleId},
                    success: function(data) {
                        try {
                            var res = JSON.parse(data);
                            if(res.error === false) {
                                $("#role_icon").attr("src", '/images/none.png');
                                socket.emit("req", {type: "ADMIN_ROLE_REMOVE_ICON", data: {role: res.role, token: token} });
                            } else {
                                if(res.msg == "unremovable role") {
                                    alert("هذا السوبر غير قابل للحذف");
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

            function saveAutorizations(form, roleID) {
                var data = {};
                $.each(form.find("input, textarea, select"), function(indes, value) {
                    if($(this).attr("type") == "checkbox") {
                        data[$(this).attr("id")] = $(this)[0].checked;
                    } else if($(this).attr("type") == "number") {
                        data[$(this).attr("id")] = parseInt($(this).val());
                    } else {
                        data[$(this).attr("id")] = $(this).val();
                    }
                });
                data.role = roleID;
                $.ajax({
                    url: window.location.pathname+'/save',
                    type: "post",
                    data: data,
                    success: function(data) {
                        try {
                            var res = JSON.parse(data);
                            if(res.error === false) {
                                socket.emit("req", {type: "ADMIN_PERMISSIONS_RESET", data: {roleID: roleID, token: token} });
                            } else {
                                if(res.msg == "error") {
                                    alert("حدث خطأ أثناء القيام بالعملية, الرجاء إعادة المحاولة");
                                } else if(res.msg == "not permitted") {
                                    alert("لا تلمك الصلاحيات للقيام بهذه العملية");
                                }
                            }
                        } catch(e) {console.log(e)}
                    }
                });

            }

            function uploadRoleIcon(formData) {
                $.ajax({
                    xhr: function() {
                        return new window.XMLHttpRequest();
                    },
                    url: window.location.pathname+'/icon',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        try {
                            if(response.error === false) {
                                $("#role_icon").attr("src", '/uploads/roles/'+response.icon);
                                socket.emit("req", {type: "ADMIN_ROLE_CHANGE_ICON", data: {role: response.role, icon: response.icon, token: token} });
                            }
                        } catch(e) {
                            var response = JSON.parse(response);
                            if(response.msg == "error") {
                                alert("حدث خطأ أثناء القيام بالعملية, الرجاء إعادة المحاولة");
                            } else if(response.msg == "not permitted") {
                                alert("لا تلمك الصلاحيات للقيام بهذه العملية");
                            }
                        }
                    },
                    error: function() { alert("حدث خطأ أثناء رفع الصورة"); }
                });

            }

        });
    </script>
@endsection