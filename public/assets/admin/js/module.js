$(document).ready(function() {
// Add New User
$(document).on("click", ".btn-add", function (e) {
    $(".modal-body").html("");
    $(".drawer-title").html("Add New Module");
    jQuery.getJSON(admin_url + "/acl/module/add", function (response) {
        if (response.responseCode == 1) {
            $(".drawer-body").html(response.html);
            const drawerElement = document.querySelector("#kt_drawer_chat");
            if (drawerElement) {
                KTDrawer.getInstance(drawerElement).show();
            }
        }
    });
});

//add form
$(document).on("submit", "#fform", function (e) {
    console.log("e:", e);
    e.preventDefault();
    $("#msg_box").html("");
    $(".btn-save").attr("disabled", "disabled");

    // loadSpinner();
    $.ajax({
        url: admin_url + "/acl/module/add" ,
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
                    $(".btn-save").removeAttr("disabled");
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


//edit form
//edit form
$(document).on("submit", "#editForm", function (e) {
    console.log("e:", e);

    e.preventDefault();
    $("#msg_box").html("");
    $(".btn-save").attr("disabled", "disabled");
    var eid = $("#eid").val();
    // loadSpinner();
    $.ajax({
        url: admin_url + "/acl/module/edit/"+eid,
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


/** edit view */
$(document).on("click", ".btn-edit", function (e) {
    $(".modal-body").html("");
    $(".drawer-title").html("Edit Module");
    var id = $(this).data("id");
    console.log('id:', id)
    jQuery.getJSON(
        admin_url + "/acl/module/edit/" +id,
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


//show
$(document).on('click', '.btn-show', function(e) {
    $('.modal-body').html('');
    var id = $(this).data("id");
    jQuery.getJSON(admin_url + "/acl/module/show/" + id, function(response) {
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
    jQuery.getJSON(admin_url + "/acl/module/do-edit/" + id + "/" + displayValue, function(response) {
        alert('done');
    });
});

$("#module_name").keyup(function() {
    var Text = $(this).val();
    Text = Text.toLowerCase();
    Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
    $("#route").val(Text);
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
            window.location.href = admin_url + "/acl/module/delete/" + did;
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


$('#module-datatable thead tr').clone(true).appendTo('#module-datatable thead');
$('#module-datatable thead tr:eq(1) th').each(function(i) {
    var title = $(this).text();
    $(this).html('<input type="text" placeholder="Search..." />');
    $('input', this).on('keyup change', function() {
        if (table.column(i).search() !== this.value) {
            table
                .column(i)
                .search(this.value)
                .draw();
        }
    });
});

// DataTable
var table = $('#module-datatable').DataTable({
    orderCellsTop: true,
    fixedHeader: true,
    "order": [],
    "columnDefs": [{
        "targets": 'no-sort',
        "orderable": false,
    }]
});


});
