@extends('admin.master')

@section('content')
    <?php
        $types = ["text" => "رسالة نصية", "photo" => "صورة", "sound" => "مقطع صوتي", "video" => "مقطع مرئي"];
    ?>
    <?php $activeLI = 5; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div id="reg-private-section">
            <div class="section2">
                <h3>رسائل الخاص</h3>

                <div class="section-table-header">
                    <div class="left-section">
                        <ul>
                            <li><a href="{{ route('reg.private') }}">إعادة تحميل</a></li>
                            <li><a href="#" id="reg-private-toggle-search">بحث</a></li>
                            <li><a href="#" id="select-all">حدد الكل</a></li>
                            <li><a href="#" id="unselect-all" style="display: none;">إلغاء تحديد الكل</a></li>
                        </ul>
                        <form id="reg-private-search-form" method="POST" style="display: none; margin-top: 5px;">
                            <div class="form-group">
                                <input type="search" name="keyword" style="width:73%;border:1px solid gray;border-radius:2px;padding: 7px 8px;" placeholder="إسم مستخدم أو أي كلمة">
                                <input type="submit" value="بحث" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                    <div dir="rtl" class="right-section">
                        <ul>
                            <li><a href="#" id="reg-private-delete">حذف</a></li>
                            <li><a href="#" id="reg-private-empty">تفريغ سجل الخاص</a></li>
                            <li><a href="#" id="reg-private-empty-attach">حذف جميع المرفقات</a></li>
                        </ul>
                        <br/>

                        <div class="pagination">
                            <span>الصفحة:</span>  {!! $messages->render() !!}
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
                            <th>الرسالة</th><th>نوع الرسالة</th><th>التاريخ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($messages as $msg) {
                            if($msg->msgType != 'text') {
                                $text = '<a target="_blank" href="/uploads/private/'.$msg->msg.'">إضغط لمشاهدة المحتوى</A>';
                            } else {
                                $text = $msg->msg;
                            }
                            echo '<tr row_id="' . $msg->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $msg->id . '"></td>';
                            echo '<td>' . $msg->byRealName . '</td>';
                            echo '<td>' . $msg->byNickName . '</td>';
                            echo '<td>' . $msg->toRealName . '</td>';
                            echo '<td>' . $msg->toNickName . '</td>';
                            echo '<td>' . $text . '</td>';
                            echo '<td>' . $types[$msg->msgType] . '</td>';
                            echo '<td>' .$msg->created_at. '</td></tr>';
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
    @include("scripts.reg.private")
@stop