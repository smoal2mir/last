<script type="text/javascript">
    $(document).ready(function() {
        var baseUrl = '/'+'{{env("ADMIN_HASH")}}'+'/admin/';
        var ADMIN_HASH = '{{env("ADMIN_HASH")}}';

        socket.on("connect_error", function() {
            $("#start-chat").removeAttr("disabled");
            $("#restart-chat").attr("disabled", true);
            $("#stop-chat").attr("disabled", true);
        });

        socket.on("connect", function() {
            $("#start-chat").attr("disabled", true);
            $("#restart-chat").removeAttr("disabled");
            $("#stop-chat").removeAttr("disabled");
        });

        $(".navbar-toggle").on("click", function(e) {
            e.preventDefault();
            $(".sidebar").toggleClass('displayed');
        });

        $("#start-chat").on("click", function(e) {
            if(confirm("هل أنت متأكد من تشغيل الشات؟")) {
                e.target.setAttribute("disabled", true);
                $.ajax({
                    url: baseUrl+"start-chat",
                    type: "post",
                    data: {},
                    success: function(result) {
                        e.target.removeAttribute("disabled");
                    }
                });
            }
        });

        $("#restart-chat").on("click", function(e) {
            if(confirm("هل أنت متأكد من إعادة التشفيل؟")) {
                e.target.setAttribute("disabled", true);
                $.ajax({
                    url: baseUrl+"restart-chat",
                    type: "post",
                    data: {},
                    success: function(result) {
                        e.target.removeAttribute("disabled");
                    }
                });
            }
        });

        $("#stop-chat").on("click", function(e) {
            if(confirm("هل أنت متأكد من إيقاف التشفيل؟")) {
                e.target.setAttribute("disabled", true);
                $.ajax({
                    url: baseUrl+"stop-chat",
                    type: "post",
                    data: {},
                    success: function(result) {
                        e.target.removeAttribute("disabled");
                    }
                });
            }
        });

        $("#clear-wall").on("click", function(e) {
            e.preventDefault();
            if(confirm("هل أنت متأكد من تفريغ الحائط؟")) {
                $("#clear-wall").attr("disabled", true);
                $.ajax({
                    url: baseUrl+"clearWall",
                    type: "post",
                    data: {
                        action: "clearWall-"+"{{env("ADMIN_HASH")}}"
                    },
                    success: function(result) {
                        try {
                            var json = JSON.parse(result);
                            if(json.error === false) {
                                socket.emit("req", {
                                    type: "ADMIN_WALL_CLEAR",
                                    data: { admin_hash: ADMIN_HASH }
                                });
                                alert("تم تفريغ الحائط بنجاج");
                            } else {
                                $("#clear-wall").attr("disabled", false);
                                alert("حدث خطأ أثناء تفريغ الحائط");
                            }
                        } catch(e) {}
                    }
                });
            }
        });

    });
</script>