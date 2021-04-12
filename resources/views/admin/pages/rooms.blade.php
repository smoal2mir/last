@extends('admin.master')

@section('content')
    <?php $activeLI = 3; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div id="rooms-section">
            <div class="section1">
                <h3>إضافة غرفة جديدة</h3>
                <script src="{{url('js/socket.io.js')}}"></script>
                <script>
                    var socket = io(socketHost, {transports: ['websocket', 'xhr-polling', 'polling', 'htmlfile', 'flashsocket'],
                        secure: true,
                        rejectUnauthorized: false,
                        query: "admin_hash="+'{{env('ADMIN_HASH')}}'}
                    );
                </script>
                <?php
                    if(Session::has('addedRoom')) { $room = Session::get('addedRoom'); ?>
                    <script>
                        var room = {};

                        room.id         = <?php echo $room->id . ";"; ?>
                        room.name       = <?php echo '"'.$room->name . '";'; ?>
                        room.welcome    = <?php echo '"'.$room->welcome . '";'; ?>
                        room.description= <?php echo '"";'; ?>
                        room.password   = <?php echo '"'.$room->password . '";'; ?>
                        room.capacity   = <?php echo $room->capacity . ";"; ?>
                        room.target     = <?php echo '"'.$room->target . '";'; ?>

                        socket.emit("req", {
                            type: "ADMIN_ROOM_CREATE",
                            data: { room: room, admin_hash: '{{env('ADMIN_HASH')}}' }
                        });

                    </script>
                <?php } ?>

                <form action="{{ route('rooms.add') }}" method="POST">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="name" style="color: #777; font-weight: normal;">إسم الغرفة</label>
                        <input type="text" name="name" id="name" placeholder="إسم الغرفة"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password" style="color: #777; font-weight: normal;">كلمة السر</label>
                        <input type="text" name="password" id="password" placeholder="كلمة السر" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="welcome" style="color: #777; font-weight: normal;">الرسالة الترحيبية</label>
                        <input type="text" name="welcome" id="welcome" placeholder="الرسالة الترحيبية" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="max" style="color: #777; font-weight: normal;">عدد الزوار</label>
                        <input type="number" name="max" id="max" value="100" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="for" style="color: #777; font-weight: normal;">نوع الزوار</label>
                        <select name="for" class="form-control">
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
                        <ul>
                            <li><a href="{{ route('rooms') }}">إعادة تحميل</a></li>
                            <li><a href="#" id="rooms-toggle-search">بحث</a></li>
                            <li><a href="#" id="select-all">حدد الكل</a></li>
                            <li><a href="#" id="unselect-all" style="display: none;">إلغاء تحديد الكل</a></li>
                        </ul>
                        <form id="rooms-search-form" action="" style="display: {{$query ? "inline" : "none"}};">
                            <div class="form-group">
                                <input type="search" name="q" value="{{$query}}" placeholder="إسم أو أيبي المستخدم">
                                <input type="submit" value="بحث" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                    <div dir="rtl" class="right-section">
                        <ul>
                            <li><a href="#" id="rooms-delete">حذف</a></li>
                            <li><a href="#" class="btn btn-success"id="rooms-save-changes">حفظ التعديلات</a></li>
                        </ul>
                        <br/>

                        <div class="pagination">
                            <span>الصفحة:</span>  {!! $rooms->render() !!}
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><input id="checkbox-select-all" type="checkbox"style="width: 17px; height: 17px;"></th>
                            <th>إسم الغرفة</th><th>كلمة المرور</th><th>الرسالة الترحيبية</th>
                            <th>عدد الزوار</th><th>من أجل</th><th>تاريخ الإنشاء</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $roomsFor = ['all' => 'الجميع', 'supers' => 'المراقبين'];
                        foreach ($rooms as $room) {
                            echo '<tr row_id="' . $room->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $room->id . '"></td>';
                            echo '<td><input style="width: 100%" name="name" type="text" value="' . $room->name . '" /></td>';
                            echo '<td><input style="width: 100%" name="password" type="text" value="' . $room->password . '" /></td>';
                            echo '<td><input style="width: 100%" name="welcome" type="text" value="' . $room->welcome . '" /></td>';
                            echo '<td><input style="width: 100%" name="max" type="number" value="' . $room->max . '" /></td>';
                            echo '<td><select>';
                            foreach($roomsFor as $key => $value) {
                                echo ($key == $room->for) ? '<option selected="selected" value="'.$key.'">'.$value.'</option>' : '<option value="'.$key.'">'.$value.'</option>';
                            }
                            echo '</select></td>';
                            echo '<td><input style="width: 100%" name="created_at" type="text" value="' . $room->created_at . '" /></td>';
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    @include("scripts.rooms")
@stop