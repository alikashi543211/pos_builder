$(document).ready(function () {
    function initSelect2()
    {
        // $('select[data-control="select2"]').select2('destroy');
        $('select[data-control="select2"]').select2();
    }
    function initTinyMCE() {
        tinymce.remove()
        tinymce.init({
            selector: '#detailsDescription, #shortDescription, #short_summary, .editorBox',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            height: 300
        });
    }

    // Upload and Display Image File
    $(document).on('change', 'input[data-type="input-file-1"]', function(event){
        var imageUrl = URL.createObjectURL(event.target.files[0]);
        $('img[data-type="display-file-1"]').attr('src', imageUrl)
    });
    // Upload and Display Image File
    $(document).on('change', 'input[data-type="input-file-2"]', function(event){
        var imageUrl = URL.createObjectURL(event.target.files[0]);
        $('img[data-type="display-file-2"]').attr('src', imageUrl)
    });
    // Upload and Display Image File
    $(document).on('change', 'input[data-type="input-file-3"]', function(event){
        var imageUrl = URL.createObjectURL(event.target.files[0]);
        $('img[data-type="display-file-3"]').attr('src', imageUrl)
    });

  // Add New User
  $(document).on("click", ".btn-add", function (e) {
        $(".modal-body").html("");
        $(".drawer-title").html("Add New Category");
        jQuery.getJSON(admin_url + "/category/add/", function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_activities");
                if (drawerElement) {
                    initTinyMCE();
                    initSelect2();
                    KTDrawer.getInstance(drawerElement).show();
                }
            }
        });
    });

    /** edit view */
    $(document).on("click", ".btn-edit", function (e) {
        $(".modal-body").html("");
        $(".drawer-title").html("Edit Category");
        var id = $(this).data("id");
        console.log('id:', id)
        jQuery.getJSON(
            admin_url + "/category/edit/" +id,
            function (response) {
                if (response.responseCode == 1) {
                    $(".drawer-body").html(response.html);
                    const drawerElement = document.querySelector("#kt_activities");
                    if (drawerElement) {
                        initTinyMCE();
                        initSelect2();
                        KTDrawer.getInstance(drawerElement).show();
                    }
                }
            }
        );
    });

    /** Status Change */
    $(document).on("click", ".btn-status-change", function (e) {
        e.preventDefault(); // Prevent default action for anchor tags

        var categoryId = $(this).data("id");
        var message = 'Are you sure you want to change category status?';
        var statusElement = $(this);
        var updateCategoryStatusUrl = admin_url + "/category/change-status/"+ categoryId;

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
                            Swal.fire("Success", obj.msg, "success");
                            // Update the status and badge class based on the new status
                            statusElement.removeClass('badge-primary badge-warning badge-success badge-light-danger');
                            if (obj.newStatus == '0') {
                                statusElement.addClass('badge-primary').text('In-Active');
                             } else if (obj.newStatus == '1') {
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

    /** Delete Category */
    $(document).on("click", ".btn-del", function(e) {
        e.preventDefault(); // Prevent default action for anchor tags

        var button = $(this);
        var categoryId = button.data("id");
        var deleteUrl = admin_url + "/category/destroy/"+categoryId;
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
        console.log('eid:', eid)
        var params = act_val;

        if (eid != "") {
            params = act_val + "/" + eid;
        }

        // loadSpinner();
        $.ajax({
            url: admin_url + "/category/" + params,
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
                        const drawerElement = document.querySelector("#kt_drawer_chat");
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

    var table = $('#category-listing-table').DataTable({
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

    // Close The Drawer After Operation Performed
    function closeAllDrawers()
    {
        $("#kt_drawer_chat_close").click();
        $("#kt_activities_close").click();
    }
});
