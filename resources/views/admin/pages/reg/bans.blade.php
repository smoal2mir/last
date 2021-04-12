@extends('admin.master')

@section('content')
    <?php
        $actions = ["ban" => "حظر", "unban" => "رفع الحظر"];
    ?>
    <?php $activeLI = 10; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div id="reg-bans-section">
            <div class="section2">
                <h3>سجل الحظر</h3>

                <div class="section-table-header">
                    <div class="left-section">
                        <ul>
                            <li><a href="{{ route('reg.bans') }}">إعادة تحميل</a></li>
                            <li><a href="#" id="reg-bans-toggle-search">بحث</a></li>
                            <li><a href="#" id="select-all">حدد الكل</a></li>
                            <li><a href="#" id="unselect-all" style="display: none;">إلغاء تحديد الكل</a></li>
                        </ul>
                        <form id="reg-bans-search-form" style="display: {{$query ? "inline" : "none"}}; margin-top: 5px;">
                            <div class="form-group">
                                <input type="search" name="q" value="{{$query}}" style="width:73%;border:1px solid gray;border-radius:2px;padding: 7px 8px;" placeholder="إسم مستخدم أو أي كلمة">
                                <input type="submit" value="بحث" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                    <div dir="rtl" class="right-section">
                        <ul>
                            <li><a href="#" id="reg-bans-delete">حذف</a></li>
                            <li><a href="#" id="reg-bans-empty">تفريغ سجل الحظر</a></li>
                        </ul>
                        <br/>

                        <div class="pagination">
                            <span>الصفحة:</span>  {!! $bans->render() !!}
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><input id="checkbox-select-all" type="checkbox" style="width: 17px; height: 17px;"></th>
                            <th>الإسمل الأصلي للفاعل</th><th>الإسم المستعار للفاعل</th>
                            <th>الإسم الأصلي للمحظور</th><th>الإسم المستعار للمحظور</th>
                            <th>السبب</th><th>العملية</th><th>التاريخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($bans as $ban) {
                            echo '<tr row_id="' . $ban->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $ban->id . '"></td>';
                            echo '<td>' . $ban->byRealName . '</td>';
                            echo '<td>' . $ban->byNickName . '</td>';
                            echo '<td>' . $ban->toRealName . '</td>';
                            echo '<td>' . $ban->toNickName . '</td>';
                            echo '<td>' . $ban->getReason() . '</td>';
                            echo '<td><span class="'.$ban->actionType.'-action">' . $actions[$ban->actionType] . '</span></td>';
                            echo '<td>' .$ban->created_at. '</td></tr>';
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
    @include("scripts.reg.bans")
@stop