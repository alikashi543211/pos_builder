$(document).ready(function () {

    function initTinyMCE() {
        tinymce.init({
            selector: '#detailsDescription, #shortDescription',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            height: 300
        });
    }

    // Upload and Display Image File
    $(document).on('change', 'input[data-type="input-file"]', function(event){
        var imageUrl = URL.createObjectURL(event.target.files[0]);
        $(this).closest('.row').find('img[data-type="display-file"]').attr('src', imageUrl)
    });

  // Add New User
  $(document).on("click", ".btn-add", function (e) {
        $(".modal-body").html("");
        $(".drawer-title").html("Add New Policy");
        jQuery.getJSON(admin_url + "/policies/add/", function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_activities");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();
                    initTinyMCE();
                }
            }
        });
    });

    /** edit view */
    $(document).on("click", ".btn-edit", function (e) {
        $(".modal-body").html("");
        $(".drawer-title").html("Edit Existing Policy");
        var id = $(this).data("id");
        console.log('id:', id)
        jQuery.getJSON(
            admin_url + "/policies/edit/" +id,
            function (response) {
                if (response.responseCode == 1) {
                    $(".drawer-body").html(response.html);
                    const drawerElement = document.querySelector("#kt_activities");
                    if (drawerElement) {
                        KTDrawer.getInstance(drawerElement).show();
                        initTinyMCE();
                    }
                }
            }
        );
    });

    /** Status Change */
    $(document).on("click", ".btn-status-change", function (e) {
        e.preventDefault(); // Prevent default action for anchor tags

        var policyId = $(this).data("id");
        var message = 'Are you sure you want to change policy status?';
        var statusElement = $(this);
        var updateCategoryStatusUrl = admin_url + "/policies/status-change/"+ policyId;

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
                    url: updateCategoryStatusUrl,
                    type: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        var obj = JSON.parse(data);

                        if (obj.responseCode == 1) {
                            console.log(obj);
                            Swal.fire("Success", obj.msg, "success");
                            // Update the status and badge class based on the new status
                            obj.newStatus === "Active"
                                    ? statusElement.html('<span class="badge badge-pill badge-success">Active</span>')
                                    : statusElement.html('<span class="badge  badge-pill badge-danger">In-Active</span>');
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

    /** Delete Category */
    $(document).on("click", ".btn-del", function(e) {
        e.preventDefault(); // Prevent default action for anchor tags

        var button = $(this);
        var categoryId = button.data("id");
        var deleteUrl = admin_url + "/policies/delete/"+categoryId;
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

                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        var obj = JSON.parse(response);

                        if (obj.responseCode == 1) {
                            Swal.fire("Deleted!", obj.msg, "success");
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
     //Form Handler
     $(document).on("submit", "#sform", function (e) {

        e.preventDefault();
        $("#msg_box").html("");
        $(".btn-save").attr("disabled", "disabled");
        var act_val = $("#act").val();
        var eid = $("#eid").val();
        var actionUrl = $(this).attr('action');
        console.log('eid:', eid)
        var params = act_val;

        // loadSpinner();
        $.ajax({
            url: actionUrl,
            type: "POST",
            data: new FormData(this),

            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                try {
                    $(".btn-save").removeAttr("disabled");
                    var obj = JSON.parse(data);

                    if (obj.responseCode == 1) {
                        $(".modal-body").html("");
                        const drawerElement = document.querySelector("#kt_activities");
                        if (drawerElement) {
                            KTDrawer.getInstance(drawerElement).hide();
                        }
                        // hideSpinner();
                        Swal.fire("Success", obj.msg, "success");
                        closeAllDrawers();
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                        return;
                    } else {
                        // hideSpinner();
                        var errMsg = "";
                        // $(".btn_submit").removeAttr("disabled");
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


    var table = $('#policy-listing-table').DataTable({
        info: false,
        order: [],
        pageLength: 50,
        lengthChange: false,
        columnDefs: [{
            orderable: false
        }, {
            orderable: false
        }]
    });

    document.querySelector('[data-category-listing-table-filter="search"]').addEventListener("keyup", function (t) {
        table.search(t.target.value).draw();
    });

    function initTinyMCE() {
        tinymce.remove()
        tinymce.init({
            selector: '#detailsDescription, #shortDescription, #short_summary, .editorBox',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            height: 300
        });
    }

    // Close The Drawer After Operation Performed
    function closeAllDrawers()
    {
        $("#kt_drawer_chat_close").click();
        $("#kt_activities_close").click();
    }
});
