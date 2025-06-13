$(document).ready(function () {

    function initTinyMCE() {
        tinymce.init({
            selector: '#detailsDescription, #shortDescription',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            height: 300
        });
    }

  // Add New User
  $(document).on("click", ".btn-add", function (e) {
        $(".modal-body").html("");
        $(".drawer-title").html("Add New CMS Page");
        jQuery.getJSON(admin_url + "/cms-pages/add/", function (response) {
            if (response.responseCode == 1) {
                initTinyMCE();
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_activities");
                if (drawerElement) {
                    initTinyMCE();
                    KTDrawer.getInstance(drawerElement).show();
                }
            }
        });
    });

    /** edit view */
    $(document).on("click", ".btn-edit", function (e) {
        $(".modal-body").html("");
        $(".drawer-title").html("Edit CMS Page");
        var id = $(this).data("id");
        console.log('id:', id)
        jQuery.getJSON(
            admin_url + "/cms-pages/edit/" +id,
            function (response) {
                if (response.responseCode == 1) {
                    initTinyMCE();
                    $(".drawer-body").html(response.html);
                    const drawerElement = document.querySelector("#kt_activities");
                    if (drawerElement) {
                        initTinyMCE();
                        KTDrawer.getInstance(drawerElement).show();
                    }
                }
            }
        );
    });
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
            url: admin_url + "/cms-pages/" + params,
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
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                        return;
                    } else {
                        // hideSpinner();
                        var errMsg = "";
                        $(".btn_submit").removeAttr("disabled");
                        Swal.fire("Error", obj.msg, "error");
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





    var table = $('#cms-listing-table').DataTable({
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

    document.querySelector('[data-cms-listing-table-filter="search"]').addEventListener("keyup", function (t) {
        table.search(t.target.value).draw();
    });

});
