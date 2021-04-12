@extends('admin.master')

@section('content')
    <?php $activeLI = 14; ?>
    @include('admin.partials.sidebar', [$activeLI])
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <div id="self-messages-section">
            <div class="section1">
                <h3>إضاف رسالة تلقائية</h3>
                <script src="{{url('js/socket.io.js')}}"></script>
                <?php

                    if(Session::has('addedSelfMessage')) { $msg = Session::get('addedSelfMessage'); ?>
                    <script>
                        var host = '{{trim(env("APP_HTTPS_HOST", env("APP_HTTP_HOST")), '/')}}';
                        var ADMIN_HASH = '{{env('ADMIN_HASH')}}';
                        var socket = io(host+"{{env("APP_PORT")}}", {query: "ADMIN_HASH="+ADMIN_HASH});
                        var msg = {};

                        msg.id         = <?php echo $msg->id . ";"; ?>
                        msg.msg       = <?php echo '"'.$msg->msg . '";'; ?>
                        msg.interval    = <?php echo $msg->interval . ";"; ?>

                        socket.emit("publishSelfMessage", msg, ADMIN_HASH);
                    </script>

                <?php } ?>

                <form action="{{ route('self-messages.add') }}" method="POST">
                    @if(session("success_message"))
                        <div class="alert alert-success">{{session("success_message")}}</div>
                    @endif
                        @if(session("error_message"))
                            <div class="alert alert-error">{{session("error_message")}}</div>
                        @endif
                    <div class="form-group">
                        <label for="msg" style="color: #777; font-weight: normal;">الرسالة</label>
                        <input type="text" name="msg" id="msg" placeholder="الرسالة" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="interval" style="color: #777; font-weight: normal;">الفاصل الزمني (بالدقائق)</label>
                        <input type="number" name="interval" id="interval" value="30" min="1" class="form-control">
                    </div>

                    <input type="submit" value="أضف" class="btn btn-success" style="width: 100px;">
                </form>
            </div>

            <div class="section2">
                <h3>الرسائل التلقائية</h3>

                <div class="section-table-header">
                    <div class="left-section">
                        <ul>
                            <li><a href="{{ route('self-messages') }}">إعادة تحميل</a></li>
                            <li><a href="#" id="self-messages-toggle-search">بحث</a></li>
                            <li><a href="#" id="select-all">حدد الكل</a></li>
                            <li><a href="#" id="unselect-all" style="display: none;">إلغاء تحديد الكل</a></li>
                        </ul>
                        <form id="self-messages-search-form" method="POST" action="" style="display: none;">
                            <div class="form-group">
                                <input type="search" name="userOrIp" placeholder="أدخل كلمة للبحث">
                                <input type="submit" value="بحث" class="btn btn-default">
                            </div>
                        </form>
                    </div>
                    <div dir="rtl" class="right-section">
                        <ul>
                            <li><a href="#" id="self-messages-delete">حذف</a></li>
                            <li><a href="#" class="btn btn-success"id="self-messages-save-changes">حفظ التعديلات</a></li>
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
                            <th><input id="checkbox-select-all" type="checkbox"style="width: 17px; height: 17px;"></th>
                            <th>الرسالة</th><th>الفاصل الزمني (بالدقائق)</th><th>الحالة</th></th><th>تاريخ الإنشاء</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $status = ['running' => 'مشغل', 'paused' => 'موقف'];
                        foreach ($messages as $msg) {
                            echo '<tr row_id="' . $msg->id . '"><td><input class="table-check-row" type="checkbox" check_id="' . $msg->id . '"></td>';
                            echo '<td width="45%"><input style="width: 100%;" name="msg" type="text" value="' . $msg->msg . '" /></td>';
                            echo '<td width="20%"><input style="width: 100%;" name="interval" type="text" value="' . $msg->interval . '" /></td>';
                            echo '<td width="15%"><select class="status-'.$msg->status.'">';
                            foreach($status as $key => $value) {
                                echo ($key == $msg->status) ? '<option selected="selected" value="'.$key.'">'.$value.'</option>' : '<option value="'.$key.'">'.$value.'</option>';
                            }
                            echo '</select></td>';
                            echo '<td width="20%">'.$msg->created_at.'</td>';
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
    @include("scripts.self-messages")
@stop