<div class="col-sm-3 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <li class="{{$activeLI == 1 ? "active" : ""}}">
            <a href="{{ route('users') }}" id="toggle-users-section"><span
                        class="nav-item-icon flaticon-speed13"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>المستخدمين</a>
        </li>
        <li class="{{$activeLI == 2 ? "active" : ""}}">
            <a href="{{ route('banneds') }}" id="toggle-banneds-section"><span
                        class="nav-item-icon flaticon-adding4"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>المحظورين</a>
        </li>
        <li class="{{$activeLI == 3 ? "active" : ""}}">
            <a href="{{ route('rooms') }}" id="toggle-rooms-section"><span
                        class="nav-item-icon flaticon-squares36"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>الغرف</a>
        </li>
        <li class="{{$activeLI == 4 ? "active" : ""}}">
            <a href="{{ route('settings') }}" id="toggle-settings-section"><span
                        class="nav-item-icon flaticon-worker37"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>الإعدادات</a>
        </li>
        <li class="{{$activeLI == 5 ? "active" : ""}}">
            <a href="{{ route('reg.private') }}" id="toggle-settings-section"><span
                        class="nav-item-icon flaticon-worker37"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>سجل الخاص</a>
        </li>
        <li class="{{$activeLI == 6 ? "active" : ""}}">
            <a href="{{ route('reg.public') }}" id="toggle-settings-section"><span
                        class="nav-item-icon flaticon-worker37"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>سجل العام</a>
        </li>
        <li class="{{$activeLI == 7 ? "active" : ""}}">
            <a href="{{ route('reg.wall') }}" id="toggle-settings-section"><span
                        class="nav-item-icon flaticon-worker37"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>سجل الحائط</a>
        </li>
        <li class="{{$activeLI == 8 ? "active" : ""}}">
            <a href="{{ route('reg.notifications') }}" id="toggle-settings-section"><span
                        class="nav-item-icon flaticon-worker37"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>سجل التنبيهات</a>
        </li>
        <li class="{{$activeLI == 9 ? "active" : ""}}">
            <a href="{{ route('reg.ads') }}" id="toggle-settings-section"><span
                        class="nav-item-icon flaticon-worker37"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>سجل الإعلانات</a>
        </li>
        <li class="{{$activeLI == 10 ? "active" : ""}}">
            <a href="{{ route('reg.bans') }}" id="toggle-settings-section"><span
                        class="nav-item-icon flaticon-worker37"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>سجل الحظر</a>
        </li>
        <li class="{{$activeLI == 11 ? "active" : ""}}">
            <a href="{{ route('reg.excpels') }}" id="toggle-settings-section"><span
                        class="nav-item-icon flaticon-worker37"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>سجل الطرد</a>
        </li>
        <li class="{{$activeLI == 12 ? "active" : ""}}">
            <a href="{{ route('reg.rooms') }}" id="toggle-settings-section"><span
                        class="nav-item-icon flaticon-worker37"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>سجل الغرف</a>
        </li>
        <li class="{{$activeLI == 13 ? "active" : ""}}">
            <a href="{{ route('reg.orders') }}" id="toggle-settings-section"><span
                        class="nav-item-icon flaticon-worker37"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>سجل الأوامر</a>
        </li>
        <li class="{{$activeLI == 15 ? "active" : ""}}">
            <a href="{{ route('reveal-nicknames') }}" id="toggle-rooms-section"><span
                        class="nav-item-icon flaticon-squares36"></span><span
                        class="nav-right-arrow flaticon-fastforward4"></span>كشف النكات</a>
        </li>
    </ul>
</div>