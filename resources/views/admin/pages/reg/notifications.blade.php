@extends('admin.master')

@section('content')
    <?php $activeLI = 8; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div id="reg-notifications-section">
            <div class="section2">
                <h3>سجل التنتبيهات</h3>

                <div class="section-table-header">
                    <div class="left-section">
                        <ul>
                            <li><a href="{{ route('reg.notifications') }}">إعادة تحميل</a></li>
                            <li><a href="#" id="reg-notifications-toggle-search">بحث</a></li>
                            <li><a href="#" id="select-all">حدد الكل</a></li>
                            <li><a href="#" id="unselect-all" style="display: none;">إلغاء تحديد الكل</a></li>
                        </ul>
                        <form id="reg-notifications-search-form" style="display: {{$query ? "inline" : "none"}}; margin-top: 5px;">
                            <div class="form-group">
                                <input type="search" name="q" value="{{$query}}" style="width:73%;border:1px solid gray;border-radius:2px;padding: 7px 8px;" placeholder="إسم مستخدم أو أي كلمة">
                                <input type="submit" value="بحث" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                    <div dir="rtl" class="right-section">
                        <ul>
                            <li><a href="#" id="reg-notifications-delete">حذف</a></li>
                            <li><a href="#" id="reg-notifications-empty">تفريغ سجل التنبيهات</a></li>
                        </ul>
                        <br/>

                        <div class="pagination">
                            <span>الصفحة:</span>  {!! $notifications->render() !!}
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><input id="checkbox-select-all" type="checkbox" style="width: 17px; height: 17px;"></th>
                            <th>الإسم الأصلي للمرسل</th><th>الإسم المستعار للمرسل</th>
                            <th>الإسم الأصلي للمستقبل</th><th>الإمس المستعار للمستقبل</th>
                            <th>التنبيه</th><th>التاريخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($notifications as $notification) {
                            echo '<tr row_id="' . $notification->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $notification->id . '"></td>';
                            echo '<td>' . $notification->byRealName . '</td>';
                            echo '<td>' . $notification->byNickName . '</td>';
                            echo '<td>' . $notification->toRealName . '</td>';
                            echo '<td>' . $notification->toNickName . '</td>';
                            echo '<td>' . $notification->msg . '</td>';
                            echo '<td>' .$notification->created_at. '</td></tr>';
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
    @include("scripts.reg.notifications")
@stop