<script type="text/javascript">
    $(document).ready(function() {
        var baseUrl = '/'+'{{env("ADMIN_HASH")}}'+'/admin/';
        var ADMIN_HASH = '{{env("ADMIN_HASH")}}';

        var socket = io(socketHost, {transports: ['websocket', 'xhr-polling', 'polling', 'htmlfile', 'flashsocket'],
            secure: true,
            rejectUnauthorized: false,
            query: "admin_hash="+'{{env('ADMIN_HASH')}}'}
        );

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
        $("#banneds-toggle-search").on("click", function(e) {
            $("#banneds-search-form").toggle();
            $('#banneds-search-form input[type="search"]').focus();
        });
        $("#banneds-unban").on('click', function(e) {
            e.preventDefault();
            var ids = [];
            $.each($('#banneds-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                }
            });
            if(ids.length > 0) {
                var confirmation = confirm("هل أنت متأكد من إزالة الحظر؟");
                if(confirmation) {
                    $.ajax({
                        url: baseUrl+"banneds/unban",
                        type: "POST",
                        data: {
                            action: "unbanUser-{{env("ADMIN_HASH")}}",
                            ids: ids
                        },
                        success: function(result) {
                            try {
                                var json = JSON.parse(result);
                                if(!json.error) {
                                    for(var key in json.unbaneds) {
                                        $('#banneds-section .section2 .table tbody tr[row_id="'+json.unbaneds[key]+'"]').remove();

                                        for(var k in json.unbanedIps) {
                                            if(!json.unbanedIps[k]) continue;
                                            socket.emit("req", {
                                                type: "ADMIN_BANNEDS_CLEAR",
                                                data: { ip: json.unbanedIps[k], admin_hash: ADMIN_HASH }
                                            });
                                        }

                                        for(var k in json.unbanedsFps) {
                                            if(!json.unbanedsFps[k]) continue;
                                            socket.emit("req", {
                                                type: "ADMIN_BANNEDS_CLEAR",
                                                data: { device: json.unbanedsFps[k], admin_hash: ADMIN_HASH }
                                            });
                                        }

                                    }
                                }
                            } catch(e) {
                                console.log(e);
                            }
                        }
                    });
                }
            } else {
                alert("الرجاء إختيار على الأقل مستخدم واحد");
            }
        });
        $("#banneds-change-finish").on("click", function(e) {
            e.preventDefault();

        });
        $("#banneds-edit-finish").on('click', function(e) {
            e.preventDefault();
            $("#banneds-section .popup").addClass('displayed');
        });
        $("#banneds-change-finish form input[type='submit']").on("click", function(e) {
            e.preventDefault();
            var ids = [];
            $.each($('#banneds-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                }
            });
            $.ajax({
                url: baseUrl+"banneds/change",
                type: "POST",
                data: {
                    action: "changeBanned-{{env("ADMIN_HASH")}}",
                    finishes_at: $("#banneds-change-finish form input[name='finishes_at']").val(),
                    ids: ids
                },
                success: function(result) {
                    try {
                        var json = JSON.parse(result);
                        if(!json.error) {
                            $("#banneds-section .popup").removeClass('displayed');
                        }
                    } catch(e) {
                        //console.log(e);
                    }
                }
            });
        });
        $("#banneds-change-finish form button").on('click', function(e) {
            e.preventDefault();
            $("#banneds-section .popup").removeClass('displayed');
        });

        /**
         * ***************************************************************************************************
         */

    });
</script>