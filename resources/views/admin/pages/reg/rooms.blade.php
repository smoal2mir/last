@extends('admin.master')

@section('content')
    <?php
    $actions = ["add" => "إضافة", "remove" => "حذف", "edit" => "تعديل"];
    ?>
    <?php $activeLI = 12; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div id="reg-rooms-section">
            <div class="section2">
                <h3>سجل الرومات</h3>

                <div class="section-table-header">
                    <div class="left-section">
                        <ul>
                            <li><a href="{{ route('reg.rooms') }}">إعادة تحميل</a></li>
                            <li><a href="#" id="reg-rooms-toggle-search">بحث</a></li>
                            <li><a href="#" id="select-all">حدد الكل</a></li>
                            <li><a href="#" id="unselect-all" style="display: none;">إلغاء تحديد الكل</a></li>
                        </ul>
                        <form id="reg-rooms-search-form" style="display: {{$query ? "inline" : "none"}}; margin-top: 5px;">
                            <div class="form-group">
                                <input type="search" name="q" value="{{$query}}" style="width:73%;border:1px solid gray;border-radius:2px;padding: 7px 8px;" placeholder="إسم مستخدم أو أي كلمة">
                                <input type="submit" value="بحث" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                    <div dir="rtl" class="right-section">
                        <ul>
                            <li><a href="#" id="reg-rooms-delete">حذف</a></li>
                            <li><a href="#" id="reg-rooms-empty">تفريغ سجل الرومات</a></li>
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
                            <th><input id="checkbox-select-all" type="checkbox" style="width: 17px; height: 17px;"></th>
                            <th>الإسم الأصلس للفاعل</th><th>الإسم المستعار للفاعل</th>
                            <th>الغرفة</th><th>العملية</th><th>التاريخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($rooms as $room) {
                            echo '<tr row_id="' . $room->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $room->id . '"></td>';
                            echo '<td>' . $room->byRealName . '</td>';
                            echo '<td>' . $room->byNickName . '</td>';
                            echo '<td>' . $room->roomName . '</td>';
                            echo '<td><span class="rooms-'.$room->actionType.'-action">' . $actions[$room->actionType] . '</span></td>';
                            echo '<td>' .$room->created_at. '</td></tr>';
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
    @include("scripts.reg.rooms")
@stop