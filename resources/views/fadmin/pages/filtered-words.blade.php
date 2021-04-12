@extends('fadmin.master')

@section('content')
    <div id="main-content" class="filtered-words-page">
        <div class="row">
            <form id="filtered-words-search">
                <input type="saerch" name="search" value="{{$keyword}}" placeholder="إبحث عن"/>
                <button class="btn btn-info">إبحث</button>
                <button class="btn btn-danger" id="clear-filtered-words">تفريغ الكلمات المفلترة</button>
            </form>
            <form id="filtered-words-filter">
                <select name="filter" id="filtered-words-filter-select">
                    <option value="all">كل الكلمات</option>
                    @foreach($wordsByType as $key => $vaule)
                        <?php $selected = $filter == $key ? "selected" : ""; ?>
                        <option {{$selected}} value="{{$key}}">{{$vaule}}</option>
                    @endforeach
                </select>
                <label dir="rtl" for="filtered-words-filter-select">فلتر عبر: </label>
            </form>
        </div>

        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>الكلمة</th><th>التصنيف</th><th>العضو</th><th>النص</th><th>الأيبي</th><th>التاريخ</th><th>حذف</th>
                </tr>
                </thead>
                <tbody>
                @foreach($filteredWords as $word)
                    <tr data-id="{{$word->id}}" data-word-id="{{$word->getWord()->id}}" class="{{$word->getWord()->type}}">
                        <td>{{$word->word ?: "/"}}</td>
                        <td>
                            <select class="change_word_type">
                                @foreach($wordsByType as $key => $vaule)
                                    <?php $selected = $word->getWord()->type == $key ? "selected" : ""; ?>
                                        <option {{$selected}} value="{{$key}}">{{$vaule}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>{{$word->user ?: "/"}}</td>
                        <td>{{$word->full_text ?: "/"}}</td>
                        <td>{{$word->ip ?: "/"}}</td>
                        <td>{{$word->created_at ?: "/"}}</td>
                        <td style="max-width: 30px; text-align: center;"><button class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{url('js/socket.io.js')}}"></script>
    <script>
        $(document).ready(function() {

            var token = '{{$token}}';
            var socket = io(socketHost, {transports: ['websocket', 'xhr-polling', 'polling', 'htmlfile', 'flashsocket'],
                secure: true,
                rejectUnauthorized: false,
                query: "token="+token}
            );

            $("#clear-filtered-words").on("click", function(e) {
                e.preventDefault();
                if(!confirm("هل أنت متأكد من تفريغ الكلمات المفلترة؟")) return;
                $.ajax({
                    url: window.location.pathname+"/clear",
                    type: "post",
                    success: function(data) {
                        try {
                            var res = JSON.parse(data);
                            if(res.error === false) {
                                location.reload();
                            } else {
                                if(res.msg == "not permitted") {
                                    alert("لا تلمك الصلاحيات للقيام بهذه العملية");
                                } else {
                                    alert("حدث خطأ أثناء القيام بالعملية, الرجاء إعادة المحاولة");
                                }
                            }
                        } catch(e) {console.log(e);}
                    }
                });
            });

            $("table td button.remove").on("click", function(e) {
                var id = $(this).closest("tr").data("id");
                if(id) {
                    if(confirm("هل أنت متأكد من عملية الحذف؟")) {
                        removeItem(id);
                    }
                }
            });

            $("#filtered-words-filter-select").on("change", function() {
                $(this).closest("form").submit();
            });

            $("table tr select.change_word_type").on("change", function() {
                $(this).closest("tr").attr("class", $(this).val());
                updateWordType($(this).closest("tr"));
            });

            function updateWordType(row) {
                if(!row) return;
                $.ajax({
                    url: window.location.pathname+"/edit",
                    type: "post",
                    data: {wordId: row.data("word-id"), filteredWordId: row.data("id"), type: row.find("select.change_word_type").val()},
                    success: function(data) {
                        try {
                            var res = JSON.parse(data);
                            if(res.error === false) {
                                row.addClass("succeeded");
                                setTimeout(function() {
                                    row.removeClass("succeeded");
                                }, 4000);

                                socket.emit("req", {
                                    type: "ADMIN_WORDS_RESET",
                                    data: { token: token }
                                }, function() {
                                    location.reload();
                                });

                            } else {
                                if(res.msg == "not permitted") {
                                    alert("لا تلمك الصلاحيات للقيام بهذه العملية");
                                } else {
                                    alert("حدث خطأ أثناء القيام بالعملية, الرجاء إعادة المحاولة");
                                }
                            }
                        } catch(e) {console.log(e);}
                    }
                });
            }

            function removeItem(id) {
                $.ajax({
                    url: window.location.pathname + "/remove",
                    type: "post",
                    data: { id: id },
                    success: function(res) {
                        var data = res;
                        if(data.error === false) {
                            $("table tr[data-id='" + id + "']").remove();
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