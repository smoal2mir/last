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
        $("#reg-bans-toggle-search").on("click", function(e) {
            $("#reg-bans-search-form").toggle();
            $('#reg-bans-search-form input[type="search"]').focus();
        });
        $("#reg-bans-delete").on('click', function(e) {
            e.preventDefault();
            var ids = [];
            $.each($('#reg-bans-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                }
            });
            if(ids.length > 0) {
                var confirmation = confirm("هل أنت متأكد من الحذف؟");
                if(confirmation) {
                    $.ajax({
                        url: baseUrl+"reg-bans/delete",
                        type: "POST",
                        data: {
                            action: "deleteRegBans-{{env("ADMIN_HASH")}}",
                            ids: ids
                        },
                        success: function(result) {
                            try {
                                var json = JSON.parse(result);
                                if(!json.error) {
                                    for(var key in json.deleteds) {
                                        $('#reg-bans-section .section2 .table tbody tr[row_id="'+json.deleteds[key]+'"]').remove();
                                    }
                                }
                            } catch(e) {
                                //console.log(e);
                            }
                        }
                    });
                }
            } else {
                alert("الرجاء أختيار على الأقل عنصر واحد");
            }
        });
        $("#reg-bans-empty").on('click', function(e) {
            e.preventDefault();
            var confirmation = confirm("هل أنت متأكد من تفريغ سجل الحظر؟");
            if(confirmation) {
                $.ajax({
                    url: baseUrl+"reg-bans/empty",
                    type: "POST",
                    data: {
                        action: "emptyRegBans-{{env("ADMIN_HASH")}}"
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