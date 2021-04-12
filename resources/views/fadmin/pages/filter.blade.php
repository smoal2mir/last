@extends('fadmin.master')

@section('content')
    <div id="main-content" class="filter-page">
        <div class="row">
            <form id="filter-add" method="post" action="{{url("/fadmn/filter/$token/add")}}">
                @if(Session::has("errors"))
                    <div class="alert alert-danger">{{$errors->first()}}</div>
                @endif

                @if(Session::has("success_msg"))
                    <div class="alert alert-success">{{Session::get("success_msg")}}</div>
                @endif
                {!! csrf_field() !!}
                <input type="input" name="word" value="{{old("word")}}" placeholder="إضافة كلمة إلى..."/><br />
                <input type="hidden" name="type"/>
                <button data-type="controlled" class="btn btn-info">المراقبة</button>
                <button data-type="denied" class="btn btn-danger">الممنوعة</button>
            </form>


            <form id="filter-search">
                <input type="saerch" name="search" value="{{$keyword}}" placeholder="إبحث عن"/>
                <button class="btn btn-info">إبحث</button>
            </form>
            <form id="filter-filter">
                <select name="filter" id="filter-filter-select">
                    <option value="all">كل الكلمات</option>
                    @foreach($wordsByType as $key => $vaule)
                        <?php $selected = $filter == $key ? "selected" : ""; ?>
                        <option {{$selected}} value="{{$key}}">{{$vaule}}</option>
                    @endforeach
                </select>
                <label dir="rtl" for="filter-filter-select">فلتر عبر: </label>
            </form>
        </div>

        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>الكلمة</th><th>التصنيف</th><th>حذف</th>
                </tr>
                </thead>
                <tbody>
                @foreach($words as $word)
                    <tr data-id="{{$word->id}}" class="{{$word->type}}">
                        <td>{{$word->word ?: "/"}}</td>
                        <td>
                            <select class="change_word_type">
                                @foreach($wordsByType as $key => $vaule)
                                    <?php $selected = $word->type == $key ? "selected" : ""; ?>
                                    <option {{$selected}} value="{{$key}}">{{$vaule}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="max-width: 30px; text-align: center;"><button class="remove"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{url('js/socket.io.js')}}"></script>
    @if(Session::has("success_msg") && Session::get("success_msg") == "تم إضافة الكلمة بنجاح")
        <script>
            var addedWord = {
                id: '{{json_decode(Session::get("word"))->id}}',
                word: '{{json_decode(Session::get("word"))->word}}',
                type: '{{json_decode(Session::get("word"))->type}}',
                created_at: '{{json_decode(Session::get("word"))->created_at}}',
                updated_at: '{{json_decode(Session::get("word"))->updated_at}}',
            };
        </script>
    @else
        <script>var addedWord = null;</script>
    @endif
    <script>
        $(document).ready(function() {

            var token = '{{$token}}';
            var socket = io(socketHost, {transports: ['websocket', 'xhr-polling', 'polling', 'htmlfile', 'flashsocket'],
                secure: true,
                rejectUnauthorized: false,
                query: "token="+token}
            );

            if(addedWord) {
                socket.emit("req", {
                    type: "ADMIN_WORDS_ADD",
                    data: { word: addedWord, token: token }
                });
            }

            setTimeout(function() {
                $(".alert").remove();
            }, 5000);

            $("table td button.remove").on("click", function() {
                var id = $(this).closest("tr").data("id");
                if(id && confirm("هل أنت متأكد من إزالة الكلمة؟")) {
                    rmoveWord(id);
                }
            });

            $("#filter-filter-select").on("change", function() {
                $(this).closest("form").submit();
            });

            $("table tr select.change_word_type").on("change", function() {
                $(this).closest("tr").attr("class", $(this).val());
                updateWordType($(this).closest("tr"));
            });

            $("#filter-add button").on("click", function(e) {
                e.preventDefault();
                var form = $(this).closest("form");
                form.find("input[name='type']").val($(this).data("type"));
                form.submit();
            });

            function updateWordType(row) {
                if(!row) return;
                $.ajax({
                    url: window.location.pathname+"/edit",
                    type: "post",
                    data: {wordId: row.data("id"), type: row.find("select.change_word_type").val()},
                    success: function(data) {
                        try {

                            if(data.error === false) {
                                row.addClass("succeeded");

                                setTimeout(function() {
                                    row.removeClass("succeeded");
                                }, 4000);

                                socket.emit("req", {
                                    type: "ADMIN_WORDS_UPDATE_TYPE",
                                    data: { word: data.word, token: token }
                                });

                            }

                        } catch(e) {console.log(e);}
                    },
                    error: function(res) {
                        if(res.msg == "not permitted") {
                            alert("لا تلمك الصلاحيات للقيام بهذه العملية");
                        } else {
                            alert("حدث خطأ أثناء القيام بالعملية, الرجاء إعادة المحاولة");
                        }
                    }
                });
            }

            function rmoveWord(id) {
                $.ajax({
                    url: window.location.pathname+'/remove',
                    type: "post",
                    data: { id: id },
                    success: function(res) {
                        var data = JSON.parse(res);
                        if(data.error === false) {
                            $("table tr[data-id='" + id + "']").remove();
                            socket.emit("req", {
                                type: "ADMIN_WORDS_REMOVE",
                                data: { id: id, token: token }
                            });
                        } else {
                            if(data.msg == "no permission") {
                                alert("لا تملك الصلاحيات للقيام بهذه العمية");
                            } else {
                                alert("حدث خطأ أثناء العملية");
                            }
                        }
                    }
                });
            }

        });
    </script>
@endsection