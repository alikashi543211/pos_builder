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

function initSelect2WithParent() {
    var $selectElements = $('select[data-control="select2"]');
    $selectElements.each(function () {
        var $el = $(this);
        if ($el.hasClass('select2-hidden-accessible')) {
            $el.select2('destroy');
        }
        $el.select2({
            dropdownParent: $('#kt_drawer_chat_messenger_body')
        });
    });
}

function closeDrawer() {
    const drawerElement = document.querySelector("#kt_drawer_chat");
    if (drawerElement) {
        KTDrawer.getInstance(drawerElement).hide();
    }
}

function openDrawer() {
    const drawerElement = document.querySelector("#kt_drawer_chat");
    if (drawerElement) {
        KTDrawer.getInstance(drawerElement).show();
    }
}

$(document).ready(function () {
    // Initialize DataTable
    var table = $('#data-modules-listing-table').DataTable({
        info: false,
        order: [],
        pageLength: 50,
        lengthChange: false,
        ordering: false,
        columnDefs: [{ orderable: false }, { orderable: false }]
    });

    document.querySelector('[data-modules-listing-table-filter="search"]').addEventListener("keyup", function (t) {
        table.search(t.target.value).draw();
    });

    // Add New Module
    $(document).on("click", ".btn-add", function () {
        $(".modal-body").html("");
        $(".drawer-title").html("Add New Module");

        $.getJSON(admin_url + "/acl/module/add", function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_drawer_chat");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();
                    initSelect2WithParent();
                }
            }
        });
    });

    // Submit Add Form
    $(document).on("submit", "#fform", function (e) {
        e.preventDefault();
        closeDrawer();
        loadSpinner();
        $("#msg_box").html("");
        $(".btn-save").attr("disabled", "disabled");

        $.ajax({
            url: admin_url + "/acl/module/add",
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
                        closeDrawer();
                        hideSpinner();
                        Swal.fire("Success", obj.msg, "success");
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        openDrawer();
                        hideSpinner();
                        $(".btn-save").removeAttr("disabled");
                        var errMsg = Array.isArray(obj.msg) ? obj.msg.join("\n") : obj.msg;
                        Swal.fire("Error", errMsg, "error");
                    }
                } catch (err) {
                    hideSpinner();
                    Swal.fire("Error", "Oops! Something went wrong. " + err, "error");
                }
            }
        });
    });

    // Submit Edit Form
    $(document).on("submit", "#editForm", function (e) {
        e.preventDefault();
        $("#msg_box").html("");
        $(".btn-save").attr("disabled", "disabled");
        var eid = $("#eid").val();

        $.ajax({
            url: admin_url + "/acl/module/edit/" + eid,
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
                        closeDrawer();
                        Swal.fire("Success", obj.msg, "success");
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        $(".btn_submit").removeAttr("disabled");
                        var errMsg = Array.isArray(obj.msg) ? obj.msg.join("\n") : obj.msg;
                        Swal.fire("Error", errMsg, "error");
                    }
                } catch (err) {
                    Swal.fire("Error", "Oops! Something went wrong. " + err, "error");
                }
            }
        });
    });

    // Edit View
    $(document).on("click", ".btn-edit", function () {
        $(".modal-body").html("");
        $(".drawer-title").html("Edit Module");

        var id = $(this).data("id");

        $.getJSON(admin_url + "/acl/module/edit/" + id, function (response) {
            if (response.responseCode == 1) {
                $(".drawer-body").html(response.html);
                const drawerElement = document.querySelector("#kt_drawer_chat");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();
                    initSelect2WithParent();
                }
            }
        });
    });

    // Show View
    $(document).on('click', '.btn-show', function () {
        $('.modal-body').html('');
        var id = $(this).data("id");

        $.getJSON(admin_url + "/acl/module/show/" + id, function (response) {
            if (response.responseCode == 1) {
                $('.modal-body').html(response.html);
                $('#icdModal').modal();
            }
        });
    });

    // Display Order
    $(document).on('blur', '.display-box', function () {
        var id = $(this).data("did");
        var displayValue = $("#txt_" + id).val();

        $.getJSON(admin_url + "/acl/module/do-edit/" + id + "/" + displayValue, function () {
            alert('done');
        });
    });

    // Auto Route Slug
    $("#module_name").keyup(function () {
        var text = $(this).val().toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-');
        $("#route").val(text);
    });

    // Delete Module
    $(document).on('click', '.btn-del', function () {
        var did = $(this).data("id");

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            animation: false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                window.location.href = admin_url + "/acl/module/delete/" + did;
            }
        });
    });

    // Parsley Validation
    $('form').parsley();

    $.listen('parsley:field:error', function () {
        $("#sform .form-control").each(function (k, e) {
            var field = $(e).data("err");
            $(e).next("ul").find("li:eq(0)").html(field + " field is required");
        });
    });

    // Filter Row for Module DataTable
    $('#module-datatable thead tr').clone(true).appendTo('#module-datatable thead');
    $('#module-datatable thead tr:eq(1) th').each(function (i) {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search..." />');

        $('input', this).on('keyup change', function () {
            if (table.column(i).search() !== this.value) {
                table.column(i).search(this.value).draw();
            }
        });
    });

    // Initialize Module DataTable
    $('#module-datatable').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        order: [],
        columnDefs: [{
            targets: 'no-sort',
            orderable: false
        }]
    });
});
