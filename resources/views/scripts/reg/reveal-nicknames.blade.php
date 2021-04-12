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
        $("#reveal-nicknames-toggle-search").on("click", function(e) {
            $("#reveal-nicknames-search-form").toggle();
            $('#reveal-nicknames-search-form input[type="search"]').focus();
        });
        $("#reveal-nicknames-delete").on('click', function(e) {
            e.preventDefault();
            var ids = [];
            $.each($('#reveal-nicknames-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                }
            });
            if(ids.length > 0) {
                var confirmation = confirm("هل أن متأكد من عملية الحذف؟");
                if(confirmation) {
                    $.ajax({
                        url: baseUrl+"reveal-nicknames/delete",
                        type: "POST",
                        data: {
                            action: "deleteRevealNicknames-{{env("ADMIN_HASH")}}",
                            ids: ids
                        },
                        success: function(result) {
                            try {
                                var json = JSON.parse(result);
                                if(!json.error) {
                                    for(key in json.deleteds) {
                                        $('#reveal-nicknames-section .section2 .table tbody tr[row_id="'+json.deleteds[key]+'"]').remove();
                                    }
                                }
                            } catch(e) {
                                //console.log(e);
                            }
                        }
                    });
                }
            } else {
                alert("الرجاء إختيار على الأقل عنصر واحد");
            }
        });
        $("#reveal-nicknames-empty").on('click', function(e) {
            e.preventDefault();
            var confirmation = confirm("هل أنت متأكد من تفريغ سجل كشف النكات؟");
            if(confirmation) {
                $.ajax({
                    url: baseUrl+"reveal-nicknames/empty",
                    type: "POST",
                    data: {
                        action: "emptyRevealNicknames-{{env("ADMIN_HASH")}}"
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