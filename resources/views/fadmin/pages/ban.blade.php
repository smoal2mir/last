@extends('fadmin.master')

@section('content')
    <div id="main-content" class="ban-page">

        <div class="section1 row" style="padding-bottom: 15px; margin-bottom: 15px; border-bottom: 2px solid #ccc;">
            <h3>إضافة حظر مخصص</h3>

            @if(Session::has("errors"))
                <div class="alert alert-danger">{{$errors->first()}}</div>
            @endif

            @if(Session::has("success_msg"))
                <div class="alert alert-success">{{Session::get("success_msg")}}</div>
            @endif

            <form action="{{ url("/fadmn/ban-device-or-ip/$token") }}" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="ip">خظر الأيبي</label>
                    <input type="text" name="ip" id="ip" placeholder="عنوان الأيبي" class="form-control">
                </div>
                <div class="form-group">
                    <label for="device">رقم الجهاز</label>
                    <input type="text" name="device" id="device" placeholder="رقم الجهاز" class="form-control">
                </div>
                <input type="submit" value="أضف" class="btn btn-success" style="width: 100px;">
            </form>
        </div>

        <div class="row">
            <form id="ban-search">
                <input type="saerch" name="search" value="{{$keyword}}" placeholder="إبحث عن: الجهاز / الدولة / الأيبي"/>
                <button class="btn btn-info">إبحث</button>
            </form>
        </div>

        <p><button id="clear-banneds" class="btn btn-danger">إزالة الحظر عن الجميع</button></p>

        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>العضو</th><th>المراقب</th><th>الأيبي</th><th>الجهاز</th><th>الدوله</th><th>الوقت</th><th>رفع</th>
                </tr>
                </thead>
                <tbody>
                @foreach($banneds as $banned)
                    <tr data-id="{{$banned->id}}">
                        <td>{{$banned->username ?: "/"}}</td>
                        <td>{{$banned->by ?: "/"}}</td>
                        <td>{{$banned->ip ?: "/"}}</td>
                        <td>{{$banned->device ?: "/"}}</td>
                        <td style="max-width: 30px; text-align: center;">{!!$banned->country ? '<img src="/images/flags/'.strtolower($banned->country).'.png" />' : "/"!!}</td>
                        <td>{{$banned->updated_at ?: "/"}}</td>
                        <td style="max-width: 30px; text-align: center;"><button class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection

@section("scripts")
    <script src="{{url('js/socket.io.js')}}"></script>
    <script>

        var token = '{{$token}}';
        var socket = io(socketHost, {transports: ['websocket', 'xhr-polling', 'polling', 'htmlfile', 'flashsocket'],
            secure: true,
            rejectUnauthorized: false,
            query: "token="+token}
        );

        $("table td button.remove").on("click", function() {
            var id = $(this).closest("tr").data("id");
            if(id) {
                if(confirm("هل أنت متأكد من إزالة الحظر؟")) {
                    unbanID(id);
                }
            }

        });

        $("#clear-banneds").on("click", function() {
            if(confirm("هل أنت متأكد من إزالة الحظر؟")) {
                unbanID("all");
            }
        });

        function unbanID(id) {
            $.ajax({
                url: window.location.pathname,
                type: "post",
                data: { id: id },
                success: function(res) {
                    var data = res;
                    if(data.error === false) {

                        if(data.isTruncated) {
                            $("table tbody tr").remove();
                        } else if(data.ip || data.device) {
                            $("table tr[data-id='" + id + "']").remove();
                        }

                        data.token = token;

                        socket.emit("req", {type: "ADMIN_BANNEDS_CLEAR", data: data });

                    }
                },
                error: function (res) {
                    if(res.msg == "no permission") {
                        alert("لا تملك الصلاحيات للقيام بهذه العمية");
                    } else {
                        alert("حدث خطأ أثناء العملية");
                    }
                }
            });
        }
    </script>
@endsection