$(document).ready(function () {

    // Add New User
    $(document).on("click", ".btn-add", function (e) {
        $(".modal-body").html("");
        $(".drawer-title").html("Add New Currency");
        jQuery.getJSON(admin_url + "/currency/add/", function (response) {
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
        $(".drawer-title").html("Edit Currency");
        var id = $(this).data("id");
        console.log('id:', id)
        jQuery.getJSON(
            admin_url + "/currency/edit/" +id,
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

    /** Status Change */
    $(document).on("click", ".btn-status-change", function (e) {
        e.preventDefault(); // Prevent default action for anchor tags
    
        var documentID = $(this).data("id");
        var message = 'Are you sure you want to change currency status?';
        var statusElement = $(this);
        var updateFundsStatusUrl = admin_url + "/currency-update-status";
        
        Swal.fire({
            title: 'Confirm',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: updateFundsStatusUrl,
                    type: 'POST',
                    data: JSON.stringify({
                        documentID: documentID,
                    }),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        var obj = JSON.parse(data);
    
                        if (obj.responseCode == 1) {
                            Swal.fire("Success", obj.msg, "success");
    
                            // Update the status and badge class based on the new status
                            statusElement.removeClass('badge-primary badge-warning badge-success badge-light-danger');
                            if (obj.newStatus == 'In-Active') {
                                statusElement.addClass('badge-primary').text('In-Active');
                             } else if (obj.newStatus == 'Active') {
                                statusElement.addClass('badge-success').text('Active');
                            } else {
                                statusElement.addClass('badge-light-danger').text(obj.newStatus);
                            }
                        } else {
                            Swal.fire("Error", obj.msg, "error");
                        }
                    },
                    error: function(error) {
                        Swal.fire("Error", error.responseText, "error");
                    }
                });
            }
        });
    });

    /** Delete Document  */

    $(document).on("click", ".btn-del", function(e) {
        e.preventDefault(); // Prevent default action for anchor tags
    
        var button = $(this);
        var documentID = button.data("id");
        var deleteUrl = admin_url + "/currency/delete";
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: JSON.stringify({
                        documentID: documentID,
                    }),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        var obj = JSON.parse(response);
    
                        if (obj.responseCode == 1) {
                            Swal.fire("Deleted!", obj.msg, "success");
                            // Remove the parent row of the clicked delete button
                            button.closest('tr').remove();
                        } else {
                            Swal.fire("Error", obj.msg, "error");
                        }
                    },
                    error: function(error) {
                        Swal.fire("Error", error.responseText, "error");
                    }
                });
            }
        });
    });
    


    //Form 

       //Form Handler
       $(document).on("submit", "#fform", function (e) {
        console.log('e:', e)
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
            url: admin_url + "/currency/" + params,
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
                        const drawerElement = document.querySelector("#kt_drawer_chat");
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
                        "Oops! Something went wrong. Please refresh the page and try again. Or contact IT Department." +
                            err,
                        "error"
                    );
                }
            },
        });
        return;
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
});
