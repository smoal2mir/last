@extends('fadmin.master')

@section('content')
    <div id="main-content" class="reg-page">
        <div class="row">
            <form id="reg-search">
                <input type="saerch" name="search" value="{{$keyword}}" placeholder="إبحث عن: الجهاز / الدولة / الأيبي"/>
                <button class="btn btn-info">إبحث</button>
            </form>
            <button id="clear-reg" class="btn btn-danger" style="margin: 10px; margin-top: 5px">تغريغ السجل</button>
        </div>

        <div class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الرتبة</th><th>العضو</th><th>الزخرفه</th><th>الأيبي</th><th>الدوله</th><th>الجهاز</th><th>الوقت</th><th>حذف</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reg as $value)
                        <tr data-id="{{$value->id}}">
                            <td>{{ \App\Role::getRoleName($value->role) }}</td>
                            <td>{{$value->username ?: "/"}}</td>
                            <td>{{$value->decoration ?: "/"}}</td>
                            <td>{{$value->ip ?: "/"}}</td>
                            <td>{!!$value->country ? '<img src="/images/flags/'.strtolower($value->country).'.png" />' : "/"!!}</td>
                            <td>{{$value->device ?: "/"}}</td>
                            <td>{{$value->updated_at ?: "/"}}</td>
                            <td style="max-width: 30px; text-align: center;"><button class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection

@section("scripts")
    <script>

        $("table td button.remove").on("click", function() {
            var id = $(this).closest("tr").data("id");
            if(id) {
                if(confirm("هل أنت متأكد من عملية الحذف؟")) {
                    removeItem(id);
                }
            }

        });

        $("#clear-reg").on("click", function() {
            if(confirm("هل أنت متأكد من عملية الحذف؟")) {
                removeItem("all");
            }
        });

        function removeItem(id) {
            $.ajax({
                url: window.location.pathname,
                type: "post",
                data: { id: id },
                success: function(res) {
                    var data = res;
                    if(data.error === false) {

                        if(data.isTruncated) {
                            $("table tbody tr").remove();
                        } else {
                            $("table tr[data-id='" + id + "']").remove();
                        }

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