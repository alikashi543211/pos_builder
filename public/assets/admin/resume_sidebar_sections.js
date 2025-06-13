$(document).ready(function () {
    // Add New User
    $(document).on("click", ".btn-add", function (e) {
        console.log("e:", e);
        $(".modal-body").html("");
        $(".drawer-title").html("Add New Employee");
        jQuery.getJSON(admin_url + "/employee/add/", function (response) {
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
        $(".drawer-title").html("Edit Employee");
        var id = $(this).data("id");
        console.log("id:", id);
        jQuery.getJSON(admin_url + "/employee/edit/" + id, function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_drawer_chat");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();
                }
            }
        });
    });

    $(document).on("click", ".btn-status-change", function (e) {
        var did = $(this).data("uid");
        var currentRow = $(this).closest("td");
        console.log("currentRow:", currentRow);
        Swal.fire({
            title: "Confirmation",
            text: "You want to change employee status.",
            icon: "warning",
            animation: !1,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: admin_url + "/employee/status-change/" + did,
                    method: "GET",
                    success: function (data) {
                        var obj = JSON.parse(data);
                        if (obj.responseCode == 1) {
                            // $(currentRow).remove();
                            Swal.fire("Success", obj.msg, "success");
                            var statusBadge =
                                obj.status === 1
                                    ? '<span class="badge badge-pill badge-success">Active</span>'
                                    : '<span class="badge  badge-pill badge-danger">In-Active</span>';
                            $(currentRow).html(
                                '<a title="Change Tax Status" data-uid="' +
                                did +
                                '" class="btn-status-change" href="javascript:void(0)">' +
                                statusBadge +
                                "</a>"
                            );
                            return;
                        } else {
                            Swal.fire("Error", errMsg, "error");
                            return;
                        }
                    },
                });
            }
        });
    });

    //update status
    $(document).on('click', '.active-status, .inactive-status', function (e) {
        var status = $(this).hasClass('active-status') ? 0 : 1;
        var did = $(this).data("uid");
        var action = status == 1 ? 'Active' : 'Inactive';
        var confirmText = 'Are you sure you want to ' + action.toLowerCase() + ' this Employee?';
        var $badge = $(this);
        Swal.fire({
            title: 'Confirm',
            text: confirmText,
            icon: 'warning',
            animation: false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, ' + action
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    type: 'GET',
                    url: admin_url + "/employee/status-change/" + did,
                    data: {
                        status: status
                    },
                    success: function (response) {

                        if (status == 1) {
                            $badge.removeClass('badge-light-danger').addClass('badge-light-success').text('Active');

                            $badge.removeClass('inactive-status').addClass('active-status');
                        } else {
                            $badge.removeClass('badge-light-success').addClass('badge-light-danger').text('Inactive');
                            $badge.removeClass('active-status').addClass('inactive-status');

                        }
                        Swal.fire(
                            'Updated!',
                            'Employee status has been updated.',
                            'success'
                        );
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while deleting the user.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });

            }
        });
    });

    //del
    $(document).on("click", ".btn-delete", function (e) {
        var did = $(this).data("id");
        var currentRow = $(this).closest("tr");
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            animation: !1,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: admin_url + "/employee/delete/" + did,
                    method: "GET",
                    success: function (data) {
                        var obj = JSON.parse(data);
                        if (obj.responseCode == 1) {
                            $(currentRow).remove();
                            Swal.fire("Success", obj.msg, "success");
                            return;
                        } else {
                            Swal.fire("Error", errMsg, "error");
                            return;
                        }
                    },
                });
            }
        });
    });
    //Form

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
            url: admin_url + "/employee/" + params,
            type: "POST",
            data: new FormData(this),

            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                try {
                    var obj = data;

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
                        var errMsg = "";
                        $(".btn_submit").removeAttr("disabled");
                        if (typeof response.msg === "object") {
                            $.each(response.msg, function (index, value) {
                                errMsg += value.join("\n");
                                errMsg += "\n";
                            });
                        } else {

                            errMsg = response.msg;
                        }

                        // Display the errors using SweetAlert
                        Swal.fire("Error", errMsg.trim(), "error");
                        return
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

    $(document).on("click", ".resume_header_section_save_btn", function (e) {
        console.log('Save Clicked')
        $("#resume_header_sections_form").submit();
    });

    $(document).on("submit", "#resume_header_sections_form", function (e) {

        console.log("e:", e);
        e.preventDefault();
        $("#msg_box").html("");
        $(".resume_header_section_save_btn").attr("disabled", "disabled");
        var act_val = $("#act").val();
        var eid = $("#eid").val();
        var params = act_val;

        // loadSpinner();
        $.ajax({
            url: admin_url + "/resume/sidebar-sections/store",
            type: "POST",
            data: new FormData(this),

            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                try {
                    $(".resume_header_section_save_btn").removeAttr("disabled");
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
                        // Display the errors using SweetAlert
                        Swal.fire("Error", obj.msg, "error");
                    }
                } catch (err) {
                    // hideSpinner();
                    $(".resume_header_section_save_btn").removeAttr("disabled");
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

    var cVal = 1;
    $("#checkAll").click(function () {
        if (cVal == 1) {
            $("input:checkbox").prop("checked", true);
            cVal = 2;
        } else {
            $("input:checkbox").prop("checked", false);
            cVal = 1;
        }
    });

    //show
    $(document).on("click", ".btn-show", function (e) {
        $(".modal-body").html("");
        var id = $(this).data("id");
        jQuery.getJSON(admin_url + "/employee/" + id, function (response) {
            if (response.responseCode == 1) {
                $(".modal-body").html(response.html);
                $("#dodModal").modal();
            }
        });
    });

    //Display Order
    $(document).on("blur", ".display-box", function (e) {
        var id = $(this).data("did");
        var displayValue = $("#txt_" + id).val();
        jQuery.getJSON(
            admin_url + "/acl/users/do-edit/" + id + "/" + displayValue,
            function (response) {
                alert("done");
            }
        );
    });

    //Parsley Form
    $("form").parsley();
    $.listen("parsley:field:error", function () {
        var i = 0;
        $("#sform .form-control").each(function (k, e) {
            var field = $(e).data("err");
            $(e)
                .next("ul")
                .find("li:eq(" + i + ")")
                .html(field + " field is required");
        });
    });


    /** Team Assignment  */
    // Add New User
    $(document).on("click", ".btn-add-team", function (e) {
        console.log("e:", e);
        $(".modal-body").html("");
        $(".drawer-title").html("Assign New Team");
        jQuery.getJSON(admin_url + "/team/add", function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_drawer_chat");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();
                }
            }
        });
    });

    $(document).ready(function () {
        $('input[name="background_image"]').on('change', function (event) {
            let input = event.target;
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#backgroundImagePreview').attr('src', e.target.result).show();
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
    });


    /** Team Save */

    //Form Handler
    $(document).on("submit", "#teamform", function (e) {

        e.preventDefault();

        var employeesIDs = [];
        $('.checkbox:checked').each(function () {
            employeesIDs.push($(this).val());
        });
        if (employeesIDs.length === 0) {
            Swal.fire("Error", 'Please choose any employee for team assignment', "error");
            return;
        }
        $("#msg_box").html("");
        $(".btn-save").attr("disabled", "disabled");
        var act_val = $("#act").val();
        var eid = $("#eid").val();
        var params = act_val;

        if (eid != "") {
            params = act_val + "/" + eid;
        }
        var data = new FormData(this);
        data.append('employeesIDs', employeesIDs);


        // loadSpinner();
        $.ajax({
            url: admin_url + "/team/" + params,
            type: "POST",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                try {
                    var obj = JSON.parse(data);
                    console.log('obj:', obj)
                    console.log('responseCode:', obj.responseCode)

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
});

function showEntities(val) {
    if (val == "custom") {
        $("#elist").css("display", "block");
    } else {
        $("#elist").css("display", "none");
    }
}

function showPasswordField(val) {
    if (val == "1") {
        $("#passfield").css("display", "none");
    } else {
        $("#passfield").css("display", "block");
    }
}

$(document).ready(function () {
    $("#datatableusr").DataTable({
        order: [],
        pageLength: 50,
        columnDefs: [
            {
                targets: "no-sort",
                orderable: false,
            },
        ],
        dom: "Bfrtip",

        /*  buttons: [{
                extend: 'csv',
                footer: false,
                text: 'Export Data in CSV',
                title: 'reporting-matrix',
                exportOptions: {
                    modifier: {
                        page: 'all',
                        search: 'none'
                    },
                    columns: [0, 1, 2, 3, 4, 5]
                }
            },
            {
                extend: 'pdf',
                //orientation: 'landscape',
                pageSize: 'LEGAL',
                footer: false,
                text: 'Export Data in PDF',
                title: '',
                exportOptions: {
                    modifier: {
                        page: 'all',
                        search: 'none'
                    },
                    columns: [0, 1, 2, 3, 4, 5]
                }
            }
        ],
*/
    });
});
