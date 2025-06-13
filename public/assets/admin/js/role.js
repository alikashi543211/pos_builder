// Spinner Functions
function loadSpinner() {
    Swal.fire({
        title: 'Loading...',
        text: 'Please wait while we process your request.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

function hideSpinner() {
    Swal.close();
}

// Select2 Initialization
function initSelect2WithParent() {
    const $selectElements = $('select[data-control="select2"]');

    $selectElements.each(function () {
        const $el = $(this);
        if ($el.hasClass('select2-hidden-accessible')) {
            $el.select2('destroy');
        }

        $el.select2({
            dropdownParent: $('#kt_activities_body')
        });
    });
}

// Drawer Control
function closeDrawer() {
    const drawer = document.querySelector("#kt_activities");
    if (drawer) {
        KTDrawer.getInstance(drawer).hide();
    }
}

function openDrawer() {
    const drawer = document.querySelector("#kt_activities");
    if (drawer) {
        KTDrawer.getInstance(drawer).show();
    }
}

// Document Ready
$(document).ready(function () {
    // Show Role Details
    $(document).on("click", ".btn-show", function () {
        $(".modal-body").html("");
        const id = $(this).data("id");

        $.getJSON(`${admin_url}/acl/role/show/${id}`, function (response) {
            if (response.responseCode === 1) {
                $(".modal-body").html(response.html);
                $("#icdModal").modal();
            }
        });
    });

    // Add Role
    $(document).on("click", ".btn-add", function () {
        $(".modal-body").html("");
        $(".drawer-title").html("Add Role");

        $.getJSON(`${admin_url}/acl/role/add`, function (response) {
            if (response.responseCode === 1) {
                $(".drawer-body").html(response.html);
                openDrawer();
            }
        });
    });

    // Edit Role
    $(document).on("click", ".btn-edit_", function () {
        $(".modal-body").html("");
        $(".drawer-title").html("Edit Role");

        const id = $(this).data("id");

        $.getJSON(`${admin_url}/acl/role/edit/${id}`, function (response) {
            if (response.responseCode === 1) {
                $(".drawer-body").html(response.html);
                openDrawer();
            }
        });
    });

    // Form Submission (Add/Edit)
    $(document).on("submit", "#fform", function (e) {
        e.preventDefault();
        closeDrawer();
        loadSpinner();
        $(".btn-save").attr("disabled", true);

        const act = $("#act").val();
        const eid = $("#eid").val();
        const endpoint = eid ? `${act}/${eid}` : act;

        $.ajax({
            url: `${admin_url}/acl/role/${endpoint}`,
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                hideSpinner();

                try {
                    const obj = JSON.parse(data);

                    if (obj.responseCode === 1) {
                        Swal.fire("Success", obj.msg, "success");
                        setTimeout(() => {
                            window.location.href = '/acl/role';
                        }, 2000);
                    } else {
                        openDrawer();
                        $(".btn_submit").removeAttr("disabled");

                        const errMsg = typeof obj.msg === "object"
                            ? Object.values(obj.msg).join("\r\n")
                            : obj.msg;

                        Swal.fire("Error", errMsg, "error");
                    }
                } catch (err) {
                    Swal.fire("Error", "Oops! Something went wrong. Please refresh and try again.\n" + err, "error");
                }
            }
        });
    });

    // Change Display Order
    $(document).on("blur", ".display-box", function () {
        const id = $(this).data("did");
        const displayValue = $(`#txt_${id}`).val();

        $.getJSON(`${admin_url}/acl/role/do-edit/${id}/${displayValue}`, function () {
            alert("done");
        });
    });

    // Delete Role
    $(document).on("click", ".btn-del", function () {
        const did = $(this).data("id");

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then(result => {
            if (result.value) {
                window.location.href = `${admin_url}/acl/role/delete/${did}`;
            }
        });
    });

    // Initialize Parsley Validation
    $("form").parsley();

    $.listen("parsley:field:error", function () {
        $("#sform .form-control").each(function (k, e) {
            const field = $(e).data("err");
            $(e).next("ul").find("li:eq(0)").html(`${field} field is required`);
        });
    });
});
