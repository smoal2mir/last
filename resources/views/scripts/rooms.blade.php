<script type="text/javascript">
    $(document).ready(function() {

        var baseUrl = '/'+'{{env("ADMIN_HASH")}}'+'/admin/';
        var ADMIN_HASH = '{{env("ADMIN_HASH")}}';

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
        $("#rooms-toggle-search").on("click", function(e) {
            $("#rooms-search-form").toggle();
            $('#rooms-search-form input[type="search"]').focus();
        });
        $("#rooms-delete").on('click', function(e) {
            e.preventDefault();
            var ids = [];
            $.each($('#rooms-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                }
            });
            if(ids.length > 0) {
                var confirmation = confirm("هل أنت متأكد من حذف الغرف؟");
                if(confirmation) {
                    $.ajax({
                        url: baseUrl+"rooms/delete",
                        type: "POST",
                        data: {
                            action: "deleteRoom-{{env("ADMIN_HASH")}}",
                            ids: ids
                        },
                        success: function(result) {
                            try {
                                var json = JSON.parse(result);
                                if(!json.error) {
                                    for(var key in json.deleteds) {
                                        $('#rooms-section .section2 .table tbody tr[row_id="'+json.deleteds[key]+'"]').remove();
                                        socket.emit("req", {
                                            type: "ADMIN_ROOM_DELETE",
                                            data: { deleteds: json.deleteds, admin_hash: ADMIN_HASH }
                                        });
                                    }
                                }
                            } catch(e) {
                                //console.log(e);
                            }
                        }
                    });
                }
            } else {
                alert("الرجاء إختيار على الأقل غرفة واحدة؟");
            }
        });
        $("#rooms-section .section2 table tbody tr td *").on('change', function() {
            $("input[check_id='"+$(this).parent().parent().attr('row_id')+"']").prop('checked', true);
        });
        $("#rooms-save-changes").on('click', function(e) {
            e.preventDefault();
            var ids         = [];
            var names       = [];
            var passwords   = [];
            var welcomes    = [];
            var max         = [];
            var fors        = [];
            var createds    = [];

            $.each($('#rooms-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                    names.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='name']").val());
                    passwords.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='password']").val());
                    welcomes.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='welcome']").val());
                    max.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='max']").val());
                    fors.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td select").val());
                    createds.push($("#rooms-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td input[name='created_at']").val());
                }
            });

            if(ids.length > 0) {
                $.ajax({
                    url: baseUrl+'rooms/save',
                    type: 'POST',
                    data: {
                        action: 'saveChanges-{{env("ADMIN_HASH")}}',
                        ids: ids,
                        names: names,
                        passwords: passwords,
                        welcomes: welcomes,
                        max: max,
                        fors: fors,
                        createds: createds
                    },
                    success: function(result) {
                        try {
                            var json = JSON.parse(result);
                            if(!json.error) {

                                socket.emit("req", {
                                    type: "ADMIN_ROOM_UPDATE",
                                    data: { rooms: json.rooms, admin_hash: ADMIN_HASH }
                                });

                                location.reload();
                            }
                        } catch(e) {
                            //console.log(e);
                        }
                    }
                });
            } else {
                alert("الرجاء إختيار على الأقل غرفة واحدة");
            }

        });

    });
</script>