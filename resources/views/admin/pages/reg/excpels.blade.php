@extends('admin.master')

@section('content')
    <?php $activeLI = 11; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div id="reg-excpels-section">
            <div class="section2">
                <h3>سجل الطرد</h3>

                <div class="section-table-header">
                    <div class="left-section">
                        <ul>
                            <li><a href="{{ route('reg.excpels') }}">إعادة تحميل</a></li>
                            <li><a href="#" id="reg-excpels-toggle-search">بحث</a></li>
                            <li><a href="#" id="select-all">حدد الكل</a></li>
                            <li><a href="#" id="unselect-all" style="display: none;">إلغاء تحديد الكل</a></li>
                        </ul>
                        <form id="reg-excpels-search-form" style="display: {{$query ? "inline" : "none"}}; margin-top: 5px;">
                            <div class="form-group">
                                <input type="search" name="q" value="{{$query}}" style="width:73%;border:1px solid gray;border-radius:2px;padding: 7px 8px;" placeholder="إسم المستخدم...">
                                <input type="submit" value="بحث" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                    <div dir="rtl" class="right-section">
                        <ul>
                            <li><a href="#" id="reg-excpels-delete">حذف</a></li>
                            <li><a href="#" id="reg-excpels-empty">تفريغ سجل الطرد</a></li>
                        </ul>
                        <br/>

                        <div class="pagination">
                            <span>الصفحة:</span>  {!! $excpels->render() !!}
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
                            <th>الإسم الأصلي للمطرود</th><th>الإسم المستعار للمطرود</th><th>التاريخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($excpels as $excpel) {
                            echo '<tr row_id="' . $excpel->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $excpel->id . '"></td>';
                            echo '<td>' . $excpel->byRealName . '</td>';
                            echo '<td>' . $excpel->byNickName . '</td>';
                            echo '<td>' . $excpel->toRealName . '</td>';
                            echo '<td>' . $excpel->toNickName . '</td>';
                            echo '<td>' . $excpel->created_at . '</td></tr>';
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
    @include("scripts.reg.excpels")
@stop