<script type="text/javascript">
    $(document).ready(function() {
        var baseUrl = '/'+'{{env("ADMIN_HASH")}}'+'/admin/';

        var host = '{{trim(env("APP_HTTPS_HOST", env("APP_HTTP_HOST")), '/')}}';
        var ADMIN_HASH = '{{env("ADMIN_HASH")}}';
        var socket = io(host+"{{env("APP_PORT")}}", {query: "ADMIN_HASH="+ADMIN_HASH});

        $(".navbar-toggle").on("click", function(e) {
            e.preventDefault();
            $(".sidebar").toggleClass('displayed');
        });

        setTimeout(function() {
            $(".alert").hide(500, function() {
                $(this).remove();
            });
        }, 5000);

        /**
         * Handling Table Navigation Events ****************************************************************
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

        $("#self-messages-toggle-search").on("click", function(e) {
            e.preventDefault();
            $("#self-messages-search-form").toggle();
            $('#self-messages-search-form input[type="search"]').focus();
        });

        $("#self-messages-search-form").on("submit", function(e) {
            e.preventDefault();
            var status = {running: "مشغل", paused: "موقف"};
            var keyword = $('#self-messages-search-form input[type="search"]').val();
            if(keyword) {
                var action = 'searchSelfMessags-{{env("ADMIN_HASH")}}';
            } else {
                var action = 'allSelfMessags-{{env("ADMIN_HASH")}}';
            }
            $.ajax({
                url: baseUrl+'self-messages/search',
                type: 'POST',
                data: {
                    action: action,
                    keyword: keyword
                },
                success: function(result) {
                    try {
                        var json = JSON.parse(result);
                        if(!json.error) {
                            var tableBody = $("#self-messages-section .section2 table tbody");
                            tableBody.html("");
                            for(key in json.messages) {
                                var msg = json.messages[key];
                                var tableRow = '<tr row_id="'+msg.id+'">' +
                                        '<td><input class="table-check-row" type="checkbox" check_id="'+msg.id+'"></td>' +
                                        '<td width="45%"><input style="width: 100%;" name="msg" type="text" value="'+msg.msg+'" /></td>' +
                                        '<td width="20%"><input style="width: 100%;" name="interval" type="number" value="'+msg.interval+'" /></td>';
                                tableRow += '<td width="15%"><select class="status-'+msg.status+'">';
                                for(var key in status) {
                                    if(msg.status == key) {
                                        tableRow += '<option selected="selected" value="'+key+'">'+status[key]+'</option>';
                                    } else {
                                        tableRow += '<option value="'+key+'">'+status[key]+'</option>';
                                    }
                                }
                                tableRow += '</select></td>';
                                tableRow += '<td width="20%">'+msg.created_at+'</td></tr>';

                                tableBody.append(tableRow);
                            }
                        }
                    } catch (e) {
                        //console.log(e);
                    }
                }
            });
        });

        $("#self-messages-delete").on('click', function(e) {
            e.preventDefault();
            var ids = [];
            $.each($('#self-messages-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                }
            });
            if(ids.length > 0) {
                var confirmation = confirm("هل أن متأكد من حذف هذه الرسائل؟");
                if(confirmation) {
                    $.ajax({
                        url: baseUrl+"self-messages/delete",
                        type: "POST",
                        data: {
                            action: "deleteSelfMessages-{{env("ADMIN_HASH")}}",
                            ids: ids
                        },
                        success: function(result) {
                            try {
                                var json = JSON.parse(result);
                                if(!json.error) {
                                    for(key in json.deleteds) {
                                        $('#self-messages-section .section2 .table tbody tr[row_id="'+json.deleteds[key]+'"]').remove();
                                        socket.emit("deleteSelfMessage", json.deleteds, ADMIN_HASH);
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

        $("#self-messages-section .section2 table tbody tr td *").on('change', function() {
            $("input[check_id='"+$(this).parent().parent().attr('row_id')+"']").prop('checked', true);
            if($(this).val() == "running") {
                $(this).addClass("status-running").removeClass("status-paused");
            } else if($(this).val() == "paused") {
                $(this).addClass("status-paused").removeClass("status-running");
            }
        });

        $("#self-messages-save-changes").on('click', function(e) {
            e.preventDefault();
            var ids         = [];
            var messages    = [];
            var intervals   = [];
            var status      = [];

            $.each($('#self-messages-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                    messages.push($("#self-messages-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='msg']").val());
                    intervals.push($("#self-messages-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='interval']").val());
                    status.push($("#self-messages-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td select").val());
                }
            });

            if(ids.length > 0) {
                $.ajax({
                    url: baseUrl+'self-messages/save',
                    type: 'POST',
                    data: {
                        action: 'saveChanges-{{env("ADMIN_HASH")}}',
                        ids: ids,
                        messages: messages,
                        intervals: intervals,
                        status: status
                    },
                    success: function(result) {
                        try {
                            var json = JSON.parse(result);
                            if(json.error === false) {
                                socket.emit("updateSelfMessage", json.messages, ADMIN_HASH);
                                location.reload();
                            }
                        } catch(e) {
                            //console.log(e);
                        }
                    }
                });
            } else {
                alert("الرجاء إختيار على الأقل رسالة واحدة");
            }

        });

        /**
         * ***************************************************************************************************
         */

    });
</script>