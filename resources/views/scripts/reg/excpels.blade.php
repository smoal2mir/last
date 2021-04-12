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
        $("#reg-excpels-toggle-search").on("click", function(e) {
            $("#reg-excpels-search-form").toggle();
            $('#reg-excpels-search-form input[type="search"]').focus();
        });
        $("#reg-excpels-delete").on('click', function(e) {
            e.preventDefault();
            var ids = [];
            $.each($('#reg-excpels-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                }
            });
            if(ids.length > 0) {
                var confirmation = confirm("هل أنت متأكد من الحذف؟");
                if(confirmation) {
                    $.ajax({
                        url: baseUrl+"reg-excpels/delete",
                        type: "POST",
                        data: {
                            action: "deleteRegExcpels-{{env("ADMIN_HASH")}}",
                            ids: ids
                        },
                        success: function(result) {
                            try {
                                var json = JSON.parse(result);
                                if(!json.error) {
                                    for(key in json.deleteds) {
                                        $('#reg-excpels-section .section2 .table tbody tr[row_id="'+json.deleteds[key]+'"]').remove();
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
        $("#reg-excpels-empty").on('click', function(e) {
            e.preventDefault();
            var confirmation = confirm("هل أنت متأكد من تفريغ سجل الطرد؟");
            if(confirmation) {
                $.ajax({
                    url: baseUrl+"reg-excpels/empty",
                    type: "POST",
                    data: {
                        action: "emptyRegExcpels-{{env("ADMIN_HASH")}}"
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