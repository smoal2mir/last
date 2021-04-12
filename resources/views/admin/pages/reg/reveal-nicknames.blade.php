@extends('admin.master')

@section('content')
    <?php $activeLI = 15; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div id="reveal-nicknames-section">
            <div class="section2">
                <h3>كشف النكات</h3>

                <div class="section-table-header">
                    <div class="left-section">
                        <ul>
                            <li><a href="{{ route('reveal-nicknames') }}">إعادة تحميل</a></li>
                            <li><a href="#" id="reveal-nicknames-toggle-search">بحث</a></li>
                            <li><a href="#" id="select-all">حدد الكل</a></li>
                            <li><a href="#" id="unselect-all" style="display: none;">إلغاء تحديد الكل</a></li>
                        </ul>
                        <form id="reveal-nicknames-search-form" style="display: {{$query ? "inline" : "none"}};">
                            <div class="form-group">
                                <input type="search" name="q" value="{{$query}}" placeholder="أدخل كلمة للبحث">
                                <input type="submit" value="بحث" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                    <div dir="rtl" class="right-section">
                        <ul>
                            <li><a href="#" id="reveal-nicknames-delete">حذف</a></li>
                            <li><a href="#" id="reveal-nicknames-empty">تفريغ سجل كشف النكات</a></li>
                        </ul>
                        <br/>

                        <div class="pagination">
                            <span>الصفحة:</span>  {!! $nicknames->render() !!}
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><input id="checkbox-select-all" type="checkbox"style="width: 17px; height: 17px;"></th>
                            <th>الإسم</th><th>الزخرفة</th><th>الأيبي</th><th>الجهاز</th><th>آخر زيارة</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($nicknames as $name) {
                            echo '<tr row_id="' . $name->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $name->id . '"></td>';
                            echo '<td width="20%">' . $name->username . '</td>';
                            echo '<td width="20%">' . $name->decoration . '</td>';
                            echo '<td width="20%">' . $name->ip . '</td>';
                            echo '<td width="20%">' . $name->device . '</td>';
                            echo '<td width="15%">' . $name->updated_at . '</td></tr>';
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
    @include("scripts.reg.reveal-nicknames")
@stop