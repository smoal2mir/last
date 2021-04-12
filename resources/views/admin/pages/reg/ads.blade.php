@extends('admin.master')

@section('content')
    <?php $activeLI = 9; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div id="reg-ads-section">
            <div class="section2">
                <h3>سجل الإعلانات</h3>

                <div class="section-table-header">
                    <div class="left-section">
                        <ul>
                            <li><a href="{{ route('reg.ads') }}">إعادة تحميل</a></li>
                            <li><a href="#" id="reg-ads-toggle-search">بحث</a></li>
                            <li><a href="#" id="select-all">حدد الكل</a></li>
                            <li><a href="#" id="unselect-all" style="display: none;">إلغاء تحديد الكل</a></li>
                        </ul>
                        <form id="reg-ads-search-form" style="display: {{$query ? "inline" : "none"}}; margin-top: 5px;">
                            <div class="form-group">
                                <input type="search" name="q" value="{{$query}}" style="width:73%;border:1px solid gray;border-radius:2px;padding: 7px 8px;" placeholder="إسم مستخدم أو أي كلمة">
                                <input type="submit" value="بحث" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                    <div dir="rtl" class="right-section">
                        <ul>
                            <li><a href="#" id="reg-ads-delete">حذف</a></li>
                            <li><a href="#" id="reg-ads-empty">تفريغ سجل الإعلانات</a></li>
                        </ul>
                        <br/>

                        <div class="pagination">
                            <span>الصفحة:</span>  {!! $ads->render() !!}
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
                            <th>الإعلان</th><th>التاريخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($ads as $ad) {
                            echo '<tr row_id="' . $ad->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $ad->id . '"></td>';
                            echo '<td>' . $ad->byRealName . '</td>';
                            echo '<td>' . $ad->byNickName . '</td>';
                            echo '<td>' . $ad->msg . '</td>';
                            echo '<td>' .$ad->created_at. '</td></tr>';
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
    @include("scripts.reg.ads")
@stop