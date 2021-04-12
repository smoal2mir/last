<div class="sidebar">
    <ul class="nav nav-sidebar">
        @if(\App\Http\Controllers\FadminController::can($token, "admin"))
            <li class="{{$page == "home" ? "active" : ""}}"><a href="{{"/fadmn/home/".$token}}">اللوحة</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_reg"))
            <li class="{{$page == "reg" ? "active" : ""}}"><a href="{{"/fadmn/reg/".$token}}">السجل</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_events"))
            <li class="{{$page == "events" ? "active" : ""}}"><a  href="{{"/fadmn/events/".$token}}">الأحداث</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_users"))
            <li class="{{$page == "users" ? "active" : ""}}"><a  href="{{"/fadmn/users/".$token}}">الأعضاء</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_supers"))
            <li class="{{$page == "subscriptions" ? "active" : ""}}"><a  href="{{"/fadmn/subscriptions/".$token}}">الإشتراكات</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_virtuals"))
            <li class="{{$page == "virtuals" ? "active" : ""}}"><a  href="{{"/fadmn/virtuals/".$token}}">الوهميين</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_rooms"))
            <li class="{{$page == "rooms" ? "active" : ""}}"><a  href="{{"/fadmn/rooms/".$token}}">الغرف</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_banneds"))
            <li class="{{$page == "ban" ? "active" : ""}}"><a  href="{{"/fadmn/ban/".$token}}">الحظر</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "edit_permissions"))
            <li class="{{$page == "privileges" ? "active" : ""}}"><a  href="{{"/fadmn/privileges/".$token}}">الصلاحيات</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_settings"))
            <li class="{{$page == "filter" ? "active" : ""}}"><a  href="{{"/fadmn/filter/".$token}}">فلتر</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_settings"))
            <li class="{{$page == "filtered-words" ? "active" : ""}}"><a  href="{{"/fadmn/filtered-words/".$token}}">الكلمات المفلترة</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_settings"))
            <li class="{{$page == "gifts-and-faces" ? "active" : ""}}"><a  href="{{"/fadmn/gifts-and-faces/".$token}}">الفيسات و الهدايا</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_settings"))
            <li class="{{$page == "self-messages" ? "active" : ""}}"><a  href="{{"/fadmn/self-messages/".$token}}">الرسائل التلقائية</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_settings"))
            <li class="{{$page == "shortcuts" ? "active" : ""}}"><a  href="{{"/fadmn/shortcuts/".$token}}">الإختصارات</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_settings"))
            <li class="{{$page == "settings" ? "active" : ""}}"><a  href="{{"/fadmn/settings/".$token}}">الإعدادات</a></li>
        @endif
        @if(\App\Http\Controllers\FadminController::can($token, "admin_settings"))
            <li class="{{$page == "developer" ? "active" : ""}}"><a  href="{{"/fadmn/developer/".$token}}">خاص بالمطورين</a></li>
        @endif
    </ul>
</div>