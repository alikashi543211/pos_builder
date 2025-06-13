$(document).ready(function () {

    // Add New User
    $(document).on("click", ".btn-add", function (e) {
        console.log('e:', e)
        $(".modal-body").html("");
        $(".drawer-title").html("Add New User");
        jQuery.getJSON(admin_url + "/acl/users/add/", function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_activities");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();
                }
            }
        });
    });
    /** edit view */
    $(document).on("click", ".btn-edit", function (e) {
        $(".modal-body").html("");
        $(".drawer-title").html("Edit User");
        var id = $(this).data("id");
        console.log('id:', id)
        jQuery.getJSON(
            admin_url + "/acl/users/edit/" +id,
            function (response) {
                if (response.responseCode == 1) {
                    $(".drawer-body").html(response.html);
                    const drawerElement = document.querySelector("#kt_activities");
                    if (drawerElement) {
                        KTDrawer.getInstance(drawerElement).show();
                    }
                }
            }
        );
    });

    $(document).on('change', 'input[data-type="file"]', function(event){

        var imageUrl = URL.createObjectURL(event.target.files[0]);
        var imageHtml = `<img src="${imageUrl}" id="avatar-src" height="120px" width="120px" />`;

        $('div[data-display-image="true"]').css('background-image', 'url(' + imageUrl + ')')
        $('div[data-display-image="true"]').html(imageHtml)


    });

    $(document).on('click', 'span[data-kt-image-input-action="remove"]', function(){
        $('input[data-type="file"]').val(null);
        var defaultImageUrl = admin_url + '/assets/media/avatars/blank.png';
        var imageHtml = `<img src="${defaultImageUrl}" id="avatar-src" height="120px" width="120px" />`;
        $('div[data-display-image="true"]').css('background-image', 'url(' + defaultImageUrl + ')')
        $('div[data-display-image="true"]').html(imageHtml)
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
            url: admin_url + "/acl/users/" + params,
            type: "POST",
            data: new FormData(this),

            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                try {
                    $(".btn-save").removeAttr("disabled");
                    var data = JSON.parse(data);

                    if (data.responseCode == 1) {
                        $(".modal-body").html("");
                        const drawerElement = document.querySelector("#kt_drawer_chat");
                        if (drawerElement) {
                            KTDrawer.getInstance(drawerElement).hide();
                        }
                        // hideSpinner();
                        Swal.fire("Success", data.msg, "success");
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                        return;
                    } else if(data.responseCode == 0) {
                        // hideSpinner();
                        var errMsg = "";
                        $(".btn-save").removeAttr("disabled");
                        if (typeof data.msg === "object") {

                            $.each(data.msg, function (index, value) {
                                errMsg += value.join("\n");
                                errMsg += "\n";
                            });
                        } else {


                        }
                        Swal.fire("Error", data.msg, "error");
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
        jQuery.getJSON(
            admin_url + "/acl/users/show/" + id,
            function (response) {
                if (response.responseCode == 1) {
                    $(".modal-body").html(response.html);
                    $("#dodModal").modal();
                }
            }
        );
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

    //del

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

    var table = $('#user-listing-table').DataTable({
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

    document.querySelector('[data-user-listing-table-filter="search"]').addEventListener("keyup", function (t) {
        table.search(t.target.value).draw();
    });


});
