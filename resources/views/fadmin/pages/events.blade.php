@extends('fadmin.master')

@section('content')
    <div id="main-content" class="events-page">
        <div class="row">
            <form id="events-search">
                <input type="saerch" name="search" value="{{$keyword}}" placeholder="إبحث عن ..."/>
                <button class="btn btn-info">إبحث</button>
            </form>
            <form id="events-filter">
                <select name="filter" id="events-filter-select">
                    @foreach(\App\OrderReg::$eventTypes as $key => $vaule)
                        <?php $selected = $filter == "$key" ? "selected" : ""; ?>
                        <option {{$selected}} value="{{$key}}">{{$vaule}}</option>
                    @endforeach
                </select>
                <label dir="rtl" for="events-filter-select">فلتر عبر: </label>
            </form>
            <button id="clear-events" class="btn btn-danger" style="margin: 10px; margin-top: 5px">تغريغ الأحداث</button>
        </div>

        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>الحدث</th><th>الفاعل</th><th>العضو</th><th>الوصف</th><th>الوقت</th><th>حذف</th>
                </tr>
                </thead>
                <tbody>

                @foreach($events as $event)
                    <?php $notes = $event->notes ?>
                    @if($event->actionType == "gift" || $event->actionType == "remove-gift")
                        <?php $notes = '<img src="'.$notes.'" width="25" height="25" />' ?>
                    @endif

                    <tr data-id="{{$event->id}}">
                        <td>{{ \App\OrderReg::getType($event->actionType) }}</td>
                        <td>{{$event->byRealName ?: "/"}}</td>
                        <td>{{$event->toRealName ?: "/"}}</td>
                        <td>{!! $notes ?: "/" !!}</td>
                        <td>{{$event->created_at ?: "/"}}</td>
                        <td style="max-width: 30px; text-align: center;"><button class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $("#events-filter-select").on("change", function() {
                $(this).closest("form").submit();
            });

            $("table td button.remove").on("click", function() {
                var id = $(this).closest("tr").data("id");
                if(id) {
                    if(confirm("هل أنت متأكد من عملية الحذف؟")) {
                        removeItem(id);
                    }
                }

            });

            $("#clear-events").on("click", function() {
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

        });
    </script>
@endsection