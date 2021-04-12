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
        $("#users-toggle-search").on("click", function(e) {
            e.preventDefault();
            $("#users-search-form").toggle();
            $('#users-search-form input[type="search"]').focus();
        });
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
        $("#users-ban-form form input[type='submit']").on("click", function(e) {
            e.preventDefault();
            var ids         = [];
            var countries   = [];
            $.each($('#users-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                    countries.push($(this).find("td.user-country").attr("country"));
                }
            });
            if(ids.length > 0) {
                $.ajax({
                    url: baseUrl + "users/ban",
                    type: "POST",
                    data: {
                        action: "banUser-{{env("ADMIN_HASH")}}",
                        finishes_at: $("#users-ban-form form input[name='finishes_at']").val(),
                        cause: $("#users-ban-form form input[name='cause']").val(),
                        ids: ids,
                        countries: countries
                    },
                    success: function (result) {
                        try {
                            var json = JSON.parse(result);
                            if (!json.error) {
                                $("#users-section .popup").removeClass('displayed');
                                socket.emit("logOutBanneds", json.ips, ADMIN_HASH);
                            }
                        } catch (e) {
                            //console.log(e);
                        }
                    }
                });
            } else {
                alert("الرجاء إختيار على الأقل مستخدم واحد");
            }
        });
        $("#users-ban-form form button").on('click', function(e) {
            e.preventDefault();
            $("#users-section .popup").removeClass('displayed');
        });
        $("#users-ban").on("click", function(e) {
            e.preventDefault();
            var ids         = [];
            $.each($('#users-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                }
            });
            if(ids.length > 0) {
                $("#users-section .popup").addClass('displayed');
            } else {
                alert("الرجاء إختيار على الأقل مستخدم واحد");
            }
        });
        $("#users-delete").on('click', function(e) {
            e.preventDefault();
            var ids = [];
            $.each($('#users-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                }
            });
            if(ids.length > 0) {
                var confirmation = confirm("هل أنت متأكد من حذف المستخدمين؟");
                if(confirmation) {
                    $.ajax({
                        url: baseUrl+"users/delete",
                        type: "POST",
                        data: {
                            action: "deleteUser-{{env("ADMIN_HASH")}}",
                            ids: ids
                        },
                        success: function(result) {
                            try {
                                var json = JSON.parse(result);
                                if(!json.error) {
                                    for(key in json.deleteds) {
                                        $('#users-section .section2 .table tbody tr[row_id="'+json.deleteds[key]+'"]').remove();
                                        socket.emit("logOutDeletedUsers", json.deleteds, ADMIN_HASH);
                                    }
                                }
                            } catch(e) {
                                //console.log(e);
                            }
                        }
                    });
                }
            } else {
                alert("الرجاء إختيار على الأقل مستخدم واحد");
            }
        });
        $("#users-section .section2 table tbody tr td select").on('change', function() {
            $("input[check_id='"+$(this).parent().parent().attr('row_id')+"']").prop('checked', true);
        });
        $("#users-save-changes").on('click', function(e) {
            e.preventDefault();
            var ids    = [];
            var roles = [];
            $.each($('#users-section .section2 .table tbody tr'), function(index, value) {
                var checkbox = $(this).find("td input[type='checkbox']");
                if(checkbox.prop("checked")){
                    ids.push(checkbox.attr("check_id"));
                    roles.push($("#users-section .section2 table tbody tr[row_id='"+checkbox.attr("check_id")+"'] td select").val());
                }
            });

            if(ids.length > 0) {
                $.ajax({
                    url: baseUrl+'users/save',
                    type: 'POST',
                    data: {
                        action: 'saveChanges-{{env("ADMIN_HASH")}}',
                        ids: ids,
                        roles: roles
                    },
                    success: function(result) {
                        try {
                            var json = JSON.parse(result);
                            if(!json.error) {
                                socket.emit("rolesUpdated", json.ids, ADMIN_HASH);
                                location.reload();
                            }
                        } catch(e) {
                            //console.log(e);
                        }
                    }
                });
            } else {
                alert("الرجاء إختيار على الأقل مستخدم واحد");
            }

        });
        $("#filter-role").on("change", function() {
            location.search = "?role=" + $(this).val();
        });

        /**
         * ***************************************************************************************************
         */

    });
</script>