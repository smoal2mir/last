<script type="text/javascript">
    $(document).ready(function() {
        var baseUrl = '/{{env("ADMIN_HASH")}}'+'/admin/';

        $(".navbar-toggle").on("click", function(e) {
            e.preventDefault();
            $(".sidebar").toggleClass('displayed');
        });

        /**
         * Handling Table Navigation Events
         */
        $("#select-all").on("click", function(e) {
            e.preventDefault();
            $(this).hide();
            $("#unselect-all").show();
            $(".table-check-row, #checkbox-select-all").prop("checked", true);
        });
        $("#unselect-all").on("click", function(e) {
            e.preventDefault();
            $(this).hide();
            $("#select-all").show();
            $(".table-check-row, #checkbox-select-all").prop("checked", false);
        });
        $("#checkbox-select-all").on("click", function() {
            $(".table-check-row").prop("checked", $(this).prop("checked"));
            $("#select-all, #unselect-all").toggle();
        });
        $("#reg-private-toggle-search").on("click", function(e) {
            e.preventDefault();
            $("#reg-private-search-form").toggle();
            $('#reg-private-search-form input[type="search"]').focus();
        });
        $("#reg-private-search-form").on("submit", function(e) {
            e.preventDefault();
            var keyword = $('#reg-private-search-form input[type="search"]').val();
            if(!keyword) return;
            $.ajax({
                url: baseUrl+'reg-private/search',
                type: 'POST',
                data: {
                    action: 'searchPrivateMsg-{{env("ADMIN_HASH")}}',
                    keyword: keyword
                },
                success: function(result) {
                    try {
                        var json = JSON.parse(result);
                        if(json.error === false) {
                            var tableBody = $("#reg-private-section .section2 table tbody");
                            tableBody.html("");
                            var types = {"text": "رسالة نصية", "photo": "صورة", "sound": "مقطع صوتي", "video": "مقطع مرئي"};
                            for(key in json.messages) {
                                var msg = json.messages[key];
                                if(msg.msgType != 'text') {
                                    var text = '<a target="_blank" href="{{env("APP_HTTPS_HOST", env("APP_HTTP_HOST"))}}'+msg.msg+'">إضغط لمشاهدة المحتوى</a>';
                                } else {
                                    var text = msg.msg;
                                }
                                var tableRow = '<tr row_id="'+msg.id+'">' +
                                        '<td><input class="table-check-row" type="checkbox" check_id="'+msg.id+'"></td>' +
                                        '<td>'+msg.byRealName+'</td>' +
                                        '<td>'+msg.byNickName+'</td>' +
                                        '<td>'+msg.toRealName+'</td>' +
                                        '<td>'+msg.toNickName+'</td>' +
                                        '<td>'+text+'</td>' +
                                        '<td>'+types[msg.msgType]+'</td>' +
                                        '<td>'+msg.created_at+'</td>';

                                tableBody.append(tableRow);
                            }
                        } else {
                            //console.log('Request failed');
                        }
                    } catch (e) {
                        //console.log(e);
                    }
                }
            });
        });
        $("#reg-private-delete").on('click', function(e) {
            e.preventDefault();
            var ids = [];
            $.each($('#reg-private-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                }
            });
            if(ids.length > 0) {
                var confirmationMsg = (ids.length > 2) ? "هذه الرسالئل؟" : ids.length == 2 ? "هتين الرسالتين؟" : "هذه الرسالة؟" ;
                var confirmation = confirm("هل أنت متأكد من حذف "+confirmationMsg);
                if(confirmation) {
                    $.ajax({
                        url: baseUrl+"reg-private/delete",
                        type: "POST",
                        data: {
                            action: "deleteRegPrivate-{{env("ADMIN_HASH")}}",
                            ids: ids
                        },
                        success: function(result) {
                            try {
                                var json = JSON.parse(result);
                                if(!json.error) {
                                    for(key in json.deleteds) {
                                        $('#reg-private-section .section2 .table tbody tr[row_id="'+json.deleteds[key]+'"]').remove();
                                    }
                                }
                            } catch(e) {
                                //console.log(e);
                            }
                        }
                    });
                }
            } else {
                alert("الرجاء إختيار على الأقل رسالة واحدة");
            }
        });
        $("#reg-private-empty").on('click', function(e) {
            e.preventDefault();
            var confirmation = confirm("هل أنت متأكد من حذف جميع الرسائل؟");
            if(confirmation) {
                $.ajax({
                    url: baseUrl+"reg-private/empty",
                    type: "POST",
                    data: {
                        action: "emptyRegPrivate-{{env("ADMIN_HASH")}}"
                    },
                    success: function(result) {
                        try {
                            var json = JSON.parse(result);
                            if(json.error === false) {
                                window.location.reload();
                            }
                        } catch(e) {
                            //console.log(e);
                        }
                    }
                });
            }

        });
        $("#reg-private-empty-attach").on('click', function(e) {
            e.preventDefault();
            var confirmation = confirm("هل أنت متأكد من حذف جميع المرفقات؟");
            if(confirmation) {
                $.ajax({
                    url: baseUrl+"reg-private/empty-attach",
                    type: "POST",
                    data: {
                        action: "emptyRegPrivateAttach-{{env("ADMIN_HASH")}}"
                    },
                    success: function(result) {
                        try {
                            var json = JSON.parse(result);
                            if(json.error === false) {
                                window.location.reload();
                            }
                        } catch(e) {
                            //console.log(e);
                        }
                    }
                });
            }

        });

        /**
         * ***************************************************************************************************
         */

    });
</script>