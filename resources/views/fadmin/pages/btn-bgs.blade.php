@extends('fadmin.master')

@section('content')
    <div id="main-content" class="btn-bgs-page">

        <div class="section" id="btn-bgs-section">
            <input data-type="btn-bg" type="file" id="add-btn-bg-input" style="display: none; position: absolute; left: -99999px;"/>
            <p><label for="add-bg">خلفيات الأزرار</label><button data-type="btn-bg" id="add-bg" class="btn btn-success">أضف</button></p>
            <div id="btn-bgs-container" class="img-container">
                @foreach($bgs as $bg)
                    <div id="item-{{$bg->id}}" class="item">
                        <img src="/uploads/btn-bgs/{{$bg->icon}}" alt=""/>
                        <button data-type="gift" data-target="{{$bg->id}}" class="btn btn-danger">&times;</button>
                    </div>
                @endforeach
            </div>
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

        setTimeout(function() {
            $(".alert").remove();
        }, 5000);

        $(document).ready(function() {

            $("#add-bg").on("click", function() {
                $("#add-btn-bg-input").trigger("click");
            });

            $("#add-btn-bg-input").on("change", function() {
                var formData = new FormData();
                formData.append("icon", $(this).prop("files")[0]);
                uploadBg(formData);
            });

            removeBgListener();

        });

        function uploadBg(formData) {
            $.ajax({
                xhr: function() {
                    return new window.XMLHttpRequest();
                },
                url: window.location.pathname+'/add',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    try {
                        if(response.error === false) {

                            var html = '<div id="item-'+response.id+'" class="item"><img src="/uploads/btn-bgs/'+response.icon+'" alt=""/>'+
                                '<button data-type="btn-bgs" data-target="'+response.id+'" class="btn btn-danger">&times;</button>'+
                                '</div>';

                            $("#btn-bgs-container").append(html);
                            removeBgListener();

                            socket.emit("reset-btn-bgs", token);

                        } else {
                            if(response.msg == "error") {
                                alert("حدث خطأ أثناء القيام بالعملية, الرجاء إعادة المحاولة");
                            } else if(response.msg == "not permitted") {
                                alert("لا تلمك الصلاحيات للقيام بهذه العملية");
                            }
                        }
                    } catch(e) {}
                },
                error: function() { alert("حدث خطأ أثناء رفع الصورة"); }
            });
        }

        function removeBg(btn) {
            $.ajax({
                url: window.location.pathname+'/remove',
                type: 'post',
                data: {id: btn.data("target")},
                success: function(res) {
                    try {
                        if(res.error === false) {
                            $("#btn-bgs-container #item-"+btn.data("target")).remove();
                            socket.emit("reset-btn-bgs", token);
                        } else {
                            if(res.msg == "not permitted") {
                                alert("لا تلمك الصلاحيات للقيام بهذه العملية");
                            } else {
                                alert("حدث خطأ أثناء القيام بالعملية, الرجاء إعادة المحاولة");
                            }
                        }
                    } catch(e) {}
                },
                error: function() { alert("حدث خطأ أثناء رفع الصورة"); }
            });
        }

        function removeBgListener() {
            $(".img-container .item button").on("click", function() {
                removeBg($(this));
            });
        }

    </script>
@stop