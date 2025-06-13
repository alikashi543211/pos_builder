$(document).ready(function () {
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

    $("#suppliertable thead tr").clone(true).appendTo("#suppliertable thead");
    $("#suppliertable thead tr:eq(1) th").each(function (i) {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search..." />');
        $("input", this).on("keyup change", function () {
            if (table.column(i).search() !== this.value) {
                table.column(i).search(this.value).draw();
            }
        });
    });

    // Supplier Options Listing Data Tables
    // DataTable
    if($("#supplier-options-listing-table"))
    var supplierOptionsTable = $("#supplier-options-listing-table").DataTable({
        info: false,
        order: [],
        pageLength: 50,
        lengthChange: false,
        columnDefs: [
            {
                orderable: false,
            },
            {
                orderable: false,
            },
        ],
    });

    var supplierOptionsInput = document.querySelector('[data-supplier-options-listing-table-filter="search"]');
    if (supplierOptionsInput) {
        supplierOptionsInput.addEventListener("keyup", function (t) {
            supplierOptionsTable.search(t.target.value).draw();
        });
    }

    // DataTable
    var table = $("#supplier-listing-table").DataTable({
        info: false,
        order: [],
        pageLength: 50,
        lengthChange: false,
        columnDefs: [
            {
                orderable: false,
            },
            {
                orderable: false,
            },
        ],
    });

    var searchInput = document.querySelector('[data-supplier-listing-table-filter="search"]');
    if (searchInput) {
        searchInput.addEventListener("keyup", function (t) {
            table.search(t.target.value).draw();
        });
    }


    //Add Supplier
    $(document).on("click", ".btn-add", function (e) {
        console.log("e:", e);
        $(".modal-body").html("");
        $(".drawer-title").html("Add New Supplier");
        jQuery.getJSON(admin_url + "/suppliers/add", function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_activities");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();

                }
            }
        });
    });

    //Detail Of Supplier
    $(document).on("click", ".btn-detail", function (e) {
        console.log("e:", e);
        $(".modal-body").html("");
        $(".drawer-title").html("Supplier Detail");
        var data_url = $(this).attr("data-url");
        jQuery.getJSON(admin_url + data_url, function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_activities");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();
                }
            }
        });
    });



    //Detail Of Product
    $(document).on("click", ".btn-product-detail", function (e) {
        console.log("e:", e);
        $(".modal-body").html("");
        $(".drawer-title").html("Product Detail");
        var data_url = $(this).attr("data-url");
        jQuery.getJSON(admin_url + data_url, function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_activities");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();
                    $(".product_basics_tab").click();
                }
            }
        });
    });
    // Initialize Select2
    function InitializeSelect2(){
        $('select[data-control="select2"]').select2();
    }

    //Detail Of Supplier
    $(document).on("click", ".btn-supplier-status-change", function (e) {
        console.log("e:", e);
        $(".modal-body").html("");
        $(".drawer-title").html("Change Status");
        var data_url = $(this).attr("data-url");
        jQuery.getJSON(admin_url + data_url, function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_drawer_chat");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();
                }
            }else if (response.responseCode == 0) {
                Swal.fire("Error", response.msg, "error");
            }
        });
    });

    //Product Status Change
    $(document).on("click", ".btn-supplier-product-status-change", function (e) {
        console.log("e:", e);
        $(".modal-body").html("");
        $(".drawer-title").html("Change Status");
        var data_url = $(this).attr("data-url");
        jQuery.getJSON(admin_url + data_url, function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_drawer_chat");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();
                }
            }else if (response.responseCode == 0) {
                Swal.fire("Error", response.msg, "error");
            }
        });
    });
     //Add Supplier Details
    $(document).on("click", ".btn-add-supplier-options", function (e) {

        $(".modal-body").html("");
        $(".drawer-title").html($(this).attr("data-drawer-title"));
        data_url = $(this).attr("data-url");
        console.log('data_url:', admin_url, data_url)
        jQuery.getJSON(admin_url + data_url, function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_drawer_chat");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();
                    $('select[data-control="select2"]').select2();
                }
            }
        });
    });

});

$(document).on("click", ".btn-delete-supplier-options", function (e) {
    var did = $(this).data("id");
    var del_url = $(this).attr("data-url");
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
                url: admin_url + del_url,
                method: "GET",
                success: function(data){
                    var obj = JSON.parse(data);
                    if (obj.responseCode == 1) {
                        $(currentRow).remove();
                        Swal.fire("Success", obj.msg, "success");
                        return;
                    } else {
                        Swal.fire("Error", obj.msg, "error");
                        return;
                    }


                }
            })
        }

    });
});

/** add action */
//Form Handler
$(document).on("submit", "#sform", function (e) {
    e.preventDefault();
    $("#msg_box").html("");
    $(".btn_submit").attr("disabled", "disabled");
    var act_val = $("#act").val();
    var eid = $("#eid").val();
    console.log("eid:", eid);
    var params = act_val;
    if (eid != "") {
        params = act_val + "/" + eid;
    }
    var url = admin_url + "/suppliers/" + params;

    //loadSpinner();
    $.ajax({
        url: url,
        type: "POST",
        data: new FormData(this),

        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
            try {
                var obj = data;
                var obj = JSON.parse(data);
                if (obj.responseCode == 1) {
                    console.log('obj:', obj)
                    $(".modal-body").html("");
                    setTimeout(function () {
                        closeAllDrawers();
                        Swal.fire("Success", obj.msg, "success").then(() => {
                            location.reload();
                        });
                    }, 200);
                    return null;
                } else {
                    var errMsg = "";
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
                hideSpinner();
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

// Change status form handler
$(document).on("submit", "#changeStatusForm", function (e) {
    e.preventDefault();
    submitUrl = $(this).attr('action');
    $("#msg_box").html("");
    $(".change_status_btn").attr("disabled", "disabled");

    var url = submitUrl;

    //loadSpinner();
    $.ajax({
        url: url,
        type: "POST",
        data: new FormData(this),

        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function(){
            $(".change_status_btn").html("Please wait...");
        },
        success: function (data) {
            try {
                $(".change_status_btn").removeAttr("disabled");
                $(".change_status_btn").html("Save");
                var obj = data;
                var obj = JSON.parse(data);
                if (obj.responseCode == 1) {
                    console.log('obj:', obj)
                    $(".modal-body").html("");
                    setTimeout(function () {
                        closeAllDrawers();
                        Swal.fire("Success", obj.msg, "success").then(() => {
                            location.reload();
                        });
                    }, 200);
                    return null;
                } else {
                    var errMsg = "";
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
                hideSpinner();
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
//Supplier Detail Status Changes

//Status Change contracts

$(document).on("click", ".btn-status-change-contracts", function (e) {
    var did = $(this).data("uid");
    var currentRow = $(this).closest("td");
    console.log("currentRow:", currentRow);
    Swal.fire({
        title: "Confirmation",
        text: "You want to change contracts status.",
        icon: "warning",
        animation: !1,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: admin_url + "/suppliers/contract-status-change/" + did,
                method: "GET",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj.responseCode == 1) {
                        // $(currentRow).remove();
                        Swal.fire("Success", obj.msg, "success");
                        var statusBadge =
                            obj.status === "active"
                                ? '<span class="badge badge-pill badge-success">Active</span>'
                                : '<span class="badge  badge-pill badge-danger">In-Active</span>';
                        $(currentRow).html(
                            '<a title="Change Deal Status" data-uid="' +
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

//Status Change user

$(document).on("click", ".btn-status-change-user", function (e) {
    var did = $(this).data("uid");
    var currentRow = $(this).closest("td");
    console.log("currentRow:", currentRow);
    Swal.fire({
        title: "Confirmation",
        text: "You want to change contact person status.",
        icon: "warning",
        animation: !1,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: admin_url + "/suppliers/user-status-change/" + did,
                method: "GET",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj.responseCode == 1) {
                        // $(currentRow).remove();
                        Swal.fire("Success", obj.msg, "success");
                        var statusBadge =
                            obj.status === "Active"
                                ? '<span class="badge badge-pill badge-success">Active</span>'
                                : '<span class="badge  badge-pill badge-danger">In-Active</span>';
                        $(currentRow).html(
                            '<a title="Change Deal Status" data-uid="' +
                                did +
                                '" class="btn-status-change-user" href="javascript:void(0)">' +
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
//Status Change contact

$(document).on("click", ".btn-status-change-contact", function (e) {
    var did = $(this).data("uid");
    var currentRow = $(this).closest("td");
    console.log("currentRow:", currentRow);
    Swal.fire({
        title: "Confirmation",
        text: "You want to change contact person status.",
        icon: "warning",
        animation: !1,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: admin_url + "/suppliers/contacts-status-change/" + did,
                method: "GET",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj.responseCode == 1) {
                        // $(currentRow).remove();
                        Swal.fire("Success", obj.msg, "success");
                        var statusBadge =
                            obj.status === "active"
                                ? '<span class="badge badge-pill badge-success">Active</span>'
                                : '<span class="badge  badge-pill badge-danger">In-Active</span>';
                        $(currentRow).html(
                            '<a title="Change Deal Status" data-uid="' +
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

// Supplier Status Change
$(document).on("click", ".btn-status-change", function (e) {
    var did = $(this).data("uid");
    var currentRow = $(this).closest("td");
    console.log("currentRow:", currentRow);
    Swal.fire({
        title: "Confirmation",
        text: "You want to change supplier status.",
        icon: "warning",
        animation: !1,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: admin_url + "/suppliers/status-change/" + did,
                method: "GET",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj.responseCode == 1) {
                        // $(currentRow).remove();
                        Swal.fire("Success", obj.msg, "success");
                        var statusBadge =
                            obj.status === "active"
                                ? '<span class="badge badge-pill badge-success">Active</span>'
                                : '<span class="badge  badge-pill badge-danger">Blocked</span>';
                        $(currentRow).html(
                            '<a title="Change Deal Status" data-uid="' +
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

// Upload Supplier Company Logo
$(document).on('change', 'input[data-type="file"]', function(event){

    var imageUrl = URL.createObjectURL(event.target.files[0]);
    $('img[data-type="supplier-company-logo"]').attr('src', imageUrl)


});

//Status Change excursions

$(document).on("click", ".btn-status-change-excursions", function (e) {
    var did = $(this).data("uid");
    var currentRow = $(this).closest("td");
    console.log("currentRow:", currentRow);
    Swal.fire({
        title: "Confirmation",
        text: "You want to change excursion status.",
        icon: "warning",
        animation: !1,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: admin_url + "/suppliers/excursion-status-change/" + did,
                method: "GET",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj.responseCode == 1) {
                        // $(currentRow).remove();
                        Swal.fire("Success", obj.msg, "success");
                        var statusBadge =
                            obj.status === "1"
                                ? '<span class="badge badge-pill badge-success">Active</span>'
                                : '<span class="badge  badge-pill badge-danger">In-Active</span>';
                        $(currentRow).html(
                            '<a title="Change Deal Status" data-uid="' +
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
//Status Change assigned-user

$(document).on("click", ".btn-status-change-assigned-user", function (e) {
    var did = $(this).data("uid");
    var currentRow = $(this).closest("td");
    console.log("currentRow:", currentRow);
    Swal.fire({
        title: "Confirmation",
        text: "You want to change assigned user status.",
        icon: "warning",
        animation: !1,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url:
                    admin_url +
                    "/suppliers/assigned-users-status-change/" +
                    did,
                method: "GET",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj.responseCode == 1) {
                        // $(currentRow).remove();
                        Swal.fire("Success", obj.msg, "success");
                        var statusBadge =
                            obj.status === "1"
                                ? '<span class="badge badge-pill badge-success">Active</span>'
                                : '<span class="badge  badge-pill badge-danger">In-Active</span>';
                        $(currentRow).html(
                            '<a title="Change Deal Status" data-uid="' +
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

/*--------------Dynamic Save Function..................*/
function addContactForm(formId, serverRoutePath) {
    saveData(formId, serverRoutePath);
}
function addExcursionForm(formId, serverRoutePath) {
    saveData(formId, serverRoutePath);
}

/*--------------Dynamic Save Function..................*/
function saveData(formid, serverRoutePath) {
    var form = document.getElementById(formid);
    var formData = new FormData(form);
    var url = admin_url + "/" + serverRoutePath;

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function(){

        },
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.responseCode == 1) {
                closeAllDrawers();
                Swal.fire("Success", obj.msg, "success").then(() => {
                    location.reload();
                });
            } else if (obj.responseCode == 0) {
                var errMsg = obj.msg;

                var htmlTagErrorShown = false;
                var messages = errMsg.split("\n").map(function (message) {
                    if (message.includes("should not contain any HTML tags")) {
                        if (!htmlTagErrorShown) {
                            htmlTagErrorShown = true;
                            return "Fields should not contain any HTML tags.";
                        } else {
                            return "Hello";
                        }
                    }
                    return message;
                });

                errMsg = messages
                    .filter(function (message) {
                        return message !== null;
                    })
                    .join("\n");

                Swal.fire("Warning", errMsg, "warning");
            } else {
                Swal.fire(
                    "Warning",
                    "Something went wrong. Please refresh the page and try again.",
                    "warning"
                );
                closeAllDrawers();
                location.reload();
            }
        },
        error: function (xhr) {
            var response = xhr.responseJSON;
            if (response && response.errors) {
                var errMsg = "";
                $.each(response.errors, function (field, messages) {
                    errMsg += messages.join("\r\n") + "\r\n";
                });
                Swal.fire("Error", errMsg, "error");
            } else {
                Swal.fire("Error", "An unexpected error occurred", "error");
            }
        },
    });
}

// Close The Drawer After Operation Performed
function closeAllDrawers()
{
    $("#kt_drawer_chat_close").click();
    $("#kt_activities_close").click();
}

// Product Detail Popup Handling
$(document).on('click', '.product-detail-tab', function(){
    $(".product-detail-tab").removeClass('active');
    $(this).addClass('active');
    // Content hide and display detail
    $(".product-detail-content").hide();
    $(".product-detail-content-please-wait").removeClass('d-none');

    var url = $(this).attr('data-url');
    jQuery.getJSON(url, function (response) {

        if (response.responseCode == 1) {
            $(".product-detail-content").html(response.html).fadeIn('slow')
            $(".product-detail-content-please-wait").addClass('d-none');
        }
    });
});
