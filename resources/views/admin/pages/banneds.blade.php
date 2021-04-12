@extends('admin.master')

@section('content')
    <?php $activeLI = 2; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div id="banneds-section">
            <div class="section1">
                <h3>إضافة حظر مخصص</h3>

                <form action="{{ route('banneds.add') }}" method="POST">
                    <div class="form-group">
                        <label for="ip" style="color: #777; font-weight: normal;">خظر الأيبي</label>
                        <input type="text" name="ip" id="ip" placeholder="عنوان الأيبي"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="finishes_at" style="color: #777; font-weight: normal;">المدة الزمنية</label>
                        <input type="text" name="finishes_at" id="finishes_at" placeholder="المدة الزمنية" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="cause" style="color: #777; font-weight: normal;">السبب / مذكرة</label>
                        <input type="text" name="cause" id="cause" placeholder="سبب الحظر" class="form-control">
                    </div>
                    <input type="submit" value="أضف" class="btn btn-danger" style="width: 100px;">
                </form>
            </div>

            <div class="section2">
                <h3>الأعضاء المحظورين</h3>

                <div class="section-table-header">
                    <div class="left-section">
                        <ul>
                            <li><a href="{{ route('banneds') }}">إعادة تحميل</a></li>
                            <li><a href="#" id="banneds-toggle-search">بحث</a></li>
                            <li><a href="#" id="select-all">حدد الكل</a></li>
                            <li><a href="#" id="unselect-all" style="display: none;">إلغاء تحديد الكل</a></li>
                        </ul>
                        <form id="banneds-search-form" action="" style="display: {{$query ? "inline" : "none"}};">
                            <div class="form-group">
                                <input type="search" name="q" value="{{$query}}" placeholder="إسم أو أيبي المستخدم">
                                <input type="submit" value="بحث" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                    <div dir="rtl" class="right-section">
                        <ul>
                            <li><a href="#" id="banneds-unban">رفع الحظر</a></li>
                        </ul>
                        <br/>

                        <div class="pagination">
                            <span>الصفحة:</span>  {!! $banneds->render() !!}
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><input id="checkbox-select-all" type="checkbox" style="width: 17px; height: 17px;"></th>
                            <th>إسم المستخدم</th><th>إسم المراقب</th><th>بداية الحظر</th><th>نهاية الحظر</th><th>الأيبي</th><th>البلد</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach ($banneds as $banned) {
                                echo '<tr row_id="' . $banned->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $banned->id . '"></td>';
                                echo '<td>' . $banned->username . '</td>';
                                echo '<td>' . $banned->by . '</td>';
                                echo '<td>' . $banned->created_at . '</td>';
                                echo '<td>' . $banned->finishes_at . '</td>';
                                echo '<td>' . $banned->ip . '</td>';
                                echo '<td><img src="/images/flags/' . strtolower($banned->country) . '.png" /></td></tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="popup" id="banneds-change-finish">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="finishes_at" class="form-label">المدة الزمنية</label>
                        <input type="text" name="finishes_at" placeholder="المدة الزمنية" class="form-control"/>
                    </div>
                    <button class="btn btn-default">رجوع</button>
                    <input type="submit" value="حظر" class="btn btn-danger">
                </form>
            </div>

        </div>
    </div>
@stop
@section('scripts')
    <script src="{{url('js/socket.io.js')}}"></script>
    @include("scripts.banneds")
@stop