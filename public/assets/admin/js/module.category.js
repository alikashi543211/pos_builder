$(document).ready(function() {


    // Add New User
    $(document).on("click", ".btn-add", function (e) {
        $(".modal-body").html("");
        $(".drawer-title").html("Add New Module Category");
        jQuery.getJSON(admin_url + "/acl/module-categories/add/", function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_drawer_chat");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();
                }
            }
        });
    });

    /** edit view */
    $(document).on("click", ".btn-edit", function (e) {
        $(".modal-body").html("");
        $(".drawer-title").html("Edit Module Category");
        var id = $(this).data("id");
        console.log('id:', id)
        jQuery.getJSON(
            admin_url + "/acl/module-categories/edit/" +id,
            function (response) {
                if (response.responseCode == 1) {
                    $(".drawer-body").html(response.html);
                    const drawerElement = document.querySelector("#kt_drawer_chat");
                    if (drawerElement) {
                        KTDrawer.getInstance(drawerElement).show();
                    }
                }
            }
        );
    });

     //Form Handler
     $(document).on("submit", "#fform", function (e) {
        console.log("e:", e);
        e.preventDefault();
        $("#msg_box").html("");
        $(".btn-save").attr("disabled", "disabled");
        var act_val = $("#act").val();
        var eid = $("#eid").val();
        var params = act_val;

        if (eid != "") {
            params = act_val + "/" + eid;
        }

        // loadSpinner();
        $.ajax({
            url: admin_url + "/acl/module-categories/" + params,
            type: "POST",
            data: new FormData(this),

            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                try {
                    var obj = JSON.parse(data);

                    if (obj.responseCode == 1) {
                        $(".modal-body").html("");
                        const drawerElement =
                        document.querySelector("#kt_drawer_chat");
                        if (drawerElement) {
                            KTDrawer.getInstance(drawerElement).hide();
                        }
                        // hideSpinner();
                        Swal.fire("Success", obj.msg, "success");
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                        return;
                    } else {
                        // hideSpinner();
                        var errMsg = "";
                        $(".btn_submit").removeAttr("disabled");
                        if (typeof obj.msg === "object") {
                            $.each(obj.msg, function (index, value) {
                                errMsg = errMsg + value + "\r\n";
                            });
                        } else {
                            errMsg = obj.msg;
                        }
                        Swal.fire("Error", errMsg, "error");
                        return;
                    }
                } catch (err) {
                    // hideSpinner();
                    Swal.fire(
                        "Error",
                        "Oops! Something went wrong. Please refresh the page and try again. Or contact IT department." +
                            err,
                        "error"
                    );
                }
            },
        });
        return;
    });


    //show
    $(document).on('click', '.btn-show', function(e) {
        $('.modal-body').html('');
        var id = $(this).data("id");
        jQuery.getJSON(admin_url + "/acl/module-categories/show/" + id, function(response) {
            if (response.responseCode == 1) {
                $('.modal-body').html(response.html);
                $('#dodModal').modal();
            }
        });
    });

    //Display Order
    $(document).on('blur', '.display-box', function(e) {
        var id = $(this).data("did");
        var displayValue = $("#txt_" + id).val();
        jQuery.getJSON(admin_url + "/acl/module-categories/do-edit/" + id + "/" + displayValue, function(response) {
            alert('done');
        });
    });

    //del
    $(document).on('click', '.btn-del', function(e) {
        var did = $(this).data("id");
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            animation: !1,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                window.location.href = admin_url + "/acl/module-categories/delete/" + did;
            }
        })

    });

    //Parsley Form
    $('form').parsley();
    $.listen('parsley:field:error', function() {
        var i = 0;
        $("#sform .form-control").each(function(k, e) {
            var field = $(e).data("err");
            $(e).next("ul").find("li:eq(" + i + ")").html(field + " field is required");
        });
    });

});
