@extends('fadmin.master')

@section('content')
    <div id="main-content" class="gifts-and-faces-page">

        <div class="section" id="gifts-section">
            <input data-type="gift" type="file" id="add-gift-input" style="display: none; position: absolute; left: -99999px;"/>
            <p><label for="add-gift">الهدايا</label><button data-type="gift" id="add-gift" class="btn btn-success">أضف</button></p>
            <div id="gifts-container" class="img-container">
                @foreach($gifts as $gift)
                    <div id="item-{{$gift->id}}" class="item">
                        <img src="/uploads/gifts/{{$gift->icon}}" alt=""/>
                        <button data-type="gift" data-target="{{$gift->id}}" class="btn btn-danger">&times;</button>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="section" id="faces-section">
            <input data-type="face" type="file" id="add-face-input" style="display: none; position: absolute; left: -99999px;"/>
            <p><label for="add-face">الإبتسامات</label><button data-type="face" id="add-face" class="btn btn-success">أضف</button></p>
            <div id="faces-container" class="img-container" style="text-align: center">
                @foreach($faces as $face)
                    <div id="item-{{$face->id}}" class="item">
                        <img src="/uploads/faces/{{$face->icon}}" alt=""/>
                        <input type="text" value="{{$face->key}}" id="face-{{$face->id}}" style="width: 60px">
                        <button data-type="face-key" data-target="{{$face->id}}" class="btn btn-success fa fa-save"></button>
                        <button data-type="face" data-target="{{$face->id}}" class="btn btn-danger fa fa-times"></button>
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

            $("#add-gift, #add-face").on("click", function() {
                $("#add-"+$(this).data("type")+"-input").trigger("click");
            });

            $("#add-gift-input, #add-face-input").on("change", function() {
                var formData = new FormData();
                formData.append("icon", $(this).prop("files")[0]);
                formData.append("type", $(this).data("type"));

                if($(this).data("type") == "face") {
                    var key = prompt("الرجاء إدخال الإختصار");
                    if(key && key.trim()) {
                        formData.append("key", key);
                        uploadGiftFace(formData);
                    }
                } else {
                    uploadGiftFace(formData);
                }


            });

            removeGiftFaceListener();

        });

        function uploadGiftFace(formData) {
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

                            var type = response.type;

                            var html = '<div id="item-'+response.id+'" class="item"><img src="/uploads/'+type+'s/'+response.icon+'" alt=""/>'+
                                '<button data-type="'+type+'" data-target="'+response.id+'" class="btn btn-danger">&times;</button>'+
                                '</div>';

                            $("#"+type+"s-container").append(html);
                            removeGiftFaceListener();

                            if(type == 'face')  socket.emit("req", { type: "ADMIN_RESET_FACES", data: { token: token } });
                            else if(type == "gift") socket.emit("req", { type: "ADMIN_RESET_GIFTS", data: { token: token } });
                        } else {
                            if(response.msg == "error") {
                                alert("حدث خطأ أثناء القيام بالعملية, الرجاء إعادة المحاولة");
                            } else if(response.msg == "not permitted") {
                                alert("لا تلمك الصلاحيات للقيام بهذه العملية");
                            } else if(response.msg == "key is required") {
                                alert("الرجاء إعطاء إختصار للفيس");
                            } else if(response.msg == "key is used") {
                                alert("هذا الإختصار موجود, الرجاء إختيار إختصار آخر");
                            }
                        }
                    } catch(e) {}
                },
                error: function() { alert("حدث خطأ أثناء رفع الصورة"); }
            });
        }

        function removeGiftFace(btn) {
            $.ajax({
                url: window.location.pathname+'/remove',
                type: 'post',
                data: {id: btn.data("target"), type: btn.data("type")},
                success: function(res) {
                    try {
                        if(res.error === false) {
                            var type = res.type;
                            $("#"+btn.data("type")+"s-container #item-"+btn.data("target")).remove();
                            if(type == 'face')  socket.emit("req", { type: "ADMIN_RESET_FACES", data: { token: token } });
                            else if(type == "gift") socket.emit("req", { type: "ADMIN_RESET_GIFTS", data: { token: token } });
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

        function updateFaceKey(id) {
            if(id) {
                var key = $("#face-"+id).val().trim();
                if(key) {
                    $.ajax({
                        url: window.location.pathname+'/update-face-key',
                        type: 'post',
                        data: {id: id, key: key},
                        success: function(res) {
                            try {
                                if(res.error === false) {
                                    socket.emit("req", { type: "ADMIN_RESET_FACES", data: { token: token } });
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
            }
        }

        function removeGiftFaceListener() {
            $(".img-container .item button").on("click", function() {
                if($(this).data("type") == "face-key") {
                    updateFaceKey($(this).data("target"));
                } else {
                    removeGiftFace($(this));
                }
            });
        }

    </script>
@stop